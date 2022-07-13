<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\core\db\DbModel;
use PDO;

/**
 * Class Limit
 * @package app\models
 */
class Limit extends DbModel
{
    /** @var array|string[]  */
    protected array $attributes = [
        'thing_id',
        'min_value',
        'max_value',
    ];

    /** @var string  */
    public string $thing_id = '';

    /** @var string  */
    public string $min_value = '';

    /** @var string  */
    public string $max_value = '';

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'limits';
    }

    /**
     * @return array|array[]
     */
    public function rules(): array
    {
        return [
            'thing_id' => [
                Rules::REQUIRED,
                [Rules::MIN_VALUE, Rules::MIN_VALUE => 1],
                [
                    Rules::UNIQUE,
                    'class' => self::class,
                ],
            ],
            'min_value' => [Rules::REQUIRED, [Rules::MIN_VALUE, Rules::MIN_VALUE => 1]],
            'max_value' => [Rules::REQUIRED, [Rules::MAX_VALUE, Rules::MAX_VALUE => 4000000000]],
        ];
    }

    /**
     * @return array|string[]
     */
    public function labels(): array
    {
        return [
            'thing_id' => 'Приз',
            'min_value' => 'Минимальное количество, которое можно выиграть',
            'max_value' => 'Максимальное количество, которое можно выиграть',
        ];
    }

    /**
     * @return string
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * @param array $condition
     * @return array
     */
    public function findMaxLimitsFor(array $condition = []): array
    {
        $tableName = $this->tableName();
        $inQuery = implode(',', array_fill(0, count(current($condition)), '?'));
        $query = sprintf('
            SELECT t.code, l.max_value, l.min_value
            FROM %s l
            JOIN things t ON t.id = l.thing_id 
            WHERE t.%s IN (%s)',
            $tableName,
            key($condition),
            $inQuery
        );
        $statement = self::prepare($query);

        foreach (current($condition) as $key => $value) {
            $statement->bindValue(($key + 1), $value);
        }
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}