<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Field;
use app\constants\Rules;
use app\core\db\DbModel;

/**
 * Class PriseType
 * @package app\models
 */
class PriseType extends DbModel
{
    /** @var array|string[]  */
    protected array $attributes = [
        'code',
        'name',
        'is_limited',
    ];

    /** @var string  */
    public string $code = '';

    /** @var string  */
    public string $name = '';

    /** @var string  */
    public string $is_limited = Field::CHECKBOX_VALUE_CHECKED;

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'prises_type';
    }

    /**
     * @return array|array[]
     */
    public function rules(): array
    {
        return [
            'code' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 3]],
            'name' => [Rules::REQUIRED, [Rules::MIN_LENGTH, Rules::MIN_LENGTH => 3]],
        ];
    }

    /**
     * @return array|string[]
     */
    public function labels(): array
    {
        return [
            'code' => 'Код типа приза',
            'name' => 'Название типа приза',
            'is_limited' => 'Может ли приз закончиться',
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
     * @param array $requestBody
     */
    public function setNeedleValueToIsLimited(array $requestBody): void
    {
        if (empty($requestBody['is_limited'])) {
            $this->is_limited = Field::CHECKBOX_VALUE_UNCHECKED;
        }
    }
}