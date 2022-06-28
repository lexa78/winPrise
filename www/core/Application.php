<?php

namespace app\core;

use app\models\User;
use Exception;

/**
 * Class Application
 * @package app\core
 */
class Application
{
    public static string $ROOT_DIR;
    /** @var Router  */
    public  Router $router;

    /** @var Request  */
    public Request $request;

    /** @var Response  */
    public Response $response;

    public static Application $app;

    public ?Controller $controller = null;

    public Database $db;

    public Session $session;

    public ?DbModel $user;

    public ?User $userClass;

    public string $layout = 'main';

    public View $view;

    /**
     * Application constructor.
     */
    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $this->session = new Session();
        $this->userClass = new $config['userClass']();

        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass->primaryKey();
            $this->user = $this->userClass->findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }

        $this->view = new View();
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);

        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }


}