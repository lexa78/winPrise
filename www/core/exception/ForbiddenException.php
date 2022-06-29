<?php
declare(strict_types=1);

namespace app\core\exception;

use Exception;

/**
 * Class ForbiddenException
 * @package app\core\exception
 */
class ForbiddenException extends Exception
{
    /** @var int */
    protected $code = 403;

    /** @var string */
    protected $message = 'You have to sign in or sign out for have access to this page';
}