<?php
declare(strict_types=1);

namespace app\core;

use app\core\db\Database;
use app\models\User;
use Exception;

/**
 * Class Application
 * @package app\core
 */
class Application
{
    /** @var string  */
    public static string $ROOT_DIR;

    /** @var Router  */
    public  Router $router;

    /** @var Request  */
    public Request $request;

    /** @var Response  */
    public Response $response;

    /** @var Application  */
    public static Application $app;

    /** @var Controller|null  */
    public ?Controller $controller = null;

    /** @var Database  */
    public Database $db;

    /** @var Session  */
    public Session $session;

    /** @var db\DbModel|UserModel|null  */
    public ?UserModel $user;

    /** @var User|null  */
    public ?User $userClass;

    /** @var string  */
    public string $layout = 'main';

    /** @var View  */
    public View $view;

    /**
     * Application constructor.
     * @param string $rootPath
     * @param array $config
     */
    public function __construct(string $rootPath, array $config)
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

    /**
     * @return void
     */
    public function run(): void
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

    /**
     * @param UserModel $user
     * @return bool
     */
    public function login(UserModel $user): bool
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);

        return true;
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->user = null;
        $this->session->remove('user');
    }

    /**
     * @return bool
     */
    public static function isGuest(): bool
    {
        return !self::$app->user;
    }
}