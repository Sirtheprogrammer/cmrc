<?php
// admin/login.php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';

if (is_admin_logged_in()) {
    header('Location: orders.php'); exit;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) { $errors[] = 'Invalid CSRF token.'; }
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    if (!$user || !$pass) $errors[] = 'Username and password are required.';
    if (!$errors) {
        if (admin_login($user, $pass)) {
            header('Location: orders.php'); exit;
        } else {
            $errors[] = 'Invalid credentials.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Admin login</h4>
          <?php if ($errors): ?>
            <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>'; ?></ul></div>
          <?php endif; ?>
          <form method="post">
            <?php echo csrf_field(); ?>
            <div class="mb-2"><input class="form-control" name="username" placeholder="Username" required></div>
            <div class="mb-2"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
            <div class="d-grid"><button class="btn btn-primary">Log in</button></div>
          </form>
        </div>
      </div>
      <p class="mt-3 small text-muted">Default admin can be created via DB. Use password_hash() with PASSWORD_DEFAULT.</p>
    </div>
  </div>
</div>
</body>
</html>
