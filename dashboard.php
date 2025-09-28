<?php
require_once __DIR__ . '/helpers/auth.php';
$pdo = require __DIR__ . '/db/connection.php';

// Must be logged in to view dashboard
if (!is_logged_in()) {
    header('Location: /lupyanatech/login.php');
    exit;
}

// Get username and user_id from session
$username = $_SESSION['username'] ?? 'User';
$user_id = $_SESSION['user_id'] ?? 0;

// Fetch user's recent orders
$stmt = $pdo->prepare('
    SELECT o.*, p.name as package_name, p.gb_amount, p.duration 
    FROM orders o 
    LEFT JOIN packages p ON o.package_id = p.id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC 
    LIMIT 5
');
$stmt->execute([$user_id]);
$recent_orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lupyana Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .nav-link {
            color: #333;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }
        .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .nav-link i {
            width: 24px;
        }
        .stats-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .table {
            background: white;
            border-radius: 10px;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-white shadow-sm px-3 py-4 min-vh-100">
                <h4 class="mb-4 fw-bold text-primary">Lupyana Tech</h4>
                <div class="nav flex-column">
                    <a class="nav-link active" href="/lupyanatech/dashboard.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a class="nav-link" href="/lupyanatech/packages.php">
                        <i class="fas fa-box"></i> Browse Packages
                    </a>
                    <a class="nav-link" href="/lupyanatech/my_orders.php">
                        <i class="fas fa-shopping-cart"></i> My Orders
                    </a>
                    <a class="nav-link" href="/lupyanatech/services.php">
                        <i class="fas fa-cogs"></i> Services
                    </a>
                    <a class="nav-link" href="/lupyanatech/contact.php">
                        <i class="fas fa-envelope"></i> Contact Support
                    </a>
                    <hr>
                    <a class="nav-link text-danger" href="/lupyanatech/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Welcome, <?= htmlspecialchars($username) ?></h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/lupyanatech/">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card stats-card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Active Orders</h5>
                                <p class="card-text display-6">
                                    <?php
                                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "active"');
                                    $stmt->execute([$user_id]);
                                    echo $stmt->fetchColumn();
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <p class="card-text display-6">
                                    <?php
                                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE user_id = ?');
                                    $stmt->execute([$user_id]);
                                    echo $stmt->fetchColumn();
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Active Data (GB)</h5>
                                <p class="card-text display-6">
                                    <?php
                                    $stmt = $pdo->prepare('
                                        SELECT SUM(p.gb_amount) 
                                        FROM orders o 
                                        JOIN packages p ON o.package_id = p.id 
                                        WHERE o.user_id = ? AND o.status = "active"
                                    ');
                                    $stmt->execute([$user_id]);
                                    echo number_format($stmt->fetchColumn() ?? 0, 1);
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Orders</h5>
                            <a href="/lupyanatech/my_orders.php" class="btn btn-primary btn-sm">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recent_orders)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted mb-0">No orders found</p>
                                                <a href="/lupyanatech/packages.php" class="btn btn-primary btn-sm mt-2">Browse Packages</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recent_orders as $order): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong><?= htmlspecialchars($order['package_name']) ?></strong>
                                                        <div class="text-muted small">
                                                            <?= htmlspecialchars($order['gb_amount']) ?> GB - <?= htmlspecialchars($order['duration']) ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= number_format($order['amount_paid'], 2) ?> TZS</td>
                                                <td>
                                                    <?php
                                                    $status_class = match($order['status']) {
                                                        'active' => 'bg-success',
                                                        'pending' => 'bg-warning',
                                                        'completed' => 'bg-info',
                                                        default => 'bg-secondary'
                                                    };
                                                    ?>
                                                    <span class="status-badge <?= $status_class ?> text-white">
                                                        <?= ucfirst(htmlspecialchars($order['status'])) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                                <td>
                                                    <a href="/lupyanatech/order_view.php?id=<?= $order['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
