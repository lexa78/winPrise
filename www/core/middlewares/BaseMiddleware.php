<?php
declare(strict_types=1);

namespace app\core\middlewares;

/**
 * Class BaseMiddleware
 * @package app\core\middlewares
 */
abstract class BaseMiddleware
{
    /** @var array  */
    public array $actions = [];

    /**
     * AuthMiddleware constructor.
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * @return void
     */
    abstract public function execute(): void;
}