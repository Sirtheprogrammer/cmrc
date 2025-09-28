<?php
// Debug helper - visit in browser: /lupyanatech/scripts/debug.php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');

echo "LupyanaTech debug\n";
echo "PHP version: " . PHP_VERSION . "\n";

$files = [
    __DIR__ . "/../config.php",
    __DIR__ . "/../db/connection.php",
    __DIR__ . "/../helpers/auth.php",
    __DIR__ . "/../helpers/csrf.php",
];

foreach ($files as $f) {
    echo "\n-- Checking: $f\n";
    if (!file_exists($f)) {
        echo "MISSING\n";
        continue;
    }
    echo "Exists\n";
    // Try linting using php -l if available
    $lint = null;
    if (function_exists('shell_exec')) {
        $lint = @shell_exec("php -l " . escapeshellarg($f) . " 2>&1");
        if ($lint !== null) {
            echo "lint result:\n" . $lint . "\n";
        } else {
            echo "lint: shell_exec returned null or disabled\n";
        }
    } else {
        echo "lint: shell_exec not available\n";
    }

    // Show first 200 characters to inspect for obvious truncation
    $contents = @file_get_contents($f);
    if ($contents === false) {
        echo "Could not read file contents (permissions?)\n";
    } else {
        echo "Preview:\n" . substr($contents, 0, 800) . "\n";
    }
}

// Try to load config safely
echo "\n-- Attempting to include config.php (safely)\n";
$config_file = __DIR__ . "/../config.php";
if (file_exists($config_file)) {
    $cfg = @include $config_file;
    if ($cfg === false) {
        echo "Failed to include config.php (parse error?).\n";
    } else {
        echo "Config included. Keys: " . implode(', ', array_keys($cfg)) . "\n";
    }
} else {
    echo "config.php missing.\n";
}

// Try DB connection
echo "\n-- Attempting DB connection\n";
try {
    $pdo = @include __DIR__ . "/../db/connection.php";
    if ($pdo && $pdo instanceof PDO) {
        $res = $pdo->query('SELECT VERSION() as v')->fetch();
        echo "DB connected. Server version: " . ($res['v'] ?? 'unknown') . "\n";
        $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_NUM);
        $table_names = array_map(function($r){ return $r[0]; }, $tables ?: []);
        echo "Tables: " . implode(', ', $table_names) . "\n";
    } else {
        echo "db/connection.php did not return a PDO instance.\n";
    }
} catch (Throwable $e) {
    echo "DB connect error: " . $e->getMessage() . "\n";
}

echo "\n-- End of debug\n";
