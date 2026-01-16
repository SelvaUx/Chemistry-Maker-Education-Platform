<?php
// admin/videos.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if ($course_id == 0) {
    echo "<script>window.location.href='courses.php';</script>";
    exit;
}

// Fetch Course Info
$stmt = $pdo->prepare("SELECT title FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM videos WHERE id = ?")->execute([$id]);
    echo "<script>window.location.href='videos.php?course_id=$course_id';</script>";
}

// Fetch Videos
$stmt = $pdo->prepare("SELECT * FROM videos WHERE course_id = ? ORDER BY position ASC");
$stmt->execute([$course_id]);
$videos = $stmt->fetchAll();
?>

<div class="flex justify-between align-center" style="margin-bottom: 30px;">
    <div>
        <a href="courses.php" style="color: #777; font-size: 0.9rem; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Back to Courses</a>
        <h2 style="margin-top: 10px;">Videos: <?php echo htmlspecialchars($course['title']); ?></h2>
    </div>
    <a href="add-video.php?course_id=<?php echo $course_id; ?>" class="btn btn-primary"><i class="fa-solid fa-cloud-arrow-up"></i> Add Video</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Pos</th>
                <th>Title</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Free/Preview</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($videos)): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 40px; color: #888;">No videos added yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach($videos as $video): ?>
                <tr>
                    <td><?php echo $video['position']; ?></td>
                    <td><?php echo htmlspecialchars($video['title']); ?></td>
                    <td>
                        <span style="font-size: 0.85rem; padding: 4px 8px; background: #eee; border-radius: 4px;">
                            <?php echo ucfirst($video['video_type']); ?>
                        </span>
                    </td>
                    <td><?php echo $video['duration']; ?></td>
                    <td>
                        <?php if($video['is_free']): ?>
                            <span style="color: var(--secondary); font-weight: 600;">Yes</span>
                        <?php else: ?>
                            <span style="color: #ccc;">No</span>
                        <?php endif; ?>
                    </td>
                    <td>
                         <a href="?course_id=<?php echo $course_id; ?>&delete=<?php echo $video['id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem; background: #ffecec; color: #d63031;" onclick="return confirm('Are you sure?');" title="Delete"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</div>
</div>
</body>
</html>
