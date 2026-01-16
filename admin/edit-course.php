<?php
// admin/edit-course.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Mock Fetch
$all_courses = $pdo->query("SELECT * FROM courses")->fetchAll();
$course = null;
foreach($all_courses as $c) { if($c['id'] == $id) $course = $c; }

if(!$course) { echo "Course not found."; exit; }

// Handle Post (Mock Update)
    // Handle Post (Mock Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    
    // Thumbnail Upload
    $thumbnail_name = $course['thumbnail']; // Default to existing
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $thumbnail_name = uniqid() . '.' . $ext;
            if (!is_dir('../public_html/uploads/thumbnails/')) {
                 mkdir('../public_html/uploads/thumbnails/', 0777, true);
            }
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../public_html/uploads/thumbnails/' . $thumbnail_name);
        }
    }

    echo "<div class='alert alert-success'>Course updated successfully! (Demo Mode)</div>";
    
    // Update local variable for display
    $course['title'] = $title;
    $course['thumbnail'] = $thumbnail_name;
    $course['description'] = $_POST['description'];
    $course['price'] = $_POST['price'];
    $course['learning_outcomes'] = array_filter(explode("\n", $_POST['learning_outcomes']));
    $course['instructor'] = [
        'name' => $_POST['instructor_name'] ?? 'Chemistry Maker',
        'bio' => $_POST['instructor_bio'] ?? ''
    ];
    $course['language'] = $_POST['language'] ?? 'English';
    $course['features'] = [
        'duration' => $_POST['duration'],
        'access' => $_POST['access']
    ];
}
?>

<div style="max-width: 900px; margin: 0 auto;">
    <!-- ... header ... -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin: 0;">Edit Course</h2>
        <a href="courses.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <!-- Course Title Display -->
    <div style="background: linear-gradient(135deg, var(--primary), var(--secondary)); padding: 30px; border-radius: 12px; color: white; margin-bottom: 30px; box-shadow: var(--shadow-lg);">
        <h1 style="margin: 0; font-size: 2rem;"><?php echo htmlspecialchars($course['title']); ?></h1>
        <p style="opacity: 0.9; margin-top: 5px; font-size: 0.95rem;">Created: <?php echo date('M d, Y', strtotime($course['created_at'])); ?> • Status: <?php echo ucfirst($course['status']); ?></p>
    </div>
    
    <div class="stat-card" style="padding: 40px; border-radius: 12px; box-shadow: var(--shadow-md);">
        <form method="POST" enctype="multipart/form-data">
            <!-- Basic Info Section -->
            <h4 style="margin-bottom: 20px; color: var(--primary); border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">Basic Information</h4>
            
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 25px;">
                <!-- Thumbnail Preview & Upload -->
                <div style="grid-column: 1 / -1; margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Course Thumbnail</label>
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div style="width: 160px; height: 90px; background: #eee; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                            <?php if(!empty($course['thumbnail']) && file_exists('../public_html/uploads/thumbnails/'.$course['thumbnail'])): ?>
                                <img src="../public_html/uploads/thumbnails/<?php echo $course['thumbnail']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; color: #aaa;">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div style="flex: 1;">
                            <input type="file" name="thumbnail" accept="image/*" style="padding: 10px; border: 1px solid #ddd; width: 100%; border-radius: 6px;">
                            <small style="color: #888;">Upload to replace current image. Recommended: 800x450px.</small>
                        </div>
                    </div>
                </div>

                <!-- Title & Price (Moved into PHP block replacement target to keep context valid) -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Course Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required 
                           style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input); color: var(--text-main); transition: all 0.3s;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Price (₹)</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 15px; top: 12px; color: #888;">₹</span>
                        <input type="number" name="price" value="<?php echo $course['price']; ?>" required 
                               style="width: 100%; padding: 12px 15px 12px 35px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input); color: var(--text-main); font-weight: 700;">
                    </div>
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Description</label>
                <textarea name="description" rows="6" 
                          style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; background: var(--bg-input); line-height: 1.6; color: var(--text-main); font-family: inherit;"><?php echo htmlspecialchars($course['description']); ?></textarea>
                <small style="color: #888; margin-top: 5px; display: block;">Supports basic formatting.</small>
            </div>
            
            <!-- Details Section -->
            <h4 style="margin-bottom: 20px; color: var(--primary); border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-top: 40px;">Detailed Content</h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Learning Outcomes -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">What You'll Learn</label>
                    <textarea name="learning_outcomes" rows="8" placeholder="• Master Organic Chemistry&#10;• Solve complex reactions..."
                              style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: var(--bg-input); color: var(--text-main); line-height: 1.5;"><?php 
                        if(isset($course['learning_outcomes']) && is_array($course['learning_outcomes'])) {
                            echo htmlspecialchars(implode("\n", $course['learning_outcomes']));
                        }
                    ?></textarea>
                    <small style="color: #888;">Enter each outcome on a new line.</small>
                </div>
                
                <!-- Features & Instructor -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Course Duration</label>
                        <input type="text" name="duration" value="<?php echo $course['features']['duration'] ?? ''; ?>" placeholder="e.g. 40 Hours"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: var(--bg-input);">
                    </div>
                    
                    <div>
                         <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Access Type</label>
                         <select name="access" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: var(--bg-input);">
                            <option value="Lifetime Access">Lifetime Access</option>
                            <option value="1 Year Access">1 Year Access</option>
                         </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Instructor Name</label>
                        <input type="text" name="instructor_name" value="<?php echo $course['instructor']['name'] ?? 'Chemistry Maker'; ?>"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: var(--bg-input);">
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Instructor Bio</label>
                        <input type="text" name="instructor_bio" value="<?php echo $course['instructor']['bio'] ?? ''; ?>" placeholder="Brief instructor bio"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: var(--bg-input);">
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Language</label>
                        <select name="language" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: var(--bg-input);">
                            <option value="English" <?php echo ($course['language'] ?? 'English') == 'English' ? 'selected' : ''; ?>>English</option>
                            <option value="Hindi" <?php echo ($course['language'] ?? '') == 'Hindi' ? 'selected' : ''; ?>>Hindi</option>
                            <option value="Bilingual" <?php echo ($course['language'] ?? '') == 'Bilingual' ? 'selected' : ''; ?>>English + Hindi</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 15px;">
                <a href="courses.php" class="btn btn-light" style="padding: 12px 25px; border: 1px solid #ddd; background: #fff; color: #333;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-size: 1.05rem; box-shadow: 0 4px 12px rgba(116, 185, 255, 0.4);">Save Changes</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
</body>
</html>
