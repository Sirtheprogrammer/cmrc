<?php
// public/index.php - packages listing
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../helpers/csrf.php';
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');
$pdo = $GLOBALS['pdo'] ?? $pdo; // in case connection returned pdo

// fetch packages
$stmt = $pdo->query('SELECT * FROM packages ORDER BY price ASC');
$packages = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lupyana Tech — Internet Data Packages</title>
    <meta name="description" content="Affordable internet data packages. Pay with mobile money, upload payment screenshot and confirm via WhatsApp.">
    <link rel="icon" href="<?php echo $base; ?>/public/assets/favicon.png" type="image/png">
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
        
        .quick-order-form {
            background: white;
            border-radius: var(--rounded-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
        }
        
        .quick-order-form h5 {
            color: var(--dark);
            margin-bottom: 1.25rem;
            font-weight: 700;
        }
        
        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .form-control {
            border-radius: var(--rounded);
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-light);
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
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
        
        .card-package {
            border-radius: var(--rounded-lg);
            border: 1px solid var(--gray-light);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }
        
        .card-package:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .package-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
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
        
        .testimonial {
            background: white;
            border-radius: var(--rounded-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
        }
        
        .step-card {
            background: white;
            border-radius: var(--rounded-lg);
            padding: 1.5rem;
            height: 100%;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }
        
        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .step-number {
            width: 36px;
            height: 36px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .accordion-button {
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: var(--rounded) !important;
        }
        
        .accordion-button:not(.collapsed) {
            background: var(--primary);
            color: white;
        }
        
        .accordion-button:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            border-color: var(--primary);
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
            
            .quick-order-form {
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
    <a class="navbar-brand" href="#">
        <i class="fas fa-wifi me-2"></i>LUPYANA TECH
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="#packages">Packages</a></li>
        <li class="nav-item"><a class="nav-link" href="#how">How it works</a></li>
        <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
        <li class="nav-item ms-3"><a class="btn btn-primary" href="checkout.php?package_id=<?php echo isset($packages[0]) ? (int)$packages[0]['id'] : 1; ?>">Buy Now</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="hero">
  <div class="container hero-content">
    <div class="row align-items-center">
      <div class="col-lg-6 text-white">
        <h1>Fast & Affordable Internet Data for Everyone</h1>
        <p>Choose a package, pay via mobile money, upload your payment screenshot and get connected fast. Secure, reliable and backed by responsive support via WhatsApp.</p>
        <div class="mt-4">
          <a href="#packages" class="btn btn-light btn-lg me-2">
            <i class="fas fa-list me-2"></i>View Packages
          </a>
          <a href="https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>" class="btn btn-outline-light btn-lg">
            <i class="fab fa-whatsapp me-2"></i>Contact via WhatsApp
          </a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="quick-order-form">
          <h5><i class="fas fa-bolt me-2"></i>Quick Order</h5>
          <form action="submit_order.php" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="package_id" value="<?php echo isset($packages[0]) ? (int)$packages[0]['id'] : 0; ?>">
            <div class="mb-3">
              <label class="form-label">Phone (country code, no +)</label>
              <input class="form-control" name="phone" placeholder="2557xxxxxxxx" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Network</label>
              <input class="form-control" name="network" placeholder="Halotel, Vodacom...">
            </div>
            <div class="mb-3">
              <label class="form-label">Payment screenshot (jpg/png)</label>
              <input class="form-control" type="file" name="screenshot" accept="image/*" required>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary">
                <i class="fab fa-whatsapp me-2"></i>Submit & Confirm on WhatsApp
              </button>
            </div>
            <div class="mt-2 small text-muted">By submitting you will be redirected to WhatsApp to confirm.</div>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>

<main class="container my-5 py-4">
  <section id="features" class="mb-5 py-4">
    <div class="row g-4 align-items-center">
      <div class="col-lg-4">
        <div class="text-center text-lg-start">
          <div class="feature-icon mx-auto mx-lg-0">
            <i class="fas fa-bolt"></i>
          </div>
          <h5 class="mb-2">Fast Activation</h5>
          <p class="text-muted mb-0">Orders processed quickly after confirmation.</p>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="text-center text-lg-start">
          <div class="feature-icon mx-auto mx-lg-0">
            <i class="fas fa-lock"></i>
          </div>
          <h5 class="mb-2">Secure Payments</h5>
          <p class="text-muted mb-0">We only store proof images — no financial data is kept.</p>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="text-center text-lg-start">
          <div class="feature-icon mx-auto mx-lg-0">
            <i class="fab fa-whatsapp"></i>
          </div>
          <h5 class="mb-2">Support via WhatsApp</h5>
          <p class="text-muted mb-0">Quick confirmation and support through WhatsApp.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="packages" class="mb-5 py-4">
    <h2 class="section-title">Data Packages</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($packages as $pkg): ?>
        <div class="col">
          <div class="card h-100 card-package">
            <div class="card-body d-flex flex-column">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <h5 class="card-title mb-1"><?php echo htmlspecialchars($pkg['name']); ?></h5>
                  <div class="text-muted small"><?php echo htmlspecialchars($pkg['duration']); ?> • <?php echo htmlspecialchars($pkg['gb_amount']); ?> GB</div>
                </div>
                <div class="text-end">
                  <div class="package-price">Tsh <?php echo number_format($pkg['price'], 0); ?></div>
                </div>
              </div>

              <p class="card-text text-muted mb-4 small"><?php echo htmlspecialchars($pkg['description']); ?></p>

              <div class="mt-auto d-flex gap-2">
                <a href="checkout.php?package_id=<?php echo (int)$pkg['id']; ?>" class="btn btn-primary flex-fill">
                  <i class="fas fa-shopping-cart me-2"></i>Order Now
                </a>
                <a href="javascript:void(0)" class="btn btn-outline-secondary" onclick="copyLink(<?php echo (int)$pkg['id']; ?>)">
                  <i class="fas fa-share-alt"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="testimonials" class="mb-5 py-4">
    <h2 class="section-title">What Our Customers Say</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="testimonial">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
              <i class="fas fa-user text-white"></i>
            </div>
            <strong class="ms-2">Ally</strong>
          </div>
          <p class="text-muted mb-0">"Quick and affordable. Activation was instant after confirmation."</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
              <i class="fas fa-user text-white"></i>
            </div>
            <strong class="ms-2">Mariam</strong>
          </div>
          <p class="text-muted mb-0">"Excellent support via WhatsApp — very helpful team."</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
              <i class="fas fa-user text-white"></i>
            </div>
            <strong class="ms-2">James</strong>
          </div>
          <p class="text-muted mb-0">"Good value for money. Smooth ordering process."</p>
        </div>
      </div>
    </div>
  </section>

  <section id="how" class="mb-5 py-4">
    <h2 class="section-title">How It Works</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-number">1</div>
          <h5>Choose</h5>
          <p class="text-muted mb-0">Select a package that fits your needs.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-number">2</div>
          <h5>Pay</h5>
          <p class="text-muted mb-0">Pay using mobile money and take a screenshot.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-number">3</div>
          <h5>Upload & Confirm</h5>
          <p class="text-muted mb-0">Upload the screenshot and confirm via WhatsApp.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="faq" class="mb-5 py-4">
    <h2 class="section-title">Frequently Asked Questions</h2>
    <div class="accordion" id="faqAcc">
      <div class="accordion-item border-0 mb-3">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
            <i class="fas fa-question-circle me-2"></i>How long until activation?
          </button>
        </h2>
        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body text-muted">
            After payment confirmation, activation typically occurs within minutes.
          </div>
        </div>
      </div>
      <div class="accordion-item border-0">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
            <i class="fas fa-question-circle me-2"></i>Can I request custom GB?
          </button>
        </h2>
        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body text-muted">
            Some packages allow custom amounts. Use the custom field on checkout if enabled.
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="contact" class="mb-5 py-4">
    <h2 class="section-title">Contact & Support</h2>
    <div class="row">
      <div class="col-md-6 mb-4 mb-md-0">
        <p class="text-muted">For support or custom orders, contact us on WhatsApp or send an email.</p>
        <p>
          <a href="https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>" class="btn btn-success me-2">
            <i class="fab fa-whatsapp me-2"></i>WhatsApp Us
          </a>
          <a href="mailto:info@lupyanatech.example" class="btn btn-outline-secondary">
            <i class="fas fa-envelope me-2"></i>Email
          </a>
        </p>
      </div>
      <div class="col-md-6">
        <div class="p-4 bg-white border rounded">
          <h5 class="mb-3">Quick Message</h5>
          <form onsubmit="contactSubmit(event)">
            <div class="mb-3">
              <input class="form-control" id="contact_name" placeholder="Your name" required>
            </div>
            <div class="mb-3">
              <input class="form-control" id="contact_phone" placeholder="Phone" required>
            </div>
            <div class="mb-3">
              <textarea class="form-control" id="contact_msg" placeholder="Message" rows="3" required></textarea>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary">
                <i class="fab fa-whatsapp me-2"></i>Send via WhatsApp
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
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
        <a href="https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>" class="btn btn-outline-primary">
          <i class="fab fa-whatsapp me-2"></i>Contact Support
        </a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const BASE = '<?php echo addslashes($base); ?>';
  function copyLink(id) {
    const url = BASE + '/checkout.php?package_id=' + id;
    navigator.clipboard?.writeText(url).then(() => alert('Link copied to clipboard'));
  }

  function contactSubmit(e) {
    e.preventDefault();
    const name = document.getElementById('contact_name').value.trim();
    const phone = document.getElementById('contact_phone').value.trim();
    const msg = document.getElementById('contact_msg').value.trim();
    if (!name || !phone || !msg) return alert('Please fill all fields');
    const text = encodeURIComponent(`Hello, my name is ${name}. Phone: ${phone}. ${msg}`);
    window.open('https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>?text=' + text, '_blank');
  }
</script>
</body>
</html>