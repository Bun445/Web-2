<?php
require_once __DIR__ . '/../templates/header.php';
$pageTitle = 'About Us';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="section-header section-header--no-margin">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h1 class="page-title">About MÂY MƠ BOOK</h1>
                    <p class="page-subtitle">Our story</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="section">
    <div class="container">
        <div class="about-grid">
            <div class="about-content animate-on-scroll">
                <h2>Welcome to MÂY MƠ BOOK</h2>
                <p>MÂY MƠ BOOK is Vietnam's leading online book rental platform, founded to help book lovers access a vast library of titles easily and affordably.</p>
                
                <p>With over <strong>15,000+ titles</strong> across genres like fiction, self-help, and classics, we are committed to delivering the best reading experience.</p>

                <h3>Why choose MÂY MƠ BOOK?</h3>
                <ul class="about-features">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Diverse collection</strong>
                            <p>Over 15,000 titles across multiple genres</p>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Affordable pricing</strong>
                            <p>Only pay for the days you actually read.</p>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Fast delivery</strong>
                            <p>Free shipping on orders of 3 or more books</p>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Easy returns</strong>
                            <p>7-day no-fee return policy</p>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div class="about-image animate-on-scroll animate-delay-2">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80" alt="About Us">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section section-alt">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item animate-on-scroll animate-delay-1">
                <div class="stat-number">15K+</div>
                <div class="stat-label">Titles</div>
            </div>
            <div class="stat-item animate-on-scroll animate-delay-2">
                <div class="stat-number">50K+</div>
                <div class="stat-label">Customers</div>
            </div>
            <div class="stat-item animate-on-scroll animate-delay-3">
                <div class="stat-number">100K+</div>
                <div class="stat-label">Orders</div>
            </div>
            <div class="stat-item animate-on-scroll animate-delay-4">
                <div class="stat-number">4.9/5</div>
                <div class="stat-label">Reviews</div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section">
    <div class="container">
        <div class="section-header section-header--center">
            <div class="section-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="text-center mt-16">
                <h2 class="section-title">Our Team</h2>
                <p class="section-subtitle">Passionate book lovers</p>
            </div>
        </div>
        
        <div class="team-grid">
            <div class="team-card animate-on-scroll animate-delay-1">
                <div class="team-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3>Nguyễn Văn A</h3>
                <p class="team-role">Founder & CEO</p>
                <p class="team-desc">Passionate about books and dedicated to spreading the reading culture.</p>
            </div>
            <div class="team-card animate-on-scroll animate-delay-2">
                <div class="team-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3>Trần Thị B</h3>
                <p class="team-role">Head of Operations</p>
                <p class="team-desc">Ensuring every order is handled quickly and accurately.</p>
            </div>
            <div class="team-card animate-on-scroll animate-delay-3">
                <div class="team-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3>Lê Văn C</h3>
                <p class="team-role">Head of Technology</p>
                <p class="team-desc">Building modern technology to serve customers better.</p>
            </div>
        </div>
    </div>
</section>

<style>
.about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.about-content h2 {
    font-size: 2rem;
    margin-bottom: 20px;
}

.about-content p {
    color: var(--text-secondary);
    line-height: 1.8;
    margin-bottom: 20px;
}

.about-content h3 {
    margin: 32px 0 20px;
    font-size: 1.25rem;
}

.about-features {
    list-style: none;
}

.about-features li {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
}

.about-features li i {
    color: var(--success);
    font-size: 1.25rem;
    margin-top: 4px;
}

.about-features li strong {
    display: block;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.about-features li p {
    margin: 0;
    font-size: 0.9rem;
}

.about-image img {
    width: 100%;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 32px;
}

.stat-item {
    text-align: center;
    padding: 32px;
    background: white;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--blue-primary);
    margin-bottom: 8px;
}

.stat-label {
    color: var(--text-muted);
    font-size: 0.95rem;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    margin-top: 40px;
}

.team-card {
    background: white;
    padding: 40px 32px;
    border-radius: var(--radius-xl);
    text-align: center;
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.team-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.team-avatar {
    width: 100px;
    height: 100px;
    background: var(--blue-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2.5rem;
    color: var(--blue-primary);
}

.team-card h3 {
    margin-bottom: 4px;
}

.team-role {
    color: var(--blue-primary);
    font-weight: 600;
    margin-bottom: 12px;
}

.team-desc {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

@media (max-width: 1024px) {
    .about-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .team-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .team-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
