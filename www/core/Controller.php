<?php

namespace app\core;

use app\constants\Template;
use app\core\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = Template::NAME_MAIN;

    public string $action = '';

    /** @var BaseMiddleware[] */
    protected array $middlewares = [];

    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}