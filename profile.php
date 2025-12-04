<?php 
include 'includes/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$errors = [];
$success = false;
$user_id = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    // Check if email is already taken by another user
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "This email is already taken by another user.";
        }
        $stmt->close();
    }
    
    // Password update (optional)
    $update_password = false;
    if (!empty($new_password)) {
        if (empty($current_password)) {
            $errors[] = "Current password is required to change password.";
        } else {
            // Verify current password
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
            $stmt->close();
            
            if (!password_verify($current_password, $user_data['password'])) {
                $errors[] = "Current password is incorrect.";
            } elseif ($new_password !== $confirm_password) {
                $errors[] = "New passwords do not match.";
            } elseif (strlen($new_password) < 6) {
                $errors[] = "New password must be at least 6 characters.";
            } else {
                $update_password = true;
            }
        }
    }
    
    // Update profile if no errors
    if (empty($errors)) {
        if ($update_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $user_id);
        }
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name;
            $success = true;
        } else {
            $errors[] = "Failed to update profile. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch user's inquiries (check if table exists first)
$inquiries = null;
$inquiries_result = $conn->prepare("SELECT ci.*, c.title as career_title FROM career_inquiries ci JOIN careers c ON ci.career_id = c.id WHERE ci.user_id = ? ORDER BY ci.created_at DESC LIMIT 5");
if ($inquiries_result !== false) {
    $inquiries_result->bind_param("i", $user_id);
    $inquiries_result->execute();
    $inquiries = $inquiries_result->get_result();
}

// Fetch user's appointments (check if table exists first)
$appointments = null;
$appointments_result = $conn->prepare("SELECT * FROM appointments WHERE user_id = ? ORDER BY appointment_date DESC, appointment_time DESC LIMIT 5");
if ($appointments_result !== false) {
    $appointments_result->bind_param("i", $user_id);
    $appointments_result->execute();
    $appointments = $appointments_result->get_result();
}

$edit_mode = isset($_GET['edit']) && $_GET['edit'] == '1';
?>

<div class="profile-page container">
    <div class="page-header">
        <h1>Your Profile</h1>
        <p>Manage your account details and view your activity.</p>
    </div>

    <?php if ($success): ?>
        <div class="success-message">
            <p>Profile updated successfully!</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($user): ?>
        <div class="profile-content">
            <div class="profile-main">
                <div class="profile-details">
                    <?php if (!$edit_mode): ?>
                        <div class="profile-header">
                            <h2>Account Information</h2>
                            <a href="?edit=1" class="edit-profile-btn">Edit Profile</a>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Name</span>
                            <span class="detail-value"><?php echo htmlspecialchars($user['name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Member Since</span>
                            <span class="detail-value"><?php echo date('F d, Y', strtotime($user['created_at'])); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="profile-header">
                            <h2>Edit Profile</h2>
                            <a href="profile.php" class="edit-profile-btn">Cancel</a>
                        </div>
                        <form method="POST" action="profile.php" class="profile-edit-form">
                            <input type="hidden" name="action" value="update">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required minlength="2">
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="current_password">Current Password (required to change password)</label>
                                <input type="password" name="current_password" id="current_password" placeholder="Enter current password">
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password (leave blank to keep current)</label>
                                <input type="password" name="new_password" id="new_password" minlength="6" placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" minlength="6" placeholder="Confirm new password">
                            </div>
                            <button type="submit" class="btn-submit">Update Profile</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-activity">
                <div class="activity-section">
                    <h2>Recent Career Inquiries</h2>
                    <?php if ($inquiries !== null && $inquiries->num_rows > 0): ?>
                        <div class="activity-list">
                            <?php while($inquiry = $inquiries->fetch_assoc()): ?>
                                <div class="activity-item">
                                    <div class="activity-header">
                                        <strong><?php echo htmlspecialchars($inquiry['career_title']); ?></strong>
                                        <span class="status-badge status-<?php echo $inquiry['status']; ?>">
                                            <?php echo ucfirst($inquiry['status']); ?>
                                        </span>
                                    </div>
                                    <p class="activity-subject"><?php echo htmlspecialchars($inquiry['subject']); ?></p>
                                    <small class="activity-date"><?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?></small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <a href="career-inquiry.php" class="view-all-link">View All Inquiries</a>
                    <?php else: ?>
                        <p class="no-activity">No inquiries yet. <a href="careers.php">Browse careers</a> to ask questions.</p>
                    <?php endif; ?>
                </div>

                <div class="activity-section">
                    <h2>Recent Appointments</h2>
                    <?php if ($appointments !== null && $appointments->num_rows > 0): ?>
                        <div class="activity-list">
                            <?php while($appointment = $appointments->fetch_assoc()): ?>
                                <div class="activity-item">
                                    <div class="activity-header">
                                        <strong><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></strong>
                                        <span class="status-badge status-<?php echo $appointment['status']; ?>">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </div>
                                    <p class="activity-subject"><?php echo htmlspecialchars(substr($appointment['purpose'], 0, 100)); ?><?php echo strlen($appointment['purpose']) > 100 ? '...' : ''; ?></p>
                                    <small class="activity-date">Booked on <?php echo date('M d, Y', strtotime($appointment['created_at'])); ?></small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <a href="book-appointment.php" class="view-all-link">Book New Appointment</a>
                    <?php else: ?>
                        <p class="no-activity">No appointments yet. <a href="book-appointment.php">Book an appointment</a> for career counselling.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>Could not retrieve user details.</p>
    <?php endif; ?>
</div>

<style>
.profile-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.profile-main {
    min-width: 0;
}

.profile-details {
    background: linear-gradient(135deg, var(--card-bg) 0%, #1a1f2e 100%);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.6s ease-out;
}

.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--card-border);
}

.profile-header h2 {
    margin: 0;
    font-size: 1.5rem;
}

.edit-profile-btn {
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, var(--dark-blue) 0%, var(--blue-accent) 100%);
    color: var(--white);
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.edit-profile-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
}

.profile-edit-form {
    margin-top: 1rem;
}

.profile-activity {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.activity-section {
    background: linear-gradient(135deg, var(--card-bg) 0%, #1a1f2e 100%);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.6s ease-out;
}

.activity-section h2 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--card-border);
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    padding: 1rem;
    background: var(--darker-bg);
    border: 1px solid var(--card-border);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.activity-item:hover {
    border-color: rgba(37, 99, 235, 0.5);
    transform: translateX(5px);
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.activity-subject {
    color: var(--text-secondary);
    margin: 0.5rem 0;
}

.activity-date {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.view-all-link {
    display: inline-block;
    margin-top: 1rem;
    color: var(--blue-light);
    font-weight: 500;
    transition: color 0.3s ease;
}

.view-all-link:hover {
    color: var(--blue-accent);
}

.no-activity {
    color: var(--text-secondary);
    text-align: center;
    padding: 2rem;
}

.no-activity a {
    color: var(--blue-light);
    text-decoration: underline;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.status-responded {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.status-resolved {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.status-approved {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.status-rejected {
    background: rgba(220, 38, 38, 0.2);
    color: #dc2626;
    border: 1px solid rgba(220, 38, 38, 0.3);
}

.status-completed {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.status-cancelled {
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.success-message {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #86efac;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    text-align: center;
}

@media (max-width: 992px) {
    .profile-content {
        grid-template-columns: 1fr;
    }
}
</style>

<?php 
include 'includes/footer.php'; 
?>
