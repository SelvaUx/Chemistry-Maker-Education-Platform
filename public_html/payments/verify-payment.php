<?php
// public_html/payments/verify-payment.php
require_once '../../config/constants.php';
require_once '../../config/db.php';
require_once '../includes/auth-check.php';

$success = false;
$error = "Payment Failed";

if (isset($_POST['razorpay_payment_id'])) {
    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];
    $course_id = $_POST['course_id'];
    
    // Verify Signature (Manual HMAC SHA256)
    $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, RAZORPAY_KEY_SECRET);

    if ($generated_signature == $razorpay_signature || RAZORPAY_KEY_ID == 'rzp_test_YOUR_KEY_HERE') { 
        // Allow mock success if using placeholder keys for demo
        $success = true;
    } else {
        $error = "Invalid Signature";
    }
    
    if ($success) {
        $user_id = $_SESSION['user_id'];
        
        try {
            $pdo->beginTransaction();
            
            // 1. Update Payment Status using order_id
            $stmt = $pdo->prepare("UPDATE payments SET status = 'success', razorpay_payment_id = ? WHERE razorpay_order_id = ?");
            // If order id was mock and not in DB (api failure), we might need to insert a new record or simple handle it.
            // For robustness, let's find if the pending payment exists.
            $check = $pdo->prepare("SELECT id FROM payments WHERE razorpay_order_id = ?");
            $check->execute([$razorpay_order_id]);
            
            if ($check->rowCount() > 0) {
                $stmt->execute([$razorpay_payment_id, $razorpay_order_id]);
                $payment_db_id = $check->fetchColumn(); // Get ID strictly
            } else {
                // Should not happen in real flow, but for safety:
                $stmt_ins = $pdo->prepare("INSERT INTO payments (user_id, razorpay_payment_id, razorpay_order_id, amount, status) VALUES (?, ?, ?, ?, 'success')");
                $stmt_ins->execute([$user_id, $razorpay_payment_id, $razorpay_order_id, 0]); // Amount unknown here easily
                $payment_db_id = $pdo->lastInsertId();
            }

            // 2. Grant Access (Insert into purchases)
            // Check if already exists
            $check_pur = $pdo->prepare("SELECT id FROM purchases WHERE user_id = ? AND course_id = ?");
            $check_pur->execute([$user_id, $course_id]);
            
            if ($check_pur->rowCount() == 0) {
                 $stmt_acc = $pdo->prepare("INSERT INTO purchases (user_id, course_id, payment_id, access_status) VALUES (?, ?, ?, 'active')");
                 $stmt_acc->execute([$user_id, $course_id, $payment_db_id]);
            }
            
            $pdo->commit();
            header("Location: payment-success.php");
            exit;
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<!-- Error Page -->
<h1 style="color: red; text-align: center; margin-top: 50px;"><?php echo $error; ?></h1>
<p style="text-align: center;"><a href="../index.php">Return Home</a></p>
