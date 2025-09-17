<?php
// We need access to the database and sessions
require_once '../includes/db.php';

$errors = [];
$email = '';

// If admin is already logged in, redirect them to the dashboard
if (isset($_SESSION['is_admin'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required.";
    } else {
        // Prepare statement to find an ADMIN user
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? AND is_admin = 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                // Correct credentials, set admin session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['is_admin'] = true; // Set a specific admin flag
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Invalid credentials for admin.";
            }
        } else {
            $errors[] = "Invalid credentials or not an admin.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CareerCounsel</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@700,500,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-page" style="padding-top: 5rem;">
        <h2>Admin Login</h2>
        <p>Please enter your admin credentials.</p>

        <?php if (!empty($errors)): ?>
            <div class="errors" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
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
    </div>
</body>
</html>