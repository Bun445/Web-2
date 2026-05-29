<?php
require_once __DIR__ . '/../templates/header.php';
$pageTitle = 'Contact';

$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if ($name && $email && $subject && $message) {
        $messageSent = true;
    }
}
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="section-header" style="margin-bottom: 0;">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <h1 style="font-size: 2rem; margin-bottom: 4px;">Contact</h1>
                    <p style="margin: 0; color: var(--text-muted);">We are ready to hear from you</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="section">
    <div class="container">
        <?php if ($messageSent): ?>
        <div class="alert alert-success" style="max-width: 600px; margin: 0 auto 32px;">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Thank you for contacting us!</strong>
                <p style="margin: 8px 0 0;">We will respond within 24 hours.</p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-wrapper animate-on-scroll">
                <h2>Send a Message</h2>
                <p style="color: var(--text-muted); margin-bottom: 24px;">Fill in your details and we will get back to you shortly.</p>
                
                <form method="POST" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Subject *</label>
                        <select name="subject" class="form-control" required>
                            <option value="">Select a subject</option>
                            <option value="order">Order support</option>
                            <option value="return">Book return</option>
                            <option value="membership">Membership plan</option>
                            <option value="partnership">Business partnership</option>
                            <option value="feedback">Service feedback</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Describe your issue in detail..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info animate-on-scroll animate-delay-2">
                <h2>Contact Information</h2>
                <p style="color: var(--text-muted); margin-bottom: 32px;">You can also reach us directly through the channels below:</p>
                
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-method-content">
                            <h4>Address</h4>
                            <p>123 ABC Street, District 1<br>Ho Chi Minh City, Vietnam</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-method-content">
                            <h4>Phone</h4>
                            <p>Hotline: 1900 1234<br>Zalo: 0901 234 567</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-method-content">
                            <h4>Email</h4>
                            <p>support@maymobook.com<br>business@maymobook.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-method-content">
                            <h4>Working Hours</h4>
                            <p>Thứ 2 - Thứ 6: 8:00 - 18:00<br>Thứ 7: 9:00 - 15:00</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-social">
                    <h4>Connect with Us</h4>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="section section-alt">
    <div class="container">
        <div class="map-placeholder">
            <div style="text-align: center; padding: 60px;">
                <i class="fas fa-map-marked-alt" style="font-size: 4rem; color: var(--blue-primary); margin-bottom: 20px;"></i>
                <h3>Map</h3>
                <p style="color: var(--text-muted);">TP. Hồ Chí Minh, Việt Nam</p>
            </div>
        </div>
    </div>
</section>

<style>
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
}

.contact-form-wrapper h2,
.contact-info h2 {
    font-size: 1.5rem;
    margin-bottom: 16px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 24px;
    margin-bottom: 32px;
}

.contact-method {
    display: flex;
    gap: 16px;
}

.contact-method-icon {
    width: 48px;
    height: 48px;
    background: var(--blue-bg);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--blue-primary);
    font-size: 1.25rem;
    flex-shrink: 0;
}

.contact-method-content h4 {
    font-size: 0.95rem;
    margin-bottom: 4px;
}

.contact-method-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin: 0;
}

.contact-social h4 {
    font-size: 1rem;
    margin-bottom: 16px;
}

.social-links {
    display: flex;
    gap: 12px;
}

.social-link {
    width: 44px;
    height: 44px;
    background: var(--blue-bg);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--blue-primary);
    transition: var(--transition);
}

.social-link:hover {
    background: var(--blue-primary);
    color: white;
    transform: translateY(-4px);
}

.map-placeholder {
    background: white;
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

@media (max-width: 1024px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
