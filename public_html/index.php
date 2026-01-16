<?php
// public_html/index.php
require_once '../config/constants.php';
require_once '../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$pageTitle = "Home";
require_once 'includes/header.php';

// Fetch 3 latest published courses
try {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $latest_courses = $stmt->fetchAll();
} catch (PDOException $e) {
    $latest_courses = [];
}
?>

<!-- Hero Section -->
<section class="hero-section" style="padding: 120px 0 80px; position: relative; overflow: hidden;">
    <!-- Abstract Background Blob -->
    <div style="position: absolute; top: -100px; right: -100px; width: 600px; height: 600px; background: linear-gradient(45deg, var(--primary), var(--secondary)); opacity: 0.1; border-radius: 50%; filter: blur(80px); z-index: -1;"></div>
    
    <div class="container">
        <div class="flex align-center justify-between" style="flex-wrap: wrap; gap: 40px;">
            <div style="flex: 1; min-width: 300px;">
                <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Master Chemistry <br><span class="text-primary">The Right Way</span></h1>
                <h3 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 15px; font-weight: 600;">Designed for JEE, NEET & Board Exam Success</h3>
                <p style="font-size: 1.1rem; color: var(--text-light); margin-bottom: 30px; max-width: 500px;">
                    Join thousands of students achieving top grades. Concept-focused HD lectures, detailed notes, and instant doubt resolution.
                </p>
                <div class="hero-btns" style="display: flex; gap: 15px;">
                    <a href="signup.php" class="btn btn-primary">Get Started Free</a>
                    <a href="login.php" class="btn btn-secondary" style="background: transparent; border: 2px solid var(--primary);">Login</a>
                </div>
                
                <div class="stats" style="margin-top: 50px; display: flex; gap: 40px;">
                    <div>
                        <h3 class="text-primary">1000+</h3>
                        <p style="font-size: 0.9rem;">Students</p>
                    </div>
                    <div>
                        <h3 class="text-primary">50+</h3>
                        <p style="font-size: 0.9rem;">Courses</p>
                    </div>
                     <div>
                        <h3 class="text-primary">4.9</h3>
                        <p style="font-size: 0.9rem;">Rating</p>
                    </div>
                </div>
            </div>
            
            <div style="flex: 1; min-width: 300px; display: flex; justify-content: center;">
                <img src="assets/hero-dashboard.png" alt="Chemistry Learning Dashboard" style="max-width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-padding" style="background: var(--white);">
    <div class="container">
        <div class="text-center" style="max-width: 700px; margin: 0 auto 60px;">
            <h2 style="margin-bottom: 15px;">Why Choose Us?</h2>
            <p style="color: var(--text-light);">We provide a complete ecosystem for learning chemistry effectively.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div class="feature-card" style="padding: 30px; background: var(--light-bg); border-radius: var(--radius-md); transition: transform 0.3s;">
                <div style="width: 60px; height: 60px; background: rgba(108, 92, 231, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fa-solid fa-layer-group fa-xl text-primary"></i>
                </div>
                <h4>Structured Learning Path</h4>
                <p style="color: var(--text-light);">Step-by-step curriculum designed to build strong foundations.</p>
            </div>
            
            <div class="feature-card" style="padding: 30px; background: var(--light-bg); border-radius: var(--radius-md); transition: transform 0.3s;">
                <div style="width: 60px; height: 60px; background: rgba(0, 210, 211, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fa-solid fa-comments fa-xl" style="color: var(--secondary);"></i>
                </div>
                <h4>Doubt Resolution System</h4>
                <p style="color: var(--text-light);">Ask questions directly below videos and get expert replies.</p>
            </div>
            
            <div class="feature-card" style="padding: 30px; background: var(--light-bg); border-radius: var(--radius-md); transition: transform 0.3s;">
                <div style="width: 60px; height: 60px; background: rgba(255, 159, 67, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fa-solid fa-chart-line fa-xl" style="color: var(--accent);"></i>
                </div>
                <h4>Progress Tracking</h4>
                <p style="color: var(--text-light);">Visualize your growth with detailed analytics and completion certificates.</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Courses -->
<section class="section-padding">
    <div class="container">
        <div class="flex justify-between align-center" style="margin-bottom: 50px;">
            <h2>Latest Courses</h2>
            <a href="courses.php" class="btn btn-secondary">View All</a>
        </div>
        
        <?php if(empty($latest_courses)): ?>
            <div class="text-center" style="padding: 40px; background: var(--white); border-radius: var(--radius-sm);">
                <p>No courses available at the moment. Check back soon!</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <?php foreach($latest_courses as $course): ?>
                <div class="course-card" style="background: var(--white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: all 0.3s;">
                    <div style="height: 200px; background: #ddd; position: relative;">
                         <?php if($course['thumbnail']): ?>
                            <img src="<?php echo BASE_URL . 'uploads/thumbnails/' . $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                         <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #eee; color: #aaa;">No Image</div>
                         <?php endif; ?>
                    </div>
                    <div style="padding: 25px;">
                        <h3 style="font-size: 1.25rem; margin-bottom: 10px;"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 20px; line-height: 1.5; height: 3em; overflow: hidden;">
                            <?php echo htmlspecialchars(substr($course['description'], 0, 100)) . '...'; ?>
                        </p>
                        
                        <!-- What You'll Learn Preview -->
                        <ul style="margin-bottom: 20px; font-size: 0.85rem; color: var(--text-light); list-style: none;">
                            <li><i class="fa-solid fa-check-circle text-primary" style="margin-right: 5px;"></i> Full Syllabus Coverage</li>
                            <li><i class="fa-solid fa-check-circle text-primary" style="margin-right: 5px;"></i> Practice Tests Included</li>
                        </ul>
                        
                        <div class="flex justify-between align-center" style="margin-top: auto;">
                            <!-- Integer Pricing Handling -->
                            <span style="font-weight: 700; color: var(--primary); font-size: 1.2rem;">₹<?php echo number_format($course['price'], 0); ?></span>
                            <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-secondary" style="padding: 8px 20px;">Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Bottom CTA -->
<section style="background: var(--primary); padding: 80px 0; text-align: center; color: #fff;">
    <div class="container">
        <h2 style="color: #fff; margin-bottom: 20px;">Ready to Excel in Chemistry?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto;">
            Start your journey today with our premium courses and study materials.
        </p>
        <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <a href="signup.php" class="btn" style="background: #fff; color: var(--primary); padding: 15px 40px; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">Start Learning Now</a>
            <small style="opacity: 0.8; font-weight: 500;">Limited seats per batch</small>
        </div>
    </div>
</section>



<?php require_once 'includes/footer.php'; ?>
