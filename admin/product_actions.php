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
        if ($action === 'add') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $category = $_POST['category'];
            $image_url = $_POST['image_url'];

            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category, image_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $stock, $category, $image_url]);

            header("Location: products.php?success=Product added successfully");
            exit;

        } elseif ($action === 'edit') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $category = $_POST['category'];
            $image_url = $_POST['image_url'];

            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ?, image_url = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $stock, $category, $image_url, $id]);

            header("Location: products.php?success=Product updated successfully");
            exit;

        } elseif ($action === 'delete') {
            $id = $_POST['id'];
            
            // Optional: Check if product is in any orders before deleting?
            // For now, we'll just delete it.
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: products.php?success=Product deleted successfully");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: products.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}

header("Location: products.php");
exit;
?>
