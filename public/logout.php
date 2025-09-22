<?php
require_once __DIR__ . '/../helpers/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
user_logout();
$config = require __DIR__ . '/../config.php';
$base = rtrim($config['app']['base_url'], '/');
header('Location: ' . $base);
exit;
