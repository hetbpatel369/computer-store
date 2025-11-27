<?php
require_once 'db/conn.php'; // Include database connection
$pageTitle = 'Checkout - Computer Store';
include 'includes/header.php';
include 'includes/navbar.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_items = [];
$total = 0;

// Fetch cart items for the checkout summary
$sql = "SELECT c.quantity, p.id as product_id, p.name, p.price, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $cart_items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Redirect if cart is empty
    if (empty($cart_items)) {
        echo '<script>window.location.href = "cart.php";</script>';
        exit;
    }

    // Calculate total
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
} else {
    $error = "Error loading cart.";
}

// Handle Order Placement (Form Submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $shipping_name = $_POST['shipping_name'];
    $shipping_phone = $_POST['shipping_phone'];
    $shipping_address = $_POST['shipping_address'];
    $shipping_city = $_POST['shipping_city'];
    $shipping_zip = $_POST['shipping_zip'];
    $payment_method = $_POST['payment_method'];
    $order_total = $total * 1.10; // Total with 10% tax

    // Start Transaction
    // A transaction ensures that all database operations (create order, add items, update stock, clear cart)
    // happen together. If one fails, everything is rolled back, preventing data inconsistency.
    mysqli_begin_transaction($conn);

    try {
        // 1. Create Order Record
        $sql_order = "INSERT INTO orders (user_id, total_price, shipping_name, shipping_address, shipping_city, shipping_zip, shipping_phone, payment_method) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_order = mysqli_prepare($conn, $sql_order);
        mysqli_stmt_bind_param(
            $stmt_order,
            "idssssss",
            $user_id,
            $order_total,
            $shipping_name,
            $shipping_address,
            $shipping_city,
            $shipping_zip,
            $shipping_phone,
            $payment_method
        );

        if (!mysqli_stmt_execute($stmt_order)) {
            throw new Exception("Error creating order: " . mysqli_error($conn));
        }

        $order_id = mysqli_insert_id($conn); // Get the ID of the newly created order

        // 2. Create Order Items & Update Stock
        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_item = mysqli_prepare($conn, $sql_item);

        $sql_stock = "UPDATE products SET stock = stock - ? WHERE id = ?";
        $stmt_stock = mysqli_prepare($conn, $sql_stock);

        foreach ($cart_items as $item) {
            // Insert Order Item
            mysqli_stmt_bind_param($stmt_item, "iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            if (!mysqli_stmt_execute($stmt_item)) {
                throw new Exception("Error adding order item: " . mysqli_error($conn));
            }

            // Update Product Stock
            mysqli_stmt_bind_param($stmt_stock, "ii", $item['quantity'], $item['product_id']);
            if (!mysqli_stmt_execute($stmt_stock)) {
                throw new Exception("Error updating stock: " . mysqli_error($conn));
            }
        }

        // 3. Clear User's Cart
        $sql_clear = "DELETE FROM cart WHERE user_id = ?";
        $stmt_clear = mysqli_prepare($conn, $sql_clear);
        mysqli_stmt_bind_param($stmt_clear, "i", $user_id);
        if (!mysqli_stmt_execute($stmt_clear)) {
            throw new Exception("Error clearing cart: " . mysqli_error($conn));
        }

        // Commit Transaction (Save changes)
        mysqli_commit($conn);

        // Redirect to Order History with success message
        echo '<script>window.location.href = "order-history.php?success=1";</script>';
        exit;

    } catch (Exception $e) {
        // Rollback Transaction (Undo changes if error occurred)
        mysqli_rollback($conn);
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
        <!-- Checkout Form -->
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
                                <input type="text" class="form-control" id="shipping-name" name="shipping_name"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping-phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="shipping-phone" name="shipping_phone"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="shipping-address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="shipping-address" name="shipping_address"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping-city" class="form-label">City</label>
                                <input type="text" class="form-control" id="shipping-city" name="shipping_city"
                                    required>
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

        <!-- Order Summary -->
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
                                <small class="text-muted">Qty: <?php echo $item['quantity']; ?> x
                                    $<?php echo number_format($item['price'], 2); ?></small>
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