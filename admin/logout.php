<?php
// We need to start the session to be able to modify it.
// The path is ../ because we are one folder deep (in /admin).
require_once '../includes/db.php';

// Unset only the admin-specific session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['is_admin']);

// Redirect the user to the admin login page
header("Location: login.php");
exit();
?>