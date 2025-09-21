<?php
// helpers/auth.php - simple admin auth helpers
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/../db/connection.php';

function admin_login(string $username, string $password): bool {
    $pdo = $GLOBALS['pdo'] ?? $pdo;
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if (!$admin) return false;
    if (password_verify($password, $admin['password_hash'])) {
        // store minimal session
        $_SESSION['admin'] = [
            'id' => $admin['id'],
            'username' => $admin['username'],
            'name' => $admin['name'] ?? null,
        ];
        return true;
    }
    return false;
}

function admin_logout(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    unset($_SESSION['admin']);
}

function is_admin_logged_in(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['admin']);
}

function require_admin(): void {
    if (!is_admin_logged_in()) {
        header('Location: /lupyanatech/admin/login.php');
        exit;
    }
}

function current_admin() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return $_SESSION['admin'] ?? null;
}
