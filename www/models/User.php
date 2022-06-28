<?php


namespace app\models;

use app\constants\Rules;
use app\constants\UserStatus;
use app\core\UserModel;

class User extends UserModel
{
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public int $status = UserStatus::INACTIVE;

    public function tableName(): string
    {
        return 'users';
    }

    public function save()
    {
        $this->status = UserStatus::INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

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

    public function primaryKey(): string
    {
        return 'id';
    }

    public function getDisplayName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
}