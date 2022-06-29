<?php
declare(strict_types=1);

namespace app\core;

use app\core\db\Database;

/**
 * Class Migration
 * @package app\core
 */
class Migration
{
    /** @var Database  */
    protected Database $db;

    /**
     * Migration constructor.
     */
    public function __construct()
    {
        $this->db = Application::$app->db;
    }
}