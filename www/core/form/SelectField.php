<?php
declare(strict_types=1);

namespace app\core\form;

use app\constants\Field;
use app\core\db\DbModel;

use function sprintf;
/**
 * Class SelectField
 * @package app\core\form
 */
class SelectField extends BaseField
{
    /** @var string  */
    public string $type;

    /** @var array  */
    public array $optionsValue;

    /**
     * SelectField constructor.
     * @param DbModel $model
     * @param string $attribute
     * @param array $optionsValue
     */
    public function __construct(DbModel $model, string $attribute, array $optionsValue = [])
    {
        $this->type = Field::TYPE_SELECT;
        $this->optionsValue = $optionsValue;
        parent::__construct($model, $attribute);
    }

    /**
     * @return string
     */
    public function renderInput(): string
    {
        return sprintf('<select name="%s" class="form-control %s">%s</select>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->renderOptions()
        );
    }

    protected function renderOptions(): string
    {
        $result = [];
        foreach ($this->optionsValue as $option) {
            $result[] = sprintf('<option value="%s" %s>%s</option>',
                empty($option[$this->model->primaryKey()]) ? '-' : $option[$this->model->primaryKey()],
                (!empty($option[$this->model->primaryKey()])
                    && ($option[$this->model->primaryKey()] === $this->model->{$this->attribute})) ? 'selected' : '',
                empty($option['name']) ? 'Undefined' : $option['name']
            );
        }
        return join('', $result);
    }
}