<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once __DIR__ . '/Models/Model.php';
require_once __DIR__ . '/Models/Task.php';
require_once __DIR__ . '/Models/TaskList.php';
require_once __DIR__ . '/Services/TaskRepository.php';
require_once __DIR__ . '/Services/TaskListRepository.php';

session_start();

$host = 'mysql';
$db   = $_ENV['MYSQL_DATABASE'] ?? getenv('MYSQL_DATABASE');
$user = $_ENV['MYSQL_USER']     ?? getenv('MYSQL_USER');
$pass = $_ENV['MYSQL_PASSWORD'] ?? getenv('MYSQL_PASSWORD');

try {
    $pdo = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbStatus = '✅ Connected to MySQL successfully';
} catch (PDOException $e) {
    $dbStatus = '❌ MySQL connection failed: ' . $e->getMessage();
}

$message = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);
