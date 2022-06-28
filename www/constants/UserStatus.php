<?php


namespace app\constants;

class UserStatus
{
    /** Статусы регистрации пользователя */
    /** @var int Неактивыный */
    public const INACTIVE = 0;
    /** @var int Активный */
    public const ACTIVE = 1;
    /** @var int Удален */
    public const DELETED = 2;
}