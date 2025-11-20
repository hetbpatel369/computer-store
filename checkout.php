<?php
require_once 'config/db.php';
$pageTitle = 'Checkout - Computer Store';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_items = [];
$total = 0;

// Fetch cart items
try {
    $stmt = $pdo->prepare("
        SELECT c.quantity, p.id as product_id, p.name, p.price, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();

    if (empty($cart_items)) {
        echo '<script>window.location.href = "cart.php";</script>';
        exit;
    }

    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
} catch (PDOException $e) {
    $error = "Error loading cart.";
}

// Handle Order Placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_name = $_POST['shipping_name'];
    $shipping_phone = $_POST['shipping_phone'];
    $shipping_address = $_POST['shipping_address'];
    $shipping_city = $_POST['shipping_city'];
    $shipping_zip = $_POST['shipping_zip'];
    $payment_method = $_POST['payment_method'];

    try {
        $pdo->beginTransaction();

        // 1. Create Order
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_price, shipping_name, shipping_address, shipping_city, shipping_zip, shipping_phone, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $user_id, 
            $total * 1.10, // Total with tax
            $shipping_name,
            $shipping_address,
            $shipping_city,
            $shipping_zip,
            $shipping_phone,
            $payment_method
        ]);
        $order_id = $pdo->lastInsertId();

        // 2. Create Order Items & Update Stock
        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

        foreach ($cart_items as $item) {
            $stmt_item->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            $stmt_stock->execute([$item['quantity'], $item['product_id']]);
        }

        // 3. Clear Cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $pdo->commit();
        echo '<script>window.location.href = "order-history.php?success=1";</script>';
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Order failed: " . $e->getMessage();
    }
}
?>

<main class="container my-5">
    <h2 class="mb-4">Checkout</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Shipping Information</h5>
                </div>
                <div class="card-body">
                    <form id="checkout-form" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping-name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="shipping-name" name="shipping_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping-phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="shipping-phone" name="shipping_phone" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="shipping-address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="shipping-address" name="shipping_address" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping-city" class="form-label">City</label>
                                <input type="text" class="form-control" id="shipping-city" name="shipping_city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping-zip" class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" id="shipping-zip" name="shipping_zip" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="payment-method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment-method" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="credit-card">Credit Card</option>
                                <option value="debit-card">Debit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="cash-on-delivery">Cash on Delivery</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                            <br>
                            <small class="text-muted">Qty: <?php echo $item['quantity']; ?> x $<?php echo number_format($item['price'], 2); ?></small>
                        </div>
                        <div>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                    </div>
                    <?php endforeach; ?>
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
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
