<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\constants\UserRole;
use app\core\Application;
use app\core\exception\RuntimeException;
use app\core\UserModel;
use Exception;
use PDO;

use function password_hash;
use function sprintf;
/**
 * Class User
 * @package app\models
 */
class User extends UserModel
{
    /** @var array|string[]  */
    protected array $attributes = [
        'firstName',
        'lastName',
        'email',
        'password',
    ];

    /** @var string  */
    public string $firstName = '';

    /** @var string  */
    public string $lastName = '';

    /** @var string  */
    public string $email = '';

    /** @var string  */
    public string $password = '';

    /** @var string  */
    public string $passwordConfirm = '';

    /** @var string  */
    public string $role;// = UserRole::NORMAL_USER;

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'users';
    }

    /**
     * @return string
     */
    public function save(): string
    {
        Application::$app->db->pdo->beginTransaction();
        try {
            $this->role = UserRole::NORMAL_USER;
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $this->{$this->primaryKey()} = parent::save();
            $this->setRole($this->role);
        } catch (Exception $e) {
            Application::$app->db->pdo->rollBack();
        }
        Application::$app->db->pdo->commit();
        Application::$app->login($this);

        return $this->{$this->primaryKey()};
    }

    /**
     * @return array|array[]
     */
    public function rules(): array
    {
        return [
            'firstName' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 3]],
            'lastName' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 3]],
            'email' => [Rules::REQUIRED, Rules::EMAIL, [
                Rules::UNIQUE,
                'class' => self::class,
            ]],
            'password' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 8], [Rules::MAX_LENGTH, Rules::MAX_LENGTH => 24]],
            'passwordConfirm' => [Rules::REQUIRED, [Rules::MATCH, Rules::MATCH => 'password']],
        ];
    }

    /**
     * @return array|string[]
     */
    public function labels(): array
    {
        return [
            'firstName' => 'Your first name',
            'lastName' => 'Your last name',
            'email' => 'Your email',
            'password' => 'Your password',
            'passwordConfirm' => 'Please confirm your password',
        ];
    }

    /**
     * @return string
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    /**
     * @param string $roleName
     * @throws RuntimeException
     */
    private function setRole(string $roleName): void
    {
        $statement = self::prepare('INSERT INTO role_user (role_id, user_id) VALUES (:role, :user);');
        /** @var Role $role */
        $role = (new Role())->findOne(['code' => $this->role]);
        if (!($role instanceof Role)) {
            throw new RuntimeException(sprintf('Role with code %s not found', $this->role));
        }
        $statement->bindValue(':role', $role->{$role->primaryKey()});
        $statement->bindValue(':user', $this->{$this->primaryKey()});

        $statement->execute();
    }

    /**
     * @param $userId
     * @return array|false
     */
    public function findOutRoleId($userId)
    {
        $statement = self::prepare('SELECT role_id FROM role_user WHERE user_id = :userId LIMIT 1;');
        $statement->bindValue(':userId', $userId);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}