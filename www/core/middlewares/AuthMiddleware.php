<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::isGuest()) {
            if (count($this->actions) === 0 || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}