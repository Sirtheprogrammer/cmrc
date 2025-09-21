<?php
// public/checkout.php - checkout form
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');

// get PDO safely
try {
    $pdo = require __DIR__ . '/../db/connection.php';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Database connection error.';
    error_log('DB connect error in checkout.php: ' . $e->getMessage());
    exit;
}

require_once __DIR__ . '/../helpers/csrf.php';

$package = null;
$id = isset($_GET['package_id']) ? (int)$_GET['package_id'] : 0;
if ($id > 0) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM packages WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $package = $stmt->fetch();
    } catch (PDOException $e) {
        error_log('DB error fetching package id ' . $id . ': ' . $e->getMessage());
        http_response_code(500);
        echo 'Internal server error.';
        exit;
    }
}

if (!$package) {
    // return a friendly 404-like page so admin/user can see what's wrong
    http_response_code(404);
    ?>
    <!doctype html>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Package not found — Lupyana Tech</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-8 text-center">
          <div class="card p-4">
            <h1 class="display-6">Package not found</h1>
            <p class="small-muted">We couldn't find the package you requested (id: <?php echo $id ?: 'N/A'; ?>).</p>
            <p>If you followed a link, please go back to the <a href="<?php echo $base; ?>">home page</a> and choose a package. If you are the site owner, add the package in the admin panel or insert it into the database.</p>
            <div class="mt-3">
              <a class="btn btn-primary" href="<?php echo $base; ?>">Back to home</a>
              <a class="btn btn-outline-secondary" href="/lupyanatech/admin/packages.php">Manage packages</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    </body>
    </html>
    <?php
    exit;
}

$maxSize = $config['app']['upload']['max_size'];
$allowed = $config['app']['upload']['allowed_types'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout — <?php echo htmlspecialchars($package['name']); ?> — Lupyana Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --secondary: #0ea5a2;
        --dark: #1e293b;
        --light: #f8fafc;
        --gray: #64748b;
        --gray-light: #e2e8f0;
        --success: #10b981;
        --danger: #ef4444;
        --rounded: 12px;
        --rounded-lg: 16px;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    body { 
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; 
        background: var(--light); 
        color: var(--dark);
        line-height: 1.6;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-weight: 700;
    }
    
    .navbar {
        padding: 1rem 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .navbar-brand {
        font-weight: 800;
        color: var(--primary);
        font-size: 1.5rem;
    }
    
    .btn {
        border-radius: var(--rounded);
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-2px);
    }
    
    .btn-success {
        background: var(--success);
        border-color: var(--success);
    }
    
    .card {
        border-radius: var(--rounded-lg);
        border: 1px solid var(--gray-light);
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: var(--shadow-lg);
    }
    
    .form-control, .form-select {
        border-radius: var(--rounded);
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-light);
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }
    
    .form-text {
        color: var(--gray);
        font-size: 0.875rem;
    }
    
    .preview { 
        max-width: 100%; 
        max-height: 280px; 
        object-fit: contain; 
        border-radius: var(--rounded); 
        border: 1px solid var(--gray-light);
        padding: 0.5rem;
        background: white;
    }
    
    .badge {
        border-radius: 50px;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
    }
    
    .package-header {
        border-bottom: 1px solid var(--gray-light);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .sticky-sidebar {
        position: sticky;
        top: 20px;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--gray-light);
        z-index: 1;
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 2px solid var(--gray-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .step.active .step-number {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .step-label {
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .required-label::after {
        content: '*';
        color: var(--danger);
        margin-left: 0.25rem;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .sticky-sidebar {
            position: static;
            margin-top: 2rem;
        }
        
        .step-indicator {
            display: none;
        }
    }
  </style>
</head>
<body>
<nav class="navbar navbar-light bg-white">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $base; ?>">
        <i class="fas fa-wifi me-2"></i>LUPYANA TECH
    </a>
  </div>
</nav>

<main class="container py-4">
  <div class="step-indicator">
    <div class="step active">
      <div class="step-number">1</div>
      <div class="step-label">Select Package</div>
    </div>
    <div class="step active">
      <div class="step-number">2</div>
      <div class="step-label">Checkout</div>
    </div>
    <div class="step">
      <div class="step-number">3</div>
      <div class="step-label">Confirmation</div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-7">
      <div class="card mb-4">
        <div class="card-body">
          <div class="package-header">
            <h4 class="card-title mb-2"><?php echo htmlspecialchars($package['name']); ?></h4>
            <p class="text-muted"><?php echo htmlspecialchars($package['description']); ?></p>

            <div class="d-flex gap-3 align-items-center">
              <div class="fs-4 fw-bold text-primary">Tsh <?php echo number_format($package['price'], 0); ?></div>
              <span class="badge bg-light text-dark"><?php echo htmlspecialchars($package['gb_amount']); ?> GB</span>
              <div class="text-muted ms-auto"><?php echo htmlspecialchars($package['duration']); ?></div>
            </div>
          </div>

          <form id="checkoutForm" action="submit_order.php" method="post" enctype="multipart/form-data" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="package_id" value="<?php echo (int)$package['id']; ?>">

            <div class="mb-4">
              <label class="form-label required-label">Phone number</label>
              <input type="text" name="phone" id="phone" class="form-control" placeholder="2557xxxxxxxx" required>
              <div class="form-text">Include country code, no plus sign (+). Example: 255712345678</div>
            </div>

            <div class="mb-4">
              <label class="form-label">Network</label>
              <select name="network" class="form-select">
                <option value="">Select network (optional)</option>
                <option>Halotel</option>
                <option>Vodacom</option>
                <option>Tigo</option>
                <option>Airtel</option>
                <option>Other</option>
              </select>
            </div>

            <?php if ($package['allow_custom_gb']): ?>
            <div class="mb-4">
              <label class="form-label">Custom GB amount (optional)</label>
              <input type="number" step="0.01" name="custom_gb" class="form-control" placeholder="e.g. 3.5">
              <div class="form-text">Specify if you need a different amount than the standard package</div>
            </div>
            <?php endif; ?>

            <div class="mb-4">
              <label class="form-label required-label">Payment screenshot</label>
              <input type="file" name="screenshot" id="screenshot" class="form-control" accept="image/*" required>
              <div class="form-text">Allowed: <?php echo implode(', ', array_map('htmlspecialchars', $allowed)); ?>. Max: <?php echo round($maxSize/1024/1024,2); ?> MB.</div>
            </div>

            <div class="mb-4" id="previewWrap" style="display:none">
              <label class="form-label">Preview</label>
              <div><img id="imgPreview" class="preview" src="" alt="Preview"></div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-paper-plane me-2"></i>Submit Order
              </button>
              <a href="<?php echo $base; ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to site
              </a>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <h6 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Important Notes</h6>
          <ul class="list-group list-group-flush">
            <li class="list-group-item px-0 py-2 border-0 small">
              <i class="fas fa-check-circle text-success me-2"></i>After submission you'll be redirected to WhatsApp to confirm. Keep your phone available.
            </li>
            <li class="list-group-item px-0 py-2 border-0 small">
              <i class="fas fa-check-circle text-success me-2"></i>We only store the screenshot URL for verification - no financial data is stored.
            </li>
            <li class="list-group-item px-0 py-2 border-0 small">
              <i class="fas fa-check-circle text-success me-2"></i>If you face any issues, contact our support via WhatsApp.
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="sticky-sidebar">
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="card-title mb-3"><i class="fas fa-receipt me-2 text-primary"></i>Order Summary</h6>
            
            <div class="summary-item">
              <span>Package:</span>
              <strong><?php echo htmlspecialchars($package['name']); ?></strong>
            </div>
            
            <div class="summary-item">
              <span>Data:</span>
              <strong><?php echo htmlspecialchars($package['gb_amount']); ?> GB</strong>
            </div>
            
            <div class="summary-item">
              <span>Duration:</span>
              <strong><?php echo htmlspecialchars($package['duration']); ?></strong>
            </div>
            
            <hr>
            
            <div class="summary-item">
              <span>Total:</span>
              <strong class="fs-5 text-primary">Tsh <?php echo number_format($package['price'], 0); ?></strong>
            </div>
            
            <div class="mt-4 p-3 bg-light rounded">
              <p class="small mb-0"><i class="fas fa-bolt me-2 text-success"></i>Once your payment is confirmed, the data will be activated immediately.</p>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body text-center">
            <h6 class="card-title mb-3"><i class="fas fa-headset me-2 text-primary"></i>Need help?</h6>
            <p class="small text-muted mb-3">Contact our operator for assistance with your order or payment confirmation.</p>
            <a href="https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>" class="btn btn-success w-100">
              <i class="fab fa-whatsapp me-2"></i>WhatsApp Support
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  const input = document.getElementById('screenshot');
  const previewWrap = document.getElementById('previewWrap');
  const img = document.getElementById('imgPreview');
  const maxSize = <?php echo (int)$maxSize; ?>;
  const allowed = <?php echo json_encode($allowed); ?>;

  input?.addEventListener('change', (e) => {
    const f = input.files && input.files[0];
    if (!f) { previewWrap.style.display = 'none'; return; }
    if (f.size > maxSize) { alert('File too large. Max ' + (maxSize/1024/1024).toFixed(2) + ' MB'); input.value = ''; previewWrap.style.display = 'none'; return; }
    if (!allowed.includes(f.type)) { alert('Invalid file type. Allowed: ' + allowed.join(', ')); input.value = ''; previewWrap.style.display = 'none'; return; }
    const reader = new FileReader();
    reader.onload = function(ev) { img.src = ev.target.result; previewWrap.style.display = 'block'; }
    reader.readAsDataURL(f);
  });

  document.getElementById('checkoutForm').addEventListener('submit', function(e){
    const phone = document.getElementById('phone').value.trim();
    if (!phone) { e.preventDefault(); alert('Phone number is required'); return false; }
    // basic phone validation: digits only
    if (!/^\d{7,15}$/.test(phone)) { if (!confirm('Phone number format looks unusual. Continue anyway?')) { e.preventDefault(); return false; } }
  });
})();
</script>
</body>
</html>