<?php
// admin/api/delete-resource.php
header('Content-Type: application/json');
require_once '../../config/constants.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$resource_id = isset($_POST['resource_id']) ? (int)$_POST['resource_id'] : 0;

if ($resource_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid resource ID']);
    exit;
}

try {
    // Get file path to delete physical file
    $stmt = $pdo->prepare("SELECT file_path FROM resources WHERE id = ?");
    $stmt->execute([$resource_id]);
    $resource = $stmt->fetch();
    
    if ($resource) {
        $file_path = '../public_html/uploads/resources/' . $resource['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM resources WHERE id = ?");
    $stmt->execute([$resource_id]);
    
    echo json_encode(['success' => true, 'message' => 'Resource deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
