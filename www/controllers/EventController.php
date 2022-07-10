<?php
declare(strict_types=1);

namespace app\controllers;

use app\constants\Template;
use app\core\Application;
use app\core\Controller;
use app\core\exception\RuntimeException;
use app\core\middlewares\AdminMiddleware;
use app\core\Request;
use app\models\Event;

use function is_array;

/**
 * Class EventController
 * @package app\controllers
 */
class EventController extends Controller
{
    /**
     * EventController constructor.
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
        $events = (new Event())->findAll();
        if (!is_array($events)) {
            $events = [];
        }

        $this->setLayout(Template::NAME_ADMIN);

        return $this->render('admin/event/index', compact('events'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request): string
    {
        $this->setLayout(Template::NAME_ADMIN);

        $event = new Event();

        if ($request->isPost()) {
            $event->loadData($request->getBody());

            if ($event->validate() && $event->save()) {
                Application::$app->session->setFlash('success', 'New event was added!');
                Application::$app->response->redirect('/admin/events');
                exit;
            }
        }

        return $this->render('admin/event/_form', compact('event'));
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

            /** @var Event $event */
            $event = (new Event())->findOne(['id' => $requestBody['id']]);
            if (!($event instanceof Event)) {
                throw new RuntimeException(
                    sprintf('Event with id %s was not found', $requestBody['id']),
                    500
                );
            }

            $event->loadData($request->getBody());
        }

        if ($request->isPost()) {
            $getBody = $request->getGetBody();
            if (empty($getBody['id']) || (int)$getBody['id'] <= 0) {
                throw new RuntimeException('Necessary parameter id was not found', 502);
            }

            $event = new Event();
            $event->loadData($request->getBody());

            if ($event->validate() && $event->update($getBody['id'])) {
                Application::$app->session->setFlash('success', 'Event was updated!');
                Application::$app->response->redirect('/admin/events');
                exit;
            }
        }

        return $this->render('admin/event/_form', compact('event'));
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

        if ((new Event())->delete(['id' => $getBody['id']])) {
            Application::$app->session->setFlash('success', 'Event was deleted!');
            Application::$app->response->redirect('/admin/events');
            exit;
        } else {
            Application::$app->session->setFlash('error', 'Something is wrong, please try it later!');
            Application::$app->response->redirect('/admin/events');
            exit;
        }
    }
}