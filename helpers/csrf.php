<?php
// helpers/csrf.php - minimal CSRF token helpers
if (session_status() === PHP_SESSION_NONE) session_start();

function csrf_token(): string {
    $config = require __DIR__ . '/../config.php';
    $salt = $config['app']['csrf_salt'] ?? '';
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = hash_hmac('sha256', bin2hex(random_bytes(16)), $salt);
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    $token = csrf_token();
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars($token, ENT_QUOTES) . '">';
}

/**
 * Verify CSRF token.
 * If $token is null, the function will try to read from POST['_csrf'].
 * Returns boolean.
 */
function csrf_verify($token = null): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if ($token === null) {
        $token = $_POST['_csrf'] ?? '';
    }
    $stored = $_SESSION['csrf_token'] ?? '';
    if (!is_string($stored) || $stored === '') return false;
    return is_string($token) && hash_equals($stored, (string)$token);
}
