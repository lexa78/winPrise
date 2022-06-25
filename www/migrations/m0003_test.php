<?php

namespace app\migrations;

use app\core\Migration;

class m0003_test extends Migration
{
    public function up()
    {
        $this->db->pdo->exec('ALTER TABLE users ADD COLUMN role TINYINT NOT NULL');
    }

    public function down()
    {
        $this->db->pdo->exec('ALTER TABLE users DROP COLUMN role');
    }
}
