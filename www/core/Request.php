<?php
declare(strict_types=1);

namespace app\core;

use app\constants\Request as RequestConstant;

use function stripos;
use function substr;
use function strtolower;
use function filter_input;

/**
 * Class Request
 * @package app\core
 */
class Request
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = stripos($path, '?');
        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method() === RequestConstant::METHOD_GET;
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === RequestConstant::METHOD_POST;
    }

    /**
     * @return array
     */
    public function getBody(): array
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