<?php
// public_html/includes/footer.php
?>
<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <a href="<?php echo BASE_URL; ?>" class="footer-logo">CHEMISTRY MAKER</a>
                <p>Empowering students to master chemistry with ease. Premium courses, high-quality video lectures, and comprehensive study materials.</p>
                <div class="social-links" style="margin-top: 20px; display: flex; gap: 15px;">
                    <a href="https://www.youtube.com/@CHEMISTRYMAKER135" target="_blank"><i class="fa-brands fa-youtube fa-lg"></i></a>
                    <a href="https://www.youtube.com/@CHEMISTRYMAKER135" target="_blank"><i class="fa-brands fa-instagram fa-lg"></i></a>
                </div>
            </div>
            
            <div class="footer-links">
                <h4>Platform</h4>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>courses.php">All Courses</a></li>
                    <li><a href="<?php echo BASE_URL; ?>about.php">About Us</a></li>
                    <li><a href="<?php echo BASE_URL; ?>login.php">Login</a></li>
                    <li><a href="<?php echo BASE_URL; ?>signup.php">Sign Up</a></li>
                </ul>
            </div>
            
            <div class="footer-links">
                <h4>Legal</h4>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>policies/privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="<?php echo BASE_URL; ?>policies/terms.php">Terms of Service</a></li>
                    <li><a href="<?php echo BASE_URL; ?>policies/refund-policy.php">Refund Policy</a></li>
                </ul>
            </div>
            
            <div class="footer-links">
                <h4>Contact</h4>
                <ul>
                    <li><i class="fa-solid fa-map-marker-alt"></i> Chennai, Tamil Nadu, India</li>
                    <li><i class="fa-solid fa-envelope"></i> support@chemistrymaker.com</li>
                    <li><i class="fa-solid fa-phone"></i> +91 98765 43210</li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Chemistry Maker. All Rights Reserved.</p>
        </div>
    </div>
    </div>
</footer>

<!-- Mobile Sticky CTA -->
<div class="mobile-sticky-cta">
    <a href="<?php echo BASE_URL; ?>signup.php" class="btn btn-primary" style="width: 100%; border-radius: 0; padding: 15px; text-align: center; box-shadow: 0 -4px 10px rgba(0,0,0,0.1);">Get Started</a>
</div>

<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>
