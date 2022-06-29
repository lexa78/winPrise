<?php
declare(strict_types=1);

namespace app\core\middlewares;

/**
 * Class BaseMiddleware
 * @package app\core\middlewares
 */
abstract class BaseMiddleware
{
    /**
     * @return void
     */
    abstract public function execute(): void;
}