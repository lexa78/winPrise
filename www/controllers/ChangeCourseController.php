<?php
declare(strict_types=1);

namespace app\controllers;

use app\constants\Template;
use app\core\Application;
use app\core\Controller;
use app\core\exception\RuntimeException;
use app\core\middlewares\AdminMiddleware;
use app\core\Request;
use app\models\ChangeCourse;
use app\models\Thing;

use function is_array;

/**
 * Class ChangeCourseController
 * @package app\controllers
 */
class ChangeCourseController extends Controller
{
    /**
     * ChangeCourseController constructor.
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
        $changeCourses = (new ChangeCourse())->findAll();
        if (!is_array($changeCourses)) {
            $changeCourses = [];
        }
        $prises = (new Thing())->findAllWithPrimaryKeyAsArrayKey();

        $this->setLayout(Template::NAME_ADMIN);

        return $this->render('admin/change-course/index', compact('changeCourses', 'prises'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $changeCourse = new ChangeCourse();
        $prises = (new Thing())->findAll();

        if ($request->isPost()) {
            $changeCourse->loadData($request->getBody());

            if ($changeCourse->validate() && $changeCourse->save()) {
                Application::$app->session->setFlash('success', 'New course of change was added!');
                Application::$app->response->redirect('/admin/courses');
                exit;
            }
        }

        return $this->render('admin/change-course/_form', compact('changeCourse', 'prises'));
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

            /** @var ChangeCourse $changeCourse */
            $changeCourse = (new ChangeCourse())->findOne(['id' => $requestBody['id']]);
            if (!($changeCourse instanceof ChangeCourse)) {
                throw new RuntimeException(
                    sprintf('Course of change with id %s was not found', $requestBody['id']),
                    500
                );
            }

            $changeCourse->loadData($request->getBody());
        }

        if ($request->isPost()) {
            $getBody = $request->getGetBody();
            if (empty($getBody['id']) || (int)$getBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            $changeCourse = new ChangeCourse();
            $changeCourse->loadData($request->getBody());

            if ($changeCourse->validate() && $changeCourse->update($getBody['id'])) {
                Application::$app->session->setFlash('success', 'Course of change was updated!');
                Application::$app->response->redirect('/admin/courses');
                exit;
            }
        }

        return $this->render('admin/change-course/_form', compact('changeCourse', 'prises'));
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

        if ((new ChangeCourse())->delete(['id' => $getBody['id']])) {
            Application::$app->session->setFlash('success', 'Course of change was deleted!');
            Application::$app->response->redirect('/admin/courses');
            exit;
        } else {
            Application::$app->session->setFlash('error', 'Something is wrong, please try it later!');
            Application::$app->response->redirect('/admin/courses');
            exit;
        }
    }
}