<?php


namespace app\models;

use app\constants\Rules;
use app\core\Application;
use app\core\Model;

class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            'email' => [Rules::REQUIRED, Rules::EMAIL,],
            'password' => [Rules::REQUIRED,],
        ];
    }

    public function login()
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

    public function labels(): array
    {
        return [
            'email' => 'Enter your email',
            'password' => 'Enter your password',
        ];
    }
}