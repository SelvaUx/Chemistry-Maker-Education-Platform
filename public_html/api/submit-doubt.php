<?php
// public_html/api/submit-doubt.php
header('Content-Type: application/json');
require_once '../../config/constants.php';
require_once '../../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$user_id = $_SESSION['user_id'];
$video_id = isset($_POST['video_id']) ? (int)$_POST['video_id'] : 0;
$course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
$question = isset($_POST['question']) ? trim($_POST['question']) : '';

if ($video_id == 0 || empty($question)) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO doubts (user_id, video_id, course_id, question, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([$user_id, $video_id, $course_id, $question]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Question submitted successfully! Our team will respond soon.'
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
