<?php
include 'includes/header.php';

$career_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];

// Fetch existing career data
$stmt = $conn->prepare("SELECT * FROM careers WHERE id = ?");
$stmt->bind_param("i", $career_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    echo "Career not found!";
    exit;
}
$career = $result->fetch_assoc();
// Convert skills from JSON back to a comma-separated string for the form
$skills_str = implode(', ', json_decode($career['skills']));

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $salary = (int)$_POST['averageSalary'];
    $education = trim($_POST['requiredEducation']);
    $skills_str_updated = trim($_POST['skills']);
    
    $skills_arr = array_map('trim', explode(',', $skills_str_updated));
    $skills_json = json_encode($skills_arr);

    if (empty($title)) $errors[] = "Title is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE careers SET title = ?, description = ?, averageSalary = ?, requiredEducation = ?, skills = ? WHERE id = ?");
        $stmt->bind_param("ssissi", $title, $description, $salary, $education, $skills_json, $career_id);
        if ($stmt->execute()) {
            header("Location: index.php?status=updated");
            exit();
        } else {
            $errors[] = "Database error: Failed to update career.";
        }
    }
}
?>

<div class="admin-header">
    <h1>Edit Career</h1>
    <a href="index.php" class="admin-button">Back to Dashboard</a>
</div>

<?php if (!empty($errors)): ?>
    <?php endif; ?>

<form method="POST" action="edit-career.php?id=<?php echo $career_id; ?>" class="auth-form">
    <div class="form-group"><label>Title</label><input type="text" name="title" value="<?php echo htmlspecialchars($career['title']); ?>" required></div>
    <div class="form-group"><label>Description</label><textarea name="description" rows="4" required><?php echo htmlspecialchars($career['description']); ?></textarea></div>
    <div class="form-group"><label>Average Salary (INR)</label><input type="number" name="averageSalary" value="<?php echo htmlspecialchars($career['averageSalary']); ?>" required></div>
    <div class="form-group"><label>Required Education</label><input type="text" name="requiredEducation" value="<?php echo htmlspecialchars($career['requiredEducation']); ?>" required></div>
    <div class="form-group"><label>Skills (comma-separated)</label><input type="text" name="skills" value="<?php echo htmlspecialchars($skills_str); ?>" required></div>
    <button type="submit" class="btn-submit">Update Career</button>
</form>

<?php include 'includes/footer.php'; ?>