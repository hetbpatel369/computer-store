<?php
require_once '../db/conn.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check Admin Access
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// --- Fetch Statistics (MySQLi) ---
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
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    require_once '../db/conn.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check Admin Access
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header("Location: ../login.php");
        exit();
    }

    // --- Fetch Statistics (MySQLi) ---
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
    ?>
    <!DOCTYPE html>
    <html lang="en" data-bs-theme="light">

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

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/darkmodetoggle.js"></script>
    </body>

    </html>