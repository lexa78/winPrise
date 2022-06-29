<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\core\Application;
use app\core\Model;

use function password_verify;

/**
 * Class LoginForm
 * @package app\models
 */
class LoginForm extends Model
{
    /** @var string  */
    public string $email = '';

    /** @var string  */
    public string $password = '';

    /**
     * @return array|array[]
     */
    public function rules(): array
    {
        return [
            'email' => [Rules::REQUIRED, Rules::EMAIL,],
            'password' => [Rules::REQUIRED,],
        ];
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        /** @var User $user */
        $user = (new User())->findOne([
            'email' => $this->email,
        ]);

        if (!$user || !password_verify($this->password, $user->password)) {
            Application::$app->session->setFlash('error', 'Email or password is wrong!');
            return false;
        }

        return Application::$app->login($user);
    }

    /**
     * @return array|string[]
     */
    public function labels(): array
    {
        return [
            'email' => 'Enter your email',
            'password' => 'Enter your password',
        ];
    }
}