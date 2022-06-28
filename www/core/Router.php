<?php

namespace app\core;

use app\constants\Request as RequestConstant;
use app\core\exception\NotFoundException;
use Exception;

/**
 * Class Router
 * @package app\core
 */
class Router
{
    /** @var Request  */
    public Request $request;

    /** @var Response  */
    public Response $response;

    /** @var array  */
    protected array $routes = [];

    /**
     * Router constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes[RequestConstant::METHOD_GET][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes[RequestConstant::METHOD_POST][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            throw new NotFoundException();
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        } elseif (is_array($callback)) {
            Application::$app->controller = new $callback[0]();
            $controller = Application::$app->controller;
            $controller->action = $callback[1];
            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
            $callback[0] = Application::$app->controller;
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}