<?php
// admin/quizzes.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

// Handle Duplicate
if (isset($_GET['duplicate'])) {
    // Mock Duplicate Logic
    // In real app: Fetch Quiz -> Insert Copy -> Fetch Questions -> Insert Copies
    echo "<script>alert('Quiz Duplicated Automatically! (Mock)'); window.location.href='quizzes.php';</script>";
    exit;
}

// Mock Stats Calculation Helper
function getQuizStats($pdo, $quiz_id) {
    // In real app, COUNT(*) from quiz_questions and test_attempts
    // Mocking random realistic numbers
    return [
        'questions' => rand(15, 50),
        'attempts' => rand(10, 500),
        'revenue' => rand(1000, 50000)
    ];
}

$quizzes = $pdo->prepare("SELECT * FROM quizzes ORDER BY created_at DESC")->fetchAll();
?>

<div style="max-width: 1200px; margin: 0 auto;">
    <div class="flex justify-between align-center" style="margin-bottom: 30px;">
        <div>
            <h2 style="margin: 0;">Paid Test Series</h2>
            <p style="color: #666; margin-top: 5px;">Manage your premium exams, analyze revenue, and track student performance.</p>
        </div>
        <a href="add-quiz.php" class="btn btn-primary" style="padding: 12px 25px; box-shadow: 0 4px 10px rgba(0, 184, 148, 0.3);">
            <i class="fa-solid fa-plus"></i> Create New Test
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="25%">Test Details</th>
                    <th width="25%">Business Stats</th>
                    <th width="10%">Price</th>
                    <th width="15%">Status</th>
                    <th width="20%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($quizzes as $q): 
                    $stats = getQuizStats($pdo, $q['id']);
                ?>
                <tr>
                    <td style="font-weight: bold; color: #888;">#<?php echo $q['id']; ?></td>
                    <td>
                        <div style="font-weight: 600; font-size: 1.05rem; color: var(--text-main); margin-bottom: 4px;">
                            <?php echo htmlspecialchars($q['title']); ?>
                        </div>
                        <div style="font-size: 0.85rem; color: #888;">
                            <i class="fa-regular fa-clock"></i> <?php echo $q['time_limit']; ?> mins • 
                            <i class="fa-regular fa-calendar"></i> <?php echo date('M d, Y', strtotime($q['created_at'] ?? 'now')); ?>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 15px; font-size: 0.9rem;">
                            <div title="Total Questions">
                                <span style="display: block; font-weight: 700; color: #333;"><?php echo $stats['questions']; ?> Q</span>
                                <span style="font-size: 0.75rem; color: #888;">Questions</span>
                            </div>
                            <div style="width: 1px; background: #eee;"></div>
                            <div title="Total Student Attempts">
                                <span style="display: block; font-weight: 700; color: #333;"><?php echo $stats['attempts']; ?></span>
                                <span style="font-size: 0.75rem; color: #888;">Attempts</span>
                            </div>
                            <div style="width: 1px; background: #eee;"></div>
                            <div title="Estimated Revenue">
                                <span style="display: block; font-weight: 700; color: #00b894;">₹<?php echo number_format($stats['revenue']); ?></span>
                                <span style="font-size: 0.75rem; color: #888;">Revenue</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if($q['price'] > 0): ?>
                            <span style="font-weight: 700; color: var(--dark);">₹<?php echo number_format($q['price'], 2); ?></span>
                        <?php else: ?>
                            <span style="background: #e1ffe1; color: #00b894; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem;">FREE</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <select onchange="alert('Status updated to: ' + this.value)" style="padding: 6px 10px; border-radius: 20px; border: 1px solid #ddd; font-size: 0.85rem; font-weight: 600; cursor: pointer; background: white;">
                            <option value="published" <?php echo ($q['status']??'') == 'published' ? 'selected' : ''; ?>>🟢 Published</option>
                            <option value="draft" <?php echo ($q['status']??'') == 'draft' ? 'selected' : ''; ?>>🟡 Draft</option>
                            <option value="archived" <?php echo ($q['status']??'') == 'archived' ? 'selected' : ''; ?>>🔒 Closed</option>
                        </select>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="quiz-builder.php?id=<?php echo $q['id']; ?>&tab=build" class="btn btn-primary" style="padding: 8px 12px; font-size: 0.9rem;" title="Manage Questions">
                                <i class="fa-solid fa-list-check"></i>
                            </a>
                            <a href="quiz-builder.php?id=<?php echo $q['id']; ?>&tab=settings" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9rem;" title="Edit Settings">
                                <i class="fa-solid fa-gear"></i>
                            </a>
                            <a href="../take-quiz.php?id=<?php echo $q['id']; ?>" target="_blank" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9rem;" title="Student Preview">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                             <a href="?duplicate=<?php echo $q['id']; ?>" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9rem;" title="Duplicate" onclick="return confirm('Duplicate this quiz?');">
                                <i class="fa-regular fa-copy"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
</div>
</div>
</body>
</html>
