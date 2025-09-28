<?php
// Simple DB diagnostic script for Lupyana Tech
// Visit in browser: http://localhost/lupyanatech/scripts/db_check.php

$pdo = require __DIR__ . '/../db/connection.php';

$required = ['packages', 'users', 'orders', 'admins', 'uploads'];

header('Content-Type: text/html; charset=utf-8');

echo '<h2>Database diagnostic</h2>';
try {
    $tables = []; 
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    if (empty($tables)) {
        echo '<p><strong>No tables found.</strong> You need to run <code>db/schema.sql</code> to create the schema.</p>';
        exit;
    }

    echo '<p><strong>Found tables:</strong> ' . htmlspecialchars(implode(', ', $tables)) . '</p>';

    $missing = array_diff($required, $tables);
    if (!empty($missing)) {
        echo '<p style="color:darkred"><strong>Missing tables:</strong> ' . htmlspecialchars(implode(', ', $missing)) . '</p>';
        echo '<p>Run the schema file to create missing tables. Example (shell):</p>';
        echo '<pre>mysql -u your_mysql_user -p < ' . htmlspecialchars(__DIR__ . '/../db/schema.sql') . '</pre>';
    } else {
        echo '<p style="color:green"><strong>All required tables are present.</strong></p>';
    }

    // Show counts for each required table
    echo '<h3>Row counts</h3>';
    echo '<ul>';
    foreach ($required as $t) {
        if (in_array($t, $tables)) {
            $c = $pdo->query("SELECT COUNT(*) FROM `" . $t . "`")->fetchColumn();
            echo '<li>' . htmlspecialchars($t) . ': ' . (int)$c . '</li>';
        } else {
            echo '<li>' . htmlspecialchars($t) . ': <em>missing</em></li>';
        }
    }
    echo '</ul>';

} catch (PDOException $e) {
    echo '<p style="color:red"><strong>Database error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p>Check your <code>config.php</code> credentials and that the database exists.</p>';
}
