<?php
// admin/api/update-chapter.php
header('Content-Type: application/json');
require_once '../../config/constants.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$chapter_id = isset($_POST['chapter_id']) ? (int)$_POST['chapter_id'] : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';

if ($chapter_id == 0 || empty($title)) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE modules SET title = ? WHERE id = ?");
    $stmt->execute([$title, $chapter_id]);
    
    echo json_encode(['success' => true, 'message' => 'Chapter updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
