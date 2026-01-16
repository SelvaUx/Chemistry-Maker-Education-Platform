<?php
// public_html/contact.php
require_once '../config/constants.php';
$pageTitle = "Contact Us";
require_once 'includes/header.php';
?>

<div class="container section-padding">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 class="text-center" style="margin-bottom: 40px;">Get in Touch</h1>
        
        <div class="contact-grid">
            <div>
                <h3 style="margin-bottom: 20px;">Contact Information</h3>
                <div style="margin-bottom: 20px;">
                    <h5 style="margin-bottom: 5px; color: var(--primary);">Address</h5>
                    <p style="color: var(--text-light);">123 Education Lane,<br>Knowledge City, India 400001</p>
                </div>
                <div style="margin-bottom: 20px;">
                    <h5 style="margin-bottom: 5px; color: var(--primary);">Email</h5>
                    <p style="color: var(--text-light);">support@chemistrymaker.com</p>
                </div>
                <div style="margin-bottom: 20px;">
                    <h5 style="margin-bottom: 5px; color: var(--primary);">Phone</h5>
                    <p style="color: var(--text-light);">+91 98765 43210</p>
                </div>
            </div>
            
            <div style="background: var(--white); padding: 30px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
                <form onsubmit="event.preventDefault(); alert('Message sent successfully! (Demo)');">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Name</label>
                        <input type="text" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Email</label>
                        <input type="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Message</label>
                        <textarea rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
