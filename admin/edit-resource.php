<?php
// admin/edit-resource.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$resource_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($resource_id == 0) {
    echo "Resource ID missing."; exit;
}

// Fetch Resource
$stmt = $pdo->prepare("SELECT * FROM resources WHERE id = ?");
$stmt->execute([$resource_id]);
$resource = $stmt->fetch();

if (!$resource) {
    echo "Resource not found."; exit;
}

$folder_id = $resource['module_id'];
$course_id = $resource['course_id'];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    
    if (empty($title)) $error = "Title required.";
    
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE resources SET title = ? WHERE id = ?");
            $stmt->execute([$title, $resource_id]);
            echo "<script>window.location.href='manage-folder.php?folder_id=$folder_id&course_id=$course_id&tab=notes';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Error updating resource: " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Edit PDF Resource</h2>
    
    <?php if($error): ?>
        <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="stat-card">
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Resource Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($resource['title']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Current File</label>
                <a href="../uploads/resources/<?php echo $resource['file_path']; ?>" target="_blank" style="color: var(--primary); text-decoration: underline;">
                    <i class="fa-solid fa-file-pdf"></i> <?php echo htmlspecialchars($resource['file_path']); ?>
                </a>
                <p style="font-size: 0.9rem; color: #888; margin-top: 8px;">To replace the file, delete this resource and re-upload.</p>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage-folder.php?folder_id=<?php echo $folder_id; ?>&course_id=<?php echo $course_id; ?>&tab=notes" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
