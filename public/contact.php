<?php
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact — Lupyana Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $base; ?>">LUPYANA TECH</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/packages.php">Packages</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?php echo $base; ?>/contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  <h1>Contact</h1>
  <p>WhatsApp: <a href="https://wa.me/<?php echo htmlspecialchars($config['owner']['whatsapp']); ?>"><?php echo htmlspecialchars($config['owner']['whatsapp']); ?></a></p>
  <p>Email: info@lupyanatech.example</p>
</main>

<footer class="bg-white border-top py-4">
  <div class="container text-center">&copy; <?php echo date('Y'); ?> Lupyana Tech</div>
</footer>
</body>
</html>
