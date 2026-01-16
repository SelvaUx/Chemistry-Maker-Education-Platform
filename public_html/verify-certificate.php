<?php
// public_html/verify-certificate.php
require_once '../config/constants.php';
require_once '../config/db.php';
$pageTitle = "Verify Certificate";
require_once 'includes/header.php';

$code = isset($_GET['code']) ? trim($_GET['code']) : '';
$certificate = null;
$error = '';

if ($code) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM certificates WHERE certificate_code = ?");
        $stmt->execute([$code]);
        $certificate = $stmt->fetch();
        
        if (!$certificate) {
            $error = "Invalid Certificate Code. No record found.";
        } else {
             // Get Course Name for display
             $stmt2 = $pdo->prepare("SELECT title FROM courses WHERE id = ?");
             $stmt2->execute([$certificate['course_id']]);
             $course = $stmt2->fetch();
             $certificate['course_name'] = $course ? $course['title'] : 'Unknown Course';
        }
    } catch (Exception $e) {
        $error = "Verification Service Unavailable.";
    }
}
?>

<div class="container section-padding">
    <div style="max-width: 600px; margin: 0 auto; text-align: center;">
        <h1 style="color: var(--primary); margin-bottom: 20px;">Verify Certificate</h1>
        <p style="margin-bottom: 40px; color: var(--text-light);">Enter the unique certificate ID found on the bottom of the certificate.</p>
        
        <form method="GET" action="" style="display: flex; gap: 10px; margin-bottom: 50px;">
            <input type="text" name="code" value="<?php echo htmlspecialchars($code); ?>" placeholder="e.g. CERT-DEMO-1234" required style="flex: 1; padding: 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 1.1rem; text-transform: uppercase;">
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
        
        <?php if($error): ?>
            <div style="padding: 20px; background: rgba(214, 48, 49, 0.1); color: #e17055; border-radius: 8px; border: 1px solid rgba(214, 48, 49, 0.2);">
                <i class="fa-solid fa-circle-xmark fa-2x" style="margin-bottom: 10px;"></i>
                <h3>Invalid Certificate</h3>
                <p><?php echo $error; ?></p>
            </div>
            <a href="index.php" class="btn btn-secondary" style="margin-top: 20px;">Go Home</a>
        <?php elseif($certificate): ?>
            <div style="padding: 40px; background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-lg); border-top: 5px solid #00b894;">
                <i class="fa-solid fa-circle-check fa-4x" style="color: #00b894; margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 10px; color: var(--text-main);">Verified Certificate</h2>
                <p style="color: var(--text-light); margin-bottom: 30px;">This certificate is valid and was issued by <?php echo SITE_NAME; ?>.</p>
                
                <div style="text-align: left; background: var(--bg-input); padding: 20px; border-radius: 8px;">
                    <div style="margin-bottom: 15px;">
                        <small style="color: var(--text-light);">Student Name</small>
                        <h4 style="margin: 0; color: var(--text-main);"><?php echo htmlspecialchars($certificate['student_name']); ?></h4>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <small style="color: var(--text-light);">Course</small>
                        <h4 style="margin: 0; color: var(--text-main);"><?php echo htmlspecialchars($certificate['course_name']); ?></h4>
                    </div>
                    <div>
                        <small style="color: var(--text-light);">Issue Date</small>
                        <h4 style="margin: 0; color: var(--text-main);"><?php echo date('F j, Y', strtotime($certificate['issued_at'])); ?></h4>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
