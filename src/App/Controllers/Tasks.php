<?php
declare(strict_types=1);

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\TaskRepository;
use Valitron\Validator;

class Tasks
{
    public function __construct(private TaskRepository $taskRepository,
                                private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'titolo' => ['required', ['lengthMin', 5]],
            'descrizione' => ['required'],
        ]);
    }
    public function show(Request $request, Response $response, string $id): Response
    {
        $task = $request->getAttribute('task');

        $body = json_encode($task);
        $response->getBody()->write($body);
        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $this->validator = $this->validator->withData($data);

        if(!$this->validator->validate()){
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);
        }
        $task = $this->taskRepository->createTask($data);
        $body = json_encode(
            [
                'message' => 'Task created',
                'id' => $task
            ]
        );
        $response->getBody()->write($body);
        return $response->withStatus(201);
    }

    public function update(Request $request, Response $response, string $id): Response
    {
        $data = $request->getParsedBody();
        $this->validator = $this->validator->withData($data);

        if(!$this->validator->validate()){
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);
        }
        $rows = $this->taskRepository->updateTask((int) $id, $data);
        $body = json_encode(
            [
                'message' => 'Task updated',
                'rows' => $rows
            ]
        );
        $response->getBody()->write($body);
        return $response;
    }

    public function delete(Request $request, Response $response, string $id): Response
    {
        $rows = $this->taskRepository->deleteTask((int) $id);
        $body = json_encode(
            [
                'message' => 'Task deleted',
                'rows' => $rows
            ]
        );
        $response->getBody()->write($body);
        return $response;
    }
}
