<?php
declare(strict_types=1);
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use App\Repository\TaskRepository;
use Slim\Exception\HttpNotFoundException;

class GetTask
{
    public function __construct(private TaskRepository $taskRepository)
    {

    }
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $id = $route->getArgument('id');

        $task = $this->taskRepository->getTask((int)$id);

        if($task === false){
            throw new HttpNotFoundException($request,
                "Task not found");
        }

        $request = $request->withAttribute('task', $task);

        return $handler->handle($request);
    }
}
