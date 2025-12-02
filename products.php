<?php
// Use the lecture-standard connection file
require_once 'db/conn.php';
$pageTitle = 'Products - Computer Store';
include 'includes/header.php';
include 'includes/navbar.php';

// 1. Build Dynamic Query
// We start with a base query that selects all products.
// "WHERE 1=1" is a common trick to make appending "AND" clauses easier.
$sql = "SELECT * FROM products WHERE 1=1";
$types = "";
$params = [];

// Filter by Category
// Check if 'category' is present in the URL (e.g., products.php?category=laptops)
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $sql .= " AND category = ?"; // Add condition to SQL
    $types .= "s"; // 's' indicates the parameter is a string
    $params[] = $_GET['category']; // Add the value to parameters array
}

// Search Functionality
// Check if 'search' is present in the URL
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $sql .= " AND name LIKE ?";
    $types .= "s";
    $params[] = '%' . $_GET['search'] . '%'; // Add wildcards for partial matching
}

// Sorting
// We use a switch statement to handle different sort options safely.
// This prevents SQL injection by not inserting user input directly into ORDER BY.
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
// Prepared statements are crucial for security (preventing SQL Injection).
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Dynamic binding: Only bind parameters if we have any (from filters/search)
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt); // Run the query
    $result = mysqli_stmt_get_result($stmt); // Get the result set
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC); // Fetch all rows as an associative array
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
                    <!-- Links to filter products by category -->
                    <a href="products.php" class="list-group-item list-group-item-action">All Products</a>
                    <a href="products.php?category=desktops" class="list-group-item list-group-item-action">Desktops</a>
                    <a href="products.php?category=laptops" class="list-group-item list-group-item-action">Laptops</a>
                    <a href="products.php?category=graphics-cards"
                        class="list-group-item list-group-item-action">Graphics Cards</a>
                    <a href="products.php?category=memory" class="list-group-item list-group-item-action">Memory</a>
                    <a href="products.php?category=accessories"
                        class="list-group-item list-group-item-action">Accessories</a>
                </div>
            </div>
        </div>

        <!-- Main Content: Product Grid -->
        <div class="col-md-9">
            <!-- Search and Sort Controls -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="products.php" method="GET" class="row g-3">
                        <!-- Search Input -->
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="search" placeholder="Search products..."
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        </div>
                        <!-- Sort Dropdown -->
                        <div class="col-md-4">
                            <select class="form-select" name="sort" onchange="this.form.submit()">
                                <option value="default">Sort by: Default</option>
                                <option value="price-low" <?php echo $sort == 'price-low' ? 'selected' : ''; ?>>Price: Low
                                    to High</option>
                                <option value="price-high" <?php echo $sort == 'price-high' ? 'selected' : ''; ?>>Price:
                                    High to Low</option>
                            </select>
                        </div>
                        <!-- Preserve category filter if it exists -->
                        <?php if (isset($_GET['category'])): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                        <?php endif; ?>
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
                                <!-- Product Image Link -->
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top"
                                        style="height: 250px; object-fit: contain; background-color: white;">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <!-- Product Name Link -->
                                    <a href="product.php?id=<?php echo $product['id']; ?>"
                                        class="text-decoration-none text-dark">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    </a>
                                    <!-- Description Truncated -->
                                    <p class="card-text text-muted">
                                        <?php echo htmlspecialchars(substr($product['description'], 0, 80)) . '...'; ?>
                                    </p>
                                    <div class="mt-auto">
                                        <h5 class="text-primary mb-3">$<?php echo number_format($product['price'], 2); ?></h5>
                                        <div class="d-grid gap-2">
                                            <!-- View Details Button -->
                                            <a href="product.php?id=<?php echo $product['id']; ?>"
                                                class="btn btn-outline-primary">Details</a>
                                            <!-- Add to Cart Form -->
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

<?php include 'includes/footer.php'; ?>