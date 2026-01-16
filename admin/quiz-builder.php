<?php
// admin/quiz-builder.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$quiz_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'build'; // 'build' or 'settings' or 'results'
$edit_id = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;

// Fetch Quiz
$all_quizzes = $pdo->prepare("SELECT * FROM quizzes")->fetchAll();
$quiz = null;
foreach($all_quizzes as $q) { if($q['id'] == $quiz_id) $quiz = $q; }
if(!$quiz) die("Quiz not found");

// Fetch Questions (sorted by position)
$all_questions = $pdo->prepare("SELECT * FROM quiz_questions")->fetchAll();
$questions = [];
foreach($all_questions as $q) { 
    if($q['quiz_id'] == $quiz_id) $questions[] = $q; // In real DB: ORDER BY position ASC
}

// Handle Form Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add_question') {
        // Mock Insert
        // $new_id = $pdo->lastInsertId(); 
        $new_id = time(); // Fake ID
        header("Location: quiz-builder.php?id=$quiz_id&tab=build&edit_id=$new_id#end");
        exit;
    }
    // Handle other actions (save, delete, etc) -> Redirect to same page
}
?>

<style>
    /* Premium Layout Overrides */
    body { background-color: #f3f4f6; }
    
    .builder-layout {
        display: grid;
        grid-template-columns: 1fr 300px; /* Main Content | Sidebar */
        gap: 30px;
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
        align-items: start;
    }

    /* Left Column: Questions */
    .question-stack { display: flex; flex-direction: column; gap: 20px; }
    
    .q-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }
    .q-card:hover { border-color: #d1d5db; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .q-card.active { border: 2px solid var(--primary); box-shadow: 0 0 0 4px rgba(0, 184, 148, 0.1); }
    
    .q-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 15px 20px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    .q-number { font-weight: 700; color: #6b7280; font-size: 0.9rem; }
    .q-badges { display: flex; gap: 10px; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; background: #e5e7eb; color: #374151; }
    .badge-mcq { background: #e0f2fe; color: #0369a1; }
    .badge-num { background: #fef3c7; color: #92400e; }
    
    .q-body { padding: 25px; }
    .q-preview-text { font-size: 1.1rem; font-weight: 500; color: #1f2937; margin-bottom: 15px; }
    
    /* Right Column: Toolkit (Sticky) */
    .toolkit-panel {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        padding: 20px;
        position: sticky;
        top: 20px;
        border-top: 5px solid var(--primary);
    }
    
    .tool-btn {
        display: flex; align-items: center; gap: 12px;
        width: 100%; padding: 12px 15px;
        border: 1px solid #eee; border-radius: 8px;
        background: white; color: #4b5563;
        font-weight: 600; font-size: 0.95rem;
        cursor: pointer; transition: all 0.2s;
        margin-bottom: 10px; text-align: left;
    }
    .tool-btn:hover { background: #f9fafb; border-color: #d1d5db; color: var(--primary); transform: translateX(5px); }
    .tool-btn i { width: 24px; text-align: center; }
    .tool-btn.primary-tool { background: var(--primary); color: white; border-color: var(--primary); justify-content: center; }
    .tool-btn.primary-tool:hover { background: #00a383; transform: translateY(-2px); }
    
    /* Header Tabs Override */
    .cw-tabs {
        display: flex; gap: 5px; background: white; padding: 5px; border-radius: 8px; display: inline-flex; border: 1px solid #e5e7eb;
    }
    .cw-tab { padding: 8px 16px; border-radius: 6px; color: #6b7280; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: 0.2s; }
    .cw-tab:hover { background: #f3f4f6; }
    .cw-tab.active { background: var(--primary); color: white; }

    /* Edit Form Styles */
    .edit-form label { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; color: #9ca3af; margin-bottom: 5px; display: block; }
    .edit-input { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: 0.2s; }
    .edit-input:focus { border-color: var(--primary); outline: none; }
</style>

<div style="background: white; border-bottom: 1px solid #e5e7eb; padding: 15px 0;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <a href="quizzes.php" class="btn btn-secondary" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h2 style="margin: 0; font-size: 1.5rem;"><?php echo htmlspecialchars($quiz['title']); ?></h2>
                <div style="font-size: 0.85rem; color: #6b7280;">Last saved: Just now</div>
            </div>
        </div>
        
        <div class="cw-tabs">
            <a href="?id=<?php echo $quiz_id; ?>&tab=build" class="cw-tab <?php echo $tab=='build'?'active':''; ?>">Builder</a>
            <a href="?id=<?php echo $quiz_id; ?>&tab=settings" class="cw-tab <?php echo $tab=='settings'?'active':''; ?>">Settings</a>
            <a href="?id=<?php echo $quiz_id; ?>&tab=results" class="cw-tab <?php echo $tab=='results'?'active':''; ?>">Results</a>
        </div>
        
        <a href="../take-quiz.php?id=<?php echo $quiz_id; ?>" target="_blank" class="btn btn-secondary" style="color: var(--primary); border: 1px solid var(--primary); background: #f0fdf9;">
            <i class="fa-regular fa-eye"></i> Preview
        </a>
    </div>
</div>

<?php if($tab == 'settings'): ?>
<div class="container" style="max-width: 800px; margin-top: 40px;">
    <div class="q-card" style="padding: 40px;">
        <h3 style="margin-bottom: 30px;">Quiz Configuration</h3>
        <form method="POST">
             <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                     <label style="font-weight: 600; display: block; margin-bottom: 10px;">Duration (Minutes)</label>
                     <input type="number" value="<?php echo $quiz['time_limit']; ?>" class="edit-input">
                </div>
                 <div>
                     <label style="font-weight: 600; display: block; margin-bottom: 10px;">Total Attempts Allowed</label>
                     <input type="number" value="1" class="edit-input">
                </div>
                 <div>
                     <label style="font-weight: 600; display: block; margin-bottom: 10px;">Price (₹)</label>
                     <input type="number" value="<?php echo $quiz['price']; ?>" class="edit-input">
                </div>
                <div>
                     <label style="font-weight: 600; display: block; margin-bottom: 10px;">Negative Marking</label>
                     <select class="edit-input">
                         <option>No Negative Marking</option>
                         <option>-0.25 Marks</option>
                         <option>-0.50 Marks</option>
                         <option>-1.00 Marks</option>
                     </select>
                </div>
             </div>
             
             <div style="border-top: 1px solid #eee; margin-top: 30px; padding-top: 20px; text-align: right;">
                 <button class="btn btn-primary">Save Changes</button>
             </div>
        </form>
    </div>
</div>

<?php else: ?>

<div class="builder-layout">
    <!-- Main Column: Question Stack -->
    <div class="question-stack">
        
        <?php if(empty($questions)): ?>
            <div style="text-align: center; padding: 60px; color: #9ca3af; border: 2px dashed #e5e7eb; border-radius: 12px;">
                <i class="fa-regular fa-clipboard" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.5;"></i>
                <h3>No questions yet</h3>
                <p>Use the toolkit to add your first question.</p>
            </div>
        <?php endif; ?>

        <?php foreach($questions as $index => $q): 
              $is_editing = ($edit_id == $q['id']);
        ?>
        <div class="q-card <?php echo $is_editing ? 'active' : ''; ?>" id="q<?php echo $q['id']; ?>">
            <!-- Header for Every Card -->
            <div class="q-header">
                <div class="flex align-center gap-2">
                    <span class="q-number">Q<?php echo $index + 1; ?></span>
                    <span class="badge badge-mcq"><?php echo isset($q['type']) ? strtoupper($q['type']) : 'MCQ'; ?></span>
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;" title="Move Up"><i class="fa-solid fa-arrow-up"></i></button>
                    <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;" title="Move Down"><i class="fa-solid fa-arrow-down"></i></button>
                    <div style="width: 1px; height: 20px; background: #ddd; margin: 0 5px;"></div>
                    <?php if(!$is_editing): ?>
                        <a href="?id=<?php echo $quiz_id; ?>&tab=build&edit_id=<?php echo $q['id']; ?>#q<?php echo $q['id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; color: #4b5563;"><i class="fa-solid fa-pen"></i></a>
                    <?php endif; ?>
                    <button class="btn btn-secondary" style="padding: 5px 10px; color: #ef4444; background: #fef2f2;"><i class="fa-regular fa-trash-can"></i></button>
                </div>
            </div>

            <div class="q-body">
                <?php if($is_editing): ?>
                    <!-- EDIT MODE -->
                    <form method="POST" enctype="multipart/form-data" class="edit-form">
                        <input type="hidden" name="action" value="save_question">
                        
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label>Question Type</label>
                                <select name="type" class="edit-input" style="padding: 10px;">
                                    <option value="mcq" <?php echo ($q['type']??'')=='mcq'?'selected':''; ?>>Multiple Choice</option>
                                    <option value="numerical" <?php echo ($q['type']??'')=='numerical'?'selected':''; ?>>Numerical Value</option>
                                    <option value="short" <?php echo ($q['type']??'')=='short'?'selected':''; ?>>Short Answer</option>
                                </select>
                            </div>
                            <div>
                                <label>Marks</label>
                                <input type="number" value="4" class="edit-input" style="padding: 10px;">
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label>Question Text</label>
                            <textarea name="question" rows="3" class="edit-input"><?php echo htmlspecialchars($q['question']); ?></textarea>
                        </div>

                        <!-- Image Logic -->
                        <div style="margin-bottom: 20px; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px dashed #d1d5db;">
                            <label style="margin-bottom: 10px;">Question Image</label>
                            <?php if($q['image']): ?>
                                <div style="display: flex; gap: 15px; align-items: center;">
                                    <img src="../public_html/uploads/quiz_images/<?php echo $q['image']; ?>" style="height: 60px; border-radius: 4px;">
                                    <button type="button" class="btn btn-secondary" style="font-size: 0.8rem;">Remove</button>
                                </div>
                            <?php else: ?>
                                <input type="file" style="font-size: 0.9rem;">
                            <?php endif; ?>
                        </div>

                        <!-- Options Logic -->
                        <div style="margin-bottom: 30px;">
                            <label>Options & Correct Answer</label>
                            <?php foreach(['opt_a', 'opt_b', 'opt_c', 'opt_d'] as $k): ?>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <input type="radio" name="correct" value="<?php echo $k; ?>" <?php echo $q['correct']==$k?'checked':''; ?> style="width: 20px; height: 20px; accent-color: var(--primary);">
                                <input type="text" name="<?php echo $k; ?>" value="<?php echo htmlspecialchars($q[$k] ?? ''); ?>" class="edit-input" style="padding: 10px;" placeholder="Option Value">
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div style="text-align: right; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-primary" style="padding: 10px 30px;">Save Question</button>
                        </div>
                    </form>

                <?php else: ?>
                    <!-- VIEW MODE -->
                    <div class="q-preview-text"><?php echo htmlspecialchars($q['question']); ?></div>
                     <?php if($q['image']): ?>
                        <img src="../public_html/uploads/quiz_images/<?php echo $q['image']; ?>" style="max-height: 100px; margin-bottom: 15px; border-radius: 4px;">
                     <?php endif; ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <?php foreach(['opt_a', 'opt_b', 'opt_c', 'opt_d'] as $k): ?>
                             <div style="padding: 10px 15px; background: <?php echo $q['correct']==$k ? '#ecfdf5' : '#f9fafb'; ?>; border: 1px solid <?php echo $q['correct']==$k ? '#10b981' : '#f3f4f6'; ?>; border-radius: 6px; color: <?php echo $q['correct']==$k ? '#047857' : '#4b5563'; ?>;">
                                 <span style="font-weight: 600; margin-right: 5px;"><?php echo strtoupper(substr($k, 4)); ?>.</span> <?php echo htmlspecialchars($q[$k] ?? ''); ?>
                                 <?php if($q['correct']==$k): ?> <i class="fa-solid fa-check float-right"></i> <?php endif; ?>
                             </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Bottom Add (Backup) -->
        <form method="POST" style="text-align: center; margin-top: 20px; border: 2px dashed #ddd; padding: 20px; border-radius: 12px; cursor: pointer; transition: 0.2s;" hover="this.style.borderColor='var(--primary)'">
            <input type="hidden" name="action" value="add_question">
            <button type="submit" style="background: none; border: none; font-size: 1.1rem; color: #6b7280; font-weight: 600; cursor: pointer;">
                <i class="fa-solid fa-plus-circle" style="color: var(--primary);"></i> Add New Question Here
            </button>
        </form>

    </div>

    <!-- Right Column: Toolkit -->
    <div class="toolkit-panel">
        <h4 style="margin-top: 0; margin-bottom: 20px; color: #1f2937;">Toolbox</h4>
        
        <form method="POST">
             <input type="hidden" name="action" value="add_question">
             <button type="submit" class="tool-btn primary-tool">
                <i class="fa-solid fa-plus"></i> Add Question
             </button>
        </form>

        <button class="tool-btn">
            <i class="fa-regular fa-image" style="color: #8b5cf6;"></i> Add Image Item
        </button>
        
        <button class="tool-btn">
            <i class="fa-solid fa-heading" style="color: #eab308;"></i> Add Text Block
        </button>
        
        <div style="width: 100%; height: 1px; background: #e5e7eb; margin: 15px 0;"></div>
        
        <div style="margin-bottom: 10px; font-weight: 600; font-size: 0.85rem; color: #9ca3af;">BULK ACTIONS</div>
        <button class="tool-btn">
            <i class="fa-solid fa-file-import"></i> Import Questions
        </button>
        <button class="tool-btn">
            <i class="fa-solid fa-download"></i> Export PDF
        </button>
    </div>
</div>
<?php endif; ?>

</div>
</div>
</body>
</html>
