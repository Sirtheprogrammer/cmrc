<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function csrf_verify($token = null) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // if no token passed explicitly, try to read from POST _csrf field
    if ($token === null) {
        $token = $_POST['_csrf'] ?? null;
    }

    if (empty($_SESSION['csrf_token']) || $token === null) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], (string)$token);
}

// Generate CSRF field
function csrf_field() {
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}