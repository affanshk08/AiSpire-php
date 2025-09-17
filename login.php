<?php
include 'includes/db.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) { $errors[] = "Email is required."; }
    if (empty($password)) { $errors[] = "Password is required."; }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: careers.php");
                exit();
            } else {
                $errors[] = "Invalid credentials.";
            }
        } else {
            $errors[] = "Invalid credentials.";
        }
        $stmt->close();
    }
}

include 'includes/header.php';
?>

<div class="auth-page">
    <h2>Welcome Back</h2>
    <p>Enter your credentials to access your account.</p>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php" class="auth-form">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-submit">Login</button>
    </form>
    <div class="auth-switch">
        Don't have an account? <a href="signup.php">Sign Up</a>
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
include 'includes/footer.php'; 
?>