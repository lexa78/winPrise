<?php
declare(strict_types=1);

namespace app\core\form;

use app\core\Model;

use function sprintf;
/**
 * Class BaseField
 * @package app\core\form
 */
abstract class BaseField
{
    /** @var Model  */
    public Model $model;

    /** @var string  */
    public string $attribute;

    /**
     * BaseField constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    /**
     * @return string
     */
    abstract public function renderInput(): string;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('
            <div class="form-group">
                <label>%s</label>
                    %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
        ', $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }
}