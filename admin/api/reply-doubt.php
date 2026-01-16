<?php
// admin/api/reply-doubt.php
header('Content-Type: application/json');
require_once '../../config/constants.php';
require_once '../../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$doubt_id = isset($_POST['doubt_id']) ? (int)$_POST['doubt_id'] : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$mark_resolved = isset($_POST['mark_resolved']) ? (int)$_POST['mark_resolved'] : 0;

if ($doubt_id == 0 || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

try {
    // Insert reply
    $stmt = $pdo->prepare("INSERT INTO doubt_replies (doubt_id, user_id, user_type, message, created_at) VALUES (?, ?, 'admin', ?, NOW())");
    $stmt->execute([$doubt_id, $_SESSION['admin_id'], $message]);
    
    // Update doubt status if requested
    if ($mark_resolved) {
        $stmt = $pdo->prepare("UPDATE doubts SET status = 'resolved' WHERE id = ?");
        $stmt->execute([$doubt_id]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Reply sent successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
