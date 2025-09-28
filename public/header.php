<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Lupyana Tech') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f0f2f5; 
            font-family: Arial, sans-serif;
        }
        .form-container { 
            max-width: 500px; 
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            color: #0366d6 !important;
            font-size: 1.5rem;
        }
        .btn-primary {
            background-color: #0366d6;
            border-color: #0366d6;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .form-control {
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .form-control:focus {
            border-color: #0366d6;
            box-shadow: 0 0 0 2px rgba(3,102,214,0.2);
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .form-text {
            color: #666;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .alert {
            border-radius: 6px;
            padding: 1rem;
        }
        .nav-link {
            font-weight: 500;
            color: #333 !important;
        }
        .nav-link:hover {
            color: #0366d6 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/lupyanatech/">Lupyana Tech</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/lupyanatech/services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/lupyanatech/packages.php">Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/lupyanatech/contact.php">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/lupyanatech/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/lupyanatech/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/lupyanatech/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/lupyanatech/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
