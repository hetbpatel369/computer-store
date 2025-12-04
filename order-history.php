<?php
require_once 'db/conn.php';
if (session_status() === PHP_SESSION_NONE)
    session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $order_id = $_POST['cancel_order_id'];

    // Verify order
    $check_sql = "SELECT status FROM orders WHERE id = ? AND user_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ii", $order_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    $order_data = mysqli_fetch_assoc($check_result);

    // Cancel if pending
    if ($order_data && $order_data['status'] == 'pending') {
        $update_sql = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $order_id);
        if (mysqli_stmt_execute($update_stmt)) {
            $message = "Order #$order_id has been cancelled.";
        } else {
            $message = "Error cancelling order.";
        }
    } else {
        $message = "Order cannot be cancelled.";
    }
}

// Fetch Orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

$pageTitle = 'Order History';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="container my-5">
    <h2 class="mb-4">Order History</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                            <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                            <td>
                                <?php
                                $status = $order['status'] ?? 'pending';
                                // Badge color
                                $badgeClass = match ($status) {
                                    'in-transit' => 'bg-primary',
                                    'delivered' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-warning'
                                };
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                            </td>
                            <td>
                                <?php if ($status == 'pending'): ?>
                                    <!-- Cancel Button -->
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        <input type="hidden" name="cancel_order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Cancel Order</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>