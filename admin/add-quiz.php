<?php
// admin/add-quiz.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

// Handle Post (Mock)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In real app: Insert into DB, get ID
    // $id = $pdo->lastInsertId();
    $id = rand(100, 999); // Mock ID
    
    // Redirect logic
    if (isset($_POST['save_draft'])) {
        echo "<script>alert('Draft Saved!'); window.location.href='quizzes.php';</script>";
        exit;
    } else {
        echo "<script>window.location.href='quiz-builder.php?id=$id&tab=build';</script>";
        exit;
    }
}
?>

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="margin: 0;">Create New Test Series</h2>
            <p style="color: #666; font-size: 0.9rem;">Step 1: Configure Basic Settings</p>
        </div>
        <a href="quizzes.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
    </div>
    
    <div class="stat-card" style="padding: 40px; border-radius: 12px; box-shadow: var(--shadow-md);">
        <form method="POST">
            <!-- Title & Description -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Quiz Title <span style="color: red;">*</span></label>
                <input type="text" name="title" required placeholder="e.g. JEE Mains 2025 Mock Test 1"
                       style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input);">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Description</label>
                <textarea name="description" rows="3" placeholder="Syllabus coverage, instructions, etc."
                          style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; background: var(--bg-input); line-height: 1.5;"></textarea>
            </div>
            
            <div style="width: 100%; height: 1px; background: #eee; margin: 30px 0;"></div>
            
            <!-- Pricing & Access -->
            <h4 style="margin-bottom: 20px; color: var(--primary);">Pricing & Access Control</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 25px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Price (₹) <span style="color: red;">*</span></label>
                    <div style="position: relative;">
                         <span style="position: absolute; left: 15px; top: 12px; color: #888;">₹</span>
                        <input type="number" name="price" required placeholder="0" value="0"
                               style="width: 100%; padding: 12px 15px 12px 30px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input);">
                    </div>
                    <small style="color: #666;">Set to 0 for Free Test.</small>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Time Limit (Minutes) <span style="color: red;">*</span></label>
                    <input type="number" name="time_limit" required value="60"
                           style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: var(--bg-input);">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Negative Marking</label>
                    <select name="negative_marking" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; background: white;">
                        <option value="0">No Negative Marking</option>
                        <option value="0.25">-0.25 Marks</option>
                        <option value="0.33">-0.33 Marks (1/3)</option>
                        <option value="1.0">-1.00 Marks</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main);">Attempts Allowed</label>
                    <select name="attempts" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; background: white;">
                        <option value="0">Unlimited Attempts</option>
                        <option value="1">1 Attempt Only</option>
                        <option value="3">3 Attempts</option>
                        <option value="5">5 Attempts</option>
                    </select>
                </div>
            </div>

             <!-- Advanced Toggles -->
             <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #eee;">
                 <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                     <input type="checkbox" name="show_answers" checked style="width: 18px; height: 18px; accent-color: var(--primary);">
                     <span style="font-size: 0.95rem; color: #333;">Show correct answers immediately after submission?</span>
                 </label>
             </div>
            
            <div style="border-top: 1px solid #eee; padding-top: 25px; display: flex; justify-content: space-between; align-items: center;">
                 <button type="submit" name="save_draft" class="btn btn-secondary" style="padding: 12px 30px; border: 1px solid #ccc; background: white; color: #555;">
                     <i class="fa-regular fa-floppy-disk"></i> Save as Draft
                 </button>
                 
                 <button type="submit" name="publish" class="btn btn-primary" style="padding: 12px 40px; font-size: 1.05rem; box-shadow: 0 4px 12px rgba(0, 184, 148, 0.3);">
                     Save & Add Questions <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
                 </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
