<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\core\db\DbModel;

/**
 * Class ChangeCourse
 * @package app\models
 */
class ChangeCourse extends DbModel
{
    /** @var array|string[]  */
    protected array $attributes = [
        'thing_id',
        'course',
    ];

    /** @var string  */
    public string $thing_id = '';

    /** @var string  */
    public string $course = '';

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'change_courses';
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
            'course' => [Rules::REQUIRED, [Rules::MIN_VALUE, Rules::MIN_VALUE => 0.01]],
        ];
    }

    /**
     * @return array|string[]
     */
    public function labels(): array
    {
        return [
            'thing_id' => 'Приз',
            'course' => 'Курс обмена',
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