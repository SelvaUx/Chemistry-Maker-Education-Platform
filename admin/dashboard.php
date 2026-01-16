<?php
// admin/dashboard.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

// Stats stats
$total_students = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$total_videos = $pdo->query("SELECT COUNT(*) FROM videos")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'success'")->fetchColumn() ?: 0;
?>

<h2 style="margin-bottom: 30px;">Dashboard Overview</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 30px; margin-bottom: 40px;">
    <div class="stat-card">
        <h4 style="color: #888; margin-bottom: 10px;">Total Students</h4>
        <h2 style="font-size: 2.5rem; color: var(--primary);"><?php echo $total_students; ?></h2>
    </div>
    
    <div class="stat-card">
        <h4 style="color: #888; margin-bottom: 10px;">Total Courses</h4>
        <h2 style="font-size: 2.5rem; color: var(--secondary);"><?php echo $total_courses; ?></h2>
    </div>
    
    <div class="stat-card">
        <h4 style="color: #888; margin-bottom: 10px;">Total Videos</h4>
        <h2 style="font-size: 2.5rem; color: var(--accent);"><?php echo $total_videos; ?></h2>
    </div>
    
    <div class="stat-card">
        <h4 style="color: #888; margin-bottom: 10px;">Total Revenue</h4>
        <h2 style="font-size: 2.5rem; color: #00b894;">₹<?php echo number_format($total_revenue, 2); ?></h2>
    </div>
</div>

<div class="table-container">
    <div style="padding: 20px; border-bottom: 1px solid #eee;">
        <h3 style="margin: 0;">Recent Signups</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT id, full_name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
            while ($row = $stmt->fetch()):
            ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo date('M j, Y g:i A', strtotime($row['created_at'])); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div> <!-- End Admin Content (closed in footer logic usually, but here we just end divs) -->
</div>
</body>
</html>
