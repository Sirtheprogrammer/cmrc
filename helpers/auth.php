<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin()) {
        header('Location: /lupyanatech/admin/login.php');
        exit;
    }
}

function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function get_admin_id() {
    return $_SESSION['admin_id'] ?? null;
}

function get_username() {
    return $_SESSION['username'] ?? null;
}

function get_admin_username() {
    return $_SESSION['admin_username'] ?? null;
}

// Compatibility: return current logged-in user info (legacy helper)
function current_user() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? null,
        'name' => $_SESSION['name'] ?? null,
        'email' => $_SESSION['email'] ?? null,
    ];
}

// Optional helper to set current user data into the session
function set_current_user(array $user) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($user['id'])) {
        $_SESSION['user_id'] = $user['id'];
    }
    if (isset($user['username'])) {
        $_SESSION['username'] = $user['username'];
    }
    if (isset($user['name'])) {
        $_SESSION['name'] = $user['name'];
    }
    if (isset($user['email'])) {
        $_SESSION['email'] = $user['email'];
    }
}

// Initialize admin if not exists
function ensure_admin_exists($pdo) {
    try {
        $stmt = $pdo->query('SELECT COUNT(*) FROM admins');
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash, name) VALUES (?, ?, ?)');
            $stmt->execute([
                'admin',
                password_hash('admin123', PASSWORD_DEFAULT),
                'Administrator'
            ]);
        }
    } catch (PDOException $e) {
        error_log("Error creating admin: " . $e->getMessage());
    }
}

// Authenticate a user by username and password. Returns true on success, false on failure.
function user_login(string $username, string $password): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // normalize inputs
    $username = trim($username);

    if ($username === '' || $password === '') {
        return false;
    }

    // obtain a PDO connection from the DB helper
    $dbFile = __DIR__ . '/../db/connection.php';
    if (!file_exists($dbFile)) {
        error_log('Database connection file not found: ' . $dbFile);
        return false;
    }

    try {
        $pdo = require $dbFile; // connection.php returns the PDO instance
    } catch (Throwable $e) {
        error_log('Failed to get DB connection in user_login: ' . $e->getMessage());
        return false;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, username, password_hash, email FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && isset($user['password_hash']) && password_verify($password, $user['password_hash'])) {
            // Set session values
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            if (isset($user['email'])) {
                $_SESSION['email'] = $user['email'];
            }
            return true;
        }

        return false;
    } catch (Throwable $e) {
        error_log('Error during user_login: ' . $e->getMessage());
        return false;
    }
}