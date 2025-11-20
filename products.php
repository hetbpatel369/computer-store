<?php
require_once 'config/db.php';
$pageTitle = 'Products - Computer Store';
include 'includes/header.php';
include 'includes/navbar.php';

// Build query
$where_clauses = [];
$params = [];

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $where_clauses[] = "category = ?";
    $params[] = $_GET['category'];
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $where_clauses[] = "name LIKE ?";
    $params[] = '%' . $_GET['search'] . '%';
}

$sql = "SELECT * FROM products";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

// Sort
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
switch ($sort) {
    case 'price-low':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price-high':
        $sql .= " ORDER BY price DESC";
        break;
    case 'name-asc':
        $sql .= " ORDER BY name ASC";
        break;
    case 'name-desc':
        $sql .= " ORDER BY name DESC";
        break;
    default:
        $sql .= " ORDER BY id DESC";
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
    $error = "Error loading products.";
}
?>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Categories</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="products.php" class="list-group-item list-group-item-action <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">All Products</a>
                            <a href="products.php?category=desktops" class="list-group-item list-group-item-action <?php echo (isset($_GET['category']) && $_GET['category'] == 'desktops') ? 'active' : ''; ?>">Desktops</a>
                            <a href="products.php?category=graphics-cards" class="list-group-item list-group-item-action <?php echo (isset($_GET['category']) && $_GET['category'] == 'graphics-cards') ? 'active' : ''; ?>">Graphics Cards</a>
                            <a href="products.php?category=memory" class="list-group-item list-group-item-action <?php echo (isset($_GET['category']) && $_GET['category'] == 'memory') ? 'active' : ''; ?>">Memory</a>
                            <a href="products.php?category=laptops" class="list-group-item list-group-item-action <?php echo (isset($_GET['category']) && $_GET['category'] == 'laptops') ? 'active' : ''; ?>">Laptops</a>
                            <a href="products.php?category=accessories" class="list-group-item list-group-item-action <?php echo (isset($_GET['category']) && $_GET['category'] == 'accessories') ? 'active' : ''; ?>">Accessories</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="col-md-9">
                <!-- Search and Sort -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <form action="products.php" method="GET">
                                    <?php if(isset($_GET['category'])): ?>
                                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                                    <?php endif; ?>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="products.php" method="GET" id="sortForm">
                                    <?php if(isset($_GET['category'])): ?>
                                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                                    <?php endif; ?>
                                    <?php if(isset($_GET['search'])): ?>
                                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                                    <?php endif; ?>
                                    <select class="form-select" name="sort" onchange="document.getElementById('sortForm').submit()">
                                        <option value="default" <?php echo $sort == 'default' ? 'selected' : ''; ?>>Sort by: Default</option>
                                        <option value="price-low" <?php echo $sort == 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                        <option value="price-high" <?php echo $sort == 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                        <option value="name-asc" <?php echo $sort == 'name-asc' ? 'selected' : ''; ?>>Name: A to Z</option>
                                        <option value="name-desc" <?php echo $sort == 'name-desc' ? 'selected' : ''; ?>>Name: Z to A</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row" id="products-container">
                    <?php if (empty($products)): ?>
                        <div class="col-12 text-center">
                            <p>No products found.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
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
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>
