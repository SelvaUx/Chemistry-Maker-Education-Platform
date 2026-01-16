<?php
// admin/add-test.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$folder_id = isset($_GET['folder_id']) ? (int)$_GET['folder_id'] : 0;
if ($folder_id == 0) {
    echo "<script>window.location.href='courses.php';</script>";
    exit;
}

// Fetch basic info
$stmt = $pdo->prepare("SELECT course_id FROM modules WHERE id = ?");
$stmt->execute([$folder_id]);
$folder = $stmt->fetch();
$course_id = $folder ? $folder['course_id'] : 0;

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $form_url = trim($_POST['form_url']);
    
    if (empty($title) || empty($form_url)) {
        $error = "All fields required.";
    }
    
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO tests (course_id, module_id, title, form_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$course_id, $folder_id, $title, $form_url]);
            echo "<script>window.location.href='manage-folder.php?folder_id=$folder_id&course_id=$course_id&tab=tests';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Error adding test: " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Add Chapter Test</h2>
    
    <?php if($error): ?>
        <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="stat-card">
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Test Title</label>
                <input type="text" name="title" required placeholder="e.g. Weekly Quiz 1" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Google Form URL / Quiz Link</label>
                <input type="url" name="form_url" required placeholder="https://docs.google.com/forms/..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <button type="submit" class="btn btn-primary">Add Test Link</button>
            <a href="manage-folder.php?folder_id=<?php echo $folder_id; ?>&course_id=<?php echo $course_id; ?>&tab=tests" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
