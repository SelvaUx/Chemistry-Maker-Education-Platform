<?php
// public_html/payments/payment-success.php
require_once '../../config/constants.php';
require_once '../../config/db.php';
require_once '../includes/auth-check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; height: 100vh; background: #f0f2f5;">

<div style="background: white; padding: 50px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; max-width: 500px;">
    <div style="width: 80px; height: 80px; background: #e1ffe1; color: #00b894; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
        <i class="fa-solid fa-check fa-3x"></i>
    </div>
    
    <h2 style="margin-bottom: 20px;">Payment Successful!</h2>
    <p style="color: #666; margin-bottom: 30px;">Thank you for your purchase. You now have full access to the course content.</p>
    
    <a href="../my-courses.php" class="btn btn-primary" style="padding: 12px 30px;">Go to My Courses</a>
</div>

</body>
</html>
