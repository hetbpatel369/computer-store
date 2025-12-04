<?php
// Start session
session_start();

// Unset session variables
$_SESSION = array();

// Destroy session
session_destroy();

// Redirect to login
header("Location: login.php");
exit;
?>