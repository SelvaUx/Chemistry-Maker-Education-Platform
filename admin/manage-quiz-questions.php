<?php
// admin/manage-quiz-questions.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;

// Fetch Quiz Info
$all_quizzes = $pdo->prepare("SELECT * FROM quizzes")->fetchAll();
$quiz = null;
foreach($all_quizzes as $q) { if($q['id'] == $quiz_id) $quiz = $q; }

if(!$quiz) { echo "<div class='container' style='padding:40px;'>Quiz not found.</div>"; exit; }

// Fetch Questions for this Quiz
$all_questions = $pdo->prepare("SELECT * FROM quiz_questions WHERE quiz_id = ?")->fetchAll(); // In real DB, execute([$quiz_id])
// Manual filter for Mock
$questions = [];
foreach($all_questions as $qn) {
    if($qn['quiz_id'] == $quiz_id) $questions[] = $qn;
}
?>

<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h4 style="margin: 0; color: var(--text-light);">Questions for:</h4>
            <h2 style="margin: 0;"><?php echo htmlspecialchars($quiz['title']); ?></h2>
        </div>
        <div class="flex gap-2">
            <a href="quizzes.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Quizzes</a>
            <a href="edit-question.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Question</a>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="10%">Image</th>
                    <th width="50%">Question</th>
                    <th width="15%">Correct Answer</th>
                    <th width="20%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($questions)): ?>
                <tr><td colspan="5" style="text-align: center; color: #888;">No questions added yet.</td></tr>
                <?php else: ?>
                    <?php foreach($questions as $index => $qn): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td>
                            <?php if(!empty($qn['image'])): ?>
                                <img src="../uploads/quiz_images/<?php echo $qn['image']; ?>" style="height: 40px; border-radius: 4px;">
                            <?php else: ?>
                                <span style="color: #ccc;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 500;"><?php echo htmlspecialchars($qn['question']); ?></div>
                            <div style="font-size: 0.85rem; color: #888; margin-top: 5px;">
                                A: <?php echo htmlspecialchars($qn['opt_a']); ?> | B: <?php echo htmlspecialchars($qn['opt_b']); ?>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: #00b894; background: #e1ffe1; padding: 4px 8px; border-radius: 4px;">
                                Option <?php echo strtoupper(substr($qn['correct'], 4)); ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit-question.php?quiz_id=<?php echo $quiz_id; ?>&id=<?php echo $qn['id']; ?>" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.85rem;"><i class="fa-solid fa-pen"></i> Edit</a>
                            <a href="#" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.85rem; background: #ffecec; color: #d63031;"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>
</body>
</html>
