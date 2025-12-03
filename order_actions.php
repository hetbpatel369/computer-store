<?php
// Start session first, before any output
session_start();

// Database connection
require_once 'db/conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'cancel') {
    $order_id = $_POST['order_id'] ?? '';

    if (empty($order_id)) {
        header('Location: order-history.php?error=Invalid+order+ID');
        exit;
    }

    // Verify the order belongs to the current user
    $sql = "SELECT id, status FROM orders WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);

    if (!$order) {
        header('Location: order-history.php?error=Order+not+found');
        exit;
    }

    // Only allow cancellation for pending/completed orders
    if (in_array($order['status'], ['pending', 'completed'])) {
        $update_sql = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $order_id);

        if (mysqli_stmt_execute($update_stmt)) {
            header('Location: order-history.php?success=Order+cancelled+successfully');
            exit;
        } else {
            header('Location: order-history.php?error=Failed+to+cancel+order');
            exit;
        }
    }

    // If status is delivered or already cancelled
    header('Location: order-history.php?error=Order+cannot+be+cancelled');
    exit;
}

// Fallback redirect
header('Location: order-history.php');
exit;
?>