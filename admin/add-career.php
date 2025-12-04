<?php
include 'includes/header.php';

$title = $description = $education = $skills_str = '';
$salary = 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $salary = (int)$_POST['averageSalary'];
    $education = trim($_POST['requiredEducation']);
    $skills_str = trim($_POST['skills']);

    // Convert comma-separated skills to JSON
    $skills_arr = array_map('trim', explode(',', $skills_str));
    $skills_json = json_encode($skills_arr);

    // Basic Validation
    if (empty($title)) $errors[] = "Title is required.";
    if ($salary <= 0) $errors[] = "Salary must be a positive number.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO careers (title, description, averageSalary, requiredEducation, skills) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $title, $description, $salary, $education, $skills_json);
        if ($stmt->execute()) {
            header("Location: index.php?status=added");
            exit();
        } else {
            $errors[] = "Database error: Failed to add career.";
        }
    }
}
?>

<div class="admin-page-header">
    <h1>Add New Career</h1>
    <a href="index.php" class="admin-button">Back to Dashboard</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="add-career.php" class="auth-form">
    <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
    <div class="form-group"><label>Description</label><textarea name="description" rows="4" required></textarea></div>
    <div class="form-group"><label>Average Salary (INR)</label><input type="number" name="averageSalary" required></div>
    <div class="form-group"><label>Required Education</label><input type="text" name="requiredEducation" required></div>
    <div class="form-group"><label>Skills (comma-separated)</label><input type="text" name="skills" placeholder="e.g. PHP, MySQL, JavaScript" required></div>
    <button type="submit" class="btn-submit">Add Career</button>
</form>

<?php include 'includes/footer.php'; ?>