<?php
// Ensure session is started to check login status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
        <div class="container-fluid position-relative">
            <!-- Brand Logo (Left) -->
            <a class="navbar-brand fw-bold text-uppercase" href="index.php" style="padding: 0 10px;">
                <i class="fas fa-laptop-code me-2"></i>Next Gen Tech
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Centered Links (Home, Products) -->
                <ul class="navbar-nav centered-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                            href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>"
                            href="products.php">Products</a>
                    </li>
                </ul>

                <!-- Right Side: User Menu & Cart -->
                <ul class="navbar-nav ms-auto align-items-center" style="padding: 0 10px;">
                    <!-- Cart Icon -->
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                        </a>
                    </li>

                    <!-- User Dropdown / Login Links -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg me-2"></i>
                                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                    <li><a class="dropdown-item" href="admin/index.php">Admin Panel</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="order-history.php">Order History</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary btn-sm px-3 ms-2" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary btn-sm px-3 ms-2" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>

                    <!-- Dark Mode Toggle -->
                    <li class="nav-item me-3 ms-3">
                        <div class="btn-group" role="group" aria-label="Theme switcher">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                data-bs-theme-value="light">Light</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                data-bs-theme-value="dark">Dark</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>