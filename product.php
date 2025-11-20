<?php
require_once 'config/db.php';

$product = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();
}

$pageTitle = $product ? $product['name'] . ' - Computer Store' : 'Product Not Found';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="container my-5">
    <?php if ($product): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                <li class="breadcrumb-item"><a href="products.php?category=<?php echo $product['category']; ?>"><?php echo ucfirst($product['category']); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-6 mb-4">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/600x400?text=Product+Image'">
            </div>
            <div class="col-md-6">
                <h1 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="mb-3">
                    <span class="h3 text-primary">$<?php echo number_format($product['price'], 2); ?></span>
                    <?php if ($product['stock'] > 0): ?>
                        <span class="badge bg-success ms-2">In Stock (<?php echo $product['stock']; ?> available)</span>
                    <?php else: ?>
                        <span class="badge bg-danger ms-2">Out of Stock</span>
                    <?php endif; ?>
                </div>
                <p class="lead mb-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                
                    <form action="cart_actions.php" method="POST" class="d-inline">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <div class="input-group mb-3" style="max-width: 200px;">
                            <span class="input-group-text">Qty</span>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock']; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                
                <hr class="my-4">
                
                <div class="row">
                    <div class="col-6">
                        <h5>Category</h5>
                        <p class="text-muted"><?php echo ucfirst($product['category']); ?></p>
                    </div>
                    <div class="col-6">
                        <h5>SKU</h5>
                        <p class="text-muted">PROD-<?php echo str_pad($product['id'], 5, '0', STR_PAD_LEFT); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-exclamation-circle fa-5x text-muted mb-3"></i>
            <h2>Product Not Found</h2>
            <p class="lead">The product you are looking for does not exist or has been removed.</p>
            <a href="products.php" class="btn btn-primary mt-3">Browse Products</a>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
