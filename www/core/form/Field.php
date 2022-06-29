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
class Field
{
    /** @var string  */
    public string $type;

    /** @var Model  */
    public Model $model;

    /** @var string  */
    public string $attribute;

    /**
     * Field constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = FieldConstant::TYPE_TEXT;
        $this->model = $model;
        $this->attribute = $attribute;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('
            <div class="form-group">
                <label>%s</label>
                <input type="%s" name="%s" value="%s" class="form-control %s">
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
        ', $this->model->getLabel($this->attribute),
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->getFirstError($this->attribute)
        );
    }

    /**
     * @return $this
     */
    public function passwordField(): Field
    {
        $this->type = FieldConstant::TYPE_PASSWORD;
        return $this;
    }
}