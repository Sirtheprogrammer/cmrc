<?php
// public/home.php - welcome hero page
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');
$pdo = require __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../helpers/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$currentUser = current_user();
try {
    $stmt = $pdo->query('SELECT COUNT(*) FROM users');
    $userCount = (int)$stmt->fetchColumn();
} catch (Exception $e) {
    $userCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupyana Tech — Affordable Internet Data Packages</title>
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
        
        .nav-link {
            font-weight: 500;
            color: var(--dark);
            transition: all 0.2s ease;
        }
        
        .nav-link:hover {
            color: var(--primary);
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
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
        }
        
        .hero { 
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); 
            color: white; 
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.5;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        
        .hero p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        
        .quick-links-card {
            background: white;
            border-radius: var(--rounded-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
        }
        
        .feature-icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--rounded);
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }
        
        .support-card {
            background: white;
            border-radius: var(--rounded-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
        }
        
        footer {
            background: white;
            padding: 2rem 0;
            box-shadow: 0 -1px 3px rgba(0,0,0,0.05);
        }
        
        /* Mobile responsiveness */
        @media (max-width: 992px) {
            .hero {
                padding: 3rem 0;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .quick-links-card {
                margin-top: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 1.75rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $base; ?>">
        <i class="fas fa-wifi me-2"></i>LUPYANA TECH
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/packages.php">Packages</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/contact.php">Contact</a></li>
        <?php if ($currentUser): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo htmlspecialchars($currentUser['username']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
              <li><a class="dropdown-item" href="<?php echo $base; ?>/my_orders.php">My Orders</a></li>
              <li><a class="dropdown-item" href="<?php echo $base; ?>/logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<header class="hero">
  <div class="container hero-content">
    <div class="row align-items-center">
      <div class="col-lg-7 text-white">
        <h1>Fast & Affordable Internet Data for Everyone</h1>
        <p>Choose a package, pay via mobile money, upload your payment screenshot and get connected fast. Secure, reliable and backed by responsive support via WhatsApp.</p>
        <div class="mt-4">
          <a href="<?php echo $base; ?>/services.php" class="btn btn-light btn-lg me-2">
            <i class="fas fa-list me-2"></i>View Our Services
          </a>
          <a href="<?php echo $base; ?>/packages.php" class="btn btn-outline-light btn-lg">
            <i class="fas fa-wifi me-2"></i>See Packages
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="quick-links-card">
          <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Links</h5>
            <?php if ($currentUser): ?>
              <p class="mb-2"><a class="btn btn-primary w-100" href="<?php echo $base; ?>/packages.php">
                <i class="fas fa-shopping-cart me-2"></i>Order Data
              </a></p>
              <p class="mb-2"><a class="btn btn-outline-primary w-100" href="<?php echo $base; ?>/my_orders.php">
                <i class="fas fa-list me-2"></i>My Orders
              </a></p>
              <p class="mb-0"><a class="btn btn-outline-secondary w-100" href="<?php echo $base; ?>/logout.php">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
              </a></p>
            <?php else: ?>
              <p class="mb-3"><a class="btn btn-primary w-100" href="<?php echo $base; ?>/login.php">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Order
              </a></p>
              <p class="mb-2"><a class="btn btn-outline-primary w-100" href="<?php echo $base; ?>/register.php">
                <i class="fas fa-user-plus me-2"></i>Create Account
              </a></p>
              <p class="mb-0"><a class="btn btn-outline-secondary w-100" href="https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>">
                <i class="fab fa-whatsapp me-2"></i>Contact via WhatsApp
              </a></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </header>

<main class="container py-5">
  <section class="mb-5">
    <h2 class="section-title">Why Choose Us?</h2>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="text-center text-md-start">
          <div class="feature-icon mx-auto mx-md-0">
            <i class="fas fa-bolt"></i>
          </div>
          <h5 class="mb-2">Fast Activation</h5>
          <p class="text-muted mb-0">Orders processed quickly after confirmation. Get connected in minutes, not hours.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="text-center text-md-start">
          <div class="feature-icon mx-auto mx-md-0">
            <i class="fas fa-lock"></i>
          </div>
          <h5 class="mb-2">Secure Payments</h5>
          <p class="text-muted mb-0">We only store proof images for verification — no payment credentials are stored.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="text-center text-md-start">
          <div class="feature-icon mx-auto mx-md-0">
            <i class="fas fa-headset"></i>
          </div>
          <h5 class="mb-2">24/7 Support</h5>
          <p class="text-muted mb-0">Round-the-clock customer support via WhatsApp to address any issues.</p>
        </div>
      </div>
    </div>
  </section>

  <div class="row">
    <div class="col-lg-8 mb-5 mb-lg-0">
      <h2 class="section-title">Our Commitment</h2>
      <p class="lead">At Lupyana Tech, we're committed to providing reliable internet connectivity with transparent pricing and exceptional customer service.</p>
      <p>We understand the importance of staying connected in today's digital world. That's why we've streamlined our process to make purchasing data packages as simple and efficient as possible.</p>
      <div class="row mt-4">
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Instant activation</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Competitive pricing</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Multiple network support</li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Secure transactions</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Detailed usage statistics</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> No hidden fees</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="support-card">
        <h5 class="mb-3"><i class="fas fa-life-ring me-2"></i>Support</h5>
        <p class="text-muted">Have questions or need assistance? Our support team is here to help.</p>
        <a href="https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>" class="btn btn-success w-100">
          <i class="fab fa-whatsapp me-2"></i>WhatsApp Support
        </a>
        <hr>
        <p class="small text-muted mb-0">Typically replies within minutes</p>
      </div>
    </div>
  </div>
</main>

<footer class="bg-white py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 mb-3 mb-md-0">
        <div class="d-flex align-items-center">
          <i class="fas fa-wifi fa-2x text-primary me-2"></i>
          <h4 class="mb-0">Lupyana Tech</h4>
        </div>
        <p class="text-muted small mt-2 mb-0">&copy; <?php echo date('Y'); ?> All rights reserved</p>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="<?php echo $base; ?>/packages.php" class="btn btn-primary me-2">
          <i class="fas fa-wifi me-2"></i>View Packages
        </a>
        <a href="<?php echo $base; ?>/contact.php" class="btn btn-outline-primary">
          <i class="fas fa-envelope me-2"></i>Contact Us
        </a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>