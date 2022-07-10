<?php
declare(strict_types=1);

namespace app\core\db;

use app\core\Application;
use app\core\Model;

use PDO;
use function array_map;
use function sprintf;
use function implode;

/**
 * Class DbModel
 * @package app\core\db
 */
abstract class DbModel extends Model
{
    protected array $attributes = [];
    /**
     * @return string
     */
    abstract public function tableName(): string;

    /**
     * @return string
     */
    abstract public function primaryKey(): string;

    /**
     * @return array|string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $attribute
     */
    public function addAttribute(string $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    /**
     * @return string
     */
    public function save(): string
    {
        $params = array_map(fn($attr) => sprintf(':%s', $attr), $this->getAttributes());
        return $this->executeQuery(
            sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $this->tableName(),
                implode(',', $this->getAttributes()),
                implode(',', $params)
            )
        );
    }

    /**
     * @param string $identifier
     * @return string
     */
    public function update(string $identifier): string
    {
        $setPart = sprintf('SET %s',
            implode(', ', array_map(fn($attr) => sprintf('%s = :%s', $attr, $attr), $this->getAttributes()))
        );

        $this->addAttribute($this->primaryKey());
        $this->{$this->primaryKey()} = $identifier;

        return $this->executeQuery(
            sprintf(
                'UPDATE %s %s WHERE %s = :%s',
                $this->tableName(),
                $setPart,
                $this->primaryKey(),
                $this->primaryKey()
            )
        );
    }

    /**
     * @param string $query
     * @return string
     */
    protected function executeQuery($query): string
    {
        $statement = self::prepare($query);
        foreach ($this->getAttributes() as $attribute) {
            $statement->bindValue(sprintf(':%s', $attribute), $this->{$attribute});
        }

        $statement->execute();
        return Application::$app->db->pdo->lastInsertId() ? Application::$app->db->pdo->lastInsertId() : '1';
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
     * @param array $conditions
     * @return array|false
     */
    public function findAll(array $conditions = [])
    {
        $tableName = $this->tableName();
        $attributes = array_keys($conditions);
        $whereString = implode(' AND ', array_map(fn($attr) => sprintf('%s = :%s', $attr, $attr), $attributes));
        $query = sprintf('SELECT * FROM %s ', $tableName);
        if (!empty($whereString)) {
            $query .= sprintf('WHERE %s', $whereString);
        }
        $statement = self::prepare($query);
        foreach ($conditions as $key => $value) {
            $statement->bindValue(sprintf(':%s', $key), $value);
        }

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $conditions
     * @return bool
     */
    public function delete(array $conditions = []): bool
    {
        $tableName = $this->tableName();
        $attributes = array_keys($conditions);
        $whereString = implode(' AND ', array_map(fn($attr) => sprintf('%s = :%s', $attr, $attr), $attributes));
        $query = sprintf('DELETE FROM %s ', $tableName);
        if (!empty($whereString)) {
            $query .= sprintf('WHERE %s', $whereString);
        }
        $statement = self::prepare($query);
        foreach ($conditions as $key => $value) {
            $statement->bindValue(sprintf(':%s', $key), $value);
        }

        $statement->execute();
        return true;
    }

    /**
     * @param string $sql
     * @return bool|\PDOStatement
     */
    public static function prepare(string $sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function findAllWithPrimaryKeyAsArrayKey(array $conditions = []): array
    {
        $result = $this->findAll($conditions);
        if (!is_array($result)) {
            $result = [];
        }

        $response = [];
        foreach ($result as $item) {
            if (empty($item[$this->primaryKey()])) {
                continue;
            }
            $response[$item[$this->primaryKey()]] = $item;
        }

        return $response;
    }
}