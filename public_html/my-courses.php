<?php
// public_html/my-courses.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php';

$pageTitle = "My Courses";
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch purchased courses
try {
    $stmt = $pdo->prepare("
        SELECT c.*, p.purchase_date 
        FROM courses c 
        JOIN purchases p ON c.id = p.course_id 
        WHERE p.user_id = ? AND p.access_status = 'active'
        ORDER BY p.purchase_date DESC
    ");
    $stmt->execute([$user_id]);
    $my_courses = $stmt->fetchAll();
} catch (PDOException $e) {
    $my_courses = [];
}
?>

<div class="container section-padding">
    <h1 style="margin-bottom: 40px;">My Learning</h1>
    
    <?php if(empty($my_courses)): ?>
        <div class="text-center" style="padding: 60px; background: var(--white); border-radius: var(--radius-md);">
            <i class="fa-solid fa-folder-open fa-3x" style="color: #ccc; margin-bottom: 20px;"></i>
            <h3>You haven't enrolled in any courses yet.</h3>
            <p style="color: var(--text-light); margin-bottom: 30px;">Start your learning journey today!</p>
            <a href="courses.php" class="btn btn-primary">Browse Courses</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php foreach($my_courses as $course): ?>
            <div class="course-card" style="background: var(--white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform 0.3s ease;">
                <div style="height: 180px; background: #ddd; position: relative;">
                    <?php if($course['thumbnail']): ?>
                        <img src="<?php echo BASE_URL . 'uploads/thumbnails/' . $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #eee; color: #aaa;">No Image</div>
                    <?php endif; ?>
                </div>
                <div style="padding: 25px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px;"><?php echo htmlspecialchars($course['title']); ?></h3>
                    <div class="flex justify-between align-center" style="margin-top: 15px; pt-3; border-top: 1px solid #eee;">
                        <span style="font-size: 0.85rem; color: #888;">Progress: 100% (Demo)</span>
                            <a href="content.php?course_id=<?php echo $course['id']; ?>" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem;">Start Learning</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
