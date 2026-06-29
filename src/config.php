<?php

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
