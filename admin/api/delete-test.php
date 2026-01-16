<?php
// admin/api/delete-test.php
header('Content-Type: application/json');
require_once '../../config/constants.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$test_id = isset($_POST['test_id']) ? (int)$_POST['test_id'] : 0;

if ($test_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid test ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM tests WHERE id = ?");
    $stmt->execute([$test_id]);
    
    echo json_encode(['success' => true, 'message' => 'Test deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
