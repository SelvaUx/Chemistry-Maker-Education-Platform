<?php
// public_html/course.php
require_once '../config/constants.php';
require_once '../config/db.php';

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($course_id == 0) {
    header('Location: courses.php');
    exit;
}

// Fetch course details
try {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ? AND status = 'published'");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch();

    if (!$course) {
        die("Course not found or not currently available.");
    }
    
    // Fetch videos/lessons for curriculum preview
    $stmt_vid = $pdo->prepare("SELECT title, duration, is_free FROM videos WHERE course_id = ? ORDER BY position ASC");
    $stmt_vid->execute([$course_id]);
    $videos = $stmt_vid->fetchAll();
    
    // Get actual enrollment count
    $stmt_enroll = $pdo->prepare("SELECT COUNT(*) as count FROM purchases WHERE course_id = ?");
    $stmt_enroll->execute([$course_id]);
    $enrollment_count = $stmt_enroll->fetchColumn() ?: 0;

} catch (PDOException $e) {
    die("Error fetching course information.");
}

$pageTitle = $course['title'];
require_once 'includes/header.php';
?>

<!-- Header Section (Dark) -->
<div style="background: var(--dark); color: var(--white); padding: 60px 0;">
    <div class="container">
        <div style="max-width: 800px;">
            <h1 style="color: var(--white); font-size: 2.5rem; margin-bottom: 20px;"><?php echo htmlspecialchars($course['title']); ?></h1>
            <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 20px; line-height: 1.6;">
               <?php echo htmlspecialchars(substr($course['description'], 0, 250)) . '...'; ?>
            </p>
            
            <div class="flex align-center gap-2" style="margin-bottom: 30px; flex-wrap: wrap;">
                 <div class="flex align-center gap-1">
                    <span style="color: #f1c40f; font-weight: 700;">4.8</span>
                    <i class="fa-solid fa-star" style="color: #f1c40f;"></i>
                    <i class="fa-solid fa-star" style="color: #f1c40f;"></i>
                    <i class="fa-solid fa-star" style="color: #f1c40f;"></i>
                    <i class="fa-solid fa-star" style="color: #f1c40f;"></i>
                    <i class="fa-solid fa-star-half-stroke" style="color: #f1c40f;"></i>
                 </div>
                 <span style="opacity: 0.7;">(245 ratings)</span>
                 <span style="opacity: 0.7;"><?php echo number_format($enrollment_count); ?> students enrolled</span>
                 <span style="opacity: 0.7;"><i class="fa-solid fa-language"></i> <?php echo $course['language'] ?? 'English'; ?></span>
            </div>
            
            <div style="font-size: 0.9rem; opacity: 0.8;">
                Created by <span class="text-primary" style="font-weight: 600; text-decoration: underline;"><?php echo $course['instructor']['name'] ?? 'Chemistry Maker'; ?></span>
            </div>
        </div>
    </div>
</div>

<div class="container section-padding" style="position: relative;">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
        
        <!-- Left Content -->
        <div>
            <!-- What You'll Learn -->
            <?php if (!empty($course['learning_outcomes'])): ?>
            <div style="background: var(--white); padding: 30px; border: 1px solid var(--border-color, #eee); border-radius: var(--radius-md); margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px;">What you'll learn</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <?php foreach ($course['learning_outcomes'] as $outcome): ?>
                    <div class="flex gap-1">
                        <i class="fa-solid fa-check" style="color: var(--text-light); margin-top: 5px;"></i>
                        <span style="font-size: 0.95rem; color: var(--text-main);"><?php echo htmlspecialchars($outcome); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Course Description -->
            <div style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px;">Course Description</h3>
                <div style="line-height: 1.8; color: var(--text-light); font-size: 1rem;">
                    <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                </div>
            </div>
            
            <!-- Curriculum -->
            <div style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px;">Course Content</h3>
                <div style="background: var(--white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); overflow: hidden;">
                    <?php if (empty($videos)): ?>
                        <p style="padding: 20px; color: var(--text-light);">Curriculum details coming soon.</p>
                    <?php else: ?>
                        <div style="padding: 15px 20px; background: var(--light-bg); border-bottom: 1px solid rgba(0,0,0,0.05); font-weight: 600; display: flex; justify-content: space-between;">
                            <span><?php echo count($videos); ?> Lectures</span>
                            <span>Total Duration: 40h 30m</span>
                        </div>
                        <ul class="curriculum-list">
                            <?php foreach($videos as $index => $video): ?>
                            <li style="padding: 15px 20px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; transition: background 0.2s;">
                                <div class="flex align-center gap-2">
                                    <i class="fa-solid fa-circle-play text-primary"></i>
                                    <div>
                                        <h5 style="margin-bottom: 0; font-size: 0.95rem; font-weight: 500;">
                                            <?php echo htmlspecialchars($video['title']); ?>
                                            <?php if($video['is_free']): ?>
                                                <span style="font-size: 0.7rem; background: var(--secondary); color: white; padding: 2px 6px; border-radius: 4px; margin-left: 10px;">PREVIEW</span>
                                            <?php endif; ?>
                                        </h5>
                                    </div>
                                </div>
                                <span style="font-size: 0.9rem; color: var(--text-light);"><?php echo $video['duration'] ?: '10:00'; ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Instructor -->
             <?php if (!empty($course['instructor'])): ?>
            <div style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px;">Instructor</h3>
                <div style="background: var(--light-bg); padding: 25px; border-radius: var(--radius-md);">
                    <div class="flex gap-2 align-center" style="margin-bottom: 15px;">
                        <div style="width: 80px; height: 80px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #fff;">
                           <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($course['instructor']['name']); ?>&background=random" alt="Instructor" style="border-radius: 50%;">
                        </div>
                        <div>
                            <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($course['instructor']['name']); ?></h4>
                            <p style="font-size: 0.9rem; color: var(--text-light);">Chemistry Expert</p>
                        </div>
                    </div>
                    <p style="color: var(--text-light); line-height: 1.6;">
                        <?php echo htmlspecialchars($course['instructor']['bio']); ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- FAQ -->
             <?php if (!empty($course['faq'])): ?>
            <div style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px;">Frequently Asked Questions</h3>
                <div class="accordion">
                    <?php foreach ($course['faq'] as $faq): ?>
                    <div style="margin-bottom: 15px; border: 1px solid var(--glass-border); border-radius: var(--radius-sm); padding: 15px; background: var(--white);">
                        <h5 style="margin-bottom: 10px; cursor: pointer; display: flex; justify-content: space-between;" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'none' ? 'block' : 'none'">
                            <?php echo htmlspecialchars($faq['q']); ?>
                            <i class="fa-solid fa-chevron-down"></i>
                        </h5>
                        <p style="color: var(--text-light); font-size: 0.95rem; display: none; padding-top: 10px; border-top: 1px solid #eee;">
                            <?php echo htmlspecialchars($faq['a']); ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Right Sidebar (Sticky) -->
        <div>
            <div class="course-sidebar" style="background: var(--white); border-radius: var(--radius-md); box-shadow: var(--shadow-lg); position: sticky; top: 100px; overflow: hidden; border: 1px solid var(--glass-border);">
                <!-- Preview Image/Video -->
                <div style="position: relative; background: #000; height: 200px; display: flex; align-items: center; justify-content: center;">
                     <?php if($course['thumbnail']): ?>
                        <img src="<?php echo BASE_URL . 'uploads/thumbnails/' . $course['thumbnail']; ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;">
                        <i class="fa-solid fa-play-circle" style="position: absolute; font-size: 4rem; color: #fff; opacity: 0.9; cursor: pointer;"></i>
                     <?php else: ?>
                        <div style="color: #fff;">No Preview</div>
                     <?php endif; ?>
                </div>
                
                <div style="padding: 25px;">
                    <div style="margin-bottom: 20px;">
                        <span style="font-size: 2.5rem; font-weight: 800; color: var(--text-main);">₹<?php echo number_format($course['price'], 0); ?></span>
                        <span style="text-decoration: line-through; color: var(--text-light); margin-left: 10px; font-size: 1.1rem;">₹<?php echo number_format($course['price'] * 1.5, 0); ?></span>
                        <span style="display: block; color: #e74c3c; font-weight: 600; font-size: 0.9rem; margin-top: 5px;">33% OFF • 2 days left at this price!</span>
                    </div>
                    
                    <div style="margin-bottom: 25px;">
                         <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="checkout.php?course_id=<?php echo $course['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center; padding: 15px; font-size: 1.1rem; border-radius: 8px;">Buy Now</a>
                        <?php else: ?>
                             <a href="login.php?redirect=checkout.php?course_id=<?php echo $course['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center; padding: 15px; font-size: 1.1rem; border-radius: 8px;">Login to Buy</a>
                        <?php endif; ?>
                        <p style="text-align: center; font-size: 0.75rem; margin-top: 10px; color: var(--text-light);">30-Day Money-Back Guarantee</p>
                    </div>
                    
                    <h5 style="margin-bottom: 15px;">This course includes:</h5>
                    <ul style="margin-bottom: 25px;" class="feature-list">
                        <?php if(!empty($course['features'])): 
                            foreach($course['features'] as $key => $feature): ?>
                            <li style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center; color: var(--text-light); font-size: 0.95rem;">
                                <?php 
                                    $icon = 'fa-check';
                                    if(strpos($key, 'duration') !== false) $icon = 'fa-video';
                                    if(strpos($key, 'resources') !== false) $icon = 'fa-file-arrow-down';
                                    if(strpos($key, 'tests') !== false) $icon = 'fa-clipboard-question';
                                    if(strpos($key, 'access') !== false) $icon = 'fa-mobile-screen';
                                    if(strpos($key, 'certificate') !== false) $icon = 'fa-trophy';
                                ?>
                                <i class="fa-solid <?php echo $icon; ?> text-primary" style="width: 20px;"></i> <?php echo htmlspecialchars($feature); ?>
                            </li>
                        <?php endforeach; else: ?>
                            <!-- Fallbacks -->
                            <li style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center; color: var(--text-light);"><i class="fa-solid fa-video text-primary" style="width: 20px;"></i> 40 hours on-demand video</li>
                            <li style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center; color: var(--text-light);"><i class="fa-solid fa-mobile-screen text-primary" style="width: 20px;"></i> Access on Mobile & TV</li>
                            <li style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center; color: var(--text-light);"><i class="fa-solid fa-infinity text-primary" style="width: 20px;"></i> <?php echo $value; ?></li>
                        <?php endif; ?>
                    </ul>
                    
                    <!-- Coupon Input Placeholder (Functional in Checkout) -->
                    <div style="border-top: 1px solid var(--glass-border); padding-top: 20px;">
                        <h5 style="margin-bottom: 10px; font-size: 0.9rem;">Apply Coupon</h5>
                        <div class="flex" style="gap: 5px;">
                            <input type="text" placeholder="Enter Code" style="flex: 1; padding: 8px; border: 1px solid var(--border-color, #ddd); border-radius: 4px; background: var(--bg-input, #fff); color: var(--text-main);">
                            <button class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.8rem;">Apply</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>


