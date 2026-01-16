<?php
// public_html/quiz-details.php
require_once '../config/constants.php';
require_once '../config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Fetch Quiz (Mock)
// MockPDO fetchAll returns all array, we filter manually for demo
$all_quizzes = $pdo->prepare("SELECT * FROM quizzes")->fetchAll();
$quiz = null;
foreach($all_quizzes as $q) { if($q['id'] == $id) $quiz = $q; }

if (!$quiz) { echo "Quiz not found."; exit; }

// Check if user has purchased this quiz
$has_purchased = false;
if (isset($_SESSION['user_id'])) {
    $stmt_purchase = $pdo->prepare("SELECT * FROM purchases WHERE user_id = ? AND quiz_id = ? AND access_status = 'active'");
    $stmt_purchase->execute([$_SESSION['user_id'], $id]);
    $has_purchased = $stmt_purchase->rowCount() > 0;
}

$pageTitle = $quiz['title'];
require_once 'includes/header.php';
?>

<div class="container section-padding">
    <div style="background: var(--bg-card); border-radius: var(--radius-lg); padding: 40px; box-shadow: var(--shadow-sm); display: flex; flex-wrap: wrap; gap: 40px; align-items: center;">
        <div style="flex: 1; min-width: 300px;">
            <h1 class="text-primary" style="margin-bottom: 10px; font-size: 2.2rem;"><?php echo htmlspecialchars($quiz['title']); ?></h1>
            <p style="font-size: 1.1rem; color: var(--text-light); margin-bottom: 30px;"><?php echo htmlspecialchars($quiz['description']); ?></p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                     <div style="background: var(--bg-input); padding: 15px; border-radius: 8px;">
                         <small style="color: var(--text-light);">Duration</small>
                         <h4 style="margin: 0; color: var(--text-main);"><?php echo $quiz['time_limit']; ?> Minutes</h4>
                     </div>
                     <div style="background: var(--bg-input); padding: 15px; border-radius: 8px;">
                         <small style="color: var(--text-light);">Format</small>
                         <h4 style="margin: 0; color: var(--text-main);">MCQ (Online)</h4>
                     </div>
                 </div>
                 
                 <h3 style="margin-bottom: 20px;">Price: <span style="color: var(--secondary);">₹<?php echo $quiz['price']; ?></span></h3>
                 
                 <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if ($has_purchased): ?>
                        <!-- User has purchased - allow access -->
                        <a href="take-quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-primary" style="padding: 15px 40px;">Start Quiz</a>
                    <p style="margin-top: 10px; font-size: 0.85rem; color: var(--text-light);">(In real version, this goes to Payment first)</p>
                    <?php else: ?>
                        <!-- User has NOT purchased - show buy button -->
                        <a href="payments/checkout-quiz.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-primary" style="padding: 15px 40px;">Buy Now - ₹<?php echo $quiz['price']; ?></a>
                        <p style="margin-top: 15px; color: var(--text-light); font-size: 0.9rem;"><i class="fa-solid fa-lock"></i> Purchase required to access this quiz</p>
                    <?php endif; ?>
                 <?php else: ?>
                    <a href="login.php?redirect=quiz-details.php?id=<?php echo $quiz['id']; ?>" class="btn btn-secondary" style="padding: 15px 40px;">Login to Buy</a>
                 <?php endif; ?>
            </div>
            <div style="flex: 1; display: flex; align-items: center; justify-content: center; background: var(--bg-input); border-radius: 12px; min-height: 300px;">
                <i class="fa-solid fa-laptop-code fa-6x" style="color: var(--text-light); opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
