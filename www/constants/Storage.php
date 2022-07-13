<?php
declare(strict_types=1);

namespace app\constants;

/**
 * Class Storage
 * @package app\constants
 */
class Storage
{
    /** Типы группировок */
    /** @var string  по наименованию призов */
    public const GROUP_BY_PRISES_NAMES = 'prisesNames';
    /** @var string  по группам призов */
    public const GROUP_BY_PRISES_TYPES = 'prisesTypes';

    /** Префиксы названий таблиц */
    /** @var string  префикс t таблицы things */
    public const THINGS_TABLE_PREFIX_T = 't';
    /** @var string  префикс pt таблицы prises_type */
    public const PRISES_TYPE_TABLE_PREFIX_PT = 'pt';

    /** Выбираемые поля таблицы */
    /** @var string поле code */
    public const TABLE_FIELD_CODE = 'code';
    /** @var string поле name */
    public const TABLE_FIELD_NAME = 'name';
    /** @var string поле min_value */
    public const TABLE_FIELD_MIN_VALUE = 'min_value';
    /** @var string поле max_value */
    public const TABLE_FIELD_MAX_VALUE = 'max_value';
    /** @var string поле sumItems */
    public const TABLE_FIELD_SUM_ITEMS = 'sumItems';

    /**
     * @return string[]
     */
    public static function getGroupByOptions(): array
    {
        return [
            self::GROUP_BY_PRISES_NAMES => sprintf(
                '%s.%s', self::THINGS_TABLE_PREFIX_T, self::TABLE_FIELD_CODE
            ),
            self::GROUP_BY_PRISES_TYPES => sprintf(
                '%s.%s', self::PRISES_TYPE_TABLE_PREFIX_PT, self::TABLE_FIELD_CODE
            ),
        ];
    }

    /**
     * @return string[]
     */
    public static function getPrefixes(): array
    {
        return [
            self::GROUP_BY_PRISES_NAMES => self::THINGS_TABLE_PREFIX_T,
            self::GROUP_BY_PRISES_TYPES => self::PRISES_TYPE_TABLE_PREFIX_PT,
        ];
    }
}