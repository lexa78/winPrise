<?php
declare(strict_types=1);

namespace app\controllers;

use app\constants\Template;
use app\core\Application;
use app\core\Controller;
use app\core\exception\RuntimeException;
use app\core\middlewares\AdminMiddleware;
use app\core\Request;
use app\models\Role;

use function is_array;

/**
 * Class RoleController
 * @package app\controllers
 */
class RoleController extends Controller
{
    /**
     * RoleController constructor.
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
        $roles = (new Role())->findAll();
        if (!is_array($roles)) {
            $roles = [];
        }

        $this->setLayout(Template::NAME_ADMIN);

        return $this->render('admin/role/index', compact('roles'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $role = new Role();

        if ($request->isPost()) {
            $role->loadData($request->getBody());

            if ($role->validate() && $role->save()) {
                Application::$app->session->setFlash('success', 'New role was added!');
                Application::$app->response->redirect('/admin/roles');
                exit;
            }
        }

        return $this->render('admin/role/_form', compact('role'));
    }

    /**
     * @param Request $request
     * @return string
     * @throws RuntimeException
     */
    public function edit(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        if ($request->isGet()) {
            $requestBody = $request->getBody();
            if (empty($requestBody['id']) || (int)$requestBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            /** @var Role $role */
            $role = (new Role())->findOne(['id' => $requestBody['id']]);
            if (!($role instanceof Role)) {
                throw new RuntimeException(sprintf('Role with id %s was not found', $requestBody['id']), 500);
            }

            $role->loadData($request->getBody());
        }

        if ($request->isPost()) {
            $getBody = $request->getGetBody();
            if (empty($getBody['id']) || (int)$getBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            $role = new Role();
            $role->loadData($request->getBody());

            if ($role->validate() && $role->update($getBody['id'])) {
                Application::$app->session->setFlash('success', 'Role was updated!');
                Application::$app->response->redirect('/admin/roles');
                exit;
            }
        }

        return $this->render('admin/role/_form', compact('role'));
    }

    /**
     * @param Request $request
     * @throws RuntimeException
     */
    public function delete(Request $request): void
    {
        $this->setLayout(Template::NAME_ADMIN);

        $getBody = $request->getGetBody();
        if (empty($getBody['id']) || (int)$getBody['id'] <= 0) {
            throw new RuntimeException('Necessary parameter id was not found', 502);
        }

        if ((new Role())->delete(['id' => $getBody['id']])) {
            Application::$app->session->setFlash('success', 'Role was deleted!');
            Application::$app->response->redirect('/admin/roles');
            exit;
        } else {
            Application::$app->session->setFlash('error', 'Something is wrong, please try it later!');
            Application::$app->response->redirect('/admin/roles');
            exit;
        }
    }
}