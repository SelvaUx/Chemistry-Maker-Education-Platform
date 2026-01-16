<?php
require_once '../../config/constants.php';
$pageTitle = "Terms of Service";
require_once '../includes/header.php';
?>

<div class="container section-padding">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="margin-bottom: 30px;">Terms of Service</h1>
        <p style="color: var(--text-light); margin-bottom: 30px;">Last Updated: <?php echo date('F d, Y'); ?></p>
        
        <div style="background: var(--white); padding: 40px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
            
            <h3 style="margin-bottom: 15px;">1. Acceptance of Terms</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                By accessing or using our website, you agree to be bound by these Terms of Service. If you do not agree to these terms, you may not use our services.
            </p>

            <h3 style="margin-bottom: 15px;">2. User Accounts</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                You are responsible for safeguarding the password that you use to access the service and for any activities or actions under your password. You agree not to disclose your password to any third party.
            </p>

            <h3 style="margin-bottom: 15px;">3. Intellectual Property</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                The service and its original content (excluding content provided by users), features, and functionality are and will remain the exclusive property of Chemistry Maker and its licensors.
            </p>

            <h3 style="margin-bottom: 15px;">4. Course Content Usage</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                Purchasing a course grants you a personal, non-exclusive, non-transferable license to access the course content for your own personal, non-commercial use.
            </p>

            <h3 style="margin-bottom: 15px;">5. Termination</h3>
            <p style="margin-bottom: 0; color: var(--text-light); line-height: 1.6;">
                We may terminate or suspend access to our service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.
            </p>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
