<?php


namespace app\core;

use app\constants\Request as RequestConstant;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = stripos($path, '?');
        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->method() === RequestConstant::METHOD_GET;
    }

    public function isPost()
    {
        return $this->method() === RequestConstant::METHOD_POST;
    }

    public function getBody()
    {
        $body = [];
        switch ($this->method()) {
            case RequestConstant::METHOD_GET:
                foreach ($_GET as $key => $value) {
                    $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                break;
            case RequestConstant::METHOD_POST:
                foreach ($_POST as $key => $value) {
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                break;
        }

        return $body;
    }
}