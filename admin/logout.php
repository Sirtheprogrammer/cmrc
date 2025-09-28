<?php
require_once __DIR__ . '/../helpers/auth.php';

// Clear all session data
session_start();
$_SESSION = array();
session_destroy();

// Redirect to login
header('Location: login.php');
exit;