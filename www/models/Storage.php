<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\constants\Storage as StorageConstant;
use app\core\db\DbModel;
use PDO;

use function sprintf;
use function implode;
use function count;
use function array_keys;
use function array_map;
use function str_repeat;
/**
 * Class Storage
 * @package app\models
 */
class Storage extends DbModel
{
    /** @var array|string[]  */
    protected array $attributes = [
        'action_id',
        'thing_id',
        'item_count',
    ];

    /** @var string  */
    public string $action_id = '';

    /** @var string  */
    public string $thing_id = '';

    /** @var string  */
    public string $item_count = '';

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'storage';
    }

    /**
     * @return array|array[]
     */
    public function rules(): array
    {
        return [
            'action_id' => [Rules::REQUIRED, [Rules::MIN_VALUE, Rules::MIN_VALUE => 1]],
            'thing_id' => [Rules::REQUIRED, [Rules::MIN_VALUE, Rules::MIN_VALUE => 1]],
            'item_count' => [Rules::REQUIRED],
        ];
    }

    /**
     * @return array|string[]
     */
    public function labels(): array
    {
        return [
            'action_id' => 'Действие',
            'thing_id' => 'Ценная вещь',
            'item_count' => 'Количество',
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
     * @param string $groupBy
     * @param string $prefix
     * @param array $conditions
     * @param array $extraColumn
     * @return array
     */
    public function findAllLeftGroupBy(
        string $groupBy,
        string $prefix = '',
        array $conditions = [],
        array $extraColumn = []): array
    {
        $tableName = $this->tableName();
        $extraColumnNames = '';
        $selectString = sprintf(
            'SUM(s.item_count) AS sumItems, %s', StorageConstant::getGroupByOptions()[$groupBy]
        );
        $groupByString = sprintf('GROUP BY %s', StorageConstant::getGroupByOptions()[$groupBy]);
        if (count($extraColumn) > 0) {
            $extraColumnNames = implode(
                ', ',
                array_map(fn($exCol) => sprintf('%s.%s', $prefix, $exCol), $extraColumn)
            );
        }
        if (!empty($extraColumnNames)) {
            $selectString = sprintf(
                $selectString . str_repeat(', %s', count($extraColumn)),
                $extraColumnNames
            );
            $groupByString = sprintf(
                $groupByString . str_repeat(', %s', count($extraColumn)),
                $extraColumnNames
            );
        }

        $attributes = array_keys($conditions);
        $whereString = implode(
            ' AND ',
            array_map(fn($attr) => sprintf('%s.%s = :%s', $prefix, $attr, $attr), $attributes)
        );
        $whereQueryPart = '';
        if (!empty($whereString)) {
            $whereQueryPart = sprintf('WHERE %s', $whereString);
        }

        $query = sprintf('
                SELECT %s 
                FROM %s s
                JOIN things t ON s.thing_id = t.id
                JOIN prises_type pt ON t.prise_id = pt.id
                %s
                %s;',
            $selectString,
            $tableName,
            $whereQueryPart,
            $groupByString
        );

        $statement = self::prepare($query);
        if (!empty($whereString)) {
            foreach ($conditions as $key => $value) {
                $statement->bindValue(sprintf(':%s', $key), $value);
            }
        }
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}