<?php
// public_html/dashboard.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php'; // Protect this page

$pageTitle = "Dashboard";
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch basic stats (number of courses owned)
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE user_id = ? AND access_status = 'active'");
    $stmt->execute([$user_id]);
    $items_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    $items_count = 0;
}
?>


<?php
// Fetch Announcements
try {
    $announcements = $pdo->prepare("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3")->fetchAll();
} catch (Exception $e) { $announcements = []; }
?>

<div class="container section-padding">
    <!-- Announcements Section -->
    <?php if(!empty($announcements)): ?>
    <div style="margin-bottom: 30px; background: var(--bg-card); border: 1px solid var(--primary); border-left: 5px solid var(--primary); padding: 20px; border-radius: 8px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px; color: var(--text-main);">
            <i class="fa-solid fa-bullhorn" style="color: var(--primary);"></i> Latest Announcements
        </h3>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <?php foreach($announcements as $ann): ?>
                <div style="padding-bottom: 10px; border-bottom: 1px dashed var(--glass-border);">
                    <strong style="color: var(--text-main);"><?php echo htmlspecialchars($ann['title']); ?></strong>
                    <span style="font-size: 0.8rem; color: var(--text-light); margin-left: 10px;">(<?php echo date('M j', strtotime($ann['created_at'])); ?>)</span>
                    <p style="margin: 5px 0 0; color: var(--text-light); font-size: 0.95rem;"><?php echo htmlspecialchars($ann['message']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex justify-between align-center" style="margin-bottom: 40px;">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
        <a href="courses.php" class="btn btn-primary">Browse Courses</a>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 50px;">
        <!-- Stat Card 1 -->
        <div style="background: var(--white); padding: 30px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(108, 92, 231, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-graduation-cap fa-xl text-primary"></i>
            </div>
            <div>
                <h3 style="margin-bottom: 5px;"><?php echo $items_count; ?></h3>
                <p style="color: var(--text-light);">Enrolled Courses</p>
            </div>
        </div>
        
        <!-- Stat Card 2 (Placeholder) -->
        <div style="background: var(--white); padding: 30px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(0, 210, 211, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-clock fa-xl" style="color: var(--secondary);"></i>
            </div>
            <div>
                <h3 style="margin-bottom: 5px;">0h</h3>
                <p style="color: var(--text-light);">Hours Leaned</p>
            </div>
        </div>
    </div>
    
    <h3 style="margin-bottom: 30px;">Quick Actions</h3>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <a href="my-courses.php" class="btn btn-secondary" style="background: var(--white); border: 1px solid #eee;">
            <i class="fa-solid fa-book-open" style="margin-right: 10px;"></i> My Learning
        </a>
        <a href="profile.php" class="btn btn-secondary" style="background: var(--white); border: 1px solid #eee;">
            <i class="fa-solid fa-user" style="margin-right: 10px;"></i> Edit Profile
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
