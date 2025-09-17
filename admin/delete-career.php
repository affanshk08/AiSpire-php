<?php
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}

$career_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($career_id > 0) {
    $stmt = $conn->prepare("DELETE FROM careers WHERE id = ?");
    $stmt->bind_param("i", $career_id);
    if ($stmt->execute()) {
        header("Location: index.php?status=deleted");
        exit();
    }
}

// If something went wrong, redirect with an error status
header("Location: index.php?status=error");
exit();
?>