<?php
require_once 'db/conn.php';
$pageTitle = 'Product Details';
include 'includes/header.php';
include 'includes/navbar.php';

// Check if 'id' is passed
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = $_GET['id'];

// Fetch product details
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// Check if product exists
if (!$product) {
    echo "<div class='container my-5'><h2>Product not found</h2></div>";
    include 'includes/footer.php';
    exit;
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    // Check login
    if (!isset($_SESSION['user_id'])) {
        $error = "You must be logged in to submit a review.";
    } else {
        $user_id = $_SESSION['user_id'];
        $rating = (int) $_POST['rating'];
        $comment = trim($_POST['comment']);

        // Validation
        if ($rating < 1 || $rating > 5) {
            $error = "Invalid rating.";
        } elseif (empty($comment)) {
            $error = "Comment cannot be empty.";
        } else {
            // Insert review
            $stmt = mysqli_prepare($conn, "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiis", $product_id, $user_id, $rating, $comment);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Review submitted successfully!";
            } else {
                $error = "Error submitting review.";
            }
        }
    }
}
// Fetch reviews
$reviews_sql = "SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC";
$stmt_reviews = mysqli_prepare($conn, $reviews_sql);
mysqli_stmt_bind_param($stmt_reviews, "i", $product_id);
mysqli_stmt_execute($stmt_reviews);
$reviews_result = mysqli_stmt_get_result($stmt_reviews);
$reviews = mysqli_fetch_all($reviews_result, MYSQLI_ASSOC);
?>

<div class="container my-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center justify-content-center bg-white rounded">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid"
                        style="max-height: 500px; object-fit: contain;"
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>
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

            <!-- Reviews Section -->
            <div class="mt-5">
                <h3 class="mb-4">Customer Reviews</h3>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Display Reviews -->
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet. Be the first to review this product!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title"><?php echo htmlspecialchars($review['user_name']); ?></h5>
                                    <small
                                        class="text-muted"><?php echo date('M j, Y', strtotime($review['created_at'])); ?></small>
                                </div>
                                <div class="text-warning mb-2">
                                    <?php for ($i = 0; $i < $review['rating']; $i++)
                                        echo '<i class="fas fa-star"></i>'; ?>
                                    <?php for ($i = $review['rating']; $i < 5; $i++)
                                        echo '<i class="far fa-star"></i>'; ?>
                                </div>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Review Form -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="card mb-4 mt-4">
                        <div class="card-header">Write a Review</div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <select name="rating" class="form-select" required>
                                        <option value="5">5 Stars - Excellent</option>
                                        <option value="4">4 Stars - Good</option>
                                        <option value="3">3 Stars - Average</option>
                                        <option value="2">2 Stars - Poor</option>
                                        <option value="1">1 Star - Terrible</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Comment</label>
                                    <textarea name="comment" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mt-4">Please <a href="login.php">login</a> to write a review.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>