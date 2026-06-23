<?php

$host = 'mysql';
$db   = $_ENV['MYSQL_DATABASE'] ?? getenv('MYSQL_DATABASE');
$user = $_ENV['MYSQL_USER']     ?? getenv('MYSQL_USER');
$pass = $_ENV['MYSQL_PASSWORD'] ?? getenv('MYSQL_PASSWORD');

try {
    $pdo = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
    $dbStatus = '✅ Connected to MySQL successfully';
} catch (PDOException $e) {
    $dbStatus = '❌ MySQL connection failed: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasklist</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 4rem auto; }
        .status { padding: .75rem 1rem; border-radius: 4px; background: #f0f4ff; }
    </style>
</head>
<body>
    <h1>🐘 PHP + Nginx + MySQL</h1>
    <p>    <strong>PHP Version:</strong> <?= PHP_VERSION ?></p>
    <p class="status"><strong>Database:</strong> <?= htmlspecialchars($dbStatus) ?></p>
</body>
</html>
