<?php
// admin/manage-folder.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/admin-header.php';

$folder_id = isset($_GET['folder_id']) ? (int)$_GET['folder_id'] : 0;
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'videos'; // videos, notes, tests

if ($folder_id == 0) {
    echo "<script>window.location.href='manage-course.php?id=$course_id';</script>";
    exit;
}

// Fetch Folder Info
$stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
$stmt->execute([$folder_id]);
$folder = $stmt->fetch();

// Fetch Content (Mock)
// In real app, filter by module_id
$all_videos = $pdo->prepare("SELECT * FROM videos")->fetchAll();
$videos = [];
foreach($all_videos as $v) { if(isset($v['module_id']) && $v['module_id'] == $folder_id) $videos[] = $v; }

$all_resources = $pdo->prepare("SELECT * FROM resources")->fetchAll();
$resources = [];
foreach($all_resources as $r) { if(isset($r['module_id']) && $r['module_id'] == $folder_id) $resources[] = $r; }

$all_tests = $pdo->prepare("SELECT * FROM tests")->fetchAll();
$tests = [];
foreach($all_tests as $t) { if(isset($t['module_id']) && $t['module_id'] == $folder_id) $tests[] = $t; }
?>

<style>
    .content-tab {
        padding: 10px 20px;
        font-weight: 600;
        color: #666;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: 0.3s;
    }
    .content-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    .content-tab:hover {
        background: #f9f9f9;
        color: var(--primary);
    }
    .content-item {
        background: white; border: 1px solid #eee; margin-bottom: 10px; 
        padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;
    }
    .content-item:hover { border-color: #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
</style>

<div style="max-width: 900px; margin: 0 auto;">
    <div class="flex justify-between align-center" style="margin-bottom: 20px;">
        <div>
            <a href="manage-course.php?id=<?php echo $course_id; ?>" style="color: #777; text-decoration: none; font-size: 0.9rem;"><i class="fa-solid fa-arrow-left"></i> Back to Course Structure</a>
            <h3 style="margin-top: 10px; margin-bottom: 5px; color: var(--primary);"><i class="fa-regular fa-folder-open"></i> <?php echo htmlspecialchars($folder['title']); ?></h3>
            <span style="color: #888; font-size: 0.9rem;">Manage chapter content</span>
        </div>
    </div>

    <!-- TABS -->
    <div style="border-bottom: 1px solid #eee; margin-bottom: 30px; display: flex; gap: 10px;">
        <a href="?folder_id=<?php echo $folder_id; ?>&course_id=<?php echo $course_id; ?>&tab=videos" class="content-tab <?php echo $tab=='videos'?'active':''; ?>">
            <i class="fa-solid fa-circle-play"></i> Videos (<?php echo count($videos); ?>)
        </a>
        <a href="?folder_id=<?php echo $folder_id; ?>&course_id=<?php echo $course_id; ?>&tab=notes" class="content-tab <?php echo $tab=='notes'?'active':''; ?>">
            <i class="fa-solid fa-file-pdf"></i> Notes & PDFs (<?php echo count($resources); ?>)
        </a>
        <a href="?folder_id=<?php echo $folder_id; ?>&course_id=<?php echo $course_id; ?>&tab=tests" class="content-tab <?php echo $tab=='tests'?'active':''; ?>">
            <i class="fa-solid fa-list-check"></i> Chapter Tests (<?php echo count($tests); ?>)
        </a>
    </div>

    <!-- TAB CONTENT -->
    <?php if($tab == 'videos'): ?>
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="add-video.php?folder_id=<?php echo $folder_id; ?>" class="btn btn-primary" onclick="alert('Proceed to Add Video Page'); return false;">
                <i class="fa-solid fa-plus"></i> Add New Video
            </a>
        </div>
        
        <?php if(empty($videos)): ?>
            <div style="text-align: center; padding: 40px; color: #ccc;">
                <i class="fa-solid fa-film" style="font-size: 3rem; margin-bottom: 10px;"></i>
                <p>No videos added yet.</p>
            </div>
        <?php else: ?>
            <?php foreach($videos as $index => $v): ?>
                <div class="content-item">
                    <div class="flex align-center gap-2">
                        <div style="color: #ccc; font-weight: bold; width: 20px;"><?php echo $index+1; ?></div>
                        <img src="../assets/images/thumbnail.jpg" style="width: 50px; height: 30px; object-fit: cover; border-radius: 4px; opacity: 0.8;">
                        <div>
                             <div style="font-weight: 600; font-size: 1rem; color: #333;"><?php echo htmlspecialchars($v['title']); ?></div>
                             <div style="font-size: 0.8rem; color: #888;">10:25 • Added Today</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                         <button class="btn btn-secondary" title="Edit"><i class="fa-solid fa-pen"></i></button>
                         <button class="btn btn-secondary" style="color: #d63031; background: #ffecec;" title="Delete" onclick="confirm('Delete video?');"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php elseif($tab == 'notes'): ?>
        <div style="text-align: right; margin-bottom: 20px;">
            <button class="btn btn-primary" onclick="alert('Upload PDF Modal');"><i class="fa-solid fa-cloud-arrow-up"></i> Upload PDF / Note</button>
        </div>
        <?php if(empty($resources)): ?>
             <div style="text-align: center; padding: 40px; color: #ccc;">
                <i class="fa-regular fa-file-pdf" style="font-size: 3rem; margin-bottom: 10px;"></i>
                <p>No study materials uploaded.</p>
            </div>
        <?php else: ?>
            <?php foreach($resources as $r): ?>
                <div class="content-item">
                    <div class="flex align-center gap-2">
                        <i class="fa-solid fa-file-pdf" style="font-size: 1.5rem; color: #e17055;"></i>
                        <div>
                             <div style="font-weight: 600; font-size: 1rem; color: #333;"><?php echo htmlspecialchars($r['title']); ?></div>
                             <div style="font-size: 0.8rem; color: #888;">PDF Document</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                         <a href="#" class="btn btn-secondary" title="View"><i class="fa-regular fa-eye"></i></a>
                         <button class="btn btn-secondary" style="color: #d63031; background: #ffecec;" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php elseif($tab == 'tests'): ?>
        <div style="text-align: right; margin-bottom: 20px;">
            <button class="btn btn-primary" onclick="alert('Create Test Modal');"><i class="fa-solid fa-plus"></i> Create Chapter Test</button>
        </div>
        <?php if(empty($tests)): ?>
             <div style="text-align: center; padding: 40px; color: #ccc;">
                <i class="fa-solid fa-list-check" style="font-size: 3rem; margin-bottom: 10px;"></i>
                <p>No tests created for this chapter.</p>
            </div>
        <?php else: ?>
            <?php foreach($tests as $t): ?>
                <div class="content-item">
                    <div class="flex align-center gap-2">
                        <i class="fa-solid fa-clipboard-question" style="font-size: 1.5rem; color: #6c5ce7;"></i>
                        <div>
                             <div style="font-weight: 600; font-size: 1rem; color: #333;"><?php echo htmlspecialchars($t['title']); ?></div>
                             <div style="font-size: 0.8rem; color: #888;">Google Form Link</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                         <a href="<?php echo $t['form_url']; ?>" target="_blank" class="btn btn-secondary" title="Open Link"><i class="fa-solid fa-up-right-from-square"></i></a>
                         <button class="btn btn-secondary" style="color: #d63031; background: #ffecec;" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

</div>

</div>
</div>
</body>
</html>
