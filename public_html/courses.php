<?php
// public_html/courses.php
require_once '../config/constants.php';
require_once '../config/db.php';

$pageTitle = "All Courses";
require_once 'includes/header.php';

// Fetch all published courses
// Basic Search Logic (PHP Filter for Mock Compatibility)
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE status = 'published' ORDER BY created_at DESC");
    $stmt->execute();
    $all_courses = $stmt->fetchAll();
    
    // Filter in PHP (Robust for Mock & real DB)
    if ($search_query) {
        $courses = array_filter($all_courses, function($c) use ($search_query) {
            return stripos($c['title'], $search_query) !== false || stripos($c['description'], $search_query) !== false;
        });
    } else {
        $courses = $all_courses;
    }
} catch (PDOException $e) {
    $courses = [];
}
?>

<div class="container section-padding" style="min-height: 60vh;">
    
    <div class="text-center" style="margin-bottom: 50px;">
        <h1>Our Courses</h1>
        
        <!-- Search Bar -->
        <form method="GET" action="" style="max-width: 500px; margin: 20px auto 0; position: relative;">
            <input type="text" name="q" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search for courses..." 
                   class="input" style="width: 100%; padding: 15px 20px; padding-right: 50px; border-radius: 50px; border: 1px solid #ddd; outline: none; font-size: 1rem; box-shadow: var(--shadow-sm);">
            <button type="submit" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: var(--primary); color: #fff; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer;">
                <i class="fa-solid fa-search"></i>
            </button>
        </form>
    </div>
    
    <?php if(empty($courses)): ?>
        <div class="text-center" style="padding: 60px; background: var(--white); border-radius: var(--radius-md);">
            <i class="fa-solid fa-box-open fa-3x" style="color: #ccc; margin-bottom: 20px;"></i>
            <h3>No courses found</h3>
            <p style="color: var(--text-light);">We are currently updating our course catalog. Please check back later.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php foreach($courses as $course): ?>
            <div class="course-card" style="background: var(--white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform 0.3s ease; display: flex; flex-direction: column;">
                <div style="height: 200px; background: #ddd; position: relative;">
                     <?php if($course['thumbnail']): ?>
                        <img src="<?php echo BASE_URL . 'uploads/thumbnails/' . $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                     <?php else: ?>
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #eee; color: #aaa;">No Image</div>
                     <?php endif; ?>
                </div>
                <div style="padding: 25px; flex-grow: 1; display: flex; flex-direction: column;">
                    <h3 style="font-size: 1.25rem; margin-bottom: 10px;">
                        <a href="course.php?id=<?php echo $course['id']; ?>" style="text-decoration: none; color: inherit;"><?php echo htmlspecialchars($course['title']); ?></a>
                    </h3>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 20px; line-height: 1.5;">
                         <?php echo htmlspecialchars(substr($course['description'], 0, 100)) . (strlen($course['description']) > 100 ? '...' : ''); ?>
                    </p>
                    <div class="flex justify-between align-center" style="margin-top: auto;">
                        <span style="font-weight: 700; color: var(--primary); font-size: 1.2rem;">₹<?php echo number_format($course['price'], 2); ?></span>
                        <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary" style="padding: 8px 20px;">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
