<?php
// admin/order_view.php - view single order details
require_once __DIR__ . '/../helpers/auth.php';
require_admin();
require_once __DIR__ . '/../db/connection.php';
$config = require __DIR__ . '/../config.php';
$pdo = $GLOBALS['pdo'] ?? $pdo;

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) { header('Location: orders.php'); exit; }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Order #<?php echo (int)$order['id']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Panel</a>
    <div>
      <a href="orders.php" class="btn btn-outline-light btn-sm">Back</a>
    </div>
  </div>
</nav>
<div class="container py-4">
  <h3>Order #<?php echo (int)$order['id']; ?></h3>
  <div class="row g-3">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <p><strong>Package:</strong> <?php echo htmlspecialchars($order['package_name']); ?></p>
          <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
          <p><strong>Network:</strong> <?php echo htmlspecialchars($order['network']); ?></p>
          <p><strong>Custom GB:</strong> <?php echo htmlspecialchars($order['custom_gb']); ?></p>
          <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
          <p><strong>Created:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>

          <form method="post" action="orders.php" onsubmit="return confirm('Change status?')">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
            <div class="mb-2">
              <select name="status" class="form-select">
                <?php foreach (['pending','awaiting_confirmation','confirmed','delivered','cancelled'] as $st): ?>
                  <option value="<?php echo $st; ?>" <?php if ($st === $order['status']) echo 'selected'; ?>><?php echo $st; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div><button class="btn btn-primary">Update status</button></div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-body text-center">
          <p><strong>Payment screenshot</strong></p>
          <img src="<?php echo htmlspecialchars($order['payment_screenshot_url']); ?>" alt="screenshot" class="img-fluid rounded">
          <div class="mt-2">
            <a href="<?php echo htmlspecialchars($order['payment_screenshot_url']); ?>" target="_blank" class="btn btn-outline-secondary btn-sm">Open full</a>
            <a href="<?php echo htmlspecialchars($order['payment_screenshot_url']); ?>" download class="btn btn-primary btn-sm">Download</a>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</body>
</html>
