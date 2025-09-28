<?php
session_start();

// Debug mode (turn off in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Example DB connection (adjust to your db credentials)
$dsn = "mysql:host=localhost;dbname=lupyanatech;charset=utf8mb4";
$dbUser = "root";
$dbPass = "";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$pageTitle = 'Register - Lupyana Tech';
$errors = [];
$success = false;

// Initialize variables
$full_name = $username = $email = $phone = "";

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($full_name)) $errors[] = 'Full name is required';
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters';
    }
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif (!preg_match('/^\+255[0-9]{9}$/', $phone)) {
        $errors[] = 'Phone must be in format +255XXXXXXXXX';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    // If no errors → insert user
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ? OR phone = ?');
        $stmt->execute([$username, $email, $phone]);

        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Username, email or phone number already exists';
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (full_name, username, email, phone, password_hash) VALUES (?, ?, ?, ?, ?)');
            $success = $stmt->execute([
                $full_name,
                $username,
                $email,
                $phone,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            if ($success) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = $full_name;
                header('Location: dashboard.php');
                exit;
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg p-4 rounded-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Create Your Account</h2>
                    <p class="text-muted">Join Lupyana Tech to access our services</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control form-control-lg" id="full_name" name="full_name"
                               value="<?= htmlspecialchars($full_name) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control form-control-lg" id="username" name="username"
                                   value="<?= htmlspecialchars($username) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email"
                               value="<?= htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control form-control-lg" id="phone" name="phone"
                               value="<?= htmlspecialchars($phone) ?>" placeholder="+255XXXXXXXXX" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Create Password</label>
                        <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" name="register" class="btn btn-primary w-100 btn-lg">Create Account</button>
                </form>

                <div class="text-center mt-4">
                    <span class="text-muted">Already have an account?</span>
                    <a href="login.php" class="text-decoration-none fw-bold">Sign in here</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
