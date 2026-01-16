<?php
require_once '../../config/constants.php';
$pageTitle = "Refund Policy";
require_once '../includes/header.php';
?>

<div class="container section-padding">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="margin-bottom: 30px;">Refund Policy</h1>
        <p style="color: var(--text-light); margin-bottom: 30px;">Last Updated: <?php echo date('F d, Y'); ?></p>
        
        <div style="background: var(--white); padding: 40px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
            
            <h3 style="margin-bottom: 15px;">1. 7-Day Money-Back Guarantee</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                We want you to be completely satisfied with our courses. If you are not satisfied with your purchase, you may request a full refund within 7 days of the purchase date.
            </p>

            <h3 style="margin-bottom: 15px;">2. Eligibility for Refund</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                To be eligible for a refund, you must have consumed less than 30% of the course content. Requests made after the 7-day period will not be approved.
            </p>

            <h3 style="margin-bottom: 15px;">3. How to Request a Refund</h3>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.6;">
                To request a refund, please contact our support team at <a href="mailto:support@chemistrymaker.com" class="text-primary">support@chemistrymaker.com</a> with your transaction details and the reason for your request.
            </p>

            <h3 style="margin-bottom: 15px;">4. Processing Time</h3>
            <p style="margin-bottom: 0; color: var(--text-light); line-height: 1.6;">
                Once your refund is approved, it will be processed, and a credit will automatically be applied to your original method of payment within 5-10 business days.
            </p>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
