<?php
// public/packages.php - standalone packages page (reuses package listing code)
$pdo = require __DIR__ . '/../db/connection.php';
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');
$packages = $pdo->query('SELECT * FROM packages ORDER BY price ASC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Packages — Lupyana Tech</title>
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
    
    .btn-sm {
        padding: 0.4rem 1rem;
        font-size: 0.875rem;
    }
    
    .package-card {
        background: white;
        border-radius: var(--rounded-lg);
        padding: 1.5rem;
        height: 100%;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        border: 1px solid var(--gray-light);
        position: relative;
        overflow: hidden;
    }
    
    .package-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }
    
    .package-card.popular::before {
        content: 'Most Popular';
        position: absolute;
        top: 0;
        right: 0;
        background: var(--primary);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-bottom-left-radius: var(--rounded);
    }
    
    .package-header {
        border-bottom: 1px solid var(--gray-light);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    
    .package-price {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--primary);
        margin: 0.5rem 0;
    }
    
    .package-features {
        margin: 1.5rem 0;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .feature-icon {
        color: var(--success);
        margin-right: 0.75rem;
        font-size: 0.875rem;
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
    
    .filter-container {
        background: white;
        border-radius: var(--rounded-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
    }
    
    .filter-btn {
        border-radius: 50px;
        padding: 0.4rem 1.25rem;
        font-size: 0.875rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    footer {
        background: white;
        padding: 2rem 0;
        box-shadow: 0 -1px 3px rgba(0,0,0,0.05);
        margin-top: 3rem;
    }
    
    /* Mobile responsiveness */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        animation: fadeIn 0.3s ease-in-out forwards;
    }

    .network-badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--primary);
        color: white;
    }

    @media (max-width: 768px) {
        .package-card {
            padding: 1.25rem;
        }
        
        .package-price {
            font-size: 1.5rem;
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
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?php echo $base; ?>/packages.php">Packages</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-5">
  <div class="row mb-5">
    <div class="col-lg-8 mx-auto text-center">
      <h1 class="section-title">Our Data Packages</h1>
      <p class="lead">Choose from our affordable and reliable internet data packages. All packages include instant activation after payment confirmation.</p>
    </div>
  </div>

  <div class="filter-container">
    <h6 class="mb-3">Filter by:</h6>
    <div class="d-flex flex-wrap">
      <button class="btn btn-primary filter-btn">All Networks</button>
      <button class="btn btn-outline-primary filter-btn">Vodacom</button>
      <button class="btn btn-outline-primary filter-btn">Halotel</button>
      <button class="btn btn-outline-primary filter-btn">Airtel</button>
      <button class="btn btn-outline-primary filter-btn">Tigo</button>
    </div>
  </div>

  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach ($packages as $index => $pkg): ?>
      <div class="col">
        <div class="package-card <?php echo $index === 1 ? 'popular' : ''; ?>">
          <div class="package-header">
            <h5 class="card-title mb-2"><?php echo htmlspecialchars($pkg['name']); ?></h5>
            <div class="package-price">Tsh <?php echo number_format($pkg['price'],0); ?></div>
            <div class="text-muted small">
              <?php echo htmlspecialchars($pkg['duration']); ?> • 
              <?php echo htmlspecialchars($pkg['gb_amount']); ?> GB • 
              <span class="network-badge"><?php echo ucfirst(htmlspecialchars($pkg['network'])); ?></span>
            </div>
          </div>
          
          <p class="text-muted small"><?php echo htmlspecialchars($pkg['description']); ?></p>
          
          <div class="package-features">
            <div class="feature-item">
              <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
              <div class="small">Instant activation</div>
            </div>
            <div class="feature-item">
              <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
              <div class="small">24/7 support</div>
            </div>
            <div class="feature-item">
              <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
              <div class="small">Unlimited speed</div>
            </div>
          </div>
          
          <div class="mt-auto pt-3">
            <a href="<?php echo $base; ?>/checkout.php?package_id=<?php echo (int)$pkg['id']; ?>" class="btn btn-primary w-100">
              <i class="fas fa-shopping-cart me-2"></i>Order Now
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row mt-5">
    <div class="col-lg-8 mx-auto">
      <div class="alert alert-primary rounded-lg">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <i class="fas fa-info-circle fa-2x"></i>
          </div>
          <div>
            <h6 class="mb-1">Need a custom package?</h6>
            <p class="mb-0 small">Contact us for custom data solutions tailored to your specific needs.</p>
          </div>
          <div class="ms-auto">
            <a href="<?php echo $base; ?>/contact.php" class="btn btn-sm btn-outline-primary">Contact Us</a>
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
<script>
  // Network filter functionality
  document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const packageCards = document.querySelectorAll('.package-card');
    
    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        // Remove active class from all buttons
        filterButtons.forEach(btn => {
          btn.classList.remove('btn-primary');
          btn.classList.add('btn-outline-primary');
        });
        
        // Add active class to clicked button
        this.classList.remove('btn-outline-primary');
        this.classList.add('btn-primary');
        
        const selectedNetwork = this.textContent.trim().toLowerCase();
        
        // Show/hide packages based on network
        packageCards.forEach(card => {
          const parent = card.closest('.col');
          const networkBadge = card.querySelector('.network-badge');
          const cardNetwork = networkBadge ? networkBadge.textContent.trim().toLowerCase() : '';
          
          if (selectedNetwork === 'all networks' || cardNetwork === selectedNetwork) {
            parent.style.display = '';
            parent.classList.add('fade-in');
          } else {
            parent.style.display = 'none';
            parent.classList.remove('fade-in');
          }
        });
      });
    });
  });
</script>
</body>
</html>