<?php
declare(strict_types=1);

namespace app\controllers;

use app\constants\Template;
use app\core\Application;
use app\core\Controller;
use app\core\exception\RuntimeException;
use app\core\middlewares\AdminMiddleware;
use app\core\Request;
use app\models\Limit;
use app\models\Thing;

use function is_array;

/**
 * Class LimitController
 * @package app\controllers
 */
class LimitController extends Controller
{
    /**
     * LimitController constructor.
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
        $limits = (new Limit())->findAll();
        if (!is_array($limits)) {
            $limits = [];
        }
        $prises = (new Thing())->findAllWithPrimaryKeyAsArrayKey();

        $this->setLayout(Template::NAME_ADMIN);

        return $this->render('admin/limit/index', compact('limits', 'prises'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $limit = new Limit();
        $prises = (new Thing())->findAll();

        if ($request->isPost()) {
            $limit->loadData($request->getBody());

            if ($limit->validate() && $limit->save()) {
                Application::$app->session->setFlash('success', 'New limit was added!');
                Application::$app->response->redirect('/admin/limits');
                exit;
            }
        }

        return $this->render('admin/limit/_form', compact('limit', 'prises'));
    }

    /**
     * @param Request $request
     * @return string
     * @throws RuntimeException
     */
    public function edit(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $prises = (new Thing())->findAllWithPrimaryKeyAsArrayKey();

        if ($request->isGet()) {
            $requestBody = $request->getBody();
            if (empty($requestBody['id']) || (int)$requestBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            /** @var Limit $limit */
            $limit = (new Limit())->findOne(['id' => $requestBody['id']]);
            if (!($limit instanceof Limit)) {
                throw new RuntimeException(
                    sprintf('Limit with id %s was not found', $requestBody['id']),
                    500
                );
            }

            $limit->loadData($request->getBody());
        }

        if ($request->isPost()) {
            $getBody = $request->getGetBody();
            if (empty($getBody['id']) || (int)$getBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            $limit = new Limit();
            $limit->loadData($request->getBody());

            if ($limit->validate() && $limit->update($getBody['id'])) {
                Application::$app->session->setFlash('success', 'Limit was updated!');
                Application::$app->response->redirect('/admin/limits');
                exit;
            }
        }

        return $this->render('admin/limit/_form', compact('limit', 'prises'));
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

        if ((new Limit())->delete(['id' => $getBody['id']])) {
            Application::$app->session->setFlash('success', 'Limit was deleted!');
            Application::$app->response->redirect('/admin/limits');
            exit;
        } else {
            Application::$app->session->setFlash('error', 'Something is wrong, please try it later!');
            Application::$app->response->redirect('/admin/limits');
            exit;
        }
    }
}