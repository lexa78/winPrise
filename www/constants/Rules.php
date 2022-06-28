<?php


namespace app\constants;

class Rules
{
    /** Правила валидации */
    /** @var string Обязателен */
    public const REQUIRED = 'required';
    /** @var string Валидация электронного кошелька */
    public const EMAIL = 'email';
    /** @var string Минимальная длина строки */
    public const MIN_LENGTH = 'min';
    /** @var string Максимальная длина строки */
    public const MAX_LENGTH = 'max';
    /** @var string Должно совпадать с каким-то значением */
    public const MATCH = 'match';
    /** @var string Должно быть уникальным */
    public const UNIQUE = 'unique';
}