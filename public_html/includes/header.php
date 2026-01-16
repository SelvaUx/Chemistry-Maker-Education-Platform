<?php
// public_html/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// define root if not defined to avoid path errors in includes
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <?php if (isset($extraCss)) echo '<link rel="stylesheet" href="'.$extraCss.'">'; ?>
</head>
<body>

<header class="main-header">
    <div class="container">
        <a href="<?php echo BASE_URL; ?>index.php" class="logo">
            <i class="fa-solid fa-flask"></i> Chemistry Maker
        </a>
        
        <nav>
            <ul class="nav-menu" id="nav-menu">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>index.php" class="nav-link">Home</a></li>
                <?php endif; ?>
                <li><a href="<?php echo BASE_URL; ?>courses.php" class="nav-link">Courses</a></li>
                <li><a href="<?php echo BASE_URL; ?>quizzes.php" class="nav-link">Test Series</a></li>
                <li><a href="<?php echo BASE_URL; ?>contact.php" class="nav-link">Contact</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>dashboard.php" class="btn btn-primary" style="padding: 8px 20px;">Dashboard</a></li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>includes/logout.php" class="nav-link" style="font-size: 0.9rem;">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="desktop-only" style="margin-left: 10px;">
                        <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-secondary" style="padding: 8px 20px; border: 1px solid var(--primary);">Login</a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>signup.php" class="btn btn-primary" style="padding: 10px 25px; font-size: 0.9rem;">Get Started Free</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div class="header-actions" style="display: flex; align-items: center; gap: 15px;">
            <!-- Theme Toggle -->
            <button id="theme-toggle" class="btn btn-secondary" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0;">
                <i class="fa-solid fa-moon"></i>
            </button>
            
            <div class="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </div>
</header>

<script>
    // Theme Switcher Logic
    const toggleBtn = document.getElementById('theme-toggle');
    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
    
    // Check local storage or system preference
    const currentTheme = localStorage.getItem("theme");
    if (currentTheme == "dark") {
        document.body.setAttribute("data-theme", "dark");
        toggleBtn.innerHTML = '<i class="fa-solid fa-sun"></i>';
    } else if (currentTheme == "light") {
        document.body.setAttribute("data-theme", "light");
        toggleBtn.innerHTML = '<i class="fa-solid fa-moon"></i>';
    }

    toggleBtn.addEventListener("click", function() {
        let theme = "light";
        if (document.body.getAttribute("data-theme") != "dark") {
            document.body.setAttribute("data-theme", "dark");
            theme = "dark";
            this.innerHTML = '<i class="fa-solid fa-sun"></i>';
        } else {
            document.body.setAttribute("data-theme", "light");
            theme = "light";
            this.innerHTML = '<i class="fa-solid fa-moon"></i>';
        }
        localStorage.setItem("theme", theme);
    });
</script>
