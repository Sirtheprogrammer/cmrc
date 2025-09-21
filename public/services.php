<?php
// public/services.php - services page
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Services — Lupyana Tech</title>
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
    
    .nav-link:hover, .nav-link.active {
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
    
    .service-card {
        background: white;
        border-radius: var(--rounded-lg);
        padding: 2rem;
        height: 100%;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        border-top: 4px solid var(--primary);
    }
    
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--rounded);
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary);
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        position: relative;
        padding-bottom: 0.75rem;
        margin-bottom: 2rem;
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
    
    .benefit-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .benefit-icon {
        color: var(--success);
        font-size: 1.25rem;
        margin-right: 0.75rem;
        margin-top: 0.2rem;
    }
    
    .cta-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 4rem 0;
        border-radius: var(--rounded-lg);
    }
    
    footer {
        background: white;
        padding: 2rem 0;
        box-shadow: 0 -1px 3px rgba(0,0,0,0.05);
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .service-card {
            padding: 1.5rem;
        }
        
        .cta-section {
            padding: 2.5rem 0;
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
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/packages.php">Packages</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?php echo $base; ?>/services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-5">
  <div class="row justify-content-center mb-5">
    <div class="col-lg-8 text-center">
      <h1 class="section-title">Our Services</h1>
      <p class="lead">We provide reliable internet data packages and bespoke connectivity solutions for individuals and businesses across Tanzania.</p>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-sim-card"></i>
        </div>
        <h3>Data Packages</h3>
        <p class="text-muted">Prepaid internet bundles for all major networks with instant activation after payment confirmation.</p>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>Instant activation</div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>Competitive pricing</div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>All major networks supported</div>
        </div>
        <a href="<?php echo $base; ?>/packages.php" class="btn btn-primary mt-3">View Packages</a>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-building"></i>
        </div>
        <h3>Corporate Solutions</h3>
        <p class="text-muted">Customized data plans for businesses with volume discounts and dedicated account management.</p>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>Volume discounts</div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>Dedicated support</div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>Custom billing options</div>
        </div>
        <a href="<?php echo $base; ?>/contact.php" class="btn btn-primary mt-3">Contact Sales</a>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-cogs"></i>
        </div>
        <h3>Custom Setups</h3>
        <p class="text-muted">Tailored connectivity solutions including custom data plans, network setup, and ongoing support.</p>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>Custom data plans</div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>Technical support</div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
          <div>24/7 monitoring</div>
        </div>
        <a href="<?php echo $base; ?>/contact.php" class="btn btn-primary mt-3">Get Quote</a>
      </div>
    </div>
  </div>

  <div class="row mb-5">
    <div class="col-lg-10 mx-auto">
      <div class="cta-section text-center">
        <h2 class="mb-3">Need a Custom Solution?</h2>
        <p class="mb-4">We specialize in creating tailored internet solutions for unique requirements.</p>
        <a href="<?php echo $base; ?>/contact.php" class="btn btn-light btn-lg">
          <i class="fas fa-envelope me-2"></i>Get in Touch
        </a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8 mx-auto">
      <h2 class="section-title text-center">How Our Service Works</h2>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="d-flex">
            <div class="me-4">
              <div class="service-icon" style="width: 50px; height: 50px; font-size: 1.25rem;">
                1
              </div>
            </div>
            <div>
              <h4>Select Your Service</h4>
              <p class="text-muted">Choose from our standard packages or request a custom solution for your needs.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="d-flex">
            <div class="me-4">
              <div class="service-icon" style="width: 50px; height: 50px; font-size: 1.25rem;">
                2
              </div>
            </div>
            <div>
              <h4>Make Payment</h4>
              <p class="text-muted">Pay securely via mobile money and send us your payment confirmation.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="d-flex">
            <div class="me-4">
              <div class="service-icon" style="width: 50px; height: 50px; font-size: 1.25rem;">
                3
              </div>
            </div>
            <div>
              <h4>Confirmation</h4>
              <p class="text-muted">Our team verifies your payment and confirms via WhatsApp.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="d-flex">
            <div class="me-4">
              <div class="service-icon" style="width: 50px; height: 50px; font-size: 1.25rem;">
                4
              </div>
            </div>
            <div>
              <h4>Activation</h4>
              <p class="text-muted">Your service is activated immediately after confirmation.</p>
            </div>
          </div>
        </div>
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
        <a href="https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>" class="btn btn-success me-2">
          <i class="fab fa-whatsapp me-2"></i>WhatsApp Support
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