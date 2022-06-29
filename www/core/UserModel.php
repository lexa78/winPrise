<?php
declare(strict_types=1);

namespace app\core;

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
}