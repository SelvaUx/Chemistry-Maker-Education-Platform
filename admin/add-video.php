<?php
// admin/add-video.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
if ($course_id == 0) {
    echo "<script>window.location.href='courses.php';</script>";
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $type = $_POST['video_type'];
    $duration = trim($_POST['duration']);
    $position = (int)$_POST['position'];
    $is_free = isset($_POST['is_free']) ? 1 : 0;
    
    $video_url = "";
    
    if ($type == 'youtube') {
        $video_url = trim($_POST['youtube_url']);
        if (empty($video_url)) $error = "YouTube URL required.";
    } else {
        // Handle upload
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] == 0) {
             $allowed = ['mp4', 'webm'];
             $ext = strtolower(pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION));
             if (in_array($ext, $allowed)) {
                 $video_url = uniqid() . '.' . $ext;
                 move_uploaded_file($_FILES['video_file']['tmp_name'], '../public_html/uploads/videos/course_folders/' . $video_url);
             } else {
                 $error = "Invalid video format (MP4/WebM only).";
             }
        } else {
            $error = "Please select a video file.";
        }
    }
    
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO videos (course_id, title, video_type, video_url, duration, position, is_free) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$course_id, $title, $type, $video_url, $duration, $position, $is_free]);
            echo "<script>window.location.href='videos.php?course_id=$course_id';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Error adding video: " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Add Video Lesson</h2>
    
    <?php if($error): ?>
        <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="stat-card">
        <form method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Video Title</label>
                <input type="text" name="title" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Source Type</label>
                <select name="video_type" id="video_type" onchange="toggleSource()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="youtube">YouTube URL</option>
                    <option value="upload">Upload File</option>
                </select>
            </div>
            
            <div id="youtube_input" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">YouTube URL</label>
                <input type="text" name="youtube_url" placeholder="https://youtube.com/..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div id="upload_input" style="margin-bottom: 20px; display: none;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Select Video File (MP4)</label>
                <input type="file" name="video_file" accept=".mp4,.webm" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
             <div class="flex gap-2" style="margin-bottom: 20px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Duration (e.g. 10:30)</label>
                    <input type="text" name="duration" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                 <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Position (Order)</label>
                    <input type="number" name="position" value="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_free">
                    <span>Allow as Free Preview?</span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Video</button>
            <a href="videos.php?course_id=<?php echo $course_id; ?>" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</div>

<script>
    function toggleSource() {
        const type = document.getElementById('video_type').value;
        const yt = document.getElementById('youtube_input');
        const up = document.getElementById('upload_input');
        
        if(type === 'youtube') {
            yt.style.display = 'block';
            up.style.display = 'none';
        } else {
            yt.style.display = 'none';
            up.style.display = 'block';
        }
    }
</script>

</div>
</div>
</body>
</html>
