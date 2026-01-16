<?php
// admin/login.php
require_once '../config/constants.php';
require_once '../config/db.php';

session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "Please enter username and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid credentials.";
            }
        } catch (PDOException $e) {
            $error = "Database Error.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../public_html/assets/css/style.css">
    <style>
        body {
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
    </style>
</head>
<body>

<div style="width: 100%; max-width: 400px; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: var(--dark);">Admin Portal</h2>
        <p style="color: var(--text-light);">Restricted Access</p>
    </div>
    
    <?php if($error): ?>
        <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div style="margin-bottom: 20px;">
            <input type="text" name="username" placeholder="Username" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        
        <div style="margin-bottom: 30px;">
            <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Login to Dashboard</button>
    </form>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="../public_html/index.php" style="font-size: 0.9rem; color: #777;">&larr; Back to Website</a>
    </div>
</div>

</body>
</html>
