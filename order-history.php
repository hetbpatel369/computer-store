<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Computer Store</title>
    
    <!-- Bootstrap CSS -->
    <link href="assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-desktop"></i> Computer Store
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item d-flex align-items-center">
                        <div class="btn-group" role="group" aria-label="Theme switcher">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-theme-value="light">Light</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-theme-value="dark">Dark</button>
                        </div>
                    </li>
                    <li class="nav-item dropdown" id="nav-user-menu" style="display: none;">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <span class="nav-user-name"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="order-history.php">Order History</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-item" id="nav-cart-link" style="display: none;">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <span class="cart-count badge bg-danger" style="display: none;">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <h2 class="mb-4">Order History</h2>
        <div id="order-history-container">
            <!-- Orders will be loaded here -->
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center py-3">
            <p>&copy; 2024 Computer Store. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- Dark Mode Toggle -->
    <script src="assets/js/darkmodetoggle.js"></script>
    <script>
        // Update button states based on current theme
        document.addEventListener('DOMContentLoaded', function() {
            const updateButtonStates = () => {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                const lightBtn = document.querySelector('[data-bs-theme-value="light"]');
                const darkBtn = document.querySelector('[data-bs-theme-value="dark"]');
                
                if (lightBtn && darkBtn) {
                    if (currentTheme === 'dark') {
                        lightBtn.classList.remove('active');
                        darkBtn.classList.add('active');
                    } else {
                        lightBtn.classList.add('active');
                        darkBtn.classList.remove('active');
                    }
                }
            };
            
            // Update on load
            updateButtonStates();
            
            // Update when theme changes
            const observer = new MutationObserver(updateButtonStates);
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-bs-theme']
            });
        });
    </script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/auth.js"></script>
    <script src="assets/js/cart.js"></script>
    <script>
        // Display order history
        function displayOrderHistory() {
            if (!requireLogin()) return;

            const currentUser = getCurrentUser();
            const orders = getOrders();
            const userOrders = orders.filter(order => order.user_id === currentUser.id).reverse(); // Most recent first
            const products = getProducts();

            const container = document.getElementById('order-history-container');
            if (!container) return;

            if (userOrders.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h4>No orders yet</h4>
                        <p>Start shopping to see your orders here!</p>
                        <a href="products.php" class="btn btn-primary">Browse Products</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = userOrders.map(order => {
                let itemsHtml = '';
                if (order.items && order.items.length > 0) {
                    itemsHtml = order.items.map(item => {
                        const product = products.find(p => p.id === item.product_id);
                        return product ? `
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong>${product.name}</strong>
                                    <br>
                                    <small class="text-muted">Qty: ${item.quantity} x ${formatCurrency(item.price)}</small>
                                </div>
                                <div>${formatCurrency(item.price * item.quantity)}</div>
                            </div>
                        ` : '';
                    }).join('');
                }

                return `
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Order #${order.id}</h5>
                                <small class="text-muted">${new Date(order.order_date).toLocaleString()}</small>
                            </div>
                            <div>
                                <span class="badge bg-success">Completed</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Order Items:</h6>
                                    ${itemsHtml}
                                </div>
                                <div class="col-md-4">
                                    <h6>Shipping Information:</h6>
                                    ${order.shipping_info ? `
                                        <p class="mb-1"><strong>${order.shipping_info.name}</strong></p>
                                        <p class="mb-1">${order.shipping_info.address}</p>
                                        <p class="mb-1">${order.shipping_info.city}, ${order.shipping_info.zip}</p>
                                        <p class="mb-1">${order.shipping_info.phone}</p>
                                    ` : 'N/A'}
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong>Total:</strong>
                                        <strong class="text-primary">${formatCurrency(order.total_price)}</strong>
                                    </div>
                                    ${order.payment_method ? `
                                        <small class="text-muted">Payment: ${order.payment_method.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase())}</small>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Initialize order history page
        document.addEventListener('DOMContentLoaded', function() {
            displayOrderHistory();
        });
    </script>
</body>
</html>


