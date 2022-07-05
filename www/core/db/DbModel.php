<?php
declare(strict_types=1);

namespace app\core\db;

use app\core\Application;
use app\core\Model;

use function array_map;
use function sprintf;
use function implode;

/**
 * Class DbModel
 * @package app\core\db
 */
abstract class DbModel extends Model
{
    /**
     * @return string
     */
    abstract public function tableName(): string;

    /**
     * @return array
     */
    abstract public function attributes(): array;

    /**
     * @return string
     */
    abstract public function primaryKey(): string;

    /**
     * @return string
     */
    public function save(): string
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => sprintf(':%s', $attr), $attributes);
        $statement = self::prepare(
            sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $tableName,
                implode(',', $attributes),
                implode(',', $params)
            )
        );

        foreach ($attributes as $attribute) {
            $statement->bindValue(sprintf(':%s', $attribute), $this->{$attribute});
        }

        $statement->execute();
        return Application::$app->db->pdo->lastInsertId();
    }

    /**
     * @param array $conditions
     * @return DbModel|false
     */
    public function findOne(array $conditions)
    {
        $tableName = $this->tableName();
        $attributes = array_keys($conditions);
        $whereString = implode(' AND ', array_map(fn($attr) => sprintf('%s = :%s', $attr, $attr), $attributes));
        $statement = self::prepare(sprintf('SELECT * FROM %s WHERE %s LIMIT 1', $tableName, $whereString));
        foreach ($conditions as $key => $value) {
            $statement->bindValue(sprintf(':%s', $key), $value);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    /**
     * @param string $sql
     * @return bool|\PDOStatement
     */
    public static function prepare(string $sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}