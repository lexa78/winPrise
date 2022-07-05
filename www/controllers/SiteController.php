<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\models\User;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @return string
     */
    public function home(): string
    {
        $name = null;
        if (Application::$app->user instanceof User) {
            $name = Application::$app->user->firstName;
        }
        $params = [
            'name' => $name,
        ];

        return $this->render('home', $params);
    }
}