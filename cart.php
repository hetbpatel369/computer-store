<?php
require_once 'config/db.php';
$pageTitle = 'Shopping Cart - Computer Store';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_items = [];
$total = 0;

try {
    $stmt = $pdo->prepare("
        SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image_url, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error loading cart.";
}
?>

<main class="container my-5">
    <h2 class="mb-4">Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4>Your cart is empty</h4>
            <p class="text-muted">Add some products to get started!</p>
            <a href="products.php" class="btn btn-primary">Browse Products</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($cart_items as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($item['name']); ?>" style="height: 150px; width: 100%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/150'">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text text-muted">$<?php echo number_format($item['price'], 2); ?> each</p>
                                
                                <form action="cart_actions.php" method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <label class="me-2">Quantity:</label>
                                    <input type="number" name="quantity" class="form-control" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" style="width: 80px;" onchange="this.form.submit()">
                                    <span class="ms-3 text-muted">Stock: <?php echo $item['stock']; ?></span>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <form action="cart_actions.php" method="POST">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </form>
                                <div class="mt-auto text-end">
                                    <strong class="text-primary">$<?php echo number_format($subtotal, 2); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (10%):</span>
                            <span>$<?php echo number_format($total * 0.10, 2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary">$<?php echo number_format($total * 1.10, 2); ?></strong>
                        </div>
                        <a href="checkout.php" class="btn btn-primary w-100">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
