<?php
declare(strict_types=1);

namespace app\core\exception;

use Exception;

/**
 * Class NotFoundException
 * @package app\core\exception
 */
class NotFoundException extends Exception
{
    /** @var int  */
    protected $code = 404;

    /** @var string  */
    protected $message = 'Page not found';
}