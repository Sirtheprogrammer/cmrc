<?php
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About — Lupyana Tech</title>
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
    
    .hero-section {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(14, 165, 162, 0.05) 100%);
        border-radius: var(--rounded-lg);
        padding: 3rem 2rem;
        margin-bottom: 3rem;
    }
    
    .value-card {
        background: white;
        border-radius: var(--rounded-lg);
        padding: 2rem;
        height: 100%;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .value-icon {
        width: 70px;
        height: 70px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary);
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .team-card {
        background: white;
        border-radius: var(--rounded-lg);
        padding: 1.5rem;
        height: 100%;
        box-shadow: var(--shadow);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .team-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-weight: 600;
        color: var(--dark);
    }
    
    .timeline {
        position: relative;
        padding-left: 3rem;
        margin: 2rem 0;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gray-light);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: var(--primary);
        border: 3px solid white;
        box-shadow: 0 0 0 2px var(--primary);
    }
    
    .timeline-date {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }
    
    footer {
        background: white;
        padding: 2rem 0;
        box-shadow: 0 -1px 3px rgba(0,0,0,0.05);
        margin-top: 3rem;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .hero-section {
            padding: 2rem 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .timeline {
            padding-left: 2rem;
        }
        
        .timeline-item::before {
            left: -1.5rem;
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
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?php echo $base; ?>/about.php">About</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-5">
  <div class="hero-section">
    <div class="row align-items-center">
      <div class="col-lg-8 mx-auto text-center">
        <h1 class="section-title">About Lupyana Tech</h1>
        <p class="lead">We are a dedicated team providing fast, affordable, and reliable internet data solutions across Tanzania. Our mission is to keep you connected with seamless service and instant support.</p>
      </div>
    </div>
  </div>

  <div class="row mb-5">
    <div class="col-lg-6">
      <h2 class="section-title">Our Story</h2>
      <p>Founded in 2020, Lupyana Tech began with a simple vision: to make internet connectivity accessible and affordable for everyone in Tanzania. What started as a small operation has grown into a trusted provider of data packages for individuals and businesses alike.</p>
      <p>We understand the importance of staying connected in today's digital world, and we've built our service around simplicity, reliability, and exceptional customer support.</p>
    </div>
    <div class="col-lg-6">
      <div class="bg-primary rounded-lg p-4 text-white">
        <h4 class="mb-3"><i class="fas fa-bullseye me-2"></i>Our Mission</h4>
        <p class="mb-0">To provide affordable, reliable internet connectivity with seamless customer experience through innovative solutions and personalized support.</p>
      </div>
    </div>
  </div>

  <div class="row mb-5">
    <div class="col-12">
      <h2 class="section-title text-center">Why Choose Us</h2>
    </div>
    <div class="col-md-4 mb-4">
      <div class="value-card">
        <div class="value-icon">
          <i class="fas fa-bolt"></i>
        </div>
        <h4>Instant Activation</h4>
        <p class="text-muted">Get connected within minutes of payment confirmation, not hours.</p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="value-card">
        <div class="value-icon">
          <i class="fas fa-headset"></i>
        </div>
        <h4>24/7 Support</h4>
        <p class="text-muted">Our team is always available via WhatsApp to assist you anytime.</p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="value-card">
        <div class="value-icon">
          <i class="fas fa-shield-alt"></i>
        </div>
        <h4>Secure Payments</h4>
        <p class="text-muted">We prioritize your security with safe payment processing.</p>
      </div>
    </div>
  </div>

  <div class="row mb-5">
    <div class="col-12">
      <h2 class="section-title">Our Journey</h2>
      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-date">2020</div>
          <h5>Company Founded</h5>
          <p>Lupyana Tech was established with a focus on providing affordable data solutions.</p>
        </div>
        <div class="timeline-item">
          <div class="timeline-date">2021</div>
          <h5>Expanded Network Coverage</h5>
          <p>Added support for multiple telecom networks across Tanzania.</p>
        </div>
        <div class="timeline-item">
          <div class="timeline-date">2022</div>
          <h5>Corporate Services Launched</h5>
          <p>Introduced customized data solutions for business clients.</p>
        </div>
        <div class="timeline-item">
          <div class="timeline-date">2023</div>
          <h5>Enhanced Platform</h5>
          <p>Launched improved ordering system with faster processing times.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-5">
    <div class="col-12">
      <h2 class="section-title text-center">By The Numbers</h2>
    </div>
    <div class="col-md-3 col-6">
      <div class="stat-item">
        <div class="stat-number">5,000+</div>
        <div class="stat-label">Customers Served</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="stat-item">
        <div class="stat-number">24/7</div>
        <div class="stat-label">Support Availability</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="stat-item">
        <div class="stat-number">4</div>
        <div class="stat-label">Network Partners</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="stat-item">
        <div class="stat-number">98%</div>
        <div class="stat-label">Satisfaction Rate</div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8 mx-auto">
      <div class="bg-light rounded-lg p-5 text-center">
        <h3 class="mb-3">Ready to get connected?</h3>
        <p class="mb-4">Join thousands of satisfied customers who trust Lupyana Tech for their internet needs.</p>
        <a href="<?php echo $base; ?>/packages.php" class="btn btn-primary btn-lg me-3">View Packages</a>
        <a href="<?php echo $base; ?>/contact.php" class="btn btn-outline-primary btn-lg">Contact Us</a>
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