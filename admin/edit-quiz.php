<?php
// admin/edit-quiz.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Mock Fetch
$all_quizzes = $pdo->prepare("SELECT * FROM quizzes")->fetchAll();
$quiz = null;
foreach($all_quizzes as $q) { if($q['id'] == $id) $quiz = $q; }

if(!$quiz) { echo "<div class='container' style='padding:50px;'>Quiz not found.</div>"; exit; }

// Handle Post (Mock Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real app: Update DB
    $quiz['title'] = $_POST['title'];
    $quiz['description'] = $_POST['description'];
    $quiz['price'] = $_POST['price'];
    $quiz['time_limit'] = $_POST['time_limit'];
    
    echo "<script>alert('Quiz Updated Successfully (Mock)!'); window.location.href='quizzes.php';</script>";
}
?>

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin: 0;">Edit Quiz</h2>
        <a href="quizzes.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    
    <div class="stat-card" style="padding: 40px; border-radius: 12px; box-shadow: var(--shadow-md);">
        <form method="POST">
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Quiz Title</label>
                <input type="text" name="title" required value="<?php echo htmlspecialchars($quiz['title']); ?>"
                       style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input);">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Description</label>
                <textarea name="description" rows="4" 
                          style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; background: var(--bg-input); line-height: 1.5;"><?php echo htmlspecialchars($quiz['description']); ?></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Price (₹)</label>
                    <input type="number" name="price" required value="<?php echo $quiz['price']; ?>"
                           style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input);">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Time Limit (Minutes)</label>
                    <input type="number" name="time_limit" required value="<?php echo $quiz['time_limit']; ?>"
                           style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input);">
                </div>
            </div>
            
            <div style="border-top: 1px solid #eee; padding-top: 25px; text-align: right;">
                 <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-size: 1.05rem;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
