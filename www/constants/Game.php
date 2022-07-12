<?php
declare(strict_types=1);

namespace app\constants;

/**
 * Class Game
 * @package app\constants
 */
class Game
{
    /** Типы призов */
    /** @var string  денежный приз */
    public const MONEY_PRISE = 'money';
    /** @var string  ценный приз */
    public const VALUABLE_THING_PRISE = 'valuableThing';
    /** @var string  баллы лояльности */
    public const BONUS_POINT_PRISE = 'bonusPoint';
}