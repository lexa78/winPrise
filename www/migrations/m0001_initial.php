<?php

namespace app\migrations;

use app\core\exception\RuntimeException;
use app\core\Migration;
use PDO;
use app\constants\Game;

use function password_hash;
use function sprintf;

/**
 * Class m0001_initial
 * @package app\migrations
 */
class m0001_initial extends Migration
{
    /** @var array  */
    private array $prises_type = [];

    /** @var array  */
    private array $units = [];

    /** @var array  */
    private array $events = [];

    /** @var array  */
    private array $things = [];

    /** @var array  */
    private array $users = [];

    /** @var array  */
    private array $roles = [];
    
    /**
     * @return void
     */
    public function up(): void
    {
        $query = 'CREATE TABLE roles (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL,
            name VARCHAR(100) NOT NULL
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'User`s roles\';';
        $query .= ' CREATE TABLE users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            firstName VARCHAR(255) NOT NULL,
            lastName VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_email (email)  
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'All users of this app\';';
        $query .= ' CREATE TABLE role_user (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            role_id INT UNSIGNED NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (role_id) REFERENCES roles(id)
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'Pivot table to users and their roles\';';
        $query .= ' CREATE TABLE events (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL,
            name VARCHAR(100) NOT NULL
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'List of events that can happen\';';
        $query .= ' CREATE TABLE prises_type (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL,
            name VARCHAR(100) NOT NULL,
            is_limited BOOL NOT NULL DEFAULT 0
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'List of prises type\';';
        $query .= ' CREATE TABLE units (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL,
            name VARCHAR(100) NOT NULL
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'Unit of measure\';';
        $query .= ' CREATE TABLE things (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL,
            name VARCHAR(100) NOT NULL,
            prise_id INT UNSIGNED NOT NULL,
            unit_id INT UNSIGNED NOT NULL,
            FOREIGN KEY (prise_id) REFERENCES prises_type(id),
            FOREIGN KEY (unit_id) REFERENCES units(id)
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'List of things that will be as prise\';';
        $query .= ' CREATE TABLE actions (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            event_id INT UNSIGNED NOT NULL,
            action_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            thing_id INT UNSIGNED NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            FOREIGN KEY (event_id) REFERENCES events(id),
            FOREIGN KEY (thing_id) REFERENCES things(id),
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'Actions that was with prises\';';
        $query .= ' CREATE TABLE storage (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            action_id INT UNSIGNED NOT NULL,
            thing_id INT UNSIGNED NOT NULL,
            item_count INT NOT NULL DEFAULT 0,
            FOREIGN KEY (action_id) REFERENCES actions(id),
            FOREIGN KEY (thing_id) REFERENCES things(id)
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'Balance of all prizes except not limited\';';
        $query .= ' CREATE TABLE users_prise (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            count_in_total INT UNSIGNED NOT NULL DEFAULT 0,
            action_id INT UNSIGNED NOT NULL,
            thing_id INT UNSIGNED NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (action_id) REFERENCES actions(id),
            FOREIGN KEY (thing_id) REFERENCES things(id)
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'All user`s won prises mainly for count user`s bonus points\';';
        $query .= ' CREATE TABLE change_courses (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            course FLOAT(7, 2) NOT NULL,
            thing_id INT UNSIGNED NOT NULL,
            FOREIGN KEY (thing_id) REFERENCES things(id),
            UNIQUE KEY unique_course_thing (thing_id)  
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'Courses of change money for bonus points\';';
        $query .= ' CREATE TABLE limits (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            thing_id INT UNSIGNED NOT NULL,
            min_value INT UNSIGNED NOT NULL,
            max_value INT UNSIGNED NOT NULL,
            FOREIGN KEY (thing_id) REFERENCES things(id),
            UNIQUE KEY unique_limit_thing (thing_id)
        ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT=\'Limits for money to win\';';

        $this->db->pdo->exec($query);

        $query = ' INSERT INTO roles (code, name)
            VALUES (\'admin\', \'Администратор\'), (\'user\', \'Пользователь\'), (\'postman\', \'Отправитель призов\');';
        $this->db->pdo->exec($query);

        $query = sprintf(' INSERT INTO users (email, firstName, lastName, password)
            VALUES (
            \'admin@gmail.com\', 
            \'admin\', 
            \'admin\', 
            \'%s\');',
            password_hash('admin', PASSWORD_DEFAULT),
        );
        $this->db->pdo->exec($query);

        $query = sprintf(' INSERT INTO role_user (user_id, role_id)
            VALUES (%s, %s);',
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('admin', 'roles')
        );
        $this->db->pdo->exec($query);

        $query = ' INSERT INTO events (code, name)
            VALUES (\'coming\', \'Поступление\'),
            (\'change\', \'Обмен денег на баллы\'),
            (\'expenditure\', \'Расход\'),
            (\'frozen\', \'Резерв\'),
            (\'huylo\', \'"Спецоперация"\');';
        $this->db->pdo->exec($query);

        $query = sprintf(' INSERT INTO prises_type (code, name, is_limited)
            VALUES (\'%s\', \'Ценная вещь\', 1),
            (\'%s\', \'Денежный приз\', 1),
            (\'%s\', \'Бонусные баллы\', 0);',
            Game::VALUABLE_THING_PRISE,
            Game::MONEY_PRISE,
            Game::BONUS_POINT_PRISE
        );
        $this->db->pdo->exec($query);

        $query = ' INSERT INTO units (code, name)
            VALUES (\'piece\', \'шт.\'),
            (\'kilogram\', \'кг.\'),
            (\'box\', \'кор.\'),
            (\'usd\', \'USD\'),
            (\'eur\', \'EUR\'),
            (\'jpy\', \'JPY\'),
            (\'rub\', \'rub\');';
        $this->db->pdo->exec($query);

        $query = sprintf(' INSERT INTO things (code, name, prise_id, unit_id)
            VALUES (\'computer_mouse\', \'Компьютерная мышь\', %s, %s),
            (\'keyboard\', \'Клавиатура\', %s, %s),
            (\'system_block\', \'Системный блок\', %s, %s),
            (\'monitor\', \'Монитор\', %s, %s),
            (\'loudspeakers\', \'Акустическая система\', %s, %s),
            (\'phone\', \'Телефон\', %s, %s),
            (\'pad\', \'Планшет\', %s, %s),
            (\'usd_money\', \'Доллары США\', %s, %s),
            (\'eur_money\', \'Валюта Европейского Союза\', %s, %s),
            (\'jpy_money\', \'Валюта Японии\', %s, %s),
            (\'rub_paper\', \'Валюта рабсии\', %s, %s),
            (\'cereal\', \'Зерно\', %s, %s),
            (\'washing_machine\', \'Стиральная машина\', %s, %s),
            (\'toilet\', \'Унитаз\', %s, %s);',
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('piece', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('piece', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('piece', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('piece', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('box', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('piece', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('piece', 'units'),
            $this->getRowIdByCode(Game::MONEY_PRISE, 'prises_type'),
            $this->getRowIdByCode('usd', 'units'),
            $this->getRowIdByCode(Game::MONEY_PRISE, 'prises_type'),
            $this->getRowIdByCode('eur', 'units'),
            $this->getRowIdByCode(Game::MONEY_PRISE, 'prises_type'),
            $this->getRowIdByCode('jpy', 'units'),
            $this->getRowIdByCode(Game::MONEY_PRISE, 'prises_type'),
            $this->getRowIdByCode('rub', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('kilogram', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('piece', 'units'),
            $this->getRowIdByCode(Game::VALUABLE_THING_PRISE, 'prises_type'),
            $this->getRowIdByCode('piece', 'units')
        );
        $this->db->pdo->exec($query);

        $query = sprintf(' INSERT INTO actions (event_id, thing_id, user_id)
            VALUES (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s),
            (%s, %s, %s);',
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('computer_mouse', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('keyboard', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('system_block', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('monitor', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('loudspeakers', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('phone', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('pad', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('usd_money', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('eur_money', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('jpy_money', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('coming', 'events'),
            $this->getRowIdByCode('rub_paper', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('huylo', 'events'),
            $this->getRowIdByCode('cereal', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('huylo', 'events'),
            $this->getRowIdByCode('washing_machine', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email'),
            $this->getRowIdByCode('huylo', 'events'),
            $this->getRowIdByCode('toilet', 'things'),
            $this->getRowIdByCode('admin@gmail.com', 'users', 'email')
        );
        $this->db->pdo->exec($query);

        $query = sprintf(' INSERT INTO storage (action_id, thing_id, item_count)
            VALUES (%s, %s, 10),
            (%s, %s, 10),
            (%s, %s, 10),
            (%s, %s, 10),
            (%s, %s, 10),
            (%s, %s, 10),
            (%s, %s, 10),
            (%s, %s, 10000),
            (%s, %s, 10000),
            (%s, %s, 1000000),
            (%s, %s, 10000000),
            (%s, %s, 1000000000),
            (%s, %s, 10000),
            (%s, %s, 100000);',
            $this->getRowIdByCode(
                $this->getRowIdByCode('computer_mouse', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('computer_mouse', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('keyboard', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('keyboard', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('system_block', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('system_block', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('monitor', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('monitor', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('loudspeakers', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('loudspeakers', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('phone', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('phone', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('pad', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('pad', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('usd_money', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('usd_money', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('eur_money', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('eur_money', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('jpy_money', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('jpy_money', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('rub_paper', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('rub_paper', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('cereal', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('cereal', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('washing_machine', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('washing_machine', 'things'),
            $this->getRowIdByCode(
                $this->getRowIdByCode('toilet', 'things'),
                'actions',
                'thing_id'
            ),
            $this->getRowIdByCode('toilet', 'things'),
        );
        $this->db->pdo->exec($query);

        $query = sprintf(' INSERT INTO change_courses (thing_id, course)
            VALUES (%s, 100),
            (%s, 100),
            (%s, 100),
            (%s, 1);',
            $this->getRowIdByCode('usd_money', 'things'),
            $this->getRowIdByCode('eur_money', 'things'),
            $this->getRowIdByCode('jpy_money', 'things'),
            $this->getRowIdByCode('rub_paper', 'things')
        );
        $this->db->pdo->exec($query);

        $query = sprintf(' INSERT INTO limits (thing_id, min_value, max_value)
            VALUES (%s, 1, 100),
            (%s, 1, 100),
            (%s, 50, 1000),
            (%s, 1, 10000);',
            $this->getRowIdByCode('usd_money', 'things'),
            $this->getRowIdByCode('eur_money', 'things'),
            $this->getRowIdByCode('jpy_money', 'things'),
            $this->getRowIdByCode('rub_paper', 'things')
        );
        $this->db->pdo->exec($query);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->db->pdo->exec('DROP DATABASE get_prise');
        $this->db->pdo->exec('CREATE DATABASE get_prise');
    }

    /**
     * @param mixed $code
     * @param string $tableName
     * @param string $needleField
     * @return int
     * @throws RuntimeException
     */
    private function getRowIdByCode($code, string $tableName, string $needleField = 'code'): int
    {
        if (is_callable($code)) {
            $code = $code();
        }
        if (empty($this->{$tableName}[$code])) {
            $statement = $this->db->pdo->prepare(
                sprintf('SELECT id FROM %s WHERE %s = :parameter LIMIT 1', $tableName, $needleField)
            );
            $statement->bindValue(':parameter', $code);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if (empty($result['id'])) {
                throw new RuntimeException(sprintf('Code %s in table %s not found', $code, $tableName));
            }

            $this->{$tableName}[$code] = $result['id'];
        }

        return $this->{$tableName}[$code];
    }
}
