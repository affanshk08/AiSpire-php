<?php
// This block of PHP code will handle the form submission
include 'includes/db.php';

$errors = [];
$name = '';
$email = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    // --- Validation ---
    if (empty($name)) { $errors[] = "Name is required."; }
    if (empty($email)) { $errors[] = "Email is required."; }
    if (empty($password)) { $errors[] = "Password is required."; }
    if ($password !== $password2) { $errors[] = "Passwords do not match."; }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "An account with this email already exists.";
    }
    $stmt->close();

    // --- If no errors, create user ---
    if (empty($errors)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        
        if ($stmt->execute()) {
            // Automatically log the user in after successful registration
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_name'] = $name;
            // Redirect to the careers page
            header("Location: careers.php");
            exit();
        } else {
            $errors[] = "Failed to create account. Please try again.";
        }
        $stmt->close();
    }
}

// Include the header
include 'includes/header.php';
?>

<div class="auth-page">
    <h2>Create Your Account</h2>
    <p>Join us to start your journey towards a fulfilling career.</p>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="signup.php" class="auth-form">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password2" required>
        </div>
        <button type="submit" class="btn-submit">Create Account</button>
    </form>
    <div class="auth-switch">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>

<style>
/* Simple styling for error messages */
.errors {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 8px;
}
</style>


<?php 
// Include the footer
include 'includes/footer.php'; 
?>