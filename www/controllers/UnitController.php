<?php
declare(strict_types=1);

namespace app\controllers;

use app\constants\Template;
use app\core\Application;
use app\core\Controller;
use app\core\exception\RuntimeException;
use app\core\middlewares\AdminMiddleware;
use app\core\Request;
use app\models\Unit;

use function is_array;

/**
 * Class UnitController
 * @package app\controllers
 */
class UnitController extends Controller
{
    /**
     * UnitController constructor.
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
        $units = (new Unit())->findAll();
        if (!is_array($units)) {
            $units = [];
        }

        $this->setLayout(Template::NAME_ADMIN);

        return $this->render('admin/unit/index', compact('units'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $unit = new Unit();

        if ($request->isPost()) {
            $unit->loadData($request->getBody());

            if ($unit->validate() && $unit->save()) {
                Application::$app->session->setFlash('success', 'New unit was added!');
                Application::$app->response->redirect('/admin/units');
                exit;
            }
        }

        return $this->render('admin/unit/_form', compact('unit'));
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

            /** @var Unit $unit */
            $unit = (new Unit())->findOne(['id' => $requestBody['id']]);
            if (!($unit instanceof Unit)) {
                throw new RuntimeException(
                    sprintf('Unit with id %s was not found', $requestBody['id']),
                    500
                );
            }

            $unit->loadData($request->getBody());
        }

        if ($request->isPost()) {
            $getBody = $request->getGetBody();
            if (empty($getBody['id']) || (int)$getBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            $unit = new Unit();
            $unit->loadData($request->getBody());

            if ($unit->validate() && $unit->update($getBody['id'])) {
                Application::$app->session->setFlash('success', 'Unit was updated!');
                Application::$app->response->redirect('/admin/units');
                exit;
            }
        }

        return $this->render('admin/unit/_form', compact('unit'));
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

        if ((new Unit())->delete(['id' => $getBody['id']])) {
            Application::$app->session->setFlash('success', 'Unit was deleted!');
            Application::$app->response->redirect('/admin/units');
            exit;
        } else {
            Application::$app->session->setFlash('error', 'Something is wrong, please try it later!');
            Application::$app->response->redirect('/admin/units');
            exit;
        }
    }
}