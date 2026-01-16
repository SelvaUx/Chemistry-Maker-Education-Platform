<?php
// public_html/signup.php
require_once '../config/constants.php';
require_once '../config/db.php';

$pageTitle = "Sign Up";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $error = "Email already registered. Please login.";
            } else {
                // Insert new user
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
                
                if ($stmt->execute([$full_name, $email, $password_hash])) {
                    // Auto login
                    $user_id = $pdo->lastInsertId();
                    session_start();
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $full_name;
                    
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container section-padding" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 500px; padding: 40px; background: var(--white); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
        <h2 class="text-center" style="margin-bottom: 30px;">Create Account</h2>
        
        <?php if($error): ?>
            <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Full Name</label>
                <input type="text" name="full_name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--radius-sm);">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--radius-sm);">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--radius-sm);">
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Confirm Password</label>
                <input type="password" name="confirm_password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--radius-sm);">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
        </form>
        
        <p class="text-center" style="margin-top: 20px; font-size: 0.9rem;">
            Already have an account? <a href="login.php" class="text-primary" style="font-weight: 600;">Login</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
