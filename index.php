<?php
require_once 'config/db.php';
$pageTitle = 'Computer Store - Home';
include 'includes/header.php';
include 'includes/navbar.php';
?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="text-shadow">Welcome to Computer Store</h1>
            <p class="text-shadow">Your one-stop shop for all computer products and accessories</p>
            <a href="products.php" class="btn btn-light btn-lg">
                <i class="fas fa-shopping-bag"></i> Shop Now
            </a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container my-5">
        <!-- Categories Section -->
        <section class="mb-5">
            <h2 class="text-center mb-4">Shop by Category</h2>
            <div class="row g-4 justify-content-center" id="categories-container">
                <!-- Categories rendered by JS for now, or could be static HTML -->
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="products.php?category=desktops" class="category-card">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-desktop fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Desktops</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="products.php?category=graphics-cards" class="category-card">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-microchip fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Graphics Cards</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="products.php?category=memory" class="category-card">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-memory fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Memory</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="products.php?category=laptops" class="category-card">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-laptop fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Laptops</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="products.php?category=accessories" class="category-card">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-keyboard fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Accessories</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section>
            <h2 class="text-center mb-4">Featured Products</h2>
            <div class="row g-4" id="featured-products">
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM products LIMIT 6");
                    while ($product = $stmt->fetch()) {
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 product-card">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 250px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x300?text=Product+Image'">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>
                                <div class="mt-auto">
                                    <p class="card-text">
                                        <strong class="text-primary">$<?php echo number_format($product['price'], 2); ?></strong>
                                        <?php if ($product['stock'] > 0): ?>
                                            <span class="badge bg-success ms-2">In Stock</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger ms-2">Out of Stock</span>
                                        <?php endif; ?>
                                    </p>
                                    <div class="d-flex gap-2">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary flex-grow-1">Details</a>
                                        <form action="cart_actions.php" method="POST" class="flex-grow-1">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary w-100" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                                <i class="fas fa-cart-plus"></i> Add
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                } catch(PDOException $e) {
                    echo '<div class="alert alert-danger">Error loading products.</div>';
                }
                ?>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>
