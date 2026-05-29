    <!-- Newsletter -->
    <section class="newsletter-section">
        <div class="container footer-newsletter">
            <h2 class="footer-cta-title">Subscribe for Updates</h2>
            <p class="section-subtitle">Get notified about new books and special offers.</p>
            <form class="newsletter-form" action="#" method="POST">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="index.php" class="logo">
                        <div class="logo-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        MÂY MƠ <span>BOOK</span>
                    </a>
                    <p>The leading online book rental platform in Vietnam. Discover thousands of titles with affordable pricing.</p>
                    <div class="footer-social">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4>Quick Links</h4>
                    <div class="footer-links">
                        <a href="index.php"><i class="fas fa-chevron-right"></i> Home</a>
                        <a href="books.php"><i class="fas fa-chevron-right"></i> Books</a>
                        <a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a>
                        <a href="contact.php"><i class="fas fa-chevron-right"></i> Contact</a>
                    </div>
                </div>
                
                <div>
                    <h4>Support</h4>
                    <div class="footer-links">
                        <a href="faq.php"><i class="fas fa-chevron-right"></i> FAQ</a>
                        <a href="contact.php"><i class="fas fa-chevron-right"></i> Support Contact</a>
                        <a href="#"><i class="fas fa-chevron-right"></i> Return Policy</a>
                        <a href="#"><i class="fas fa-chevron-right"></i> Terms of Service</a>
                    </div>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact</h4>
                    <p><i class="fas fa-map-marker-alt"></i> 123 ABC Street, District 1, Ho Chi Minh City</p>
                    <p><i class="fas fa-phone"></i> Hotline: 1900 1234</p>
                    <p><i class="fas fa-envelope"></i> support@maymobook.com</p>
                    <p><i class="fas fa-clock"></i> Mon - Fri: 8:00 - 18:00</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> MÂY MƠ BOOK. All rights reserved. | Made with <i class="fas fa-heart" style="color: var(--danger);"></i> in Vietnam</p>
            </div>
        </div>
    </footer>
    
    <!-- Quick View Modal -->
    <div id="quickViewModal" class="modal-overlay">
        <div class="quick-view-modal">
            <button class="modal-close"><i class="fas fa-times"></i></button>
            <div class="quick-view-content">
                <div class="quick-view-image">
                    <img src="" alt="" class="qv-image">
                </div>
                <div class="quick-view-info">
                    <h2 class="qv-title"></h2>
                    <p class="qv-author"></p>
                    <p class="qv-price"></p>
                    <p class="qv-description"></p>
                    <a href="" class="btn btn-primary btn-lg qv-link">
                        <i class="fas fa-book"></i> Xem Chi Tiết
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>
