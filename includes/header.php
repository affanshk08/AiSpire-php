<?php
// We are in the 'includes' folder, and db.php is also here.
// The correct path is just the filename.
require_once 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerCounsel</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@700,500,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="App">
        <header class="header">
            <nav class="navbar container">
                <a href="index.php" class="nav-logo">CareerCounsel</a>
                <ul class="nav-menu">
                    <li><a href="careers.php">Careers</a></li>
                    <li><a href="assessments.php">Assessments</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
                <div class="nav-auth">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="nav-button login-button">Profile</a>
                        <a href="logout.php" class="nav-button signup-button">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="nav-button login-button">Login</a>
                        <a href="signup.php" class="nav-button signup-button">Sign Up Free</a>
                    <?php endif; ?>
                </div>
            </nav>
        </header>
        <main>