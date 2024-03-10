<?php
declare(strict_types=1);

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\TaskRepository;


class TaskIndex
{
    public function __construct(private TaskRepository $taskRepository)
    {
    }
    public function __invoke(Request $request, Response $response): Response
    {
        $tasks = $this->taskRepository->getAllTasks();
        $body = json_encode($tasks, JSON_PRETTY_PRINT);
        $response->getBody()->write($body);
        return $response;
    }
}
