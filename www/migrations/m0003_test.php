<?php

namespace app\migrations;

use app\core\Migration;

class m0003_test extends Migration
{
    public function up()
    {
        $this->db->pdo->exec('ALTER TABLE users ADD COLUMN status TINYINT DEFAULT 0');
    }

    public function down()
    {
        $this->db->pdo->exec('ALTER TABLE users DROP COLUMN status');
    }
}
