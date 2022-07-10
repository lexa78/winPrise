<?php
declare(strict_types=1);

namespace app\core\form;

use app\core\Model;
use app\constants\Field as FieldConstant;

use function sprintf;
/**
 * Class Field
 * @package app\core\form
 */
class InputField extends BaseField
{
    /** @var string  */
    public string $type;

    /**
     * Field constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = FieldConstant::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    /**
     * @return $this
     */
    public function passwordField(): InputField
    {
        $this->type = FieldConstant::TYPE_PASSWORD;
        return $this;
    }

    /**
     * @return string
     */
    public function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control %s">',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
        );
    }
}