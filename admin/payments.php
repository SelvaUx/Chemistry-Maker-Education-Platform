<?php
// admin/payments.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

// Fetch payments with user details
$sql = "SELECT p.*, u.full_name, u.email 
        FROM payments p 
        JOIN users u ON p.user_id = u.id 
        ORDER BY p.created_at DESC";
$payments = $pdo->query($sql)->fetchAll();
?>

<div class="flex justify-between align-center" style="margin-bottom: 30px;">
    <h2>Payment History</h2>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Amount</th>
                <th>Transaction ID</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($payments)): ?>
                 <tr>
                    <td colspan="6" class="text-center" style="padding: 40px; color: #888;">No transactions found.</td>
                </tr>
            <?php else: ?>
                <?php foreach($payments as $pay): ?>
                <tr>
                    <td>#<?php echo $pay['id']; ?></td>
                    <td>
                        <div><?php echo htmlspecialchars($pay['full_name']); ?></div>
                        <small style="color: #888;"><?php echo htmlspecialchars($pay['email']); ?></small>
                    </td>
                    <td style="font-weight: 600;">₹<?php echo number_format($pay['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($pay['razorpay_payment_id']); ?></td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; background: #e1ffe1; color: #00b894;">
                            <?php echo ucfirst($pay['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('Y-m-d H:i', strtotime($pay['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</div>
</div>
</body>
</html>
