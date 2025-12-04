<?php
require_once '../db/conn.php';
session_start();

// Check Admin Access
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // --- UPDATE ORDER STATUS ---
    if ($action === 'update_status') {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        // Update status
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: orders.php?success=Order+status+updated");
        } else {
            header("Location: orders.php?error=Failed+to+update+status");
        }
    }
}
?>