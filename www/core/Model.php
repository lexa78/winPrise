<?php
declare(strict_types=1);

namespace app\core;

use app\constants\Rules;
use app\constants\Controller as ControllerConstant;

use function property_exists;
use function is_string;
use function is_array;
use function filter_var;
use function strlen;
use function sprintf;
use function str_replace;

/**
 * Class Model
 * @package app\core
 */
abstract class Model
{
    /**
     * @return array
     */
    abstract public function rules(): array;

    /** @var array  */
    public array $errors = [];

    /**
     * @param array $requestBody
     */
    public function loadData(array $requestBody): void
    {
        foreach ($requestBody as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = '';
                if (is_string($rule)) {
                    $ruleName = $rule;
                } elseif (is_array($rule)) {
                    $ruleName = $rule[0];
                }

                switch ($ruleName) {
                    case Rules::REQUIRED:
                        if (empty($value)) {
                            $this->addError($attribute, $ruleName);
                        }
                        break;
                    case Rules::EMAIL:
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($attribute, $ruleName);
                        }
                        break;
                    case Rules::MIN_LENGTH:
                        if (strlen($value) < $rule[Rules::MIN_LENGTH]) {
                            $this->addError($attribute, $ruleName, $rule);
                        }
                        break;
                    case Rules::MAX_LENGTH:
                        if (strlen($value) > $rule[Rules::MAX_LENGTH]) {
                            $this->addError($attribute, $ruleName, $rule);
                        }
                        break;
                    case Rules::MATCH:
                        if ($value !== $this->{$rule[Rules::MATCH]}) {
                            $rule[Rules::MATCH] = $this->getLabel($rule[Rules::MATCH]);
                            $this->addError($attribute, $ruleName, $rule);
                        }
                        break;
                    case Rules::UNIQUE:
                        if (Application::$app->controller->action !== ControllerConstant::EDIT_ACTION) {
                            $className = $rule['class'];
                            $uniqueAttribute = $rule['attribute'] ?? $attribute;
                            $tableName = $className::tableName();
                            $model = new $className();
                            $statement = $model->prepare(
                                sprintf('SELECT * FROM %s WHERE %s = :attr', $tableName, $uniqueAttribute)
                            );
                            $statement->bindValue(':attr', $value);
                            $statement->execute();
                            $record = $statement->fetchObject();
                            if ($record) {
                                $this->addError($attribute, $ruleName, ['field' => $this->getLabel($attribute)]);
                            }
                        }
                        break;
                    case Rules::MIN_VALUE:
                        if ((float) $value < $rule[Rules::MIN_VALUE]) {
                            $this->addError($attribute, $ruleName, $rule);
                        }
                        break;
                    case Rules::MAX_VALUE:
                        if ((float) $value > $rule[Rules::MAX_VALUE]) {
                            $this->addError($attribute, $ruleName, $rule);
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * @param string $attribute
     * @param string $ruleName
     * @param array $params
     */
    public function addError(string $attribute, string $ruleName, array $params = []): void
    {
        $message = $this->errorMessages()[$ruleName] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace(sprintf('{%s}', $key), $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            Rules::REQUIRED => 'This field is required',
            Rules::EMAIL => 'This field must be valid email',
            Rules::MIN_LENGTH => sprintf('Min length of this field must be {%s}', Rules::MIN_LENGTH),
            Rules::MAX_LENGTH => sprintf('Max length of this field must be {%s}', Rules::MAX_LENGTH),
            Rules::MATCH => sprintf('This field must be the same as {%s}', Rules::MATCH),
            Rules::UNIQUE => 'Record with this {field} already exists',
            Rules::MIN_VALUE => sprintf('Min value of this field must be {%s}', Rules::MIN_VALUE),
            Rules::MAX_VALUE => sprintf('Max value of this field must be {%s}', Rules::MAX_VALUE),
        ];
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function hasError(string $attribute): bool
    {
        return !empty($this->errors[$attribute]);
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getFirstError($attribute): string
    {
        return $this->errors[$attribute][0] ?? '';
    }

    /**
     * @return array
     */
    public function labels(): array
    {
        return [];
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getLabel(string $attribute): string
    {
        return $this->labels()[$attribute] ?? $attribute;
    }
}