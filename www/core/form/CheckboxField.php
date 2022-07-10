<?php
declare(strict_types=1);

namespace app\core\form;

use app\constants\Field;
use app\core\Model;

use function sprintf;
/**
 * Class CheckboxField
 * @package app\core\form
 */
class CheckboxField extends BaseField
{
    /** @var string  */
    public string $type;

    /**
     * CheckboxField constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = Field::TYPE_CHECKBOX;
        parent::__construct($model, $attribute);
    }

    /**
     * @return string
     */
    public function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="1" class="form-control %s" %s />',
            $this->type,
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            empty($this->model->{$this->attribute}) ? '' : 'checked',
        );
    }
}