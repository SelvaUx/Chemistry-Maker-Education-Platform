<?php
// public_html/profile.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php';

$pageTitle = "My Profile";
$success = "";
$error = "";
$user_id = $_SESSION['user_id'];

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];
    
    if (empty($full_name)) {
        $error = "Name cannot be empty.";
    } else {
        try {
            if (!empty($password)) {
                // Update with password
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET full_name = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$full_name, $hash, $user_id]);
            } else {
                // Update name only
                $stmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");
                $stmt->execute([$full_name, $user_id]);
            }
            $success = "Profile updated successfully.";
            $_SESSION['user_name'] = $full_name; // Update session
        } catch (PDOException $e) {
            $error = "Update failed.";
        }
    }
}

// Fetch current data
$stmt = $pdo->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

require_once 'includes/header.php';
?>

<div class="container section-padding">
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="margin-bottom: 30px;">Profile Settings</h1>
        
        <?php if($success): ?>
            <div style="background: #e1ffe1; color: #00b894; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div style="background: var(--white); padding: 30px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
            <form method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email Address</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="width: 100%; padding: 12px; border: 1px solid #eee; background: #f9f9f9; border-radius: 5px; color: #888;">
                    <small style="color: #aaa;">Email cannot be changed.</small>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                
                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">New Password (Optional)</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
