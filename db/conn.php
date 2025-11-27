<?php
// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "computer_store";

// Create Connection (MySQLi Procedural)
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>