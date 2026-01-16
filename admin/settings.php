<?php
// admin/settings.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';
?>

<div style="max-width: 600px;">
    <h2 style="margin-bottom: 30px;">Settings</h2>
    
    <div class="stat-card">
        <h3>Change Admin Password</h3>
        <p style="color: #888; margin-bottom: 20px;">Secure your account by updating your password regularly.</p>
        
        <form>
             <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Current Password</label>
                <input type="password" disabled placeholder="Disabled in Demo" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9;">
            </div>
             <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">New Password</label>
                <input type="password" disabled placeholder="Disabled in Demo" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9;">
            </div>
             <button type="button" class="btn btn-secondary">Update Password</button>
        </form>
    </div>
</div>

</div>
</div>
</body>
</html>
