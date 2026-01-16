<?php
// admin/manage-course.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($course_id == 0) {
    echo "<script>window.location.href='courses.php';</script>";
    exit;
}

// Fetch Course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

// Add Chapter Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['folder_title'])) {
    $title = trim($_POST['folder_title']);
    if (!empty($title)) {
        try {
            // Get max position
            $stmt = $pdo->prepare("SELECT MAX(position) as max_pos FROM modules WHERE course_id = ?");
            $stmt->execute([$course_id]);
            $result = $stmt->fetch();
            $next_position = ($result['max_pos'] ?? 0) + 1;
            
            // Insert new module/chapter
            $stmt = $pdo->prepare("INSERT INTO modules (course_id, title, position) VALUES (?, ?, ?)");
            $stmt->execute([$course_id, $title, $next_position]);
            
            // Redirect to refresh page
            echo "<script>window.location.href='manage-course.php?id=$course_id';</script>";
            exit;
        } catch (PDOException $e) {
            $error_msg = "Error adding chapter: " . $e->getMessage();
        }
    }
}

// Fetch Modules
$stmt = $pdo->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY position ASC");
$stmt->execute([$course_id]);
$modules = $stmt->fetchAll();
?>

<style>
    .chapter-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .chapter-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-color: #d1d5db; }
    .stat-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.85rem; padding: 4px 10px; border-radius: 20px;
        background: #f3f4f6; color: #4b5563; font-weight: 600;
    }
</style>

<div style="max-width: 1000px; margin: 0 auto;">
    <div class="flex justify-between align-center" style="margin-bottom: 30px;">
        <div class="flex align-center gap-2">
            <a href="courses.php" class="btn btn-secondary" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h2 style="margin: 0;">Course Structure</h2>
                <div style="color: #666; margin-top: 3px; font-size: 0.95rem;"><?php echo htmlspecialchars($course['title']); ?></div>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="../course.php?id=<?php echo $course_id; ?>" target="_blank" class="btn btn-secondary" style="color: var(--primary); border: 1px solid var(--primary); background: #f0fdf9;">
                <i class="fa-regular fa-eye"></i> Preview Course
            </a>
            <a href="edit-course.php?id=<?php echo $course_id; ?>" class="btn btn-primary">
                <i class="fa-solid fa-pen"></i> Edit Details
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; background: white; padding: 20px; border-radius: 12px; border: 1px solid #eee; text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?php echo count($modules); ?></div>
            <div style="color: #666; font-size: 0.9rem;">Total Chapters</div>
        </div>
        <div style="flex: 1; background: white; padding: 20px; border-radius: 12px; border: 1px solid #eee; text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #333;">48</div>
            <div style="color: #666; font-size: 0.9rem;">Total Videos</div>
        </div>
        <div style="flex: 1; background: white; padding: 20px; border-radius: 12px; border: 1px solid #eee; text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #eab308;">12</div>
            <div style="color: #666; font-size: 0.9rem;">Files & Notes</div>
        </div>
    </div>

    <!-- Add Chapter Form -->
    <div style="background: white; border: 2px dashed #e5e7eb; padding: 25px; border-radius: 12px; margin-bottom: 30px; text-align: center;">
        <form method="POST" style="display: flex; justify-content: center; gap: 10px; max-width: 600px; margin: 0 auto;">
            <input type="text" name="folder_title" placeholder="e.g. Chapter 1: Introduction to Chemistry" required 
                   style="flex: 1; padding: 12px 20px; border: 1px solid #ddd; border-radius: 50px; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#ddd'">
            <button type="submit" class="btn btn-primary" style="padding: 12px 25px; border-radius: 50px;">
                <i class="fa-solid fa-plus-circle"></i> Add Chapter
            </button>
        </form>
    </div>

    <!-- Chapters List -->
    <div class="flex" style="flex-direction: column; gap: 15px;">
        <?php if(empty($modules)): ?>
            <div style="text-align: center; color: #9ca3af; padding: 40px;">
                <i class="fa-regular fa-folder-open" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                <p>No chapters yet. Start by adding one above.</p>
            </div>
        <?php else: ?>
            <?php foreach($modules as $index => $mod): 
                 $vid_count = rand(3, 15);
                 $test_count = rand(0, 3);
            ?>
                <div class="chapter-card">
                    <div class="flex align-center gap-3">
                        <div style="width: 30px; text-align: center; color: #ccc; font-weight: 600;"><i class="fa-solid fa-grip-vertical"></i></div>
                        
                        <div style="width: 50px; height: 50px; background: #fffbeb; color: #f59e0b; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fa-regular fa-folder-open"></i>
                        </div>
                        
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;"><?php echo htmlspecialchars($mod['title']); ?></h4>
                            <div style="display: flex; gap: 10px; margin-top: 6px;">
                                <span class="stat-badge"><i class="fa-solid fa-circle-play" style="color: #ef4444;"></i> <?php echo $vid_count; ?> Videos</span>
                                <span class="stat-badge"><i class="fa-solid fa-file-pdf" style="color: #ef4444;"></i> 2 PDFs</span>
                                <?php if($test_count > 0): ?>
                                    <span class="stat-badge"><i class="fa-solid fa-list-check" style="color: var(--primary);"></i> <?php echo $test_count; ?> Tests</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 align-center">
                        <label class="switch" title="Visible to Students">
                             <input type="checkbox" checked>
                             <span class="slider round"></span>
                        </label>
                        <div style="width: 1px; height: 25px; background: #eee; margin: 0 10px;"></div>
                        
                        <a href="manage-folder.php?folder_id=<?php echo $mod['id']; ?>&course_id=<?php echo $course_id; ?>" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem; border-radius: 6px;">
                            Open <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        
                        <button class="btn btn-secondary" style="color: #64748b;" title="Edit Name"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-secondary" style="color: #ef4444; background: #fef2f2;" title="Delete" onclick="return confirm('WARNING: This will delete ALL videos, files, and tests inside this chapter. Continue?');"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
</div>
</div>

<style>
/* Toggle Switch */
.switch { position: relative; display: inline-block; width: 40px; height: 22px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: var(--primary); }
input:checked + .slider:before { transform: translateX(18px); }
</style>
</body>
</html>
