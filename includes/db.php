<?php
// Start session to manage user login state across pages
session_start();

// Database Credentials
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'careercounsel_php_db';

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>