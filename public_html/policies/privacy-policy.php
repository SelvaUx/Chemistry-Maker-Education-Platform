<?php
require_once '../../config/constants.php';
$pageTitle = "Privacy Policy";
require_once '../includes/header.php';
?>

<div class="container section-padding">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="margin-bottom: 30px;">Privacy Policy</h1>
        <p style="color: var(--text-light); margin-bottom: 30px;">Last Updated: <?php echo date('F d, Y'); ?></p>
        
        <div style="background: var(--white); padding: 40px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
            
            <h3 style="margin-bottom: 15px;">1. Information We Collect</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                We collect information you provide directly to us when you create an account, purchase a course, or communicate with us. This may include your name, email address, phone number, and payment information.
            </p>

            <h3 style="margin-bottom: 15px;">2. How We Use Your Information</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                We use the information we collect to provide, maintain, and improve our services, including to process transactions, send you related information, and respond to your comments and questions.
            </p>

            <h3 style="margin-bottom: 15px;">3. Data Security</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                We implement appropriate security measures to protect your personal information. However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.
            </p>

            <h3 style="margin-bottom: 15px;">4. Cookies</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                We use cookies to improve your experience on our website. You can control cookies through your browser settings, but disabling them may limit your use of certain features.
            </p>

            <h3 style="margin-bottom: 15px;">5. Contact Us</h3>
            <p style="margin-bottom: 0; color: var(--text-light); line-height: 1.6;">
                If you have any questions about this Privacy Policy, please contact us at <a href="mailto:support@chemistrymaker.com" class="text-primary">support@chemistrymaker.com</a>.
            </p>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
