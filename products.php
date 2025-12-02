<?php
// Use the lecture-standard connection file
require_once 'db/conn.php';
$pageTitle = 'Products - Computer Store';
include 'includes/header.php';
include 'includes/navbar.php';

// Fetch Categories for Filter Dropdown
$cat_sql = "SELECT DISTINCT category FROM products ORDER BY category ASC";
$cat_result = mysqli_query($conn, $cat_sql);
$categories = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);

// 1. Build Dynamic Query
$sql = "SELECT * FROM products WHERE 1=1";
$types = "";
$params = [];

// Filter by Category
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $sql .= " AND category = ?";
    $types .= "s";
    $params[] = $_GET['category'];
}

// Search Functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $sql .= " AND name LIKE ?";
    $types .= "s";
    $params[] = '%' . $_GET['search'] . '%';
}

// Filter by Price Range
if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $sql .= " AND price >= ?";
    $types .= "d";
    $params[] = $_GET['min_price'];
}
if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $sql .= " AND price <= ?";
    $types .= "d";
    $params[] = $_GET['max_price'];
}

// Filter by Availability (In Stock)
if (isset($_GET['in_stock']) && $_GET['in_stock'] == '1') {
    $sql .= " AND stock > 0";
}

// Sorting
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
        $sql .= " ORDER BY id DESC"; // Default sort: Newest first
}

// 2. Execute Query using MySQLi Prepared Statements
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $products = [];
    $error = "Error loading products.";
}
?>

<main class="container my-5">
    <div class="row">
        <!-- Sidebar: Categories -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Categories</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="products.php" class="list-group-item list-group-item-action">All Products</a>
                    <a href="products.php?category=desktops" class="list-group-item list-group-item-action">Desktops</a>
                    <a href="products.php?category=laptops" class="list-group-item list-group-item-action">Laptops</a>
                    <a href="products.php?category=graphics-cards" class="list-group-item list-group-item-action">Graphics Cards</a>
                    <a href="products.php?category=memory" class="list-group-item list-group-item-action">Memory</a>
                    <a href="products.php?category=accessories" class="list-group-item list-group-item-action">Accessories</a>
                </div>
            </div>
        </div>

        <!-- Main Content: Product Grid -->
        <div class="col-md-9">
            <!-- Search and Filter Component -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="products.php" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search" placeholder="Search products..."
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button class="btn btn-outline-secondary" type="button" id="filterToggle">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>

                        <!-- Filter Panel (Hidden by default) -->
                        <div id="filterPanel" class="d-none border p-3 rounded bg-light">
                            <div class="row g-3">
                                <!-- Category Filter -->
                                <div class="col-md-4">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category">
                                        <option value="">All Categories</option>
                                        <?php foreach ($categories as $cat): ?>
                                                <option value="<?php echo htmlspecialchars($cat['category']); ?>"
                                                    <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['category']) ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst($cat['category']); ?>
                                                </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Price Range Filter -->
                                <div class="col-md-4">
                                    <label class="form-label">Price Range</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="min_price" placeholder="Min"
                                            value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                                        <span class="input-group-text">-</span>
                                        <input type="number" class="form-control" name="max_price" placeholder="Max"
                                            value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                                    </div>
                                </div>

                                <!-- Availability Filter -->
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStockCheck"
                                            <?php echo (isset($_GET['in_stock']) && $_GET['in_stock'] == '1') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="inStockCheck">
                                            In Stock Only
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Sort Options -->
                                <div class="col-md-12">
                                     <label class="form-label">Sort By</label>
                                     <select class="form-select" name="sort">
                                        <option value="default">Default</option>
                                        <option value="price-low" <?php echo $sort == 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                        <option value="price-high" <?php echo $sort == 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                        <option value="name-asc" <?php echo $sort == 'name-asc' ? 'selected' : ''; ?>>Name: A-Z</option>
                                        <option value="name-desc" <?php echo $sort == 'name-desc' ? 'selected' : ''; ?>>Name: Z-A</option>
                                    </select>
                                </div>

                                <div class="col-12 text-end mt-3">
                                    <button type="submit" class="btn btn-success">Apply Filters</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4">
                <?php if (empty($products)): ?>
                        <div class="col-12">
                            <p class="text-center">No products found.</p>
                        </div>
                <?php else: ?>
                        <?php foreach ($products as $product): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 product-card">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top"
                                                style="height: 250px; object-fit: contain; background-color: white;">
                                        </a>
                                        <div class="card-body d-flex flex-column">
                                            <a href="product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                            </a>
                                            <p class="card-text text-muted">
                                                <?php echo htmlspecialchars(substr($product['description'], 0, 80)) . '...'; ?>
                                            </p>
                                            <div class="mt-auto">
                                                <h5 class="text-primary mb-3">$<?php echo number_format($product['price'], 2); ?></h5>
                                                <div class="d-grid gap-2">
                                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary">Details</a>
                                                    <form action="cart_actions.php" method="POST">
                                                        <input type="hidden" name="action" value="add">
                                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
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

<script>
    document.getElementById('filterToggle').addEventListener('click', function() {
        var filterPanel = document.getElementById('filterPanel');
        if (filterPanel.classList.contains('d-none')) {
            filterPanel.classList.remove('d-none');
        } else {
            filterPanel.classList.add('d-none');
        }
    });
</script>

<?php include 'includes/footer.php'; ?>