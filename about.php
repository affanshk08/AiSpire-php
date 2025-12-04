<?php 
include 'includes/header.php'; 
?>

<div class="about-page container">
    <div class="about-hero">
        <h1>We believe a fulfilling career is a fundamental part of a happy life.</h1>
        <p class="hero-subtitle">Empowering individuals to discover their potential and pursue work they love.</p>
    </div>

    <div class="about-content">
        <section class="about-section">
            <div class="about-text">
                <h2>Our Mission</h2>
                <p>
                    The world of work is more complex than ever. With countless options and evolving industries, finding the right path can be overwhelming. Our mission is to bring clarity and confidence to this journey. We leverage technology and expert insights to provide personalized career guidance that empowers individuals to discover their potential and pursue work they love.
                </p>
                <p>
                    We are not just a database of jobs; we are a dedicated partner in your professional development. From students choosing a major to professionals seeking a change, CareerCounsel is here to help you make your next move, the right move.
                </p>
            </div>
        </section>

        <section class="about-section">
            <h2>What We Offer</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Comprehensive Career Database</h3>
                    <p>Explore hundreds of career paths with detailed information about roles, responsibilities, salary expectations, and required skills.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Personalized Assessments</h3>
                    <p>Take scientifically-backed assessments to discover your strengths, personality traits, and interests to find the perfect career fit.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <h3>Career Inquiries</h3>
                    <p>Have questions about a specific career? Ask our experts and get detailed answers to help you make informed decisions.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>One-on-One Counselling</h3>
                    <p>Book personalized career counselling sessions with our experienced counsellors for tailored guidance and support.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <h2>Why Choose Us?</h2>
            <div class="benefits-list">
                <div class="benefit-item">
                    <h3>Data-Driven Insights</h3>
                    <p>Our recommendations are based on real data, industry trends, and comprehensive research to ensure accuracy and relevance.</p>
                </div>
                <div class="benefit-item">
                    <h3>Expert Guidance</h3>
                    <p>Access to experienced career counsellors who understand the job market and can provide personalized advice.</p>
                </div>
                <div class="benefit-item">
                    <h3>User-Friendly Platform</h3>
                    <p>Our intuitive interface makes it easy to explore careers, take assessments, and book appointments all in one place.</p>
                </div>
                <div class="benefit-item">
                    <h3>Continuous Support</h3>
                    <p>We're here for you throughout your career journey, from initial exploration to making important career decisions.</p>
                </div>
            </div>
        </section>

        <section class="about-section cta-section">
            <h2>Ready to Start Your Career Journey?</h2>
            <p>Join thousands of individuals who have found their perfect career path with CareerCounsel.</p>
            <div class="cta-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="careers.php" class="cta-button primary">Explore Careers</a>
                    <a href="book-appointment.php" class="cta-button secondary">Book Counselling</a>
                <?php else: ?>
                    <a href="signup.php" class="cta-button primary">Get Started Free</a>
                    <a href="careers.php" class="cta-button secondary">Browse Careers</a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<style>
.about-hero {
    text-align: center;
    padding: 4rem 0;
    margin-bottom: 4rem;
    animation: fadeInUp 0.8s ease-out;
}

.about-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--blue-light) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.about-hero .hero-subtitle {
    font-size: 1.25rem;
    color: var(--text-secondary);
    max-width: 600px;
    margin: 0 auto;
}

.about-content {
    max-width: 1200px;
    margin: 0 auto;
}

.about-section {
    margin-bottom: 4rem;
    animation: fadeIn 0.6s ease-out;
}

.about-section h2 {
    font-size: 2.5rem;
    margin-bottom: 2rem;
    text-align: center;
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--blue-light) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.about-text {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.about-text p {
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 1.5rem;
    color: var(--text-secondary);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.feature-card {
    background: linear-gradient(135deg, var(--card-bg) 0%, #1a1f2e 100%);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.feature-card:hover {
    transform: translateY(-8px);
    border-color: rgba(37, 99, 235, 0.5);
    box-shadow: 0 8px 30px rgba(37, 99, 235, 0.4);
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.feature-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.feature-card p {
    color: var(--text-secondary);
    line-height: 1.6;
}

.benefits-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.benefit-item {
    background: linear-gradient(135deg, var(--card-bg) 0%, #1a1f2e 100%);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.benefit-item:hover {
    transform: translateX(5px);
    border-color: rgba(37, 99, 235, 0.5);
}

.benefit-item h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.benefit-item p {
    color: var(--text-secondary);
    line-height: 1.6;
}

.cta-section {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--card-bg) 0%, #1a1f2e 100%);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    margin-top: 4rem;
}

.cta-section h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.cta-section > p {
    font-size: 1.2rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.cta-buttons {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-button {
    padding: 1rem 2.5rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    display: inline-block;
}

.cta-button.primary {
    background: linear-gradient(135deg, var(--dark-blue) 0%, var(--blue-accent) 100%);
    color: var(--white);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
}

.cta-button.primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 25px rgba(37, 99, 235, 0.6);
}

.cta-button.secondary {
    background: transparent;
    color: var(--text-primary);
    border: 1px solid var(--blue-accent);
}

.cta-button.secondary:hover {
    background: rgba(37, 99, 235, 0.1);
    transform: translateY(-3px);
}

@media (max-width: 768px) {
    .about-hero h1 {
        font-size: 2.5rem;
    }
    
    .about-section h2 {
        font-size: 2rem;
    }
    
    .features-grid,
    .benefits-list {
        grid-template-columns: 1fr;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-button {
        width: 100%;
        max-width: 300px;
    }
}
</style>

<?php 
include 'includes/footer.php'; 
?>
