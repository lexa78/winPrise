<?php
declare(strict_types=1);

namespace app\controllers;

use app\constants\Template;
use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AdminMiddleware;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->registerMiddleware(new AdminMiddleware(['*']));
    }

    /**
     * @return string
     */
    public function index(): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        return $this->render('admin/index', [
            'model' => Application::$app->user,
        ]);
    }
}