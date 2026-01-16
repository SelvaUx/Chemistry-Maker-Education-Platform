<?php
// public_html/google-login.php
require_once 'config/constants.php';
require_once 'config/db.php';

session_start();

// Mock Google Login Process
// In a real app, this would handle the OAuth redirect code from Google.

// Simulate network delay
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signing in with Google...</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
        }
        .loader {
            text-align: center;
        }
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border-left-color: #0984e3;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="loader">
        <div class="spinner"></div>
        <h3>Connecting to Google...</h3>
        <p style="color: #666; font-size: 0.9rem;">Please wait while we verify your credentials.</p>
    </div>

    <script>
        setTimeout(function() {
            // Mock Success
            <?php
            // Mock Data for "Google User"
            $_SESSION['user_id'] = 999; // Different ID to distinguish
            $_SESSION['user_name'] = "Google Student";
            $_SESSION['user_email'] = "google.user@example.com";
            ?>
            
            window.location.href = "dashboard.php";
        }, 1500);
    </script>
</body>
</html>
