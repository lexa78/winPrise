<?php

namespace app\migrations;

use app\core\Migration;

class m0002_test extends Migration
{
    public function up()
    {
        $this->db->pdo->exec('ALTER TABLE users ADD COLUMN password VARCHAR(25) NOT NULL');
    }

    public function down()
    {
        $this->db->pdo->exec('ALTER TABLE users DROP COLUMN password');
    }
}
