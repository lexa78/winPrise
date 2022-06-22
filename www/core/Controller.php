<?php

namespace app\core;

use app\constants\Template;

class Controller
{
    public string $layout = Template::NAME_MAIN;
    public function render($view, $params = [])
    {
        return Application::$app->router->renderView($view, $params);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
}