<?php
include 'includes/header.php';

// Fetch all careers to display
$careers_result = $conn->query("SELECT * FROM careers ORDER BY title ASC");
if (!$careers_result) {
    $careers_result = false;
}
?>

<div class="admin-page-header">
    <h1>Admin Dashboard</h1>
    <a href="add-career.php" class="admin-button">Add New Career</a>
</div>

<div class="admin-dashboard">
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <h3>Total Careers</h3>
            <p class="dashboard-number"><?php 
                $careers_count_result = $conn->query("SELECT COUNT(*) as count FROM careers");
                if ($careers_count_result) {
                    $careers_count = $careers_count_result->fetch_assoc()['count'];
                    echo $careers_count;
                } else {
                    echo '0';
                }
            ?></p>
            <a href="index.php" class="dashboard-link">View All</a>
        </div>
        <div class="dashboard-card">
            <h3>Total Users</h3>
            <p class="dashboard-number"><?php 
                $users_result = $conn->query("SELECT COUNT(*) as count FROM users");
                if ($users_result) {
                    $users_count = $users_result->fetch_assoc()['count'];
                    echo $users_count;
                } else {
                    echo '0';
                }
            ?></p>
            <a href="manage-users.php" class="dashboard-link">Manage Users</a>
        </div>
        <div class="dashboard-card">
            <h3>Pending Appointments</h3>
            <p class="dashboard-number"><?php 
                $pending_result = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'");
                if ($pending_result) {
                    $pending_count = $pending_result->fetch_assoc()['count'];
                    echo $pending_count;
                } else {
                    echo '0';
                }
            ?></p>
            <a href="manage-appointments.php?status=pending" class="dashboard-link">View Pending</a>
        </div>
        <div class="dashboard-card">
            <h3>Pending Inquiries</h3>
            <p class="dashboard-number"><?php 
                $inquiries_result = $conn->query("SELECT COUNT(*) as count FROM career_inquiries WHERE status = 'pending'");
                if ($inquiries_result) {
                    $inquiries_count = $inquiries_result->fetch_assoc()['count'];
                    echo $inquiries_count;
                } else {
                    echo '0';
                }
            ?></p>
            <a href="#" class="dashboard-link">View Inquiries</a>
        </div>
    </div>
</div>

<h2 style="margin-top: 3rem; margin-bottom: 1.5rem;">Manage Careers</h2>

<table class="admin-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Salary (INR)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($careers_result && $careers_result->num_rows > 0): ?>
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
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align: center; padding: 2rem;">No careers found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
include 'includes/footer.php';
?>