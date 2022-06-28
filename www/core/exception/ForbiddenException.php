<?php


namespace app\core\exception;


use Exception;

class ForbiddenException extends Exception
{
    protected $code = 403;
    protected $message = 'You have to sign in or sign out for have access to this page';
}