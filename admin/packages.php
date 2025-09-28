<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../helpers/csrf.php';

session_start();

// Ensure database connection is available
$pdo = require __DIR__ . '/../db/connection.php';

// Include header (which includes require_admin())
require_once __DIR__ . '/includes/header.php';

// handle create/edit
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) { $errors[] = 'Invalid CSRF token.'; }
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM packages WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: packages.php'); exit;
    }

    $name = trim($_POST['name'] ?? '');
    $duration = $_POST['duration'] ?? '1_month';
    $gb = (float)($_POST['gb_amount'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $desc = trim($_POST['description'] ?? '');
    $network = $_POST['network'] ?? '';
    $allow_custom = isset($_POST['allow_custom_gb']) ? 1 : 0;

    if ($name === '') $errors[] = 'Name required.';
    if ($gb <= 0) $errors[] = 'GB must be positive.';
    if ($price <= 0) $errors[] = 'Price must be positive.';

    if ($network === '') $errors[] = 'Network required.';

    if (empty($errors)) {
        if ($action === 'create') {
            $stmt = $pdo->prepare('INSERT INTO packages (name,duration,gb_amount,price,description,network,allow_custom_gb,created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute([$name, $duration, $gb, $price, $desc, $network, $allow_custom]);
        } elseif ($action === 'edit') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('UPDATE packages SET name=?, duration=?, gb_amount=?, price=?, description=?, network=?, allow_custom_gb=? WHERE id = ?');
            $stmt->execute([$name, $duration, $gb, $price, $desc, $network, $allow_custom, $id]);
        }
        header('Location: packages.php'); exit;
    }
}

// fetch packages
$rows = $pdo->query('SELECT * FROM packages ORDER BY price ASC')->fetchAll();
?>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<div class="container py-4">
  <h3>Packages</h3>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><?php foreach ($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?></div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-body">
          <h5>Create package</h5>
          <form method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="create">
            <div class="mb-2"><input name="name" class="form-control" placeholder="Name"></div>
            <div class="mb-2"><select name="duration" class="form-select"><option value="3_weeks">3 weeks</option><option value="1_month">1 month</option><option value="6_months">6 months</option><option value="12_months">12 months</option><option value="custom">Custom</option></select></div>
            <div class="mb-2"><select name="network" class="form-select" required><option value="">Select Network</option><option value="tigo">Tigo</option><option value="vodacom">Vodacom</option><option value="halotel">Halotel</option><option value="airtel">Airtel</option></select></div>
            <div class="mb-2"><input name="gb_amount" class="form-control" placeholder="GB amount"></div>
            <div class="mb-2"><input name="price" class="form-control" placeholder="Price"></div>
            <div class="mb-2"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
            <div class="form-check mb-2"><input type="checkbox" name="allow_custom_gb" class="form-check-input" id="c1"><label class="form-check-label" for="c1">Allow custom GB</label></div>
            <div><button class="btn btn-primary">Create</button></div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5>Existing packages</h5>
          <div class="list-group">
            <?php foreach ($rows as $r): ?>
              <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <strong><?php echo htmlspecialchars($r['name']); ?></strong>
                    <div class="small-muted"><?php echo htmlspecialchars($r['gb_amount']); ?> GB • <?php echo ucfirst(htmlspecialchars($r['network'])); ?> • Tsh <?php echo number_format($r['price'],0); ?></div>
                  </div>
                  <div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="populateEdit(<?php echo htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8'); ?>)">Edit</button>
                    <form method="post" style="display:inline-block" onsubmit="return confirm('Delete package?')">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <hr>
          <div id="editForm" style="display:none">
            <h6>Edit package</h6>
            <form method="post">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="action" value="edit">
              <input type="hidden" name="id" id="edit_id">
              <div class="mb-2"><input name="name" id="edit_name" class="form-control" placeholder="Name"></div>
              <div class="mb-2"><select name="duration" id="edit_duration" class="form-select"><option value="3_weeks">3 weeks</option><option value="1_month">1 month</option><option value="6_months">6 months</option><option value="12_months">12 months</option><option value="custom">Custom</option></select></div>
              <div class="mb-2"><select name="network" id="edit_network" class="form-select" required><option value="">Select Network</option><option value="tigo">Tigo</option><option value="vodacom">Vodacom</option><option value="halotel">Halotel</option><option value="airtel">Airtel</option></select></div>
              <div class="mb-2"><input name="gb_amount" id="edit_gb" class="form-control" placeholder="GB amount"></div>
              <div class="mb-2"><input name="price" id="edit_price" class="form-control" placeholder="Price"></div>
              <div class="mb-2"><textarea name="description" id="edit_description" class="form-control" placeholder="Description"></textarea></div>
              <div class="form-check mb-2"><input type="checkbox" name="allow_custom_gb" id="edit_allow" class="form-check-input"><label class="form-check-label" for="edit_allow">Allow custom GB</label></div>
              <div><button class="btn btn-primary">Save changes</button></div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function populateEdit(data) {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_duration').value = data.duration;
    document.getElementById('edit_gb').value = data.gb_amount;
    document.getElementById('edit_price').value = data.price;
    document.getElementById('edit_description').value = data.description;
    document.getElementById('edit_network').value = data.network;
    document.getElementById('edit_allow').checked = data.allow_custom_gb == 1;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
