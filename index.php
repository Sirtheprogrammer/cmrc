<?php
// Home page loader for Lupyana Tech
// Visit: http://localhost/lupyanatech/
declare(strict_types=1);
// Enable full error reporting in development. In production, adjust error display appropriately.
error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    $root = __DIR__;

    // Load application config if present
    if (file_exists($root . '/config.php')) {
        require_once $root . '/config.php';
    }

    // Prefer serving the public/home.php file if it exists
    $home = $root . '/public/home.php';
    if (file_exists($home)) {
        // Let the included file output HTML (it should set headers itself if needed)
        include $home;
        exit;
    }

    // Fallback: simple home page
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>Lupyana Tech — Home</title>';
    echo '<style>body{font-family:Arial,Helvetica,sans-serif;line-height:1.6;padding:2rem;color:#222}header{margin-bottom:1.5rem}a{color:#0366d6}</style>';
    echo '</head>';
    echo '<body>';
    echo '<header><h1>Lupyana Tech</h1><p>Welcome to the Lupyana Tech demo site.</p></header>';
    echo '<main>';
    echo '<p>The site is running. To customize this page, edit <code>public/home.php</code> or place your frontend files in the <code>public/</code> directory.</p>';
    echo '<p>Useful pages:</p>';
    echo '<ul>';
    echo '<li><a href="/lupyanatech/login.php">Login</a></li>';
    echo '<li><a href="/lupyanatech/register.php">Register</a></li>';
    echo '<li><a href="/lupyanatech/services.php">Services</a></li>';
    echo '<li><a href="/lupyanatech/contact.php">Contact</a></li>';
    echo '</ul>';
    echo '</main>';
    echo '<footer style="margin-top:2rem;color:#666;font-size:.9rem">&copy; ' . date('Y') . ' Lupyana Tech</footer>';
    echo '</body>';
    echo '</html>';

} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo "Internal Server Error\n";
    echo "Error: " . $e->getMessage();
}
