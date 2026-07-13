<?php

namespace Services;
use PDO;
use Exception;

use \Models\TaskList;

class TaskListRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo) { 
        $this->pdo = $pdo; 
    }

    public function create(int $id, string $title, ?string $description = null): TaskList {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO taskLists (title, description) VALUES (:title, :description)");
            $stmt->execute([ 
                'title' => $title, 
                'description' => $description 
            ]); 
            return new TaskList($id, $title, $description);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // public function update(TaskList $list, ?string $title = $list->title, ?string $description = $list->description): TaskList {
    //     try {
    //         $stmt = $this->pdo->prepare("UPDATE taskLists SET title = ?, description = ? WHERE id = ?");
    //         $stmt->execute([$title, $description, $list->id]);
    //         return new TaskList($title, $description);
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }

    // public function delete(): void {

    // }

    public function fetchAll(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM taskLists');
        $stmt->execute();
        $fetchedLists = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $taskLists = [];
        foreach ($fetchedLists as $list) {
            $taskList = new TaskList($list['id'], $list['title'], $list['description']);
            array_push($taskLists, $taskList);
        }
        return $taskLists;
    }

    public function fetchByID(int $taskListID): TaskList {
        $stmt = $this->pdo->prepare('SELECT * FROM taskLists WHERE id = :taskListID');
        $stmt->execute(['taskListID' => $taskListID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
