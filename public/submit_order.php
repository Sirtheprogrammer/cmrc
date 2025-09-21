<?php
// public/submit_order.php - handle order submission
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/upload.php';
$config = require __DIR__ . '/../config.php';
$pdo = $GLOBALS['pdo'] ?? $pdo;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

// basic rate limiting per IP (simple)
$ip = $_SERVER['REMOTE_ADDR'];
$limitKey = 'rl_' . $ip;
$now = time();
if (!isset($_SESSION[$limitKey])) $_SESSION[$limitKey] = [];
// remove old
$_SESSION[$limitKey] = array_filter($_SESSION[$limitKey], fn($t) => $t > $now - 60);
if (count($_SESSION[$limitKey]) >= $config['app']['rate_limit']['requests_per_minute']) {
    http_response_code(429);
    echo 'Too many requests. Try again later.';
    exit;
}
$_SESSION[$limitKey][] = $now;

// CSRF
if (!csrf_verify($_POST['_csrf'] ?? '')) {
    http_response_code(403);
    echo 'Invalid CSRF token.';
    exit;
}

$package_id = (int)($_POST['package_id'] ?? 0);
$phone = trim($_POST['phone'] ?? '');
$network = trim($_POST['network'] ?? '');
$custom_gb = isset($_POST['custom_gb']) ? (float)$_POST['custom_gb'] : null;

// fetch package
$stmt = $pdo->prepare('SELECT * FROM packages WHERE id = ? LIMIT 1');
$stmt->execute([$package_id]);
$package = $stmt->fetch();
if (!$package) {
    echo 'Invalid package'; exit;
}

// validation
$errors = [];
if ($phone === '') $errors[] = 'Phone is required.';
if (!isset($_FILES['screenshot'])) $errors[] = 'Screenshot is required.';
else {
    $v = validate_image_upload($_FILES['screenshot'], $config);
    if ($v !== true) $errors[] = $v;
}

if ($errors) {
    echo '<h3>Validation errors</h3><ul><li>' . implode('</li><li>', array_map('htmlspecialchars', $errors)) . '</li></ul>';
    echo '<p><a href="javascript:history.back()">Go back</a></p>';
    exit;
}

// upload to imgbb
try {
    $img_url = upload_to_imgbb($_FILES['screenshot'], $config);
} catch (Exception $e) {
    echo 'Upload failed: ' . htmlspecialchars($e->getMessage()); exit;
}

// save order
$insert = 'INSERT INTO orders (package_id, package_name, phone, network, custom_gb, payment_screenshot_url, status, created_at)
VALUES (:package_id, :package_name, :phone, :network, :custom_gb, :url, :status, NOW())';
$stmt = $pdo->prepare($insert);
$stmt->execute([
    ':package_id' => $package['id'],
    ':package_name' => $package['name'],
    ':phone' => $phone,
    ':network' => $network,
    ':custom_gb' => $custom_gb,
    ':url' => $img_url,
    ':status' => 'awaiting_confirmation',
]);

$orderId = $pdo->lastInsertId();

// build whatsapp message and redirect
$owner = $config['owner']['whatsapp'];
$text = urlencode(sprintf("New order #%s\nPackage: %s\nPhone: %s\nNetwork: %s\nAmount paid: %s\nScreenshot: %s", $orderId, $package['name'], $phone, $network, '', $img_url));
$wa = 'https://wa.me/' . $owner . '?text=' . $text;

header('Location: ' . $wa);
exit;
