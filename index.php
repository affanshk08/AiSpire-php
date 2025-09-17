<?php 
// Include the header
include 'includes/header.php'; 
?>

<div class="home-page container">
    <section class="hero">
        <h1 class="hero-title">
            Clarity for your <br />
            <span class="hero-highlight">Next Chapter.</span>
        </h1>
        <p class="hero-subtitle">
            Don't navigate your career journey alone. We provide personalized,
            data-driven guidance to help you find a profession you'll love.
        </p>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="careers.php" class="hero-cta">
                Explore Careers &rarr;
            </a>
        <?php else: ?>
            <a href="signup.php" class="hero-cta">
                Start Your Journey &rarr;
            </a>
        <?php endif; ?>

    </section>

    <section class="info-section">
        <div class="info-card">
            <h2>Explore Career Paths</h2>
            <p>
                Dive into our curated database of hundreds of careers. Understand
                the day-to-day, salary expectations, and required skills for each.
            </p>
            <a href="careers.php" class="info-link">
                Browse Careers
            </a>
        </div>
        <div class="info-card">
            <h2>Discover Yourself</h2>
            <p>
                Our scientifically-backed assessments help you uncover your
                strengths, personality traits, and interests to find the perfect fit.
            </p>
            <a href="assessments.php" class="info-link">
                Take an Assessment
            </a>
        </div>
    </section>
</div>

<?php 
// Include the footer
include 'includes/footer.php'; 
?>