<?php
// admin/users.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<div class="flex justify-between align-center" style="margin-bottom: 30px;">
    <h2>Student Management</h2>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Joined Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td>#<?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                <td>
                    <span style="color: #00b894; font-weight: 600;">Active</span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</div>
</div>
</body>
</html>
