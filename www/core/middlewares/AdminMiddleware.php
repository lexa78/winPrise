<?php
declare(strict_types=1);

namespace app\core\middlewares;

use app\core\exception\ForbiddenException;
use app\models\User;

/**
 * Class AdminMiddleware
 * @package app\core\middlewares
 */
class AdminMiddleware extends BaseMiddleware
{
    /**
     * @throws ForbiddenException
     */
    public function execute(): void
    {
        if (!User::isAdmin()) {
            throw new ForbiddenException('You have to be an Admin for have access to this page');
        }
    }
}