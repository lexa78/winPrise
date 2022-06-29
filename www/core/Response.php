<?php
declare(strict_types=1);

namespace app\core;

use function header;
use function http_response_code;
use function sprintf;

/**
 * Class Response
 * @package app\core
 */
class Response
{
    /**
     * @param int $code
     */
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    /**
     * @param string $url
     */
    public function redirect(string $url): void
    {
        header(sprintf('Location: %s', $url));
    }
}