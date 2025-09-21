<?php
// admin/orders.php - list and manage orders
require_once __DIR__ . '/../helpers/auth.php';
require_admin();
require_once __DIR__ . '/../db/connection.php';
$config = require __DIR__ . '/../config.php';
$pdo = $GLOBALS['pdo'] ?? $pdo;

// Handle status change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) { http_response_code(403); echo 'Invalid CSRF'; exit; }
    $orderId = (int)($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? 'pending';
    $stmt = $pdo->prepare('UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$status, $orderId]);
    header('Location: orders.php'); exit;
}

// Delete order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_order') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) { http_response_code(403); echo 'Invalid CSRF'; exit; }
    $orderId = (int)($_POST['order_id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    header('Location: orders.php'); exit;
}

// Fetch orders with optional search
$q = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM orders';
$params = [];
if ($q !== '') {
    $sql .= ' WHERE phone LIKE :q OR package_name LIKE :q OR status LIKE :q';
    $params[':q'] = '%' . $q . '%';
}
$sql .= ' ORDER BY created_at DESC LIMIT 200';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin — Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Panel</a>
    <div>
      <a href="packages.php" class="btn btn-outline-light btn-sm me-2">Manage Packages</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>
<div class="container py-4">
  <h3>Orders</h3>
  <form class="row g-2 mb-3" method="get">
    <div class="col-auto"><input name="q" value="<?php echo htmlspecialchars($q); ?>" class="form-control" placeholder="Search by phone, package, status"></div>
    <div class="col-auto"><button class="btn btn-primary">Search</button></div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead><tr><th>#</th><th>Package</th><th>Phone</th><th>Network</th><th>Amount</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td><?php echo (int)$o['id']; ?></td>
            <td><?php echo htmlspecialchars($o['package_name']); ?></td>
            <td><?php echo htmlspecialchars($o['phone']); ?></td>
            <td><?php echo htmlspecialchars($o['network']); ?></td>
            <td><?php echo htmlspecialchars($o['amount_paid'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($o['status']); ?></td>
            <td><?php echo htmlspecialchars($o['created_at']); ?></td>
            <td>
              <a href="order_view.php?id=<?php echo (int)$o['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
              <form method="post" style="display:inline-block" onsubmit="return confirm('Change status?')">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="order_id" value="<?php echo (int)$o['id']; ?>">
                <select name="status" class="form-select form-select-sm d-inline-block" style="width:140px;">
                  <?php foreach (['pending','awaiting_confirmation','confirmed','delivered','cancelled'] as $st): ?>
                    <option value="<?php echo $st; ?>" <?php if ($st === $o['status']) echo 'selected'; ?>><?php echo $st; ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-primary">Save</button>
              </form>

              <form method="post" style="display:inline-block" onsubmit="return confirm('Delete order?')">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="delete_order">
                <input type="hidden" name="order_id" value="<?php echo (int)$o['id']; ?>">
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
