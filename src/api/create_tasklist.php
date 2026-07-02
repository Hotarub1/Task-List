<?php
/*
 * This file creates a task list:
 * 1. Accept JSON request with `title` and `description`
 * 2. Validate request
 * 3. Store data in the database
 * 4. Respond with JSON if successful with 201 status code, otherwise, throw error
 */ 
require_once '../config.php';

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

// Validate request method
if ($method !== 'POST') {
    http_response(message: 'Method not allowed', status: Status::Error, status_code: 405);
}

$requestBody = file_get_contents('php://input');
$requestJSON = json_decode($requestBody, true);
// var_dump($requestJSON);
$title = trim($requestJSON['title'] ?? '');
$description = trim($requestJSON['description'] ?? '');

// Validate request variables
if (empty($title) || strlen($title) < 3) {
    http_response(message: 'Title is missing or shorter than 3 characters', status: Status::Error, status_code: 422);
}

// Handle taskList submission
try {
    // Prepare an SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("INSERT INTO taskLists (title, description) VALUES (:title, :description)");
    $stmt->execute([ 
        'title' => $title, 
        'description' => $description 
    ]); 
    $data['id'] = (int) $pdo->lastInsertId();

    $responseData = [
        'id' => (int) $pdo->lastInsertId(),
        'title' => $title,
        'description' => $description
    ];

    $_SESSION['flash_message'] = '✅ Tasklist created successfully!';
    
    http_response(message: 'Created task list', status: Status::Success, data: $responseData, status_code: 201);
} catch (PDOException $e) {
    http_response(message: 'Failed to create task list', status: Status::Error, status_code: 500);
} catch (Exception $e) {
    http_response(message: 'Something went wrong', status: Status::Error, status_code: 500);
}
