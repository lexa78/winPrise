<?php
declare(strict_types=1);

namespace app\constants;

/**
 * Class UserRole
 * @package app\constants
 */
class UserRole
{
    /** Роли пользователей */
    /** @var string Администратор */
    public const ADMINISTRATOR = 'admin';
    /** @var string Обычный пользователь */
    public const NORMAL_USER = 'user';
}