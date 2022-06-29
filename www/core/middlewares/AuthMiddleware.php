<?php
declare(strict_types=1);

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

use function count;
use function in_array;

/**
 * Class AuthMiddleware
 * @package app\core\middlewares
 */
class AuthMiddleware extends BaseMiddleware
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
     * @throws ForbiddenException
     */
    public function execute(): void
    {
        if (Application::isGuest()) {
            if (count($this->actions) === 0 || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}