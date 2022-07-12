<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\models\User;
use app\Services\Game\Game;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['game']));
    }

    /**
     * @return string
     */
    public function home(): string
    {
        $name = 'guest';
        if (Application::$app->user instanceof User) {
            $name = Application::$app->user->firstName;
        }
        $params = [
            'name' => $name,
        ];

        return $this->render('home', $params);
    }

    public function game()
    {
        $game = new Game();
        $game->letsPlay();
    }
}