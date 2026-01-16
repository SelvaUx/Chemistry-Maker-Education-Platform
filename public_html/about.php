<?php
// public_html/about.php
require_once '../config/constants.php';
require_once '../config/db.php';

$pageTitle = "About Us";
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #0f172a, #1a1a2e); color: white; padding: 100px 0 80px; text-align: center; position: relative; overflow: hidden;">
    <!-- Background Elements -->
    <div style="position: absolute; top: -50px; left: -50px; width: 300px; height: 300px; background: var(--primary); opacity: 0.1; filter: blur(80px); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -50px; right: -50px; width: 400px; height: 400px; background: var(--secondary); opacity: 0.1; filter: blur(100px); border-radius: 50%;"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <h1 style="font-size: 3.5rem; margin-bottom: 20px; background: linear-gradient(to right, #fff, #a5b1c2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Revolutionizing Chemistry Education</h1>
        <p style="font-size: 1.2rem; color: #dcdde1; max-width: 700px; margin: 0 auto;">
            Chemistry Maker is a premier online learning platform dedicated to making complex chemistry concepts simple, visual, and engaging for students preparing for JEE, NEET, and Board Exams.
        </p>
    </div>
</section>

<!-- Stats Section -->
<section style="background: var(--bg-card); padding: 40px 0; border-bottom: 1px solid var(--glass-border);">
    <div class="container">
        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; text-align: center; gap: 30px;">
            <div>
                <h2 class="text-primary" style="font-size: 2.5rem; margin-bottom: 5px;">4.8K+</h2>
                <p style="color: var(--text-light); text-transform: uppercase; font-size: 0.9rem; font-weight: 600; letter-spacing: 1px;">YouTube Subscribers</p>
            </div>
            <div>
                <h2 class="text-primary" style="font-size: 2.5rem; margin-bottom: 5px;">300+</h2>
                <p style="color: var(--text-light); text-transform: uppercase; font-size: 0.9rem; font-weight: 600; letter-spacing: 1px;">Video Lectures</p>
            </div>
            <div>
                <h2 class="text-primary" style="font-size: 2.5rem; margin-bottom: 5px;">5000+</h2>
                <p style="color: var(--text-light); text-transform: uppercase; font-size: 0.9rem; font-weight: 600; letter-spacing: 1px;">Happy Students</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Story / Mission -->
<section class="section-padding">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 60px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Chemistry Laboratory" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
            </div>
            <div style="flex: 1; min-width: 300px;">
                <h4 style="color: var(--primary); text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Who We Are</h4>
                <h2 style="margin-bottom: 25px;">Helping You Crack the Toughest Exams</h2>
                <p style="color: var(--text-light); margin-bottom: 20px; font-size: 1.05rem;">
                    At <strong>Chemistry Maker</strong>, we believe that understanding the "Why" and "How" is more important than memorizing the "What". Our methodology focuses on building strong conceptual foundations.
                </p>
                <p style="color: var(--text-light); margin-bottom: 30px; font-size: 1.05rem;">
                    Whether you are struggling with Organic Mechanisms, Physical Chemistry equations, or Inorganic trends, our structured courses and visual explanations are designed to give you that competitive edge.
                </p>
                
                <h4 style="margin-bottom: 15px; font-size: 1.1rem;">Why Students Love Us:</h4>
                <ul style="list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <li style="display: flex; align-items: center; gap: 10px; color: var(--text-main);">
                        <i class="fa-solid fa-check-circle text-primary"></i> <span>Visual Learning</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px; color: var(--text-main);">
                        <i class="fa-solid fa-check-circle text-primary"></i> <span>Exam-Oriented</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px; color: var(--text-main);">
                        <i class="fa-solid fa-check-circle text-primary"></i> <span>Doubt Support</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px; color: var(--text-main);">
                        <i class="fa-solid fa-check-circle text-primary"></i> <span>Affordable</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- YouTube Integration -->
<section class="section-padding" style="background: var(--light-bg);">
    <div class="container text-center">
        <h2 style="margin-bottom: 15px;">Join Our Community</h2>
        <p style="color: var(--text-light); margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
            Subscribe to our YouTube channel for free tips, tricks, and regular updates on chemistry topics.
        </p>
        
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
            <a href="https://www.youtube.com/@CHEMISTRYMAKER135" target="_blank" class="btn" style="background: #FF0000; color: white; display: flex; align-items: center; gap: 10px; font-size: 1.1rem;">
                <i class="fa-brands fa-youtube fa-xl"></i> Visit YouTube Channel
            </a>
             <a href="courses.php" class="btn btn-primary" style="display: flex; align-items: center; gap: 10px; font-size: 1.1rem;">
                <i class="fa-solid fa-graduation-cap"></i> Explore Premium Courses
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
