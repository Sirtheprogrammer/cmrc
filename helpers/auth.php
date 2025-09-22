<?php
// helpers/auth.php - simple admin auth helpers
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/csrf.php';
// NOTE: db connection should be required by callers to allow graceful error handling

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

// ---------------------
// Customer (user) auth helpers
// ---------------------

function user_register(PDO $pdo, string $username, string $password, ?string $phone = null, ?string $email = null): array
{
    $username = trim($username);
    if ($username === '' || $password === '') {
        throw new InvalidArgumentException('Username and password are required');
    }

    // check for existing username
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :u LIMIT 1');
    $stmt->execute([':u' => $username]);
    if ($stmt->fetch()) {
        throw new RuntimeException('Username already taken');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, phone, email) VALUES (:u, :p, :ph, :e)');
    $stmt->execute([
        ':u' => $username,
        ':p' => $hash,
        ':ph' => $phone,
        ':e' => $email
    ]);

    $id = (int)$pdo->lastInsertId();
    return [
        'id' => $id,
        'username' => $username,
        'phone' => $phone,
        'email' => $email
    ];
}

function user_login(PDO $pdo, string $username, string $password): array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :u LIMIT 1');
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new RuntimeException('Invalid username or password');
    }
    if (!password_verify($password, $user['password_hash'])) {
        throw new RuntimeException('Invalid username or password');
    }

    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    // Store minimal user data in session
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'username' => $user['username'],
        'phone' => $user['phone'] ?? null,
        'email' => $user['email'] ?? null
    ];

    return $_SESSION['user'];
}

function user_logout(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    unset($_SESSION['user']);
}

function is_user_logged_in(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    return !empty($_SESSION['user']);
}

function current_user(): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    return $_SESSION['user'] ?? null;
}

function require_user_login(): void
{
    if (!is_user_logged_in()) {
        $base = rtrim((require __DIR__ . '/../config.php')['app']['base_url'], '/');
        header('Location: ' . $base . '/login.php');
        exit;
    }
}
