<?php
require_once 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($action === 'add') {
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];

            // Check stock
            $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();

            if ($product && $product['stock'] >= $quantity) {
                // Check if item exists in cart
                $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$user_id, $product_id]);
                $existing_item = $stmt->fetch();

                if ($existing_item) {
                    // Update quantity
                    $new_quantity = $existing_item['quantity'] + $quantity;
                    if ($new_quantity <= $product['stock']) {
                        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
                        $stmt->execute([$new_quantity, $existing_item['id']]);
                    } else {
                        // Handle stock limit reached
                    }
                } else {
                    // Insert new item
                    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $product_id, $quantity]);
                }
            }
        } elseif ($action === 'update') {
            $cart_id = $_POST['cart_id'];
            $quantity = (int)$_POST['quantity'];

            if ($quantity > 0) {
                // Check stock first (optional but good)
                // For simplicity, just update for now, or join with products to check stock
                $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$quantity, $cart_id, $user_id]);
            } else {
                // Remove if quantity is 0 or less
                $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
                $stmt->execute([$cart_id, $user_id]);
            }

        } elseif ($action === 'remove') {
            $cart_id = $_POST['cart_id'];
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$cart_id, $user_id]);
        }
    } catch (PDOException $e) {
        // Log error
    }
}

// Redirect back
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: cart.php");
}
exit;
?>
