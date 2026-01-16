<?php
// admin/includes/admin-header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// define root if not defined
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}
require_once __DIR__ . '/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        /* Admin Reset */
        body { padding-top: 0 !important; background-color: #f0f2f5; }
        
        .admin-layout { display: flex; min-height: 100vh; width: 100%; overflow-x: hidden; }
        .admin-sidebar { 
            width: 250px; 
            background: #2d3436; 
            color: #b2bec3; 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0; 
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .admin-nav { flex-grow: 1; padding: 20px 0; }
        .admin-nav a { display: block; padding: 12px 20px; color: inherit; text-decoration: none; border-left: 3px solid transparent; }
        .admin-nav a:hover, .admin-nav a.active { background: rgba(255,255,255,0.05); color: white; border-left-color: var(--primary); }
        .admin-nav i { margin-right: 10px; width: 20px; text-align: center; }
        
        .admin-content { flex-grow: 1; background: #f0f2f5; display: flex; flex-direction: column; width: 100%; }
        
        .admin-topbar { 
            height: 60px; 
            background: white; 
            border-bottom: 1px solid #ddd; 
            padding: 0 20px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            position: sticky; 
            top: 0; 
            z-index: 900;
        }
        
        .page-content { padding: 30px; overflow-y: auto; flex-grow: 1; }
        
        /* Mobile Styles */
        .mobile-menu-btn { display: none; font-size: 1.5rem; cursor: pointer; color: #333; }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                position: fixed;
                left: -250px;
                top: 0;
                bottom: 0;
                height: 100vh;
                box-shadow: 5px 0 15px rgba(0,0,0,0.2);
            }
            .admin-sidebar.active { left: 0; }
            .mobile-menu-btn { display: block; margin-right: 15px; }
            .page-content { padding: 15px; }
            
            /* Overlay when sidebar open */
            .sidebar-overlay {
                display: none;
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5); z-index: 999;
            }
            .sidebar-overlay.active { display: block; }
        }
        
        .stat-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table-container { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; color: #555; }
    </style>
</head>
<body>

<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<div class="admin-layout">
    <div class="admin-sidebar" id="adminSidebar">
        <div class="brand" style="padding: 20px; font-size: 1.2rem; background: rgba(0,0,0,0.2); color: white; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
            <span>Admin Panel</span>
            <i class="fa-solid fa-times mobile-menu-btn" onclick="toggleSidebar()" style="color: white; font-size: 1.2rem;"></i>
        </div>
        <nav class="admin-nav">
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            <a href="courses.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' || basename($_SERVER['PHP_SELF']) == 'add-course.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-book"></i> Courses
            </a>
            <a href="quizzes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'quizzes.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-list-check"></i> Test Series
            </a>
            <a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Students
            </a>
            <a href="payments.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-money-bill"></i> Payments
            </a>
             <a href="settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gear"></i> Settings
            </a>
            <a href="<?php echo BASE_URL; ?>admin/logout.php" style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05);">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </div>
    
    <div class="admin-content">
        <div class="admin-topbar">
            <div style="display: flex; align-items: center;">
                <i class="fa-solid fa-bars mobile-menu-btn" onclick="toggleSidebar()"></i>
                <?php if(basename($_SERVER['PHP_SELF']) == 'dashboard.php'): ?>
                    <h3 style="margin: 0; font-size: 1.2rem; display: none;" class="desktop-only">Dashboard</h3>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
                 <a href="../index.php" target="_blank" class="btn btn-secondary" style="padding: 5px 15px; font-size: 0.85rem;"><i class="fa-solid fa-earth-americas"></i> Visit Site</a>
                 <span>Welcome, <b><?php echo $_SESSION['admin_username'] ?? 'Admin'; ?></b></span>
            </div>
        </div>
        <div class="page-content">

<script>
    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('active');
        document.querySelector('.sidebar-overlay').classList.toggle('active');
    }
</script>
