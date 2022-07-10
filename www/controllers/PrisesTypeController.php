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

use function is_array;

/**
 * Class PrisesTypeController
 * @package app\controllers
 */
class PrisesTypeController extends Controller
{
    /**
     * PrisesTypeController constructor.
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
        $prisesTypes = (new PriseType())->findAll();
        if (!is_array($prisesTypes)) {
            $prisesTypes = [];
        }

        $this->setLayout(Template::NAME_ADMIN);

        return $this->render('admin/prise-type/index', compact('prisesTypes'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $prisesType = new PriseType();

        if ($request->isPost()) {
            $prisesType->setNeedleValueToIsLimited($request->getBody());
            $prisesType->loadData($request->getBody());

            if ($prisesType->validate() && $prisesType->save()) {
                Application::$app->session->setFlash('success', 'New type of prise was added!');
                Application::$app->response->redirect('/admin/prise-types');
                exit;
            }
        }

        return $this->render('admin/prise-type/_form', compact('prisesType'));
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

            /** @var PriseType $prisesType */
            $prisesType = (new PriseType())->findOne(['id' => $requestBody['id']]);
            if (!($prisesType instanceof PriseType)) {
                throw new RuntimeException(
                    sprintf('Type of prise with id %s was not found', $requestBody['id']),
                    500
                );
            }

            $prisesType->loadData($request->getBody());
        }

        if ($request->isPost()) {
            $getBody = $request->getGetBody();
            if (empty($getBody['id']) || (int)$getBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            $prisesType = new PriseType();
            $prisesType->setNeedleValueToIsLimited($request->getBody());
            $prisesType->loadData($request->getBody());

            if ($prisesType->validate() && $prisesType->update($getBody['id'])) {
                Application::$app->session->setFlash('success', 'Type of prise was updated!');
                Application::$app->response->redirect('/admin/prise-types');
                exit;
            }
        }

        return $this->render('admin/prise-type/_form', compact('prisesType'));
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

        if ((new PriseType())->delete(['id' => $getBody['id']])) {
            Application::$app->session->setFlash('success', 'Type of prise was deleted!');
            Application::$app->response->redirect('/admin/prise-types');
            exit;
        } else {
            Application::$app->session->setFlash('error', 'Something is wrong, please try it later!');
            Application::$app->response->redirect('/admin/prise-types');
            exit;
        }
    }
}