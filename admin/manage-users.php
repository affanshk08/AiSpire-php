<?php
include 'includes/header.php';

// Handle user actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $user_id = (int)$_GET['id'];
    
    if ($action == 'delete') {
        // Prevent deleting admin users
        $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user && !$user['is_admin']) {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                header("Location: manage-users.php?deleted=1");
                exit();
            }
            $stmt->close();
        } else {
            header("Location: manage-users.php?error=1");
            exit();
        }
    } elseif ($action == 'toggle_admin') {
        $stmt = $conn->prepare("UPDATE users SET is_admin = NOT is_admin WHERE id = ? AND id != ?");
        $admin_id = $_SESSION['admin_id'];
        $stmt->bind_param("ii", $user_id, $admin_id);
        if ($stmt->execute()) {
            header("Location: manage-users.php?updated=1");
            exit();
        }
        $stmt->close();
    }
}

// Fetch all users
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$query = "SELECT id, name, email, is_admin, created_at FROM users";
if (!empty($search)) {
    $query .= " WHERE name LIKE ? OR email LIKE ?";
}
$query .= " ORDER BY created_at DESC";

if (!empty($search)) {
    $search_param = "%$search%";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ss", $search_param, $search_param);
        $stmt->execute();
        $users_result = $stmt->get_result();
    } else {
        $users_result = false;
    }
} else {
    $users_result = $conn->query($query);
}
?>

<div class="admin-page-header">
    <h1>Manage Users</h1>
</div>

<?php if (isset($_GET['deleted'])): ?>
    <div class="admin-message success">
        <p>User deleted successfully.</p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <div class="admin-message success">
        <p>User updated successfully.</p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="admin-message error">
        <p>Cannot delete admin users or perform this action.</p>
    </div>
<?php endif; ?>

<div class="admin-search">
    <form method="GET" action="manage-users.php" class="search-form">
        <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="admin-button">Search</button>
        <?php if (!empty($search)): ?>
            <a href="manage-users.php" class="admin-button">Clear</a>
        <?php endif; ?>
    </form>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Joined</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($users_result && $users_result->num_rows > 0): ?>
            <?php while($user = $users_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <?php if ($user['is_admin']): ?>
                        <span class="badge admin-badge">Admin</span>
                    <?php else: ?>
                        <span class="badge user-badge">User</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                <td class="actions">
                    <?php if (!$user['is_admin'] || $user['id'] != $_SESSION['admin_id']): ?>
                        <a href="?action=toggle_admin&id=<?php echo $user['id']; ?>" class="edit-link" onclick="return confirm('Change admin status for this user?');">
                            <?php echo $user['is_admin'] ? 'Remove Admin' : 'Make Admin'; ?>
                        </a>
                        <a href="?action=delete&id=<?php echo $user['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                            Delete
                        </a>
                    <?php else: ?>
                        <span class="no-action">Current Admin</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem;">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<style>
.admin-search {
    margin-bottom: 2rem;
}

.search-form {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.search-form input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid var(--card-border);
    background: var(--darker-bg);
    color: var(--text-primary);
    border-radius: 8px;
    font-size: 1rem;
    font-family: 'Satoshi', sans-serif;
}

.search-form input:focus {
    outline: none;
    border-color: var(--blue-accent);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
}

.admin-badge {
    background: rgba(37, 99, 235, 0.2);
    color: var(--blue-light);
    border: 1px solid rgba(37, 99, 235, 0.3);
}

.user-badge {
    background: rgba(107, 114, 128, 0.2);
    color: var(--text-secondary);
    border: 1px solid rgba(107, 114, 128, 0.3);
}

/* Styles moved to admin.css */

.no-action {
    color: var(--text-secondary);
    font-style: italic;
}
</style>

<?php
include 'includes/footer.php';
?>

