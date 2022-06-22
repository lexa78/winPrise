<?php


namespace app\models;

use app\core\Model;
use app\constants\Rules;

class RegisterModel extends Model
{
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';

    public function register()
    {
        echo 'Creating new user';
    }

    public function rules(): array
    {
        return [
            'firstName' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 3]],
            'lastName' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 3]],
            'email' => [Rules::REQUIRED, Rules::EMAIL],
            'password' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 8], [Rules::MAX_LENGTH, Rules::MAX_LENGTH => 24]],
            'passwordConfirm' => [Rules::REQUIRED, [Rules::MATCH, Rules::MATCH => 'password']],
        ];
    }
}