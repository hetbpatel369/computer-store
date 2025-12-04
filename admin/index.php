<?php
require_once '../db/conn.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check Admin Access
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// --- Fetch Statistics ---
// Total Sales
$res = mysqli_query($conn, "SELECT SUM(total_price) as total FROM orders");
$row = mysqli_fetch_assoc($res);
$total_sales = $row['total'] ?? 0;

// Total Orders
$res = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
$row = mysqli_fetch_assoc($res);
$total_orders = $row['count'];

// Total Products
$res = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
$row = mysqli_fetch_assoc($res);
$total_products = $row['count'];

// Total Users
$res = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE is_admin = 0");
$row = mysqli_fetch_assoc($res);
$total_users = $row['count'];

// Recent Orders
$sql = "SELECT o.*, u.name as user_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.order_date DESC LIMIT 5";
$res = mysqli_query($conn, $sql);
$recent_orders = mysqli_fetch_all($res, MYSQLI_ASSOC);

$pageTitle = 'Admin Dashboard';
$pathPrefix = '../';
include '../includes/header.php';
include '../includes/admin_navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <!-- Dashboard Cards -->
            <div class="row mb-4">
                <!-- Total Sales -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Total Sales</div>
                        <div class="card-body">
                            <h5 class="card-title">$<?php echo number_format($total_sales, 2); ?></h5>
                        </div>
                    </div>
                </div>
                <!-- Total Orders -->
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Total Orders</div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $total_orders; ?></h5>
                        </div>
                    </div>
                </div>
                <!-- Total Products -->
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-header">Total Products</div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $total_products; ?></h5>
                        </div>
                    </div>
                </div>
                <!-- Total Users -->
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-header">Total Users</div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $total_users; ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <h2>Recent Orders</h2>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_orders)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No orders found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                                    <td><a href="orders.php" class="btn btn-sm btn-info">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>