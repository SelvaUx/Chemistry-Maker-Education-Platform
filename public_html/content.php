<?php
// public_html/content.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$video_id = isset($_GET['video_id']) ? (int)$_GET['video_id'] : 0;
$user_id = $_SESSION['user_id'];

// Verify Purchase (Keep Logic)
$stmt = $pdo->prepare("SELECT * FROM purchases WHERE user_id = ? AND course_id = ? AND access_status = 'active'");
$stmt->execute([$user_id, $course_id]);
if ($stmt->rowCount() == 0) {
    echo "<script>alert('Please purchase this course first.'); window.location.href='course.php?id=$course_id';</script>";
    exit;
}

// Fetch Course
$course = $pdo->prepare("SELECT * FROM courses WHERE id = ?")->execute([$course_id]); // Mock
$course = $pdo->prepare("SELECT * FROM courses WHERE id = ?")->fetch(); // Just use default fetch for mock

// Fetch All Modules
$modules = $pdo->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY position ASC")->fetchAll(); 
// Mock Note: MockPDO currently returns ALL modules. We will filter in PHP for demo.

// Fetch All Content (Videos, Resources, Tests)
$all_videos = $pdo->prepare("SELECT * FROM videos")->fetchAll();
$all_resources = $pdo->prepare("SELECT * FROM resources")->fetchAll();
$all_tests = $pdo->prepare("SELECT * FROM tests")->fetchAll();

// Determine Current Video
$current_video = null;
if ($video_id) {
    foreach($all_videos as $v) {
        if ($v['id'] == $video_id) { $current_video = $v; break; }
    }
} else {
    // Default to first video of first module
    if (!empty($modules)) {
        $first_mod_id = $modules[0]['id'];
        foreach($all_videos as $v) {
            if (isset($v['module_id']) && $v['module_id'] == $first_mod_id) {
                $current_video = $v;
                break;
            }
        }
    }
}

$pageTitle = $course['title'] . " - Classroom";
?>

<?php
// Fetch Video Progress
$progress_map = [];
try {
    $stmt = $pdo->prepare("SELECT video_id FROM video_progress WHERE user_id = ? AND completed = 1");
    // MockPDO doesn't support complex where clauses well, so we fetch all and filter in PHP for mock
    $all_progress = $pdo->prepare("SELECT * FROM video_progress")->fetchAll(); 
    foreach($all_progress as $p) {
        if ($p['user_id'] == $user_id && $p['completed'] == 1) {
            $progress_map[$p['video_id']] = true;
        }
    }
} catch (Exception $e) { }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; flex-direction: column; user-select: none; -webkit-user-select: none; }
        .classroom-container { display: flex; flex: 1; overflow: hidden; }
        .video-area { flex: 3; background: #000; overflow-y: auto; display: flex; flex-direction: column; position: relative; }
        .sidebar-area { flex: 1; background: var(--bg-card); border-left: 1px solid var(--glass-border); overflow-y: auto; max-width: 400px; min-width: 300px;}
        .video-player-wrapper { width: 100%; aspect-ratio: 16/9; background: #000; position: relative; }
        .iframe-container { width: 100%; height: 100%; border: none; }
        
        /* Accordion Styles */
        .module-header { padding: 15px; background: var(--bg-input); border-bottom: 1px solid var(--glass-border); font-weight: 700; cursor: pointer; display: flex; justify-content: space-between; align-items: center; color: var(--text-main); }
        .module-header:hover { background: var(--glass-border); }
        .module-content { display: block; } /* Show by default for now */
        
        .content-item { padding: 12px 15px; border-bottom: 1px solid var(--glass-border); cursor: pointer; display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--text-light); font-size: 0.9rem; }
        .content-item:hover { background: var(--bg-input); color: var(--text-main); }
        .content-item.active { background: #eef2ff; border-left: 4px solid var(--primary); color: #333; }
        .content-item i { width: 20px; text-align: center; }
        
        /* Dark Mode Active Item Override */
        [data-theme="dark"] .content-item.active { background: var(--primary-light); color: var(--text-main); border-left-color: var(--primary); }
        
        .check-icon { color: #00b894; margin-left: auto; font-size: 0.8rem; }

        /* Level 7: Watermark */
        .watermark {
            position: absolute; top: 20%; left: 20%; color: rgba(255, 255, 255, 0.3); font-size: 1.2rem; font-weight: bold; z-index: 100; pointer-events: none; user-select: none; transform: rotate(-15deg); white-space: nowrap; animation: floatWatermark 30s linear infinite alternate;
        }
        @keyframes floatWatermark {
            0% { top: 10%; left: 10%; } 100% { top: 80%; left: 80%; }
        }
        
        @media (max-width: 768px) {
            .classroom-container { flex-direction: column; overflow-y: auto; }
            .video-area { flex: none; width: 100%; }
            .sidebar-area { flex: none; width: 100%; max-width: none; border-left: none; border-top: 1px solid #ddd; }
        }
    </style>
</head>
<body oncontextmenu="return false;"> 

<header class="main-header" style="position: relative;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; height: 100%;">
        <a href="dashboard.php" class="logo" style="font-size: 1.2rem;">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
        <span style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($course['title']); ?></span>
        <div style="width: 50px;"></div> 
    </div>
</header>

<div class="classroom-container">
    <!-- Video Area -->
    <div class="video-area">
        <?php if ($current_video): ?>
            <div class="video-player-wrapper">
                <div class="watermark"><?php echo SITE_NAME; ?> | <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                <?php if ($current_video['video_type'] == 'youtube'): ?>
                    <?php 
                        $vid_src = $current_video['video_url'];
                        if (strpos($vid_src, 'watch?v=') !== false) { parse_str(parse_url($vid_src, PHP_URL_QUERY), $params); $vid_id = $params['v'] ?? ''; } 
                        else { $vid_id = $vid_src; }
                        $embed_url = "https://www.youtube.com/embed/" . $vid_id . "?rel=0&modestbranding=1&controls=1";
                    ?>
                    <iframe class="iframe-container" src="<?php echo $embed_url; ?>" allowfullscreen></iframe>
                <?php else: ?>
                    <video id="mainVideo" class="iframe-container" controls controlsList="nodownload">
                        <source src="<?php echo BASE_URL . 'uploads/videos/course_folders/' . htmlspecialchars($current_video['video_url']); ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            </div>
            
            <div style="padding: 30px; color: var(--white);">
                <h2><?php echo htmlspecialchars($current_video['title']); ?></h2>
                
                <!-- Resources Section for this Module (Demo Logic: Show resources for current module) -->
                <?php 
                    $current_mod_id = $current_video['module_id']; 
                    $mod_resources = array_filter($all_resources, function($r) use ($current_mod_id) { return $r['module_id'] == $current_mod_id; });
                    $mod_tests = array_filter($all_tests, function($t) use ($current_mod_id) { return $t['module_id'] == $current_mod_id; });
                ?>
                
                <?php if(!empty($mod_resources) || !empty($mod_tests)): ?>
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; margin-top: 20px;">
                    <h4 style="color: #ddd; margin-bottom: 15px;">Study Materials & Tests</h4>
                    
                    <div class="flex" style="flex-wrap: wrap; gap: 15px;">
                        <?php foreach($mod_resources as $res): ?>
                            <a href="#" class="btn" style="background: rgba(255, 255, 255, 0.1); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-file-pdf" style="color: #e17055;"></i> <?php echo htmlspecialchars($res['title']); ?>
                            </a>
                        <?php endforeach; ?>
                        
                        <?php foreach($mod_tests as $test): ?>
                            <a href="<?php echo $test['form_url']; ?>" target="_blank" class="btn btn-primary" style="padding: 10px 20px;">
                                <i class="fa-solid fa-clipboard-check"></i> <?php echo htmlspecialchars($test['title']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Doubt Section -->
                <?php 
                    // Fetch Doubts for this video
                    $vid_doubts = [];
                    try {
                        // MockPDO: Fetch all and filter
                        $all_doubts = $pdo->prepare("SELECT * FROM doubts")->fetchAll(); // ORDER BY created_at DESC in real DB
                        $all_replies = $pdo->prepare("SELECT * FROM doubt_replies")->fetchAll();
                        
                        foreach($all_doubts as $d) {
                            if ($d['video_id'] == $current_video['id']) {
                                // Attach replies
                                $d['replies'] = [];
                                foreach($all_replies as $r) {
                                    if ($r['doubt_id'] == $d['id']) {
                                        $d['replies'][] = $r;
                                    }
                                }
                                $vid_doubts[] = $d;
                            }
                        }
                    } catch(Exception $e) {}
                ?>
                
                <div style="margin-top: 40px; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 30px;">
                    <h3 style="color: #fff;">Discussion & Doubts</h3>
                    
                    <!-- Ask Form -->
                    <div style="margin-bottom: 30px; background: rgba(255, 255, 255, 0.05); padding: 20px; border-radius: 8px;">
                        <textarea id="doubt-question" placeholder="Ask a question about this video..." style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(0, 0, 0, 0.2); color: #fff; margin-bottom: 10px; min-height: 80px;"></textarea>
                        <button id="submit-doubt-btn" class="btn btn-primary">Post Question</button>
                        <span id="doubt-status" style="margin-left: 15px; color: #00b894; display: none;"></span>
                    </div>
                    
                    <!-- List -->
                    <div class="doubts-list">
                        <?php if(empty($vid_doubts)): ?>
                            <p style="color: #aaa;">No questions yet. Be the first to ask!</p>
                        <?php else: ?>
                            <?php foreach($vid_doubts as $doubt): ?>
                                <div style="margin-bottom: 20px; background: rgba(255, 255, 255, 0.05); padding: 15px; border-radius: 8px; border-left: 3px solid var(--primary);">
                                    <div class="flex justify-between" style="margin-bottom: 5px;">
                                        <strong style="color: #fff;">Student</strong>
                                        <small style="color: #aaa;"><?php echo date('M j, Y', strtotime($doubt['created_at'])); ?></small>
                                    </div>
                                    <p style="color: #ddd; margin-bottom: 10px;"><?php echo htmlspecialchars($doubt['question']); ?></p>
                                    
                                    <!-- Replies -->
                                    <?php if(!empty($doubt['replies'])): ?>
                                        <div style="margin-left: 20px; padding-left: 15px; border-left: 2px solid var(--secondary); margin-top: 10px;">
                                            <?php foreach($doubt['replies'] as $reply): ?>
                                                <div style="margin-top: 10px;">
                                                    <strong style="color: var(--secondary); font-size: 0.9rem;">Instructor</strong>
                                                    <p style="color: #ccc; font-size: 0.9rem;"><?php echo htmlspecialchars($reply['message']); ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
        <?php else: ?>
            <div style="padding:40px; color: white;">Select a video to start learning.</div>
        <?php endif; ?>
    </div>

    <!-- Sidebar Playlist -->
    <div class="sidebar-area">
        <?php foreach ($modules as $mod): ?>
            <div class="module-header">
                <?php echo htmlspecialchars($mod['title']); ?>
                <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem;"></i>
            </div>
            <div class="module-content">
                <!-- Videos -->
                <?php foreach ($all_videos as $v): if(isset($v['module_id']) && $v['module_id'] == $mod['id']): ?>
                    <a href="?course_id=<?php echo $course_id; ?>&video_id=<?php echo $v['id']; ?>" class="content-item <?php echo ($current_video && $current_video['id'] == $v['id']) ? 'active' : ''; ?>">
                         <i class="fa-solid fa-circle-play" style="color: #e17055;"></i>
                         <span><?php echo htmlspecialchars($v['title']); ?></span>
                         
                         <!-- Completed Check -->
                         <?php if(isset($progress_map[$v['id']])): ?>
                            <i class="fa-solid fa-check-circle check-icon"></i>
                         <?php else: ?>
                            <small style="margin-left: auto; color: #999;"><?php echo $v['duration']; ?></small>
                         <?php endif; ?>
                    </a>
                <?php endif; endforeach; ?>
                
                <!-- Quick links to PDFs/Tests (Optional in Sidebar, mostly in main view, but let's show them as items) -->
                <?php foreach ($all_tests as $t): if(isset($t['module_id']) && $t['module_id'] == $mod['id']): ?>
                    <a href="<?php echo $t['form_url']; ?>" target="_blank" class="content-item">
                        <i class="fa-solid fa-clipboard-question" style="color: #6c5ce7;"></i>
                        <span><?php echo htmlspecialchars($t['title']); ?> (Test)</span>
                    </a>
                <?php endif; endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Video Progress Tracking
    const videoId = <?php echo $current_video ? $current_video['id'] : 0; ?>;
    
    function markComplete() {
        if(!videoId) return;
        
        fetch('api/update_progress.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ video_id: videoId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                console.log('Video marked as complete');
                // Optional: Update UI instantly here
            }
        });
    }

    // Trigger after 10 seconds for Demo purposes (Real life: use video 'ended' event)
    if(videoId) {
        setTimeout(markComplete, 10000); // Mark complete after 10s watching
        
        // Also support HTML5 video ended event
        const videoElement = document.getElementById('mainVideo');
        if(videoElement) {
            videoElement.addEventListener('ended', markComplete);
        }
    }
</script>

<script>
// Doubt Submission Handler
document.getElementById('submit-doubt-btn')?.addEventListener('click', function() {
    const textarea = document.getElementById('doubt-question');
    const statusSpan = document.getElementById('doubt-status');
    const button = this;
    const question = textarea.value.trim();
    
    if (!question) {
        alert('Please enter a question');
        return;
    }
    
    // Show loading state
    button.disabled = true;
    button.textContent = 'Submitting...';
    statusSpan.style.display = 'none';
    
    // Send AJAX request
    fetch('api/submit-doubt.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `video_id=<?php echo $current_video['id']; ?>&course_id=<?php echo $course_id; ?>&question=${encodeURIComponent(question)}`
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        button.textContent = 'Post Question';
        
        if (data.success) {
            textarea.value = ''; // Clear form
            statusSpan.textContent = '✓ ' + data.message;
            statusSpan.style.display = 'inline';
            setTimeout(() => statusSpan.style.display = 'none', 5000);
            
            // Optionally refresh page to show new doubt
            setTimeout(() => window.location.reload(), 3000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        button.disabled = false;
        button.textContent = 'Post Question';
        alert('Network error occurred. Please try again.');
    });
});
</script>

</body>
</html>
```
