<?php
// admin/edit-question.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;
$qn_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$question_data = [
    'question' => '', 'image' => '', 'opt_a' => '', 'opt_b' => '', 'opt_c' => '', 'opt_d' => '', 'correct' => 'opt_a'
];
$is_edit = false;

// Mock Fetch if ID exists
if ($qn_id > 0) {
    $all = $pdo->prepare("SELECT * FROM quiz_questions")->fetchAll();
    foreach($all as $q) { if($q['id'] == $qn_id) { $question_data = $q; $is_edit = true; break; } }
}

// Handle Upload & Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for image upload
    if (isset($_FILES['question_image']) && $_FILES['question_image']['error'] == 0) {
        // Upload logic
        $ext = pathinfo($_FILES['question_image']['name'], PATHINFO_EXTENSION);
        $filename = "qn_" . time() . ".$ext";
        // Create dir if not exists (Mocking for now, but creating path)
        if (!is_dir("../public_html/uploads/quiz_images")) mkdir("../public_html/uploads/quiz_images", 0777, true);
        move_uploaded_file($_FILES['question_image']['tmp_name'], "../public_html/uploads/quiz_images/$filename");
        
        $question_data['image'] = $filename; // Update data with new image
    }
    
    // In real app: Update/Insert DB here
    echo "<script>alert('Question Saved Successfully!'); window.location.href='manage-quiz-questions.php?quiz_id=$quiz_id';</script>";
}
?>

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin: 0;"><?php echo $is_edit ? 'Edit Question' : 'Add New Question'; ?></h2>
        <a href="manage-quiz-questions.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    
    <div class="stat-card" style="padding: 40px; border-radius: 12px; box-shadow: var(--shadow-md);">
        <form method="POST" enctype="multipart/form-data">
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Question Text</label>
                <textarea name="question" rows="3" required placeholder="Type your question here..."
                          style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input);"><?php echo htmlspecialchars($question_data['question']); ?></textarea>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Question Image (Optional)</label>
                <input type="file" name="question_image" accept="image/*"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: white;">
                <?php if($question_data['image']): ?>
                    <div style="margin-top: 10px;">
                        <img src="../public_html/uploads/quiz_images/<?php echo $question_data['image']; ?>" style="max-height: 100px; border-radius: 5px; border: 1px solid #ddd;">
                        <p style="font-size: 0.8rem; color: #888;">Current Image</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Option A</label>
                    <input type="text" name="opt_a" value="<?php echo htmlspecialchars($question_data['opt_a']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Option B</label>
                    <input type="text" name="opt_b" value="<?php echo htmlspecialchars($question_data['opt_b']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Option C</label>
                    <input type="text" name="opt_c" value="<?php echo htmlspecialchars($question_data['opt_c']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Option D</label>
                    <input type="text" name="opt_d" value="<?php echo htmlspecialchars($question_data['opt_d']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Correct Answer</label>
                <select name="correct" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: white;">
                    <option value="opt_a" <?php echo $question_data['correct'] == 'opt_a' ? 'selected' : ''; ?>>Option A</option>
                    <option value="opt_b" <?php echo $question_data['correct'] == 'opt_b' ? 'selected' : ''; ?>>Option B</option>
                    <option value="opt_c" <?php echo $question_data['correct'] == 'opt_c' ? 'selected' : ''; ?>>Option C</option>
                    <option value="opt_d" <?php echo $question_data['correct'] == 'opt_d' ? 'selected' : ''; ?>>Option D</option>
                </select>
            </div>
            
            <div style="border-top: 1px solid #eee; padding-top: 25px; text-align: right;">
                 <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-size: 1.05rem;"><?php echo $is_edit ? 'Update Question' : 'Add Question'; ?></button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
