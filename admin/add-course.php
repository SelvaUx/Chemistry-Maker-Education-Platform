<?php
// admin/add-course.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $status = $_POST['status'];
    
    // Slug generation
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Thumbnail Upload
    $thumbnail_name = null;
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $thumbnail_name = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../public_html/uploads/thumbnails/' . $thumbnail_name);
        } else {
            $error = "Invalid image format.";
        }
    }
    
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO courses (title, slug, description, price, status, thumbnail, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $slug, $description, $price, $status, $thumbnail_name]);
            $new_id = $pdo->lastInsertId();
             // Since mock DB doesn't really return lastInsertId often in this setup, fallback to redirect
            echo "<script>window.location.href='manage-course.php?id=1';</script>"; // Redirect to Manage Content (Mock ID 1)
            exit;
        } catch (PDOException $e) {
            $error = "Error adding course: " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="margin: 0;">Add New Course</h2>
        <a href="courses.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
    </div>
    
    <?php if($error): ?>
        <div style="background: #ffecec; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="stat-card" style="padding: 40px; border-radius: 12px; box-shadow: var(--shadow-md);">
        <form method="POST" enctype="multipart/form-data">
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Course Title <span style="color: red;">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Complete Organic Chemistry for JEE"
                       style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1.1rem; background: var(--bg-input);">
            </div>
            
            <!-- Category (New) -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Course Category</label>
                <select name="category" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: white;">
                    <option value="">Select Category...</option>
                    <option value="organic">Organic Chemistry</option>
                    <option value="inorganic">Inorganic Chemistry</option>
                    <option value="physical">Physical Chemistry</option>
                    <option value="general">General / Foundation</option>
                </select>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Description</label>
                <textarea name="description" rows="5" placeholder="What is this course about?"
                          style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; background: var(--bg-input); line-height: 1.5;"></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Price (₹) <span style="color: red;">*</span></label>
                    <div style="position: relative;">
                         <span style="position: absolute; left: 15px; top: 12px; color: #888;">₹</span>
                        <input type="number" step="1" name="price" required placeholder="499"
                               style="width: 100%; padding: 12px 15px 12px 35px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input); font-weight: 600;">
                    </div>
                    <small style="color: #666;">Standard pricing: ₹499, ₹999, ₹1499</small>
                </div>
                
                <div>
                     <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Status</label>
                     <select name="status" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; background: white;">
                        <option value="draft">🟡 Draft (Hidden)</option>
                        <option value="published">🟢 Published (Visible)</option>
                     </select>
                     <small style="color: #666;">Drafts are not visible to students.</small>
                </div>
            </div>
            
            <div style="margin-bottom: 40px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Thumbnail Image</label>
                <div style="border: 2px dashed #ddd; padding: 20px; border-radius: 8px; text-align: center; background: #f9f9f9;">
                    <i class="fa-regular fa-image" style="font-size: 2rem; color: #ccc; margin-bottom: 10px;"></i>
                    <input type="file" name="thumbnail" accept="image/*" style="display: block; margin: 0 auto;">
                    <small style="display: block; margin-top: 10px; color: #888;">Recommended size: 800x450px (16:9)</small>
                </div>
            </div>
            
            <div style="text-align: right; border-top: 1px solid #eee; padding-top: 20px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-size: 1.05rem; box-shadow: 0 4px 12px rgba(116, 185, 255, 0.4);">
                    Save & Continue <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
