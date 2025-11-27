<?php
require_once 'db/conn.php'; // Include database connection
$pageTitle = 'Product Details';
include 'includes/header.php';
include 'includes/navbar.php';

// Check if 'id' is passed in the URL
if (!isset($_GET['id'])) {
    // Redirect to products page if no ID is provided
    header("Location: products.php");
    exit;
}

$product_id = $_GET['id'];

// Fetch product details from database
// We use prepared statements to prevent SQL injection
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id); // 'i' means integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// Check if product exists
if (!$product) {
    echo "<div class='container my-5'><h2>Product not found</h2></div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid rounded shadow"
                alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
            <h3 class="text-primary mb-3">$<?php echo number_format($product['price'], 2); ?></h3>

            <p class="lead mb-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <div class="mb-4">
                <strong>Category:</strong> <?php echo ucfirst($product['category']); ?><br>
                <strong>Stock:</strong>
                <?php if ($product['stock'] > 0): ?>
                    <span class="text-success">In Stock (<?php echo $product['stock']; ?> available)</span>
                <?php else: ?>
                    <span class="text-danger">Out of Stock</span>
                <?php endif; ?>
            </div>

            <!-- Add to Cart Form -->
            <form action="cart_actions.php" method="POST" class="d-flex align-items-center">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                <div class="me-3" style="width: 100px;">
                    <input type="number" name="quantity" class="form-control" value="1" min="1"
                        max="<?php echo $product['stock']; ?>" <?php echo $product['stock'] == 0 ? 'disabled' : ''; ?>>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" <?php echo $product['stock'] == 0 ? 'disabled' : ''; ?>>
                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>