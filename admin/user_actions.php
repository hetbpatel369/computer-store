<?php
require_once '../db/conn.php';
session_start();

// Security Check: Ensure user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit;
}

// --- DELETE USER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $user_id = $_POST['user_id'];

    // Prevent deleting your own admin account
    if ($user_id == $_SESSION['user_id']) {
        header("Location: users.php?error=Cannot+delete+yourself");
        exit;
    }

    // Only delete non-admin users (safety check)
    $sql = "DELETE FROM users WHERE id = ? AND is_admin = 0";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: users.php?success=User+deleted+successfully");
    } else {
        header("Location: users.php?error=Failed+to+delete+user");
    }
}
?>