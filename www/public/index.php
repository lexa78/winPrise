<?php

use app\controllers\AdminController;
use app\controllers\AuthController;
use app\controllers\ChangeCourseController;
use app\controllers\EventController;
use app\controllers\LimitController;
use app\controllers\PrisesTypeController;
use app\controllers\RoleController;
use app\controllers\ThingController;
use app\controllers\UnitController;
use app\core\Application;
use app\controllers\SiteController;
use Dotenv\Dotenv;
use app\models\User;

require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(dirname(__DIR__), $config);

/** Authentication */
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);

/** Admin part */
$app->router->get('/admin', [AdminController::class, 'index']);
$app->router->get('/admin/roles', [RoleController::class, 'index']);
$app->router->get('/admin/roles/create', [RoleController::class, 'create']);
$app->router->post('/admin/roles/create', [RoleController::class, 'create']);
$app->router->get('/admin/roles/edit', [RoleController::class, 'edit']);
$app->router->post('/admin/roles/edit', [RoleController::class, 'edit']);
$app->router->get('/admin/roles/delete', [RoleController::class, 'delete']);
$app->router->get('/admin/events', [EventController::class, 'index']);
$app->router->get('/admin/events/create', [EventController::class, 'create']);
$app->router->post('/admin/events/create', [EventController::class, 'create']);
$app->router->get('/admin/events/edit', [EventController::class, 'edit']);
$app->router->post('/admin/events/edit', [EventController::class, 'edit']);
$app->router->get('/admin/events/delete', [EventController::class, 'delete']);
$app->router->get('/admin/prise-types', [PrisesTypeController::class, 'index']);
$app->router->get('/admin/prise-types/create', [PrisesTypeController::class, 'create']);
$app->router->post('/admin/prise-types/create', [PrisesTypeController::class, 'create']);
$app->router->get('/admin/prise-types/edit', [PrisesTypeController::class, 'edit']);
$app->router->post('/admin/prise-types/edit', [PrisesTypeController::class, 'edit']);
$app->router->get('/admin/prise-types/delete', [PrisesTypeController::class, 'delete']);
$app->router->get('/admin/units', [UnitController::class, 'index']);
$app->router->get('/admin/units/create', [UnitController::class, 'create']);
$app->router->post('/admin/units/create', [UnitController::class, 'create']);
$app->router->get('/admin/units/edit', [UnitController::class, 'edit']);
$app->router->post('/admin/units/edit', [UnitController::class, 'edit']);
$app->router->get('/admin/units/delete', [UnitController::class, 'delete']);
$app->router->get('/admin/things', [ThingController::class, 'index']);
$app->router->get('/admin/things/create', [ThingController::class, 'create']);
$app->router->post('/admin/things/create', [ThingController::class, 'create']);
$app->router->get('/admin/things/edit', [ThingController::class, 'edit']);
$app->router->post('/admin/things/edit', [ThingController::class, 'edit']);
$app->router->get('/admin/things/delete', [ThingController::class, 'delete']);
$app->router->get('/admin/courses', [ChangeCourseController::class, 'index']);
$app->router->get('/admin/courses/create', [ChangeCourseController::class, 'create']);
$app->router->post('/admin/courses/create', [ChangeCourseController::class, 'create']);
$app->router->get('/admin/courses/edit', [ChangeCourseController::class, 'edit']);
$app->router->post('/admin/courses/edit', [ChangeCourseController::class, 'edit']);
$app->router->get('/admin/courses/delete', [ChangeCourseController::class, 'delete']);
$app->router->get('/admin/limits', [LimitController::class, 'index']);
$app->router->get('/admin/limits/create', [LimitController::class, 'create']);
$app->router->post('/admin/limits/create', [LimitController::class, 'create']);
$app->router->get('/admin/limits/edit', [LimitController::class, 'edit']);
$app->router->post('/admin/limits/edit', [LimitController::class, 'edit']);
$app->router->get('/admin/limits/delete', [LimitController::class, 'delete']);

/** User part */
$app->router->get('/', [SiteController::class, 'home']);
//$app->router->post('/game', [SiteController::class, 'game']);
$app->router->get('/game', [SiteController::class, 'game']);

$app->run();