<?php
declare(strict_types=1);

namespace app\models;

use app\constants\Rules;
use app\core\db\DbModel;

/**
 * Class Role
 * @package app\models
 */
class Role extends DbModel
{
    /** @var string  */
    public string $code = '';

    /** @var string  */
    public string $name = '';

    /**
     * @return string
     */
    public function tableName(): string
    {
        return 'roles';
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
    public function attributes(): array
    {
        return [
            'code',
            'name',
        ];
    }

    /**
     * @return array|string[]
     */
    public function labels(): array
    {
        return [
            'code' => 'Code of rule',
            'name' => 'Name of rule',
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