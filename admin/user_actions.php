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
        if ($action === 'delete') {
            $user_id = $_POST['user_id'];
            
            // Prevent admin from deleting themselves
            if ($user_id == $_SESSION['user_id']) {
                header("Location: users.php?error=Cannot delete your own account");
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND is_admin = 0");
            $stmt->execute([$user_id]);

            header("Location: users.php?success=User deleted successfully");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: users.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}

header("Location: users.php");
exit;
?>
