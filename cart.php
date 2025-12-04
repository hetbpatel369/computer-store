<?php
require_once 'db/conn.php';
$pageTitle = 'Shopping Cart';
include 'includes/header.php';
include 'includes/navbar.php';

// Initialize cart
$cart_items = [];
$total = 0;

// Check if logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Get cart items
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
    // User not logged in
}

// Calculate total
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="container my-5">
    <h2 class="mb-4 fw-bold">Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h3 class="text-muted">Your cart is empty</h3>
            <p class="mb-4">Looks like you haven't added anything to your cart yet.</p>
            <a href="products.php" class="btn btn-primary btn-lg rounded-pill px-5">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Cart Items List -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="row mb-4 align-items-center border-bottom pb-4 last-no-border">
                                <!-- Product Image -->
                                <div class="col-md-2 text-center">
                                    <div class="bg-light rounded p-2 d-flex align-items-center justify-content-center"
                                        style="height: 80px; width: 80px; margin: 0 auto;">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="img-fluid"
                                            style="max-height: 100%; object-fit: contain;"
                                            alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="col-md-4">
                                    <h5 class="mb-1"><a href="product.php?id=<?php echo $item['product_id']; ?>"
                                            class="text-decoration-none text-dark"><?php echo htmlspecialchars($item['name']); ?></a>
                                    </h5>
                                    <p class="text-muted mb-0 small">Price: $<?php echo number_format($item['price'], 2); ?></p>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-3">
                                    <form action="cart_actions.php" method="POST"
                                        class="d-flex align-items-center justify-content-center justify-content-md-start">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <input type="number" name="quantity" class="form-control text-center"
                                                value="<?php echo $item['quantity']; ?>" min="1"
                                                max="<?php echo $item['stock']; ?>">
                                            <button type="submit" class="btn btn-outline-secondary" title="Update Quantity">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Total & Remove -->
                                <div class="col-md-3 text-end">
                                    <div class="fw-bold mb-2">
                                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                                    <form action="cart_actions.php" method="POST">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0">
                                            <i class="fas fa-trash me-1"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tax (10%)</span>
                            <span>$<?php echo number_format($total * 0.10, 2); ?></span>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between mb-4">
                            <strong class="h5 mb-0">Total</strong>
                            <strong class="h5 mb-0 text-primary">$<?php echo number_format($total * 1.10, 2); ?></strong>
                        </div>
                        <a href="checkout.php" class="btn btn-primary w-100 btn-lg rounded-pill shadow-sm">Proceed to
                            Checkout</a>
                        <div class="text-center mt-3">
                            <a href="products.php" class="text-muted small text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .last-no-border:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
    }
</style>

<?php include 'includes/footer.php'; ?>