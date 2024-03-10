<?php
declare(strict_types=1);

namespace App\Repository;
use PDO;
use App\Database;

class TaskRepository
{
    public function __construct(private Database $db)
    {
    }
    public function getAllTasks(): array
    {
        $pdo = $this->db->getConnection();
        return $pdo->query('SELECT * FROM task')->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTask(int $id): array|bool
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM task WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTask(array $data): string
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare('INSERT INTO task (titolo, descrizione) VALUES (:titolo, :descrizione)');
        $stmt->bindValue(':titolo', $data['titolo'], PDO::PARAM_STR);
        $stmt->bindValue(':descrizione', $data['descrizione'], PDO::PARAM_STR);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function updateTask(int $id, array $data): int
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare(
            'UPDATE task SET titolo = :titolo, descrizione = :descrizione WHERE id = :id');
        $stmt->bindValue(':titolo', $data['titolo']);
        $stmt->bindValue(':descrizione', $data['descrizione']);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteTask(int $id): int
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare('DELETE FROM task WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
