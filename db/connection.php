<?php
// db/connection.php - PDO connection helper
$config = require __DIR__ . '/../config.php';
$db = $config['db'];

$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $db['host'], $db['port'], $db['name'], $db['charset']);

try {
    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // throw so caller can handle and present friendly error
    throw new RuntimeException('Database connection failed: ' . $e->getMessage());
}

// helper to prepare and execute with params and return stmt
function db_query(PDO $pdo, string $sql, array $params = []) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

return $pdo;
