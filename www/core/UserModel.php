<?php
declare(strict_types=1);

namespace app\core;

use app\constants\UserRole;
use app\core\db\DbModel;

/**
 * Class UserModel
 * @package app\core
 */
abstract class UserModel extends DbModel
{
    /**
     * @return string
     */
    abstract public function getDisplayName(): string;


    /**
     * @return bool
     */
    public static function isGuest(): bool
    {
        return !Application::$app->user;
    }

    /**
     * @return bool
     */
    public static function isAdmin(): bool
    {
        if (self::isGuest()) {
            return false;
        }
        return Application::$app->user->role === UserRole::ADMINISTRATOR;
    }
}