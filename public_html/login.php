<?php
// public_html/login.php
require_once '../config/constants.php';
require_once '../config/db.php';

$pageTitle = "Login";
$error = "";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, full_name, password_hash FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                
                // Redirect to intended page or dashboard
                if (isset($_GET['redirect'])) {
                    header("Location: " . urldecode($_GET['redirect']));
                } else {
                    header("Location: dashboard.php");
                }
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "Database Error.";
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container section-padding" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 450px; padding: 40px; background: var(--white); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
        <h2 class="text-center" style="margin-bottom: 30px;">Welcome Back</h2>
        
        <?php if($error): ?>
            <div style="background: rgba(214, 48, 49, 0.1); color: #e17055; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid rgba(214, 48, 49, 0.2);">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Demo Credentials Box -->
        <div style="background: rgba(2, 119, 189, 0.1); color: var(--primary); padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid rgba(2, 119, 189, 0.2);">
            <strong><i class="fa-solid fa-circle-info"></i> Demo Student Credentials:</strong><br>
            Email: student@example.com<br>
            Password: password
            <div style="margin-top:5px; font-size: 0.8rem;">
                <a href="admin/login.php" style="color: inherit; text-decoration: underline;">Admin Login Here</a>
            </div>
        </div>
        
        <form method="POST" action="">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--radius-sm);">
            </div>
            
            <div style="margin-bottom: 30px;">
                <div class="flex justify-between" style="margin-bottom: 8px;">
                    <label style="font-weight: 500;">Password</label>
                    <a href="#" style="font-size: 0.85rem; color: var(--text-light);">Forgot Password?</a>
                </div>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--radius-sm);">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        
        <div style="text-align: center; margin: 20px 0; color: #888; font-size: 0.9rem; position: relative;">
            <span style="background: #fff; padding: 0 10px; position: relative; z-index: 1;">OR</span>
            <div style="position: absolute; top: 50%; left: 0; width: 100%; height: 1px; background: #eee; z-index: 0;"></div>
        </div>
        
        <a href="google-login.php" class="btn" style="width: 100%; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; gap: 10px; background: #fff; color: #555;">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" style="width: 20px; height: 20px;">
            Sign in with Google
        </a>
        
        <p class="text-center" style="margin-top: 20px; font-size: 0.9rem;">
            Don't have an account? <a href="signup.php" class="text-primary" style="font-weight: 600;">Sign Up</a>
        </p>
        
        <div class="text-center" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <a href="admin/login.php" style="font-size: 0.85rem; color: #888; text-decoration: underline;">Admin Login</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
