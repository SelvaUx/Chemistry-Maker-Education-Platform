<?php
// admin/courses.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

// Handle Delete (Basic)
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM courses WHERE id = ?")->execute([$id]);
    echo "<script>window.location.href='courses.php';</script>";
}

$courses = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC")->fetchAll();
?>

<div class="flex justify-between align-center" style="margin-bottom: 30px;">
    <h2>Course Management</h2>
    <a href="add-course.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Course</a>
</div>

    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="10%">Thumbnail</th>
                <th width="25%">Course Details</th>
                <th width="15%">Enrolled</th>
                <th width="10%">Price</th>
                <th width="10%">Status</th>
                <th width="25%">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($courses as $course): 
                $enrolled = rand(50, 850); // Mock Enrolled Count
            ?>
            <tr>
                <td style="color: #888;">#<?php echo $course['id']; ?></td>
                <td>
                    <?php if($course['thumbnail']): ?>
                        <img src="<?php echo BASE_URL . 'uploads/thumbnails/' . $course['thumbnail']; ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 6px;">
                    <?php else: ?>
                         <div style="width: 60px; height: 40px; background: #eee; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #aaa;">
                             <i class="fa-regular fa-image"></i>
                         </div>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="font-weight: 600; color: var(--text-main); font-size: 1rem;"><?php echo htmlspecialchars($course['title']); ?></div>
                    <div style="font-size: 0.8rem; color: #888; margin-top: 3px;">Created: <?php echo date('M d, Y', strtotime($course['created_at'])); ?></div>
                </td>
                <td>
                    <div style="font-weight: 600; color: #333;"><?php echo $enrolled; ?> Students</div>
                    <div style="height: 4px; background: #eee; border-radius: 2px; width: 80%; margin-top: 5px;">
                        <div style="height: 100%; background: var(--primary); width: <?php echo rand(10, 90); ?>%; border-radius: 2px;"></div>
                    </div>
                </td>
                <td>
                    <?php if($course['price'] > 0): ?>
                        <span style="font-weight: 700;">₹<?php echo number_format($course['price'], 0); ?></span>
                    <?php else: ?>
                        <span style="background: #e1ffe1; color: #00b894; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem;">FREE</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($course['status'] == 'published'): ?>
                        <span style="padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; background: #e1ffe1; color: #00b894; font-weight: 600;">
                            <i class="fa-solid fa-circle-check"></i> Published
                        </span>
                    <?php else: ?>
                        <span style="padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; background: #fff3cd; color: #e1b12c; font-weight: 600;">
                            <i class="fa-solid fa-circle-pause"></i> Draft
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="manage-course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary" style="padding: 8px 15px; font-size: 0.9rem;" title="Manage Content">
                            Manager Content
                        </a>
                        <a href="edit-course.php?id=<?php echo $course['id']; ?>" class="btn btn-secondary" style="padding: 8px 12px; color: #666;" title="Edit Details">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="../course.php?id=<?php echo $course['id']; ?>" target="_blank" class="btn btn-secondary" style="padding: 8px 12px; color: #666;" title="Preview">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        <a href="?delete=<?php echo $course['id']; ?>" class="btn btn-secondary" style="padding: 8px 12px; background: #ffecec; color: #d63031;" onclick="return confirm('Are you sure you want to delete this course? This action cannot be undone.');" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</div>
</div>
</body>
</html>
