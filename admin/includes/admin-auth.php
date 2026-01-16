<?php
// admin/includes/admin-auth.php
// Include this file at the top of any admin page

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Adjust valid session key for admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
