<?php
require_once 'config/db.php';
$pageTitle = 'Order History - Computer Store';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}

$user_id = $_SESSION['user_id'];
$orders = [];

try {
    // Fetch orders
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();

    // Fetch items for each order
    foreach ($orders as &$order) {
        $stmt_items = $pdo->prepare("
            SELECT oi.*, p.name, p.image_url 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt_items->execute([$order['id']]);
        $order['items'] = $stmt_items->fetchAll();
    }
} catch (PDOException $e) {
    $error = "Error loading orders.";
}
?>

<main class="container my-5">
    <h2 class="mb-4">Order History</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div id="order-history-container">
        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                <h4>No orders yet</h4>
                <p class="text-muted">Start shopping to see your orders here!</p>
                <a href="products.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Order #<?php echo $order['id']; ?></h5>
                        <small class="text-muted"><?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></small>
                    </div>
                    <div>
                        <?php 
                        $status = $order['status'] ?? 'pending';
                        $badge_class = 'bg-warning';
                        if ($status === 'completed') $badge_class = 'bg-success';
                        if ($status === 'delivered') $badge_class = 'bg-info';
                        if ($status === 'cancelled') $badge_class = 'bg-danger';
                        ?>
                        <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($status); ?></span>
                        
                        <?php if ($status !== 'cancelled' && $status !== 'delivered'): ?>
                            <form action="/computer-store/order_actions.php" method="POST" class="d-inline ms-2" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                <input type="hidden" name="action" value="cancel">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-times"></i> Cancel Order
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Order Items:</h6>
                            <?php foreach ($order['items'] as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                    <br>
                                    <small class="text-muted">Qty: <?php echo $item['quantity']; ?> x $<?php echo number_format($item['price'], 2); ?></small>
                                </div>
                                <div>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="col-md-4">
                            <h6>Shipping Information:</h6>
                            <p class="mb-1"><strong><?php echo htmlspecialchars($order['shipping_name']); ?></strong></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_city']) . ', ' . htmlspecialchars($order['shipping_zip']); ?></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong class="text-primary">$<?php echo number_format($order['total_price'], 2); ?></strong>
                            </div>
                            <small class="text-muted">Payment: <?php echo ucwords(str_replace('-', ' ', $order['payment_method'])); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
