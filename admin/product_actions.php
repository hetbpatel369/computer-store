<?php
require_once '../db/conn.php';
session_start();

// Check Admin Access
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit;
}

$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- ADD PRODUCT ---
    if ($action === 'add') {
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $cat = $_POST['category'];
        $img = $_POST['image_url'];

        // Insert Product
        $sql = "INSERT INTO products (name, description, price, stock, category, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdiss", $name, $desc, $price, $stock, $cat, $img);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: products.php?success=Product+added+successfully");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // --- DELETE PRODUCT ---
    elseif ($action === 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: products.php?success=Product+deleted+successfully");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // --- EDIT PRODUCT ---
    elseif ($action === 'edit') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $cat = $_POST['category'];
        $img = $_POST['image_url'];

        $sql = "UPDATE products SET name=?, description=?, price=?, stock=?, category=?, image_url=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdissi", $name, $desc, $price, $stock, $cat, $img, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: products.php?success=Product+updated+successfully");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>