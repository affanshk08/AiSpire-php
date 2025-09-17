<?php 
// This is a protected page
include 'includes/header.php'; 

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data from the database using the ID stored in the session
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<div class="profile-page container">
    <div class="page-header">
        <h1>Your Profile</h1>
        <p>View and manage your account details.</p>
    </div>

    <?php if ($user): ?>
    <div class="profile-details">
        <div class="detail-item">
            <span class="detail-label">Name</span>
            <span class="detail-value"><?php echo htmlspecialchars($user['name']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Email</span>
            <span class="detail-value"><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
    </div>
    <?php else: ?>
        <p>Could not retrieve user details.</p>
    <?php endif; ?>
</div>

<style>
/* You can copy the styles from your MERN project's Profile.css */
.profile-details { max-width: 600px; margin: 2rem auto; background-color: var(--off-white); border-radius: 12px; padding: 2rem; border: 1px solid #e5e5e5; }
.detail-item { display: flex; justify-content: space-between; padding: 1.5rem 0; border-bottom: 1px solid #e5e5e5; }
.detail-item:last-child { border-bottom: none; }
.detail-label { font-weight: 500; color: var(--grey); }
.detail-value { font-weight: 700; font-size: 1.1rem; }
</style>

<?php 
include 'includes/footer.php'; 
?>