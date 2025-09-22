<?php
$pdo = require __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_admin();
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');

// handle deletions
$errors = [];
$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        csrf_verify();
        $deleteId = (int)($_POST['id'] ?? 0);
        if ($deleteId <= 0) throw new RuntimeException('Invalid user id');
        // remove user (orders.user_id is FK with ON DELETE SET NULL)
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $deleteId]);
        $success = 'User deleted';
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}

// fetch users with order counts
$stmt = $pdo->query("SELECT u.id, u.username, u.email, u.phone, u.created_at, COUNT(o.id) AS orders_count
    FROM users u
    LEFT JOIN orders o ON o.user_id = u.id
    GROUP BY u.id
    ORDER BY orders_count DESC, u.created_at DESC");
$users = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Users — Admin — Lupyana Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Users</h3>
    <div>
      <a href="<?php echo $base; ?>/admin/orders.php" class="btn btn-outline-primary">Orders</a>
      <a href="<?php echo $base; ?>/admin/packages.php" class="btn btn-outline-secondary">Packages</a>
    </div>
  </div>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <?php foreach ($errors as $err): ?>
        <div><?php echo htmlspecialchars($err); ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <?php if (!$users): ?>
    <div class="alert alert-info">No users found.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered bg-white">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Orders</th>
            <th>Joined</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?php echo (int)$u['id']; ?></td>
              <td><?php echo htmlspecialchars($u['username']); ?></td>
              <td><?php echo htmlspecialchars($u['email'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($u['phone'] ?? ''); ?></td>
              <td><?php echo (int)$u['orders_count']; ?></td>
              <td><?php echo htmlspecialchars($u['created_at']); ?></td>
              <td>
                <a class="btn btn-sm btn-outline-primary" href="<?php echo $base; ?>/admin/orders.php?user_id=<?php echo (int)$u['id']; ?>">View Orders</a>
                <form method="post" style="display:inline-block;margin-left:6px;" onsubmit="return confirm('Delete this user?');">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                  <button class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
