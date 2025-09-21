<?php
// helpers/csrf.php - minimal CSRF token helpers
if (session_status() === PHP_SESSION_NONE) session_start();

function csrf_token() {
    $config = require __DIR__ . '/../config.php';
    $salt = $config['app']['csrf_salt'] ?? '';
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = hash_hmac('sha256', bin2hex(random_bytes(16)), $salt);
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    $token = csrf_token();
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars($token, ENT_QUOTES) . '">';
}

function csrf_verify($token) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return hash_equals($_SESSION['csrf_token'] ?? '', (string)$token);
}
