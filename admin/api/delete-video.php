<?php
// admin/api/delete-video.php
header('Content-Type: application/json');
require_once '../../config/constants.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$video_id = isset($_POST['video_id']) ? (int)$_POST['video_id'] : 0;

if ($video_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid video ID']);
    exit;
}

try {
    // Optional: Delete associated file if it's an uploaded video
    $stmt = $pdo->prepare("SELECT video_type, video_url FROM videos WHERE id = ?");
    $stmt->execute([$video_id]);
    $video = $stmt->fetch();
    
    if ($video && $video['video_type'] == 'upload') {
        $file_path = '../public_html/uploads/videos/course_folders/' . $video['video_url'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->execute([$video_id]);
    
    echo json_encode(['success' => true, 'message' => 'Video deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
