<?php
/*
 * This file creates a task:
 * 1. Accept JSON request with `tasklistID` `title` and `description`
 * 2. Validate request
 * 3. Store data in the database
 * 4. Respond with JSON if successful with 201 status code, otherwise, throw error
 */

use Services\TaskRepository;

require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];

enum Status: string {
    case Error = 'error';
    case Success = 'success';
}

function http_response(string $message, Status $status, array $data = [], int $status_code = 500) {
    http_response_code($status_code);
    header('Content-Type: application/json; charset=utf-8');
    $response = [
        'message' => $message,
        'status' => $status,
        'data' => $data
    ];
    print json_encode($response);
    exit;
}

$tasksRepo = new TaskRepository($pdo);

// Validate request method
if ($method !== 'POST') {
    http_response(message: 'Method not allowed', status: Status::Error, status_code: 405);
}

$requestBody = file_get_contents('php://input');
$requestJSON = json_decode($requestBody, true);
// var_dump($requestJSON);
$taskListID = trim($requestJSON['taskListID'] ?? '');
$title = trim($requestJSON['title'] ?? '');
$description = trim($requestJSON['description'] ?? '');

// Validate request variables
if (empty($taskListID) || empty($title) || strlen($title) < 3) {
    http_response(message: 'Title is missing or shorter than 3 characters', status: Status::Error, status_code: 422);
}

// Handle taskList submission
try {
    $tasksRepo->create((int)$taskListID, $title, $description);

    $responseData = [
        'id' => (int) $pdo->lastInsertId(),
        'title' => $title,
        'description' => $description
    ];

    $_SESSION['flash_message'] = '✅ Task created successfully!';
    
    http_response(message: 'Created task', status: Status::Success, data: $responseData, status_code: 201);
} catch (PDOException $e) {
    http_response(message: 'Failed to create task', status: Status::Error, status_code: 500);
} catch (Exception $e) {
    http_response(message: 'Something went wrong', status: Status::Error, status_code: 500);
}
