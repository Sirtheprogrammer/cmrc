<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';

// Already logged in? Redirect to dashboard
if (is_logged_in()) {
    header('Location: /lupyanatech/dashboard.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Invalid CSRF token';
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (user_login($username, $password)) {
            // Successful login - redirect to dashboard
            header('Location: /lupyanatech/dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }
}

// Show login form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lupyana Tech</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 2rem; }
        .container { max-width: 400px; margin: 0 auto; }
        .error { color: #dc3545; margin-bottom: 1rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button { 
            background: #0366d6; 
            color: white; 
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="/lupyanatech/register.php">Register here</a></p>
    </div>
</body>
</html>
