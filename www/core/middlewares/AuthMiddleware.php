<?php
declare(strict_types=1);

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;
use app\models\User;

use function count;
use function in_array;

/**
 * Class AuthMiddleware
 * @package app\core\middlewares
 */
class AuthMiddleware extends BaseMiddleware
{
    /**
     * @throws ForbiddenException
     */
    public function execute(): void
    {
        if (User::isGuest()) {
            if (count($this->actions) === 0 || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}