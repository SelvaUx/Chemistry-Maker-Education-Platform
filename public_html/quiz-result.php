<?php
// public_html/quiz-result.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$quiz_id = isset($_POST['quiz_id']) ? (int)$_POST['quiz_id'] : 0;
// Fetch Questions (Mock - normally fetch by ID)
$questions = $pdo->prepare("SELECT * FROM quiz_questions")->fetchAll();
// Fetch Quiz info
$quiz = ['title' => 'Chemistry Mock Test']; // Fallback mock

$score = 0;
$total = count($questions);
$results = [];

foreach($questions as $q) {
    $user_ans = isset($_POST['q' . $q['id']]) ? $_POST['q' . $q['id']] : null;
    $is_correct = ($user_ans === $q['correct']);
    if ($is_correct) $score++;
    
    $results[] = [
        'question' => $q,
        'user_ans' => $user_ans,
        'is_correct' => $is_correct
    ];
}

$percentage = ($score / $total) * 100;
$pageTitle = "Quiz Result";
require_once 'includes/header.php';
?>

<div class="container section-padding">
    <div style="max-width: 800px; margin: 0 auto;">
        
        <!-- Score Card -->
        <div style="background: white; border-radius: 15px; padding: 40px; text-align: center; box-shadow: var(--shadow-md); margin-bottom: 40px;">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Trophy" style="width: 100px; margin-bottom: 20px;">
            <h2 style="margin-bottom: 10px;">Test Completed!</h2>
            <p style="color: var(--text-light); font-size: 1.1rem; margin-bottom: 30px;">Here is your performance report.</p>
            
            <div style="display: flex; justify-content: center; gap: 40px; margin-bottom: 30px;">
                <div>
                    <h1 class="text-primary" style="font-size: 3.5rem; margin-bottom: 0;"><?php echo $score; ?>/<span style="font-size: 2rem; color: #999;"><?php echo $total; ?></span></h1>
                    <p style="font-weight: 600; color: var(--text-light);">YOUR SCORE</p>
                </div>
                <div>
                    <h1 style="font-size: 3.5rem; margin-bottom: 0; color: <?php echo $percentage >= 50 ? '#00b894' : '#e74c3c'; ?>;"><?php echo round($percentage); ?>%</h1>
                    <p style="font-weight: 600; color: var(--text-light);">PERCENTAGE</p>
                </div>
            </div>
            
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
        
        <!-- Detailed Analysis -->
        <h3 style="margin-bottom: 20px;">Detailed Analysis</h3>
        
        <?php foreach($results as $index => $res): 
            $q = $res['question'];
            $status_color = $res['is_correct'] ? '#00b894' : '#e74c3c';
            $status_icon = $res['is_correct'] ? 'fa-circle-check' : 'fa-circle-xmark';
        ?>
        <div style="background: white; border-radius: 10px; padding: 25px; margin-bottom: 20px; border-left: 5px solid <?php echo $status_color; ?>; box-shadow: var(--shadow-sm);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <h5 style="margin: 0; font-size: 1.1rem;">
                    <span style="color: var(--text-light); margin-right: 10px;">Q<?php echo $index + 1; ?>.</span> 
                    <?php echo htmlspecialchars($q['question']); ?>
                </h5>
                <i class="fa-solid <?php echo $status_icon; ?>" style="color: <?php echo $status_color; ?>; font-size: 1.5rem;"></i>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <!-- Options Logic Display -->
                <?php 
                $options = ['opt_a' => 'opt_a', 'opt_b' => 'opt_b', 'opt_c' => 'opt_c', 'opt_d' => 'opt_d'];
                foreach($options as $key => $val): 
                    $is_selected = ($res['user_ans'] === $val);
                    $is_real_correct = ($q['correct'] === $val);
                    
                    $bg_color = '#f8f9fa';
                    $border_color = '#eee';
                    $text_color = 'var(--text-main)';
                    
                    if ($is_real_correct) {
                        $bg_color = '#e1ffe1';
                        $border_color = '#00b894';
                        $text_color = '#006652';
                    } elseif ($is_selected && !$res['is_correct']) {
                        $bg_color = '#ffecec'; // Wrongly selected
                        $border_color = '#e74c3c';
                        $text_color = '#c0392b';
                    }
                ?>
                <div style="padding: 10px 15px; background: <?php echo $bg_color; ?>; border: 1px solid <?php echo $border_color; ?>; border-radius: 6px; color: <?php echo $text_color; ?>; display: flex; justify-content: space-between; align-items: center;">
                    <span><?php echo htmlspecialchars($q[$key]); ?></span>
                    <?php if($is_real_correct): ?>
                        <i class="fa-solid fa-check" style="color: #00b894;"></i>
                    <?php elseif($is_selected): ?>
                        <i class="fa-solid fa-xmark" style="color: #e74c3c;"></i>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
