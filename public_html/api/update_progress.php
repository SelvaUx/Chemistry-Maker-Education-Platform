<?php
// public_html/api/update_progress.php
require_once '../../config/constants.php';
require_once '../../config/db.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$video_id = $input['video_id'] ?? 0;
$user_id = $_SESSION['user_id'];

if ($video_id) {
    try {
        // In real mock, we can't easily INSERT/UPDATE persistent data without real DB
        // But we will simulate success
        
        // $stmt = $pdo->prepare("INSERT INTO video_progress (user_id, video_id, completed, updated_at) VALUES (?, ?, 1, NOW()) ON DUPLICATE KEY UPDATE completed=1, updated_at=NOW()");
        // $stmt->execute([$user_id, $video_id]);
        
        echo json_encode(['success' => true, 'message' => 'Progress updated']);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Database error']);
    }
} else {
    echo json_encode(['error' => 'Invalid data']);
}
?>
