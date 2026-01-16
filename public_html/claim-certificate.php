<?php
// public_html/claim-certificate.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
// Fetch Course name
$course = $pdo->prepare("SELECT * FROM courses WHERE id = ?")->execute([$course_id]); // Mock fetch logic need fix
$all_courses = $pdo->prepare("SELECT * FROM courses")->fetchAll();
$course = null;
foreach($all_courses as $c) { if($c['id'] == $course_id) $course = $c; }

if (!$course) { echo "Course not found"; exit; }

$pageTitle = "Claim Certificate";
require_once 'includes/header.php';
?>

<div class="container section-padding">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: var(--shadow-md);">
        <div class="text-center" style="margin-bottom: 30px;">
            <i class="fa-solid fa-award fa-4x" style="color: #f1c40f; margin-bottom: 20px;"></i>
            <h1 style="font-size: 2rem;">Claim Your Certificate</h1>
            <p style="color: #666;">For: <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
        </div>

        <form method="POST" action="download-certificate.php" target="_blank">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">Name on Certificate</label>
                <input type="text" name="student_name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1.1rem;">
                <small style="color: #888; display: block; margin-top: 5px;">Double check spelling. This cannot be changed later.</small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">
                <i class="fa-solid fa-download"></i> Generate & Download
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
