<?php
declare(strict_types=1);

namespace app\core\form;

use app\core\Model;

use function sprintf;
/**
 * Class Form
 * @package app\core\form
 */
class Form
{
    /**
     * @param string $action
     * @param string $method
     * @return Form
     */
    public static function begin(string $action, string $method): Form
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    /**
     * @return string
     */
    public static function end(): string
    {
        return '<\form>';
    }

    /**
     * @param Model $model
     * @param string $attribute
     * @return Field
     */
    public function field(Model $model, string $attribute): Field
    {
        return new Field($model, $attribute);
    }
}