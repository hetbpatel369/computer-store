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
$action  = $_POST['action'] ?? '';

// Debug logging - log every request
$logFile = __DIR__ . '/order_actions.log';
$logData = sprintf(
    "[%s] REQUEST_METHOD=%s, action=%s, order_id=%s, user_id=%d\n",
    date('Y-m-d H:i:s'),
    $_SERVER['REQUEST_METHOD'],
    $action,
    $_POST['order_id'] ?? 'none',
    $user_id
);
file_put_contents($logFile, $logData, FILE_APPEND);

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'cancel') {
    $order_id = $_POST['order_id'] ?? '';
    
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Entering cancel logic for order_id=$order_id\n", FILE_APPEND);

    if (empty($order_id)) {
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] ERROR: Empty order_id\n", FILE_APPEND);
        header('Location: order-history.php?error=Invalid+order+ID');
        exit;
    }

    try {
        // Verify the order belongs to the current user
        $stmt = $pdo->prepare("SELECT id, status FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$order_id, $user_id]);
        $order = $stmt->fetch();
        
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Order found: " . ($order ? "YES (status={$order['status']})" : "NO") . "\n", FILE_APPEND);

        if (!$order) {
            header('Location: order-history.php?error=Order+not+found');
            exit;
        }

        // Only allow cancellation for pending/completed orders
        if (in_array($order['status'], ['pending', 'completed'])) {
            $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$order_id]);
            
            file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Order $order_id cancelled successfully\n", FILE_APPEND);

            header('Location: order-history.php?success=Order+cancelled+successfully');
            exit;
        }

        // If status is delivered or already cancelled
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Order cannot be cancelled (status={$order['status']})\n", FILE_APPEND);
        header('Location: order-history.php?error=Order+cannot+be+cancelled');
        exit;
    } catch (PDOException $e) {
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] DATABASE ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
        header('Location: order-history.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// Fallback - log and redirect
file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Fallback redirect (no action matched)\n", FILE_APPEND);
header('Location: order-history.php');
exit;
?>
