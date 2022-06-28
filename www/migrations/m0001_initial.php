<?php

namespace app\migrations;

use app\core\Migration;

class m0001_initial extends Migration
{
    public function up()
    {
        $query = 'CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            firstName VARCHAR(255) NOT NULL,
            lastName VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ) ENGINE=INNODB;';

        $this->db->pdo->exec($query);
    }

    public function down()
    {
        $this->db->pdo->exec('DROP TABLE users');
    }
}
