<?php
declare(strict_types=1);

namespace app\constants;

/**
 * Class Migration
 * @package app\constants
 */
class Migration
{
    /** Названия коротких и длинных параметров, передаваемых в скрипт migrations.php */
    /** @var string применить миграцию */
    public const SHORT_OPTION_UP = 'u';
    /** @var string откатить миграцию */
    public const SHORT_OPTION_DOWN = 'd';
    /** @var string номер или целое название файла с миграцией */
    public const SHORT_OPTION_NUMBER = 'n';
    /** @var string посмотреть описание скрипта */
    public const SHORT_OPTION_HELP = 'h';
    /** @var string применить миграцию */
    public const LONG_OPTION_UP = 'up';
    /** @var string откатить миграцию */
    public const LONG_OPTION_DOWN = 'down';
    /** @var string номер или целое название файла с миграцией */
    public const LONG_OPTION_NUMBER = 'number';
    /** @var string посмотреть описание скрипта */
    public const LONG_OPTION_HELP = 'help';

    /** Направление сортировки */
    /** @var string Прямая */
    public const ORDER_WAY_ASC = 'ASC';
    /** @var string Обратная */
    public const ORDER_WAY_DESC = 'DESC';
}