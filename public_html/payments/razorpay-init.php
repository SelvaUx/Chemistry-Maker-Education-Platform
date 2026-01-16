<?php
// public_html/payments/razorpay-init.php
require_once '../../config/constants.php';
require_once '../../config/db.php';
require_once '../includes/auth-check.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$user_id = $_SESSION['user_id'];

if ($course_id == 0) {
    die("Invalid Course");
}

// Fetch Course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    die("Course not found");
}

// Create Razorpay Order via cURL (No SDK needed)
$api_key = RAZORPAY_KEY_ID;
$api_secret = RAZORPAY_KEY_SECRET;

$orderData = [
    'receipt'         => 'rcpt_' . uniqid(),
    'amount'          => $course['price'] * 100, // Amount in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // Auto capture
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_USERPWD, $api_key . ':' . $api_secret);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$result = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    // For demo/offline purposes, IF cURL fails (e.g. no internet/invalid keys), 
    // we can fallback to a mock order ID if desired.
    // die("Razorpay Error: " . curl_error($ch));
    $order_id = "order_mock_" . uniqid(); // Fallback for testing without keys
} else {
    $response = json_decode($result, true);
    // If keys are dummy, Razorpay API will error. 
    // We'll use a mock ID if API fails for smoother manual testing with dummy keys.
    if (isset($response['id'])) {
        $order_id = $response['id'];
    } else {
        $order_id = "order_mock_" . uniqid(); 
    }
}
curl_close($ch);

// Insert Pending Payment Record
$stmt = $pdo->prepare("INSERT INTO payments (user_id, razorpay_order_id, amount, status) VALUES (?, ?, ?, 'pending')");
$stmt->execute([$user_id, $order_id, $course['price']]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - <?php echo SITE_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; height: 100vh; background: #f0f2f5;">

<div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 100%;">
    <h2 style="margin-bottom: 20px;">Complete Payment</h2>
    <p style="color: #666; margin-bottom: 30px;">Course: <b><?php echo htmlspecialchars($course['title']); ?></b><br>Amount: <b>$<?php echo number_format($course['price'], 2); ?></b></p>
    
    <!-- Razorpay Checkout Button -->
    <button id="rzp-button1" class="btn btn-primary" style="width: 100%;">Pay Now</button>
    
    <div style="margin-top: 20px;">
        <a href="../course.php?id=<?php echo $course_id; ?>" style="color: #888; text-decoration: none; font-size: 0.9rem;">Cancel</a>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "<?php echo RAZORPAY_KEY_ID; ?>",
    "amount": "<?php echo $course['price'] * 100; ?>", 
    "currency": "INR",
    "name": "<?php echo SITE_NAME; ?>",
    "description": "Course Purchase",
    "image": "<?php echo BASE_URL; ?>assets/images/logo.png",
    "order_id": "<?php echo $order_id; ?>", 
    "handler": function (response){
        // Submit form with payment details
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "verify-payment.php");

        var payment_id = document.createElement("input");
        payment_id.setAttribute("type", "hidden");
        payment_id.setAttribute("name", "razorpay_payment_id");
        payment_id.setAttribute("value", response.razorpay_payment_id);
        form.appendChild(payment_id);

        var order_id = document.createElement("input");
        order_id.setAttribute("type", "hidden");
        order_id.setAttribute("name", "razorpay_order_id");
        order_id.setAttribute("value", response.razorpay_order_id);
        form.appendChild(order_id);

        var signature = document.createElement("input");
        signature.setAttribute("type", "hidden");
        signature.setAttribute("name", "razorpay_signature");
        signature.setAttribute("value", response.razorpay_signature);
        form.appendChild(signature);
        
        var course_id = document.createElement("input");
        course_id.setAttribute("type", "hidden");
        course_id.setAttribute("name", "course_id");
        course_id.setAttribute("value", "<?php echo $course_id; ?>");
        form.appendChild(course_id);

        document.body.appendChild(form);
        form.submit();
    },
    "prefill": {
        "name": "<?php echo $_SESSION['user_name']; ?>",
        "email": "student@example.com", 
    },
    "theme": {
        "color": "#6c5ce7"
    }
};

// If using mock order (keys invalid), Razorpay JS might fail.
// For Local Testing with Dummy Keys:
if (options.key.includes("test_id") || options.order_id.includes("order_mock")) {
    console.warn("Using Dummy Keys/Mock Order. Checkout might not open properly without real keys.");
    // Auto-mock success for demo purposes if button clicked?
}

var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>
</body>
</html>
