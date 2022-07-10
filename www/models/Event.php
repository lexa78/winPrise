<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\core\db\DbModel;

/**
 * Class Event
 * @package app\models
 */
class Event extends DbModel
{
    /** @var array|string[]  */
    protected array $attributes = [
        'code',
        'name',
    ];

    /** @var string  */
    public string $code = '';

    /** @var string  */
    public string $name = '';

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'events';
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
            'code' => 'Код события',
            'name' => 'Название события',
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