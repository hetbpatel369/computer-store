<?php
session_start();
require_once 'db/conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add item to cart
    if ($action === 'add') {
        $product_id = $_POST['product_id'];
        $quantity = (int) $_POST['quantity'];

        // Check stock
        $sql_stock = "SELECT stock FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql_stock);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $res_stock = mysqli_stmt_get_result($stmt);
        $product = mysqli_fetch_assoc($res_stock);

        if ($product && $product['stock'] >= $quantity) {
            // Check if item exists in cart
            $sql_check = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
            $stmt_check = mysqli_prepare($conn, $sql_check);
            mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $product_id);
            mysqli_stmt_execute($stmt_check);
            $res_check = mysqli_stmt_get_result($stmt_check);
            $existing_item = mysqli_fetch_assoc($res_check);

            if ($existing_item) {
                // Update quantity
                $new_quantity = $existing_item['quantity'] + $quantity;
                if ($new_quantity <= $product['stock']) {
                    $sql_update = "UPDATE cart SET quantity = ? WHERE id = ?";
                    $stmt_update = mysqli_prepare($conn, $sql_update);
                    mysqli_stmt_bind_param($stmt_update, "ii", $new_quantity, $existing_item['id']);
                    mysqli_stmt_execute($stmt_update);
                }
            } else {
                // Insert new item
                $sql_insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                mysqli_stmt_bind_param($stmt_insert, "iii", $user_id, $product_id, $quantity);
                mysqli_stmt_execute($stmt_insert);
            }
        }
    }

    // Update quantity
    elseif ($action === 'update') {
        $cart_id = $_POST['cart_id'];
        $quantity = (int) $_POST['quantity'];

        if ($quantity > 0) {
            $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iii", $quantity, $cart_id, $user_id);
            mysqli_stmt_execute($stmt);
        } else {
            // Remove item
            $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
            mysqli_stmt_execute($stmt);
        }
    }

    // Remove item
    elseif ($action === 'remove') {
        $cart_id = $_POST['cart_id'];
        $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
        mysqli_stmt_execute($stmt);
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