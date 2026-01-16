<?php
// public_html/quizzes.php
require_once '../config/constants.php';
require_once '../config/db.php';
$pageTitle = "Test Series";
require_once 'includes/header.php';

// Fetch Quizzes (Mock)
$quizzes = $pdo->prepare("SELECT * FROM quizzes")->fetchAll();
?>

<div class="container section-padding">
    <div class="text-center" style="margin-bottom: 50px;">
        <h1 style="color: var(--primary);">Premium Test Series</h1>
        <p style="color: var(--text-light); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Practice with our high-quality paid quizzes to assess your preparation level.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        <?php foreach($quizzes as $quiz): ?>
            <div class="course-card" style="background: var(--white); border-radius: 12px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: all 0.3s ease;">
                <div style="height: 140px; background: linear-gradient(135deg, #a55eea, #4b7bec); display: flex; align-items: center; justify-content: center; color: white;">
                    <i class="fa-solid fa-list-check fa-3x"></i>
                </div>
                <div style="padding: 25px;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 10px;"><?php echo htmlspecialchars($quiz['title']); ?></h3>
                    <p style="color: #666; font-size: 0.95rem; margin-bottom: 20px; line-height: 1.5;"><?php echo htmlspecialchars($quiz['description']); ?></p>
                    
                    <div class="flex justify-between align-center" style="margin-bottom: 20px; font-size: 0.9rem; color: #888;">
                        <span><i class="fa-regular fa-clock"></i> <?php echo $quiz['time_limit']; ?> Mins</span>
                        <span style="color: var(--primary); font-weight: 700; font-size: 1.2rem;">₹<?php echo $quiz['price']; ?></span>
                    </div>

                    <a href="quiz-details.php?id=<?php echo $quiz['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center;">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
