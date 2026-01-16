<?php
// public_html/download-certificate.php
require_once '../config/constants.php';
require_once '../config/db.php';
require_once 'includes/auth-check.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid Request. Use the Claim Certificate form.");
}

$course_id = (int)$_POST['course_id'];
$student_name = trim($_POST['student_name']);
$user_id = $_SESSION['user_id'];

// Mock Course Fetch
$all_courses = $pdo->prepare("SELECT * FROM courses")->fetchAll();
$course = null;
foreach($all_courses as $c) { if($c['id'] == $course_id) $course = $c; }

if (!$course) die("Course not found");

// Generate Unique Code
$cert_code = "CERT-" . strtoupper(substr(md5(uniqid($user_id, true)), 0, 10));
$issue_date = date("F j, Y");

// In a real app, INSERT INDO DB HERE
// $pdo->prepare("INSERT INTO certificates ... ");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate - <?php echo htmlspecialchars($student_name); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; background: #eee; font-family: 'Merriweather', serif; }
        .certificate-container {
            width: 1056px; /* A4 Landscape 96DPI approx wait, A4 is 297mm width */
            height: 816px;
            margin: 40px auto;
            background: #fff;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 40px;
            box-sizing: border-box;
            background-image: radial-gradient(circle at center, #fff 50%, #f9f9f9 100%);
        }
        
        .border-double {
            width: 100%;
            height: 100%;
            border: 20px solid #2c3e50;
            position: relative;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .inner-border {
            position: absolute;
            top: 5px; left: 5px; right: 5px; bottom: 5px;
            border: 2px solid #daa520;
        }
        
        h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 80px;
            margin: 0;
            color: #daa520;
            line-height: 1.2;
        }
        
        .subtitle {
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #555;
            margin-top: 10px;
        }
        
        .presented-to {
            font-style: italic;
            font-size: 18px;
            color: #777;
            margin-top: 40px;
        }
        
        .student-name {
            font-size: 48px;
            font-weight: 700;
            color: #2c3e50;
            margin: 20px 0;
            border-bottom: 2px solid #daa520;
            display: inline-block;
            padding: 0 40px 10px;
        }
        
        .course-title {
            font-size: 28px;
            color: #333;
            margin-top: 10px;
        }
        
        .footer-cert {
            margin-top: 80px;
            width: 80%;
            display: flex;
            justify-content: space-between;
        }
        
        .signature {
            border-top: 1px solid #333;
            padding-top: 10px;
            width: 200px;
            text-align: center;
            font-size: 16px;
        }
        
        .cert-id {
            position: absolute;
            bottom: 20px;
            font-size: 12px;
            color: #999;
            font-family: sans-serif;
            letter-spacing: 1px;
        }
        
        .print-btn-container {
            text-align: center;
            margin-top: 20px;
        }
        
        .btn-print {
            background: #007bff;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-family: sans-serif;
            font-weight: bold;
            display: inline-block;
            cursor: pointer;
            border: none;
        }

        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            .certificate-container { margin: 0; box-shadow: none; width: 100%; height: 100vh; }
            .print-btn-container { display: none; }
            @page {
                size: landscape;
                margin: 0;
            }
        }
    </style>
</head>
<body>

<div class="print-btn-container">
    <button onclick="window.print()" class="btn-print">Save as PDF / Print</button>
    <br><br>
    <a href="dashboard.php" style="color: #666; text-decoration: none;">&larr; Back to Dashboard</a>
</div>

<div class="certificate-container">
    <div class="border-double">
        <div class="inner-border"></div>
        
        <h1>Certificate</h1>
        <div class="subtitle">Of Achievement</div>
        
        <div class="presented-to">This certificate is proudly presented to</div>
        
        <div class="student-name"><?php echo htmlspecialchars($student_name); ?></div>
        
        <div class="presented-to" style="margin-top: 20px;">For successfully completing the course</div>
        <div class="course-title"><?php echo htmlspecialchars($course['title']); ?></div>
        
        <div class="footer-cert">
            <div class="signature">
                <div style="font-family: 'Great Vibes', cursive; font-size: 24px; margin-bottom: 5px;">ChemistryMaker</div>
                Course Director
            </div>
            <div class="signature">
                <div><?php echo $issue_date; ?></div>
                Date Issued
            </div>
        </div>
        
        <div class="cert-id">
            Certificate ID: <strong><?php echo $cert_code; ?></strong><br>
            Verify at: <?php echo BASE_URL; ?>verify-certificate.php
        </div>
    </div>
</div>

<script>
    // Auto print on load (optional, maybe distinct button is better UX)
    // window.onload = function() { window.print(); }
</script>

</body>
</html>
