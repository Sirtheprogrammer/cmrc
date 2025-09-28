<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../helpers/csrf.php';
$pdo = require __DIR__ . '/../../db/connection.php';
require_admin();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Lupyana Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: .8rem 1rem;
            border-radius: 5px;
            margin: 2px 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.1);
            color: white;
        }
        .sidebar .nav-link i {
            width: 24px;
        }
        .main-content {
            min-height: 100vh;
            background: #f8f9fa;
        }
        .header {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0 sidebar">
            <div class="p-3">
                <h5 class="mb-3">Lupyana Tech</h5>
                <nav class="nav flex-column">
                    <a class="nav-link <?php echo $current_page === 'packages.php' ? 'active' : ''; ?>" href="packages.php">
                        <i class="fas fa-box"></i> Packages
                    </a>
                    <a class="nav-link <?php echo $current_page === 'orders.php' ? 'active' : ''; ?>" href="orders.php">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </a>
                    <a class="nav-link <?php echo $current_page === 'users.php' ? 'active' : ''; ?>" href="users.php">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content px-4">
            <div class="header py-3 mb-4">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Admin Panel</h4>
                        <div class="text-muted">
                            Welcome, <?php echo htmlspecialchars(get_admin_username()); ?>
                        </div>
                    </div>
                </div>
            </div>
