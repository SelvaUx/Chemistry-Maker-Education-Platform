<?php
// admin/edit-test.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$test_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($test_id == 0) {
    echo "Test ID missing."; exit;
}

// Fetch Test
$stmt = $pdo->prepare("SELECT * FROM tests WHERE id = ?");
$stmt->execute([$test_id]);
$test = $stmt->fetch();

if (!$test) {
    echo "Test not found."; exit;
}

$folder_id = $test['module_id'];
$course_id = $test['course_id'];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $form_url = trim($_POST['form_url']);
    
    if (empty($title) || empty($form_url)) {
        $error = "All fields required.";
    }
    
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE tests SET title = ?, form_url = ? WHERE id = ?");
            $stmt->execute([$title, $form_url, $test_id]);
            echo "<script>window.location.href='manage-folder.php?folder_id=$folder_id&course_id=$course_id&tab=tests';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Error updating test: " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Edit Chapter Test</h2>
    
    <?php if($error): ?>
        <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="stat-card">
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Test Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($test['title']); ?>" required placeholder="e.g. Weekly Quiz 1" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Google Form URL / Quiz Link</label>
                <input type="url" name="form_url" value="<?php echo htmlspecialchars($test['form_url']); ?>" required placeholder="https://docs.google.com/forms/..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <small style="color: #888; display: block; margin-top: 5px;">Students will be redirected to this URL when they click on the test.</small>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage-folder.php?folder_id=<?php echo $folder_id; ?>&course_id=<?php echo $course_id; ?>&tab=tests" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
