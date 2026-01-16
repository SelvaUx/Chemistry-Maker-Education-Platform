<?php
// admin/add-resource.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$folder_id = isset($_GET['folder_id']) ? (int)$_GET['folder_id'] : 0;
// We also need course_id to redirect back properly, usually passed or fetched
if ($folder_id == 0) {
    echo "<script>window.location.href='courses.php';</script>";
    exit;
}

// Fetch basic folder info to get course_id if not passed
$stmt = $pdo->prepare("SELECT course_id FROM modules WHERE id = ?");
$stmt->execute([$folder_id]);
$folder = $stmt->fetch();
$course_id = $folder ? $folder['course_id'] : 0;

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    // Handle File Upload
    $file_path = "";
    
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
         $allowed = ['pdf'];
         $ext = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
         if (in_array($ext, $allowed)) {
             $file_path = uniqid() . '.' . $ext;
             // Ensure directory exists
             if (!is_dir('../public_html/uploads/resources/')) {
                 mkdir('../public_html/uploads/resources/', 0777, true);
             }
             move_uploaded_file($_FILES['pdf_file']['tmp_name'], '../public_html/uploads/resources/' . $file_path);
         } else {
             $error = "Invalid format (PDF only).";
         }
    } else {
        $error = "Please select a PDF file.";
    }
    
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO resources (course_id, module_id, title, file_path) VALUES (?, ?, ?, ?)");
            $stmt->execute([$course_id, $folder_id, $title, $file_path]);
            echo "<script>window.location.href='manage-folder.php?folder_id=$folder_id&course_id=$course_id&tab=notes';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Error adding resource: " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Add PDF Resource</h2>
    
    <?php if($error): ?>
        <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="stat-card">
        <form method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Title</label>
                <input type="text" name="title" required placeholder="e.g. Chapter 1 Notes" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Select PDF File</label>
                <input type="file" name="pdf_file" accept=".pdf" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <button type="submit" class="btn btn-primary">Upload PDF</button>
            <a href="manage-folder.php?folder_id=<?php echo $folder_id; ?>&course_id=<?php echo $course_id; ?>&tab=notes" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
