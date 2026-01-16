<?php
// public_html/includes/user-session.php

function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

function getCurrentUser($pdo) {
    if (!isLoggedIn()) return null;
    
    try {
        $stmt = $pdo->prepare("SELECT id, full_name, email, created_at FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}
?>
