<?php
$pdo = require __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../helpers/auth.php';

try {
    // Check if admin exists
    $stmt = $pdo->query('SELECT COUNT(*) FROM admins');
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Create default admin account
        $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash, name) VALUES (?, ?, ?)');
        $stmt->execute([
            'admin',
            password_hash('admin123', PASSWORD_DEFAULT),
            'Administrator'
        ]);
        
        echo "Default admin account created:\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
        echo "\nPlease change this password immediately after logging in!\n";
    } else {
        echo "Admin account already exists.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
