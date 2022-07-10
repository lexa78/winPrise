<?php
declare(strict_types=1);

namespace app\controllers;

use app\constants\Template;
use app\core\Application;
use app\core\Controller;
use app\core\exception\RuntimeException;
use app\core\middlewares\AdminMiddleware;
use app\core\Request;
use app\models\PriseType;
use app\models\Thing;

use app\models\Unit;
use function is_array;

/**
 * Class ThingController
 * @package app\controllers
 */
class ThingController extends Controller
{
    /**
     * ThingController constructor.
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
        $things = (new Thing())->findAll();
        if (!is_array($things)) {
            $things = [];
        }
        $prisesTypes = (new PriseType())->findAllWithPrimaryKeyAsArrayKey();
        $units = (new Unit())->findAllWithPrimaryKeyAsArrayKey();

        $this->setLayout(Template::NAME_ADMIN);

        return $this->render('admin/thing/index', compact('things', 'prisesTypes', 'units'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $thing = new Thing();
        $prisesTypes = (new PriseType())->findAll();
        $units = (new Unit())->findAll();

        if ($request->isPost()) {
            $thing->loadData($request->getBody());

            if ($thing->validate() && $thing->save()) {
                Application::$app->session->setFlash('success', 'New thing was added!');
                Application::$app->response->redirect('/admin/things');
                exit;
            }
        }

        return $this->render('admin/thing/_form', compact('thing', 'prisesTypes', 'units'));
    }

    /**
     * @param Request $request
     * @return string
     * @throws RuntimeException
     */
    public function edit(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $prisesTypes = (new PriseType())->findAllWithPrimaryKeyAsArrayKey();
        $units = (new Unit())->findAllWithPrimaryKeyAsArrayKey();

        if ($request->isGet()) {
            $requestBody = $request->getBody();
            if (empty($requestBody['id']) || (int)$requestBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            /** @var Thing $thing */
            $thing = (new Thing())->findOne(['id' => $requestBody['id']]);
            if (!($thing instanceof Thing)) {
                throw new RuntimeException(
                    sprintf('Thing with id %s was not found', $requestBody['id']),
                    500
                );
            }

            $thing->loadData($request->getBody());
        }

        if ($request->isPost()) {
            $getBody = $request->getGetBody();
            if (empty($getBody['id']) || (int)$getBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            $thing = new Thing();
            $thing->loadData($request->getBody());

            if ($thing->validate() && $thing->update($getBody['id'])) {
                Application::$app->session->setFlash('success', 'Thing was updated!');
                Application::$app->response->redirect('/admin/things');
                exit;
            }
        }

        return $this->render('admin/thing/_form', compact('thing', 'prisesTypes', 'units'));
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

        if ((new Thing())->delete(['id' => $getBody['id']])) {
            Application::$app->session->setFlash('success', 'Thing was deleted!');
            Application::$app->response->redirect('/admin/things');
            exit;
        } else {
            Application::$app->session->setFlash('error', 'Something is wrong, please try it later!');
            Application::$app->response->redirect('/admin/things');
            exit;
        }
    }
}