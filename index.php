<?php

use DI\ContainerBuilder;

use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Routing\RouteCollectorProxy;

use Middleware\AddJsonResponse;
use Middleware\GetUser;

use Controller\UserController;
use Controller\UserKeyController;
use Middleware\JwtAuth;

require __DIR__ . '/vendor/autoload.php';

//define basepath of project
$basePath = basename(dirname(__FILE__));

//load file /environment .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . "/$basePath");
$dotenv->load();

//untuk dependency injection
$builder = new ContainerBuilder;
$container = $builder
    ->addDefinitions(__DIR__ . '/config/definitions.php')
    ->build();
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->setBasePath("/" . $basePath);

//menyederhanakan parameter url
$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

//memaksa error handle untuk menampilkan json response
$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handle = $error_middleware->getDefaultErrorHandler();
$error_handle->forceContentType('application/json');

//memaksa response handle untuk menampilkan json response
$app->add(new AddJsonResponse);

//untuk memparsing body request
$app->addBodyParsingMiddleware();

//mendaftarkan pengguna baru untuk akses api dengan token
$app->group('/token', function (RouteCollectorProxy $group) {
    $group->post('/register', [UserKeyController::class, 'register_user']);
    $group->get('/register', [UserKeyController::class, 'find_user']);
});

$app->group('/api', function (RouteCollectorProxy $group) {
    //endpoint users
    $group->get('/users', [UserController::class, 'show']);
    $group->post('/users', [UserController::class, 'create']);
    $group->group('', function (RouteCollectorProxy $group1) {
        //[0-9]+ : hanya menerima bilangan
        $group1->get('/users/{id:[0-9]+}', [UserController::class, 'find']);
        $group1->patch('/users/{id:[0-9]+}', [UserController::class, 'update']);
        $group1->delete('/users/{id:[0-9]+}', [UserController::class, 'delete']);
    })->add(GetUser::class);
})->add(JwtAuth::class);

$app->run();

//https://www.youtube.com/watch?v=PHZtujcTRPk