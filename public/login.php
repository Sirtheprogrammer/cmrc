<?php
// Enable verbose errors temporarily for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    $pdo = require __DIR__ . '/../db/connection.php';
} catch (Exception $e) {
    $pdo = null;
    $dbError = $e->getMessage();
}
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/auth.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($dbError)) throw new RuntimeException('Database unavailable');
        csrf_verify();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = user_login($pdo, $username, $password);
        header('Location: ' . $base . '/my_orders.php');
        exit;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login — Lupyana Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title mb-4">Customer Login</h3>
          <?php if (!empty($dbError)): ?>
            <div class="alert alert-danger">Database connection error: <?php echo htmlspecialchars($dbError); ?></div>
          <?php endif; ?>
          <?php if ($errors): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                  <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" novalidate>
            <?php echo csrf_field(); ?>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <a href="<?php echo $base; ?>/register.php">Create an account</a>
              <button class="btn btn-primary">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
