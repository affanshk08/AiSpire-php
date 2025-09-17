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

    <div class="table-container">
        <table class="career-table">
            <thead>
                <tr>
                    <th>Career Title</th>
                    <th class="description-header">Description</th>
                    <th>Average Salary (INR)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($careers_result->num_rows > 0):
                    while($career = $careers_result->fetch_assoc()): 
                ?>
                    <tr>
                        <td data-label="Career Title"><?php echo htmlspecialchars($career['title']); ?></td>
                        <td data-label="Description"><?php echo htmlspecialchars($career['description']); ?></td>
                        <td data-label="Average Salary">₹<?php echo number_format($career['averageSalary']); ?></td>
                        <td data-label="Action" class="action-cell">
                            <a href="career-details.php?id=<?php echo $career['id']; ?>" class="table-link">
                                View Details
                            </a>
                        </td>
                    </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                    <tr>
                        <td colspan="4">No careers found in the database yet.</td>
                    </tr>
                <?php 
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>