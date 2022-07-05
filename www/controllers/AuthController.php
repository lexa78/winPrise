<?php
declare(strict_types=1);

namespace app\controllers;

use app\constants\Template;
use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

/**
 * Class AuthController
 * @package app\controllers
 */
class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['game']));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     */
    public function login(Request $request, Response $response): string
    {
        $loginForm = new LoginForm();
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                if (User::isAdmin()) {
                    $response->redirect('/admin');
                } else {
                    $response->redirect('/');
                }
                exit;
            }
        }

        $this->setLayout(Template::NAME_AUTH);
        return $this->render('login', [
            'model' => $loginForm,
        ]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function register(Request $request): string
    {
        $user = new User();

        if ($request->isPost()) {
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash('success', 'Thanks for registering!');
                Application::$app->response->redirect('/');
                exit;
            }

            return $this->render('register', [
                'model' => $user,
            ]);
        }

        $this->setLayout(Template::NAME_AUTH);

        return $this->render('register', [
            'model' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function logout(Request $request, Response $response): void
    {
        Application::$app->logout();
        $response->redirect('/');
    }

    /**
     * @return string
     */
    public function game(): string
    {
        return $this->render('game', [
            'model' => new User(),
        ]);
    }
}