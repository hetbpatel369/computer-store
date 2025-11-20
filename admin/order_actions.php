<?php
require_once '../config/db.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit;
}

$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($action === 'update_status') {
            $order_id = $_POST['order_id'];
            $status = $_POST['status'];

            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $order_id]);

            header("Location: orders.php?success=Order status updated successfully");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: orders.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}

header("Location: orders.php");
exit;
?>
