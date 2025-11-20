<?php
require_once '../config/db.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Fetch Products
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error loading products.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Computer Store Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="../assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-desktop"></i> Computer Store - Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <div class="btn-group" role="group" aria-label="Theme switcher">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-theme-value="light">Light</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-theme-value="dark">Dark</button>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">View Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 admin-sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Products</h1>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#product-form-section">
                        <i class="fas fa-plus"></i> Add New Product
                    </button>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <!-- Product Form -->
                <div class="collapse mb-4" id="product-form-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 id="product-form-title">Add New Product</h5>
                        </div>
                        <div class="card-body">
                            <form action="product_actions.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="product-name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="product-name" name="name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="product-category" class="form-label">Category</label>
                                        <select class="form-select" id="product-category" name="category" required>
                                            <option value="">Select category</option>
                                            <option value="desktops">Desktops</option>
                                            <option value="graphics-cards">Graphics Cards</option>
                                            <option value="memory">Memory</option>
                                            <option value="laptops">Laptops</option>
                                            <option value="accessories">Accessories</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="product-description" class="form-label">Description</label>
                                    <textarea class="form-control" id="product-description" name="description" rows="3" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="product-price" class="form-label">Price ($)</label>
                                        <input type="number" class="form-control" id="product-price" name="price" step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="product-stock" class="form-label">Stock</label>
                                        <input type="number" class="form-control" id="product-stock" name="stock" min="0" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Product Image</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="product-image-file" class="form-label text-muted small">Upload Image</label>
                                                <input type="file" class="form-control" id="product-image-file" accept="image/*" onchange="handleImageUpload(this, 'product-image-url')">
                                                <small class="text-muted">Max 5MB (jpg, png, gif, webp)</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="product-image-url" class="form-label text-muted small">Or Enter URL</label>
                                                <input type="text" class="form-control" id="product-image-url" name="image_url" placeholder="https://..." required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Product</button>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#product-form-section">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>All Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-products-table">
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td><img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                                        <td><?php echo $product['stock']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                                                onclick="populateEditForm(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($product['description'], ENT_QUOTES); ?>', <?php echo $product['price']; ?>, <?php echo $product['stock']; ?>, '<?php echo htmlspecialchars($product['category'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($product['image_url'], ENT_QUOTES); ?>')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="product_actions.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Edit Product Modal -->
                <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="product_actions.php" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" id="edit-product-id">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="edit-product-name" class="form-label">Product Name</label>
                                            <input type="text" class="form-control" id="edit-product-name" name="name" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="edit-product-category" class="form-label">Category</label>
                                            <select class="form-select" id="edit-product-category" name="category" required>
                                                <option value="">Select category</option>
                                                <option value="desktops">Desktops</option>
                                                <option value="graphics-cards">Graphics Cards</option>
                                                <option value="memory">Memory</option>
                                                <option value="laptops">Laptops</option>
                                                <option value="accessories">Accessories</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-product-description" class="form-label">Description</label>
                                        <textarea class="form-control" id="edit-product-description" name="description" rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="edit-product-price" class="form-label">Price ($)</label>
                                            <input type="number" class="form-control" id="edit-product-price" name="price" step="0.01" min="0" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="edit-product-stock" class="form-label">Stock</label>
                                            <input type="number" class="form-control" id="edit-product-stock" name="stock" min="0" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Product Image</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="edit-product-image-file" class="form-label text-muted small">Upload Image</label>
                                                    <input type="file" class="form-control" id="edit-product-image-file" accept="image/*" onchange="handleImageUpload(this, 'edit-product-image-url')">
                                                    <small class="text-muted">Max 5MB (jpg, png, gif, webp)</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="edit-product-image-url" class="form-label text-muted small">Or Enter URL</label>
                                                    <input type="text" class="form-control" id="edit-product-image-url" name="image_url" placeholder="https://..." required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- Dark Mode Toggle -->
    <script src="../assets/js/darkmodetoggle.js"></script>
    <script>
        // Update button states based on current theme
        document.addEventListener('DOMContentLoaded', function() {
            const updateButtonStates = () => {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                const lightBtn = document.querySelector('[data-bs-theme-value="light"]');
                const darkBtn = document.querySelector('[data-bs-theme-value="dark"]');
                
                if (lightBtn && darkBtn) {
                    if (currentTheme === 'dark') {
                        lightBtn.classList.remove('active');
                        darkBtn.classList.add('active');
                    } else {
                        lightBtn.classList.add('active');
                        darkBtn.classList.remove('active');
                    }
                }
            };
            
            // Update on load
            updateButtonStates();
            
            // Update when theme changes
            const observer = new MutationObserver(updateButtonStates);
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-bs-theme']
            });
        });

        // Populate edit form with product data
        function populateEditForm(id, name, description, price, stock, category, imageUrl) {
            document.getElementById('edit-product-id').value = id;
            document.getElementById('edit-product-name').value = name;
            document.getElementById('edit-product-description').value = description;
            document.getElementById('edit-product-price').value = price;
            document.getElementById('edit-product-stock').value = stock;
            document.getElementById('edit-product-category').value = category;
            document.getElementById('edit-product-image-url').value = imageUrl;
        }

        // Handle image upload
        async function handleImageUpload(input, targetInputId) {
            const file = input.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('upload_image.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById(targetInputId).value = result.path;
                    alert('Image uploaded successfully!');
                } else {
                    alert('Upload failed: ' + result.error);
                    input.value = ''; // Clear the file input
                }
            } catch (error) {
                alert('Upload error: ' + error.message);
                input.value = ''; // Clear the file input
            }
        }
    </script>
    <!-- Custom JS -->
    <script src="../assets/js/main.js"></script>
</body>
</html>
