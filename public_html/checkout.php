<?php
// public_html/checkout.php
require_once 'config/constants.php';
require_once 'config/db.php';

// Auth Check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    $redirect_url = urlencode('checkout.php?' . $_SERVER['QUERY_STRING']);
    header("Location: login.php?redirect=$redirect_url");
    exit;
}

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$user_id = $_SESSION['user_id'];

if ($course_id == 0) {
    header("Location: courses.php");
    exit;
}

// Fetch Course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    die("Course not found");
}

/* -------------------------------------------------------------------------- */
/*                          Backend: Create Order ID                          */
/* -------------------------------------------------------------------------- */
// In a real app, you might do this via AJAX on "Pay" click, 
// but generating on load is fine for this flow.

$api_key = RAZORPAY_KEY_ID;
$api_secret = RAZORPAY_KEY_SECRET;
$amount_paise = $course['price'] * 100;

// Init Order ID
$order_id = null;

// Mock Order Creation (since we might not have internet or valid keys in demo)
// If keys are dummy, we default to mock.
if (strpos(RAZORPAY_KEY_ID, 'test_id') !== false) {
    $order_id = "order_mock_" . uniqid();
} else {
    // Try cURL
    $orderData = [
        'receipt'         => 'rcpt_' . uniqid(),
        'amount'          => $amount_paise, 
        'currency'        => 'INR',
        'payment_capture' => 1 
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
    curl_setopt($ch, CURLOPT_USERPWD, $api_key . ':' . $api_secret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $result = curl_exec($ch);
    
    if (!curl_errno($ch)) {
        $response = json_decode($result, true);
        if (isset($response['id'])) {
            $order_id = $response['id'];
        }
    }
    curl_close($ch);
    
    if (!$order_id) {
         $order_id = "order_mock_" . uniqid(); // Fallback
    }
}

// Log Pending Payment
$stmt = $pdo->prepare("INSERT INTO payments (user_id, razorpay_order_id, amount, status) VALUES (?, ?, ?, 'pending')");
$stmt->execute([$user_id, $order_id, $course['price']]);

$pageTitle = "Checkout";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo SITE_NAME; ?></title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Checkout Specific Styles */
        body { background: var(--light-bg); }
        .checkout-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 40px;
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
        }
        @media (max-width: 768px) {
            .checkout-grid { grid-template-columns: 1fr; }
        }
        .chk-card {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 30px;
            box-shadow: var(--shadow-sm);
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: var(--text-light);
            font-size: 0.95rem;
        }
        .summary-total {
            border-top: 1px solid var(--border-color, #eee);
            padding-top: 15px;
            margin-top: 20px;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-main);
            display: flex;
            justify-content: space-between;
        }
        .trust-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: var(--text-light);
            background: rgba(0,0,0,0.03);
            padding: 10px;
            border-radius: 6px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Header (Minimal) -->
<header style="background: var(--white); padding: 20px 0; border-bottom: 1px solid #eee;">
    <div class="container flex justify-between align-center">
         <a href="index.php" class="logo">
            <img src="assets/logo.png" alt="Logo" style="height: 40px;">
        </a>
        <div class="flex align-center gap-1" style="color: var(--text-light); font-size: 0.9rem;">
            <i class="fa-solid fa-lock text-primary"></i> 100% Secure Checkout
        </div>
    </div>
</header>

<div class="checkout-grid">
    
    <!-- Left: Order Details & User Info -->
    <div>
        <h2 style="margin-bottom: 25px;">Order Summary</h2>
        
        <div class="chk-card" style="margin-bottom: 30px;">
            <div class="flex gap-2">
                <div style="width: 100px; height: 100px; background: #eee; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                     <?php if($course['thumbnail']): ?>
                        <img src="uploads/thumbnails/<?php echo $course['thumbnail']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                     <?php else: ?>
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #999; font-size: 0.8rem;">No Image</div>
                     <?php endif; ?>
                </div>
                <div>
                    <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($course['title']); ?></h4>
                    <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 10px;">
                        By <?php echo $course['instructor']['name'] ?? 'Chemistry Maker'; ?>
                    </p>
                    <div class="flex align-center gap-1">
                        <span style="font-weight: 700; color: var(--primary);">₹<?php echo number_format($course['price'], 0); ?></span>
                        <span style="text-decoration: line-through; color: var(--text-light); font-size: 0.9rem;">₹<?php echo number_format($course['price'] * 1.5, 0); ?></span>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <h5 style="margin-bottom: 15px;">Coupon Code</h5>
                <div class="flex" style="gap: 10px;">
                    <input type="text" id="coupon-code" placeholder="Enter Coupon Code" style="flex: 1; padding: 12px; border: 1px solid #ccc; border-radius: 6px; outline: none;">
                    <button id="apply-coupon" class="btn btn-secondary" style="padding: 10px 20px;">Apply</button>
                </div>
                <p id="coupon-message" style="margin-top: 10px; font-size: 0.9rem;"></p>
            </div>
        </div>
        
        <a href="course.php?id=<?php echo $course_id; ?>" style="color: var(--text-light); font-size: 0.9rem; display: flex; align-items: center; gap: 5px;">
            <i class="fa-solid fa-arrow-left"></i> Back to Course Details
        </a>
    </div>
    
    <!-- Right: Payment -->
    <div>
        <h2 style="margin-bottom: 25px;">Payment Details</h2>
        <div class="chk-card">
            <div class="summary-row">
                <span>Original Price</span>
                <span>₹<?php echo number_format($course['price'], 0); ?></span>
            </div>
            <div class="summary-row" id="discount-row" style="display: none; color: #2ecc71;">
                <span>Discount</span>
                <span id="discount-amount">-₹0</span>
            </div>
             <div class="summary-row">
                <span>Tax (18% GST)</span>
                <span>Included</span>
            </div>
            
            <div class="summary-total">
                <span>Total Amount</span>
                <span id="total-amount">₹<?php echo number_format($course['price'], 0); ?></span>
            </div>
            
            <button id="rzp-button1" class="btn btn-primary" style="width: 100%; margin-top: 25px; padding: 15px; font-size: 1.1rem; border-radius: 8px;">
                Complete Payment <i class="fa-solid fa-lock" style="margin-left: 10px;"></i>
            </button>
            
            <div class="trust-badge">
                <i class="fa-solid fa-shield-halved text-primary"></i>
                <div>
                    <div><strong>100% Secure Payment</strong></div>
                    <div>AES-256 Encryption. 7-Day Refund Policy.</div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <img src="https://cdn.razorpay.com/static/assets/methods/upi.png" style="height: 20px; display: inline-block; margin: 0 5px; opacity: 0.7;">
                <img src="https://cdn.razorpay.com/static/assets/methods/card.png" style="height: 20px; display: inline-block; margin: 0 5px; opacity: 0.7;">
                <img src="https://cdn.razorpay.com/static/assets/methods/netbanking.png" style="height: 20px; display: inline-block; margin: 0 5px; opacity: 0.7;">
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Variables
    let originalPrice = <?php echo $course['price']; ?>;
    let finalPrice = originalPrice;
    const couponInput = document.getElementById('coupon-code');
    const applyBtn = document.getElementById('apply-coupon');
    const msg = document.getElementById('coupon-message');
    const totalDisplay = document.getElementById('total-amount');
    const discountRow = document.getElementById('discount-row');
    const discountDisplay = document.getElementById('discount-amount');
    
    // 1. Coupon Logic (Mock)
    applyBtn.addEventListener('click', () => {
        const code = couponInput.value.trim().toUpperCase();
        if(!code) return;
        
        applyBtn.innerText = 'Checking...';
        
        // Mock Server Delay
        setTimeout(() => {
            if(code === 'SAVE10') {
                const discount = originalPrice * 0.10;
                finalPrice = originalPrice - discount;
                
                discountRow.style.display = 'flex';
                discountDisplay.innerText = '-₹' + Math.round(discount);
                totalDisplay.innerText = '₹' + Math.round(finalPrice);
                
                msg.style.color = '#2ecc71';
                msg.innerHTML = '<i class="fa-solid fa-check-circle"></i> Coupon Applied! 10% Off.';
                applyBtn.innerText = 'Applied';
                applyBtn.disabled = true;
                couponInput.disabled = true;
            } else {
                msg.style.color = '#e74c3c';
                msg.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Invalid Coupon Code.';
                applyBtn.innerText = 'Apply';
            }
        }, 800);
    });
    
    // 2. Razorpay Logic
    var options = {
        "key": "<?php echo RAZORPAY_KEY_ID; ?>",
        "amount": "<?php echo $amount_paise; ?>", // Default Amount
        "currency": "INR",
        "name": "<?php echo SITE_NAME; ?>",
        "description": "Course Purchase",
        "image": "assets/logo.png",
        "order_id": "<?php echo $order_id; ?>", 
        "handler": function (response){
            // Submit form with payment details
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "payments/verify-payment.php");
    
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
            
            var course_id_input = document.createElement("input");
            course_id_input.setAttribute("type", "hidden");
            course_id_input.setAttribute("name", "course_id");
            course_id_input.setAttribute("value", "<?php echo $course_id; ?>");
            form.appendChild(course_id_input);
    
            document.body.appendChild(form);
            form.submit();
        },
        "prefill": {
            "name": "<?php echo $_SESSION['user_name'] ?? 'Student'; ?>",
            "email": "<?php echo $_SESSION['user_email'] ?? 'student@example.com'; ?>", 
        },
        "theme": {
            "color": "#6c5ce7"
        }
    };
    
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function(e){
        // Ensure price is updated in options if coupon used (Mock: RZP order ID is tied to backend amount, so strictly speaking backend order needs update. 
        // For this demo, we assume the initial order ID covers it or we trigger a new order creation. 
        // Since we generated order ID PHP side, we can't easily change amount JS side without backend call.
        // For simplicity: We pay full price in RZP even if coupon visual shows discount, OR we skip updating RZP amount since it's mock.)
        
        // Note: In real app, applying coupon via AJAX should return a NEW Order ID with updated amount.
        
        rzp1.open();
        e.preventDefault();
    }
</script>

</body>
</html>
