<?php
// admin/edit-video.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$video_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($video_id == 0) {
    echo "Video ID missing."; exit;
}

// Fetch Video
$stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->execute([$video_id]);
$video = $stmt->fetch();

if (!$video) {
    echo "Video not found."; exit;
}

$course_id = $video['course_id'];
$folder_id = $video['module_id']; // Assuming module_id column exists

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $duration = trim($_POST['duration']);
    $position = (int)$_POST['position'];
    $is_free = isset($_POST['is_free']) ? 1 : 0;
    
    // Optional: Allow URL update (omitted for safety/simplicity unless requested, usually easiest to delete & re-add, but we can allow title edit)
    
    if (empty($title)) $error = "Title required.";
    
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE videos SET title = ?, duration = ?, position = ?, is_free = ? WHERE id = ?");
            $stmt->execute([$title, $duration, $position, $is_free, $video_id]);
            echo "<script>window.location.href='manage-folder.php?folder_id=$folder_id&course_id=$course_id&tab=videos';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Error updating video: " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Edit Video Details</h2>
    
    <?php if($error): ?>
        <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="stat-card">
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Video Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($video['title']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div class="flex gap-2" style="margin-bottom: 20px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Duration</label>
                    <input type="text" name="duration" value="<?php echo htmlspecialchars($video['duration']); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                 <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Position</label>
                    <input type="number" name="position" value="<?php echo $video['position']; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_free" <?php echo $video['is_free'] ? 'checked' : ''; ?>>
                    <span>Allow as Free Preview?</span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage-folder.php?folder_id=<?php echo $folder_id; ?>&course_id=<?php echo $course_id; ?>&tab=videos" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
