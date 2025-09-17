<?php 
// This page is also protected
include 'includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the career ID from the URL. (int) makes sure it's a number for security.
$career_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If the ID is invalid, show an error.
if ($career_id <= 0) {
    echo "<div class='container'><p>Invalid career ID.</p></div>";
    include 'includes/footer.php';
    exit();
}

// Prepare a statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM careers WHERE id = ?");
$stmt->bind_param("i", $career_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1):
    $career = $result->fetch_assoc();
    $skills = json_decode($career['skills']);
?>

<div class="career-details-page container">
    <h1 class="career-title"><?php echo htmlspecialchars($career['title']); ?></h1>
    <p class="career-description"><?php echo htmlspecialchars($career['description']); ?></p>
      
    <div class="details-grid">
        <div class="detail-card">
            <h3>Average Salary</h3>
            <p class="salary">₹<?php echo number_format($career['averageSalary']); ?>/yr</p>
        </div>
        <div class="detail-card">
            <h3>Required Education</h3>
            <p><?php echo htmlspecialchars($career['requiredEducation']); ?></p>
        </div>
    </div>

    <div class="skills-section">
        <h3>Required Skills</h3>
        <div class="skills-list">
            <?php foreach ($skills as $skill): ?>
                <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
else:
    // If no career with that ID was found
    echo "<div class='container'><p>Career not found.</p></div>";
endif;

$stmt->close();
include 'includes/footer.php'; 
?>

<style>
/* You can copy the styles for this page from your MERN project's CareerDetails.css */
/* For convenience, here are the styles: */
.career-details-page { max-width: 900px; }
.career-title { font-size: 4rem; text-align: center; margin-bottom: 1rem; }
.career-description { font-size: 1.2rem; text-align: center; color: var(--grey); margin-bottom: 4rem; }
.details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem; }
.detail-card { background-color: var(--off-white); padding: 2rem; border-radius: 12px; }
.detail-card h3 { font-size: 1.2rem; color: var(--grey); margin-bottom: 0.5rem; }
.detail-card p { font-size: 1.5rem; font-weight: 700; }
.detail-card .salary { color: #16a34a; }
.skills-section h3 { font-size: 2rem; margin-bottom: 1.5rem; }
.skills-list { display: flex; flex-wrap: wrap; gap: 1rem; }
.skill-tag { background-color: var(--off-white); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; }
</style>