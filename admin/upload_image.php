<?php
require_once '../config/db.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $upload_dir = '../assets/images/products/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file = $_FILES['image'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Get file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Allowed extensions
    $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    
    if (in_array($file_ext, $allowed)) {
        if ($file_error === 0) {
            if ($file_size <= 5000000) { // 5MB max
                // Generate unique filename
                $new_file_name = uniqid('product_', true) . '.' . $file_ext;
                $file_destination = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    // Return the relative path for database storage
                    $relative_path = 'assets/images/products/' . $new_file_name;
                    echo json_encode(['success' => true, 'path' => $relative_path]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'File size too large (max 5MB)']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Error uploading file']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid file type. Allowed: jpg, jpeg, png, gif, webp']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
}
?>
