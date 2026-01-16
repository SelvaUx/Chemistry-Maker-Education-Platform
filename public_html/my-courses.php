<?php
// public_html/my-courses.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php';

$pageTitle = "My Learning Dashboard";
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

// Mock Stats (In real app, calculate from DB)
$stats = [
    'enrolled' => count($my_courses),
    'hours' => 12, // Mock learning hours
];
?>

<style>
    /* Dashboard Specific Styles */
    .dashboard-header {
        margin-bottom: 40px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background: var(--white);
        padding: 20px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    
    .stat-icon {
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }
    
    /* Course Card Redesign */
    .learning-card {
        background: var(--white);
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .learning-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .card-thumb {
        height: 160px;
        position: relative;
        background: #f0f2f5;
        overflow: hidden;
    }
    
    .fallback-thumb {
        width: 100%; height: 100%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex; align-items: center; justify-content: center;
        color: white;
        font-size: 3rem;
        opacity: 0.9;
    }
    
    .card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    /* Progress Bar */
    .progress-container {
        height: 6px;
        background: #e9ecef;
        border-radius: 10px;
        margin: 15px 0 10px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border-radius: 10px;
        width: 0%;
        transition: width 1s ease-in-out;
    }
    
    .next-lesson {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .demo-badge {
        position: absolute;
        top: 10px; right: 10px;
        background: rgba(255, 234, 167, 0.95);
        color: #d35400;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        backdrop-filter: blur(4px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

<div class="container section-padding">
    
    <!-- Header & Stats -->
    <div class="dashboard-header">
        <h1 style="margin-bottom: 10px;">My Learning Dashboard</h1>
        <p style="color: var(--text-light);">Track your progress and pick up where you left off.</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(108, 92, 231, 0.1); color: var(--primary);">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.5rem;"><?php echo $stats['enrolled']; ?></h3>
                <span style="font-size: 0.9rem; color: #888;">Enrolled Courses</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(0, 184, 148, 0.1); color: #00b894;">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.5rem;"><?php echo $stats['hours']; ?>h</h3>
                <span style="font-size: 0.9rem; color: #888;">Learning Time</span>
            </div>
        </div>
    </div>

    <?php if(empty($my_courses)): ?>
        <!-- Empty State -->
        <div class="text-center" style="padding: 80px 20px; background: var(--white); border-radius: var(--radius-md); border: 1px dashed #ddd;">
            <div style="width: 80px; height: 80px; background: #f0f2f5; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-layer-group" style="font-size: 2rem; color: #ccc;"></i>
            </div>
            <h3 style="margin-bottom: 10px; color: var(--text-main);">No Enrollments Yet</h3>
            <p style="color: var(--text-light); margin-bottom: 30px; max-width: 400px; margin-left: auto; margin-right: auto;">
                You haven't started any courses. Explore our catalog to find the perfect chemistry course for you.
            </p>
            <a href="courses.php" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Browse Courses</a>
        </div>
    <?php else: ?>
        <!-- Course Grid -->
        <h3 style="margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Continue Learning</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php foreach($my_courses as $index => $course): 
                // Mock Progress Logic
                $progress = ($index == 0) ? 65 : 10; // First course 65%, others 10%
                $is_demo = ($index > 1); // Sample logic for demo course
                
                $btn_text = ($progress == 0) ? "Start Learning" : "Continue Learning";
                $next_lesson = ($progress > 0) ? "Chapter 3: Thermodynamics II" : "Chapter 1: Introduction";
            ?>
            <div class="learning-card">
                <div class="card-thumb">
                    <?php if($is_demo): ?>
                        <div class="demo-badge"><i class="fa-solid fa-lock-open"></i> DEMO ACCESS</div>
                    <?php endif; ?>
                    
                    <?php if(!empty($course['thumbnail']) && file_exists('uploads/thumbnails/'.$course['thumbnail'])): ?>
                        <img src="<?php echo BASE_URL . 'uploads/thumbnails/' . $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <!-- Gradient Fallback -->
                        <div class="fallback-thumb" style="background: linear-gradient(135deg, <?php echo 'hsl('.((200 + $index * 40)%360).', 70%, 50%)'; ?>, <?php echo 'hsl('.((240 + $index * 40)%360).', 70%, 60%)'; ?>);">
                            <i class="fa-solid fa-flask"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-body">
                    <h4 style="margin-bottom: 5px; font-size: 1.1rem; flex-grow: 1;">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </h4>
                    
                    <!-- Progress Section -->
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #888; margin-top: 10px;">
                        <span>Progress</span>
                        <span style="font-weight: 600; color: var(--primary);"><?php echo $progress; ?>%</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-fill" style="width: <?php echo $progress; ?>%;"></div>
                    </div>
                    
                    <!-- Next Lesson Nudge -->
                    <div class="next-lesson">
                        <i class="fa-regular fa-circle-play" style="color: var(--secondary);"></i> 
                        Next: <?php echo $next_lesson; ?>
                    </div>
                    
                    <a href="content.php?course_id=<?php echo $course['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center; border-radius: 8px; padding: 12px;">
                        <?php echo $btn_text; ?> <i class="fa-solid fa-arrow-right" style="margin-left: 5px; font-size: 0.8rem;"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
