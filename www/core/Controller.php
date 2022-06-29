<?php
declare(strict_types=1);

namespace app\core;

use app\constants\Template;
use app\core\middlewares\BaseMiddleware;

/**
 * Class Controller
 * @package app\core
 */
class Controller
{
    /** @var string  */
    public string $layout = Template::NAME_MAIN;

    /** @var string  */
    public string $action = '';

    /** @var BaseMiddleware[] */
    protected array $middlewares = [];

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        return Application::$app->view->renderView($view, $params);
    }

    /**
     * @param string $layout
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * @param BaseMiddleware $middleware
     */
    public function registerMiddleware(BaseMiddleware $middleware): void
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