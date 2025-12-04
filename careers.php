<?php 
// This page is protected. We will check if the user is logged in.
include 'includes/header.php'; 

// If user_id is not set in the session, it means the user is not logged in.
if (!isset($_SESSION['user_id'])) {
    // Redirect them to the login page.
    header("Location: login.php");
    exit();
}

// Fetch all careers from the database
$careers_result = $conn->query("SELECT * FROM careers");
?>

<div class="careers-page container">
    <div class="page-header">
        <h1>Explore Career Paths</h1>
        <p>
            Find detailed information about roles, responsibilities, and salary
            expectations to make an informed decision.
        </p>
    </div>

    <div class="careers-grid">
        <?php 
        if ($careers_result->num_rows > 0):
            while($career = $careers_result->fetch_assoc()): 
        ?>
            <div class="career-card">
                <h3 class="career-card-title"><?php echo htmlspecialchars($career['title']); ?></h3>
                <p class="career-card-description"><?php echo htmlspecialchars($career['description']); ?></p>
                <div class="career-card-salary">₹<?php echo number_format($career['averageSalary']); ?>/yr</div>
                <a href="career-details.php?id=<?php echo $career['id']; ?>" class="career-card-link">
                    View Details
                </a>
            </div>
        <?php 
            endwhile; 
        else:
        ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <p style="font-size: 1.2rem;">No careers found in the database yet.</p>
            </div>
        <?php 
        endif;
        ?>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>