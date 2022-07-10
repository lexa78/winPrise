<?php
declare(strict_types=1);

namespace app\constants;

/**
 * Class Rules
 * @package app\constants
 */
class Rules
{
    /** Правила валидации */
    /** @var string Обязателен */
    public const REQUIRED = 'required';
    /** @var string Валидация электронного кошелька */
    public const EMAIL = 'email';
    /** @var string Минимальная длина строки */
    public const MIN_LENGTH = 'min_length';
    /** @var string Максимальная длина строки */
    public const MAX_LENGTH = 'max_length';
    /** @var string Должно совпадать с каким-то значением */
    public const MATCH = 'match';
    /** @var string Должно быть уникальным */
    public const UNIQUE = 'unique';
    /** @var string Минимальное значение */
    public const MIN_VALUE = 'min_value';
    /** @var string Максимальное значение */
    public const MAX_VALUE = 'max_value';
}