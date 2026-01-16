<?php
// admin/api/update-quiz-status.php
header('Content-Type: application/json');
require_once '../../config/constants.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$quiz_id = isset($_POST['quiz_id']) ? (int)$_POST['quiz_id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

if ($quiz_id == 0 || empty($status)) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

// Validate status
$valid_statuses = ['published', 'draft', 'archived'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE quizzes SET status = ? WHERE id = ?");
    $stmt->execute([$status, $quiz_id]);
    
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
