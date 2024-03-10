<?php

declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Middleware\AddJsonResponseHeader;
use App\Controllers\TaskIndex;
use App\Controllers\Tasks;
use App\Middleware\GetTask;
use Slim\Routing\RouteCollectorProxy;

require dirname(__DIR__) . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(require __DIR__ . '/../config/definitions.php')->build();
AppFactory::setContainer($container);

$app = AppFactory::create();

$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

$app->addBodyParsingMiddleware();

$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');
$app->setBasePath("/slimTaskApi/public");

$app->add(new AddJsonResponseHeader);

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/tasks', TaskIndex::class);
    $group->post('/tasks', Tasks::class . ':create');
    $group->group('', function (RouteCollectorProxy $group) {
        $group->get('/tasks/{id:[0-9]+}', Tasks::class . ':show');
        $group->patch('/tasks/{id:[0-9]+}', Tasks::class . ':update');
        $group->delete('/tasks/{id:[0-9]+}', Tasks::class . ':delete');
    })->add(GetTask::class);
});
$app->run();
