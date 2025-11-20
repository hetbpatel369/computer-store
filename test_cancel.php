<!DOCTYPE html>
<html>
<head>
    <title>Test Cancel Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Test Order Cancellation</h2>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5>Order #3 - Status: Pending</h5>
                <p>This order should have a cancel button</p>
                <form action="order_actions.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    <input type="hidden" name="action" value="cancel">
                    <input type="hidden" name="order_id" value="3">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Cancel Order #3
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5>Check Database</h5>
                <?php
                require_once 'config/db.php';
                session_start();
                
                $stmt = $pdo->query("SELECT id, user_id, status, total_price FROM orders WHERE user_id = 2 ORDER BY id DESC");
                $orders = $stmt->fetchAll();
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['user_id']; ?></td>
                            <td><span class="badge bg-<?php echo $order['status'] === 'cancelled' ? 'danger' : 'warning'; ?>"><?php echo $order['status']; ?></span></td>
                            <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
