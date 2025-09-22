<?php
// public/my_orders.php - show orders for logged-in user with graceful DB handling
try {
    $pdo = require __DIR__ . '/../db/connection.php';
} catch (Exception $e) {
    $pdo = null;
    $dbError = $e->getMessage();
}
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');

require_user_login();
$user = current_user();

$orders = [];
if (!empty($dbError)) {
    // leave $orders empty and display DB error in UI
} else {
    // fetch orders for this user (use correct column names)
    $stmt = $pdo->prepare('SELECT o.*, p.name AS package_title FROM orders o LEFT JOIN packages p ON p.id = o.package_id WHERE o.user_id = :uid ORDER BY o.created_at DESC');
    $stmt->execute([':uid' => $user['id']]);
    $orders = $stmt->fetchAll();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Orders — Lupyana Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>My Orders</h3>
    <div>
      <a href="<?php echo $base; ?>/packages.php" class="btn btn-outline-primary me-2">Buy more</a>
      <a href="<?php echo $base; ?>/logout.php" class="btn btn-secondary">Logout</a>
    </div>
  </div>

  <?php if (!empty($dbError)): ?>
    <div class="alert alert-danger">Database error: <?php echo htmlspecialchars($dbError); ?></div>
  <?php endif; ?>

  <?php if (!$orders): ?>
    <div class="alert alert-info">You have not placed any orders yet.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered bg-white">
        <thead>
          <tr>
            <th>ID</th>
            <th>Package</th>
            <th>Phone</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Placed</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><?php echo (int)$o['id']; ?></td>
              <td><?php echo htmlspecialchars($o['package_title'] ?? '—'); ?></td>
              <td><?php echo htmlspecialchars($o['phone'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($o['amount_paid'] ?? $o['amount'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($o['status']); ?></td>
              <td><?php echo htmlspecialchars($o['created_at']); ?></td>
              <td><a class="btn btn-sm btn-outline-secondary" href="<?php echo $base; ?>/admin/order_view.php?id=<?php echo (int)$o['id']; ?>">View</a></td>
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
