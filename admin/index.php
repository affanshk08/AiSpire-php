<?php
include 'includes/header.php';

// Fetch all careers to display
$careers_result = $conn->query("SELECT * FROM careers ORDER BY title ASC");
?>

<div class="admin-header">
    <h1>Manage Careers</h1>
    <a href="add-career.php" class="admin-button">Add New Career</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Salary (INR)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($career = $careers_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($career['title']); ?></td>
            <td>₹<?php echo number_format($career['averageSalary']); ?></td>
            <td class="actions">
                <a href="edit-career.php?id=<?php echo $career['id']; ?>" class="edit-link">Edit</a>
                <a href="delete-career.php?id=<?php echo $career['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this career?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
include 'includes/footer.php';
?>