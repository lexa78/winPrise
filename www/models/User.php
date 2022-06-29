<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\constants\UserStatus;
use app\core\UserModel;

use function password_hash;
/**
 * Class User
 * @package app\models
 */
class User extends UserModel
{
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

    /** @var int  */
    public int $status = UserStatus::INACTIVE;

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'users';
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $this->status = UserStatus::INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
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
    public function attributes(): array
    {
        return [
            'firstName',
            'lastName',
            'email',
            'password',
            'status',
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
}