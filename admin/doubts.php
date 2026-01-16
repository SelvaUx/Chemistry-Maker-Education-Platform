<?php
// admin/doubts.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

// Fetch all doubts with user details
$stmt = $pdo->query("SELECT d.*, u.full_name as student_name, c.title as course_title, v.title as video_title 
                     FROM doubts d
                     LEFT JOIN users u ON d.user_id = u.id
                     LEFT JOIN courses c ON d.course_id = c.id
                     LEFT JOIN videos v ON d.video_id = v.id
                     ORDER BY d.created_at DESC");
$doubts = $stmt->fetchAll();

// Get filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
if ($filter == 'pending') {
    $doubts = array_filter($doubts, fn($d) => ($d['status'] ?? 'pending') == 'pending');
} elseif ($filter == 'resolved') {
    $doubts = array_filter($doubts, fn($d) => ($d['status'] ?? 'pending') == 'resolved');
}
?>

<style>
    .doubt-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.2s;
    }
    .doubt-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-resolved { background: #d4edda; color: #155724; }
</style>

<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="margin: 0;">Student Doubts & Questions</h2>
            <p style="color: #666; font-size: 0.9rem; margin-top: 5px;">Manage and respond to student queries</p>
        </div>
        
        <!-- Filter Tabs -->
        <div style="display: flex; gap: 10px;">
            <a href="?filter=all" class="btn <?php echo $filter=='all' ? 'btn-primary' : 'btn-secondary'; ?>" style="padding: 8px 20px;">
                All (<?php echo count($doubts); ?>)
            </a>
            <a href="?filter=pending" class="btn <?php echo $filter=='pending' ? 'btn-primary' : 'btn-secondary'; ?>" style="padding: 8px 20px;">
                Pending
            </a>
            <a href="?filter=resolved" class="btn <?php echo $filter=='resolved' ? 'btn-primary' : 'btn-secondary'; ?>" style="padding: 8px 20px;">
                Resolved
            </a>
        </div>
    </div>

    <?php if(empty($doubts)): ?>
        <div style="text-align: center; padding: 60px; background: white; border-radius: 12px;">
            <i class="fa-regular fa-comments" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
            <h3 style="color: #888;">No doubts found</h3>
            <p style="color: #aaa;">Students haven't asked any questions yet.</p>
        </div>
    <?php else: ?>
        <?php foreach($doubts as $doubt): ?>
            <div class="doubt-card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <strong style="color: var(--primary);"><?php echo htmlspecialchars($doubt['student_name'] ?? 'Unknown Student'); ?></strong>
                            <span class="status-badge status-<?php echo $doubt['status'] ?? 'pending'; ?>">
                                <?php echo strtoupper($doubt['status'] ?? 'PENDING'); ?>
                            </span>
                        </div>
                        <div style="font-size: 0.85rem; color: #888; display: flex; gap: 15px;">
                            <span><i class="fa-solid fa-book"></i> <?php echo htmlspecialchars($doubt['course_title'] ?? 'N/A'); ?></span>
                            <span><i class="fa-solid fa-video"></i> <?php echo htmlspecialchars($doubt['video_title'] ?? 'N/A'); ?></span>
                            <span><i class="fa-regular fa-clock"></i> <?php echo date('M d, Y h:i A', strtotime($doubt['created_at'])); ?></span>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="openReplyModal(<?php echo $doubt['id']; ?>)" style="padding: 8px 15px; font-size: 0.9rem;">
                        <i class="fa-solid fa-reply"></i> Reply
                    </button>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 3px solid var(--primary);">
                    <p style="margin: 0; color: #333; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($doubt['question'])); ?></p>
                </div>
                
                <!-- Existing Replies -->
                <?php
                $stmt_replies = $pdo->prepare("SELECT r.*, u.full_name FROM doubt_replies r LEFT JOIN users u ON r.user_id = u.id WHERE r.doubt_id = ? ORDER BY r.created_at ASC");
                $stmt_replies->execute([$doubt['id']]);
                $replies = $stmt_replies->fetchAll();
                
                if (!empty($replies)):
                ?>
                    <div style="margin-top: 15px; padding-left: 20px; border-left: 2px solid #e5e7eb;">
                        <?php foreach($replies as $reply): ?>
                            <div style="background: #e8f5e9; padding: 12px; border-radius: 6px; margin-bottom: 10px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <strong style="color: #2e7d32; font-size: 0.9rem;">
                                        <?php echo $reply['user_type'] == 'admin' ? '👨‍🏫 Admin' : htmlspecialchars($reply['full_name'] ?? 'User'); ?>
                                    </strong>
                                    <span style="font-size: 0.8rem; color: #888;"><?php echo date('M d, h:i A', strtotime($reply['created_at'])); ?></span>
                                </div>
                                <p style="margin: 0; color: #333; font-size: 0.95rem;"><?php echo nl2br(htmlspecialchars($reply['message'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Reply Modal -->
<div id="replyModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 600px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
        <h3 style="margin-top: 0; margin-bottom: 20px;">Reply to Doubt</h3>
        <textarea id="replyMessage" placeholder="Type your response here..." style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; min-height: 120px; margin-bottom: 15px; font-family: inherit;"></textarea>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <button id="submitReplyBtn" class="btn btn-primary">Send Reply</button>
            <button onclick="closeReplyModal()" class="btn btn-secondary">Cancel</button>
            <label style="margin-left: auto; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" id="markResolved" style="width: 18px; height: 18px;">
                <span style="font-size: 0.9rem;">Mark as Resolved</span>
            </label>
        </div>
    </div>
</div>

<script>
let currentDoubtId = null;

function openReplyModal(doubtId) {
    currentDoubtId = doubtId;
    document.getElementById('replyModal').style.display = 'flex';
    document.getElementById('replyMessage').value = '';
    document.getElementById('replyMessage').focus();
}

function closeReplyModal() {
    document.getElementById('replyModal').style.display = 'none';
    currentDoubtId = null;
}

document.getElementById('submitReplyBtn').addEventListener('click', function() {
    const message = document.getElementById('replyMessage').value.trim();
    const markResolved = document.getElementById('markResolved').checked;
    
    if (!message) {
        alert('Please type a response');
        return;
    }
    
    this.disabled = true;
    this.textContent = 'Sending...';
    
    fetch('api/reply-doubt.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `doubt_id=${currentDoubtId}&message=${encodeURIComponent(message)}&mark_resolved=${markResolved ? 1 : 0}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
            this.disabled = false;
            this.textContent = 'Send Reply';
        }
    })
    .catch(error => {
        alert('Network error occurred');
        this.disabled = false;
        this.textContent = 'Send Reply';
    });
});

// Close modal on background click
document.getElementById('replyModal').addEventListener('click', function(e) {
    if (e.target === this) closeReplyModal();
});
</script>

</div>
</div>
</body>
</html>
