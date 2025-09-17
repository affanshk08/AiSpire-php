<?php
require_once '../includes/db.php';

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CareerCounsel</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@700,500,400&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-panel container">
        <div class="admin-header">
            <h1>Manage Careers</h1>
            <div class="admin-header-buttons">
                <a href="index.php" class="admin-button">Dashboard</a>
                <a href="add-career.php" class="admin-button">Add New Career</a>
                <a href="logout.php" class="admin-button" style="background-color: #dc2626;">Logout</a>
            </div>
        </div>