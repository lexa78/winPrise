<?php


namespace app\core;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save()
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

        return true;
    }

    public function findOne($conditions)
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

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}