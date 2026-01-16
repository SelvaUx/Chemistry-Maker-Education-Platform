<?php
// public_html/includes/auth-check.php
// Include this file at the top of any page that requires student login

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with return url
    $current_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: " . BASE_URL . "login.php?redirect=" . $current_url);
    exit;
}
?>
