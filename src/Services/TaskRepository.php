<?php

namespace Services;
use PDO;
use Exception;

use \Models\Task;

class TaskRepository extends Repository
{
    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
    }

    public function create(int $taskListID, string $title, ?string $description = null): Task {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tasks (task_list_id, title, description) 
                VALUES (:task_list_id, :title, :description)");
            $stmt->execute([
                'task_list_id' => $taskListID,
                'title' => $title,
                'description' => $description
            ]);
            $id = (int) $this->pdo->lastInsertId();
            return new Task($id, $taskListID, $title, $description);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function fetchAll(int $taskListID): array {
        $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE task_list_id = :id');
        $stmt->execute(['id' => $taskListID]);
        $fetchedTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($fetchedTasks as $item) {
            $task = new Task($item['id'], $item['task_list_id'], $item['title'], $item['description']);
            array_push($tasks, $task);
        }
        return $tasks;
    }
}