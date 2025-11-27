<?php
require_once 'db/conn.php'; // Include database connection
$pageTitle = 'Shopping Cart';
include 'includes/header.php';
include 'includes/navbar.php';

// Initialize cart items array and total
$cart_items = [];
$total = 0;

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch cart items from database for the logged-in user
    // We join 'cart' table with 'products' table to get product details (name, price, image)
    $sql = "SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image_url, p.stock 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $cart_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // If not logged in, show a message or redirect (optional)
    // For now, we just keep the cart empty
}

// Calculate total price
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="container my-5">
    <h2 class="mb-4">Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">
            Your cart is empty. <a href="products.php">Start shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Cart Items List -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="row mb-3 align-items-center border-bottom pb-3">
                                <div class="col-md-2">
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="img-fluid rounded"
                                        alt="Product">
                                </div>
                                <div class="col-md-4">
                                    <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                    <p class="text-muted mb-0">$<?php echo number_format($item['price'], 2); ?></p>
                                </div>
                                <div class="col-md-3">
                                    <!-- Update Quantity Form -->
                                    <form action="cart_actions.php" method="POST" class="d-flex">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <input type="number" name="quantity" class="form-control form-control-sm me-2"
                                            value="<?php echo $item['quantity']; ?>" min="1"
                                            max="<?php echo $item['stock']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                                </div>
                                <div class="col-md-1 text-end">
                                    <!-- Remove Item Form -->
                                    <form action="cart_actions.php" method="POST">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax (10%)</span>
                            <span>$<?php echo number_format($total * 0.10, 2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong class="text-primary">$<?php echo number_format($total * 1.10, 2); ?></strong>
                        </div>
                        <a href="checkout.php" class="btn btn-success w-100 btn-lg">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>