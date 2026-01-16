<?php
// public_html/take-quiz.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Fetch Quiz Info
$all_quizzes = $pdo->prepare("SELECT * FROM quizzes")->fetchAll();
$quiz = null;
foreach($all_quizzes as $q) { if($q['id'] == $id) $quiz = $q; }

if(!$quiz) die("Quiz not found");

// Verify Purchase - prevent direct URL access
$user_id = $_SESSION['user_id'];
$stmt_purchase = $pdo->prepare("SELECT * FROM purchases WHERE user_id = ? AND quiz_id = ? AND access_status = 'active'");
$stmt_purchase->execute([$user_id, $id]);
if ($stmt_purchase->rowCount() == 0) {
    echo "<script>alert('Please purchase this quiz first to access it.'); window.location.href='quiz-details.php?id=$id';</script>";
    exit;
}

// Fetch Questions (Mock: Return all for demo, usually filtered by quiz_id)
$questions = $pdo->prepare("SELECT * FROM quiz_questions")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam: <?php echo htmlspecialchars($quiz['title']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .quiz-container { max-width: 800px; margin: 40px auto; padding: 20px; }
        .question-card { background: white; padding: 25px; border-radius: 12px; box-shadow: var(--shadow-sm); margin-bottom: 20px; border: 1px solid #eee; }
        .question-text { font-size: 1.1rem; font-weight: 600; margin-bottom: 20px; color: var(--dark); }
        .options-grid { display: grid; grid-template-columns: 1fr; gap: 10px; }
        .option-label { display: flex; align-items: center; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
        .option-label:hover { background: #f9f9f9; border-color: var(--primary); }
        .option-label input { margin-right: 15px; accent-color: var(--primary); transform: scale(1.2); }
        .timer-badge { position: fixed; top: 90px; right: 20px; background: var(--dark); color: white; padding: 10px 20px; border-radius: 50px; font-weight: 700; box-shadow: var(--shadow-md); z-index: 100; }
    </style>
</head>
<body style="background: #f4f6f8;">

<div class="timer-badge"><i class="fa-regular fa-clock"></i> <span id="time">59:59</span></div>

<header class="main-header" style="position: sticky;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; height: 100%;">
        <span class="logo" style="font-size: 1.2rem;">Chemistry Maker Exam Portal</span>
    </div>
</header>

<div class="quiz-container">
    <h2 style="margin-bottom: 20px;"><?php echo htmlspecialchars($quiz['title']); ?></h2>
    
    <form action="quiz-result.php" method="POST">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
        
        <?php foreach($questions as $index => $q): ?>
            <div class="question-card">
                <?php if(!empty($q['image'])): ?>
                    <img src="uploads/quiz_images/<?php echo htmlspecialchars($q['image']); ?>" style="max-width: 100%; max-height: 250px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #eee;">
                <?php endif; ?>
                <div class="question-text"><?php echo ($index + 1) . ". " . htmlspecialchars($q['question']); ?></div>
                <div class="options-grid">
                    <label class="option-label">
                        <input type="radio" name="q<?php echo $q['id']; ?>" value="opt_a" required> 
                        <?php echo htmlspecialchars($q['opt_a']); ?>
                    </label>
                    <label class="option-label">
                        <input type="radio" name="q<?php echo $q['id']; ?>" value="opt_b"> 
                        <?php echo htmlspecialchars($q['opt_b']); ?>
                    </label>
                    <label class="option-label">
                        <input type="radio" name="q<?php echo $q['id']; ?>" value="opt_c"> 
                        <?php echo htmlspecialchars($q['opt_c']); ?>
                    </label>
                    <label class="option-label">
                        <input type="radio" name="q<?php echo $q['id']; ?>" value="opt_d"> 
                        <?php echo htmlspecialchars($q['opt_d']); ?>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">Submit Test</button>
    </form>
</div>

</body>
</html>
