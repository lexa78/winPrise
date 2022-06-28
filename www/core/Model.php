<?php


namespace app\core;

use app\constants\Rules;

abstract class Model
{
    abstract public function rules(): array;

    public array $errors = [];

    public function loadData($requestBody)
    {
        foreach ($requestBody as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function validate()
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
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $ruleName, array $params = [])
    {
        $message = $this->errorMessages()[$ruleName] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace(sprintf('{%s}', $key), $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages()
    {
        return [
            Rules::REQUIRED => 'This field is required',
            Rules::EMAIL => 'This field must be valid email',
            Rules::MIN_LENGTH => sprintf('Min length of this field must be {%s}', Rules::MIN_LENGTH),
            Rules::MAX_LENGTH => sprintf('Max length of this field must be {%s}', Rules::MAX_LENGTH),
            Rules::MATCH => sprintf('This field must be the same as {%s}', Rules::MATCH),
            Rules::UNIQUE => 'Record with this {field} already exists',
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }
}