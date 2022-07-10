<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\core\db\DbModel;

/**
 * Class Thing
 * @package app\models
 */
class Thing extends DbModel
{
    /** @var array|string[]  */
    protected array $attributes = [
        'code',
        'name',
        'prise_id',
        'unit_id',
    ];

    /** @var string  */
    public string $code = '';

    /** @var string  */
    public string $name = '';

    /** @var string  */
    public string $prise_id = '';

    /** @var string  */
    public string $unit_id = '';

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'things';
    }

    /**
     * @return array|array[]
     */
    public function rules(): array
    {
        return [
            'code' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 3]],
            'name' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 3]],
            'prise_id' => [Rules::REQUIRED, [Rules::MIN_VALUE, Rules::MIN_VALUE => 1]],
            'unit_id' => [Rules::REQUIRED, [Rules::MIN_VALUE, Rules::MIN_VALUE => 1]],
        ];
    }

    /**
     * @return array|string[]
     */
    public function labels(): array
    {
        return [
            'code' => 'Код единицы измерения',
            'name' => 'Название единицы измерения',
            'prise_id' => 'Тип приза',
            'unit_id' => 'Единица измерения',
        ];
    }

    /**
     * @return string
     */
    public function primaryKey(): string
    {
        return 'id';
    }
}