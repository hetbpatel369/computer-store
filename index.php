<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Store - Home</title>
    
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
                        <a class="nav-link active" href="index.php">Home</a>
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
                    <li class="nav-item" id="nav-login-link">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item" id="nav-register-link">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item dropdown" id="nav-user-menu" style="display: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
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
                    <li class="nav-item" id="nav-admin-link" style="display: none;">
                        <a class="nav-link" href="admin/index.php">
                            <i class="fas fa-cog"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="text-shadow">Welcome to Computer Store</h1>
            <p class="text-shadow">Your one-stop shop for all computer products and accessories</p>
            <a href="products.php" class="btn btn-light btn-lg">
                <i class="fas fa-shopping-bag"></i> Shop Now
            </a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container my-5">
        <!-- Categories Section -->
        <section class="mb-5">
            <h2 class="text-center mb-4">Shop by Category</h2>
            <div class="row g-4 justify-content-center" id="categories-container">
                <!-- Categories will be loaded here -->
            </div>
        </section>

        <!-- Featured Products Section -->
        <section>
            <h2 class="text-center mb-4">Featured Products</h2>
            <div class="row g-4" id="featured-products">
                <!-- Featured products will be loaded here -->
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Computer Store</h5>
                    <p>Your trusted source for computer products and accessories.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact</h5>
                    <p>
                        <i class="fas fa-envelope"></i> info@computerstore.com<br>
                        <i class="fas fa-phone"></i> +1 (555) 123-4567
                    </p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; 2024 Computer Store. All rights reserved.</p>
            </div>
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
    <script>
        // Display categories
        document.addEventListener('DOMContentLoaded', function() {
            const categories = [
                { name: 'Desktops', icon: 'desktop', category: 'desktops' },
                { name: 'Graphics Cards', icon: 'microchip', category: 'graphics-cards' },
                { name: 'Memory', icon: 'memory', category: 'memory' },
                { name: 'Laptops', icon: 'laptop', category: 'laptops' },
                { name: 'Accessories', icon: 'keyboard', category: 'accessories' }
            ];

            const categoriesContainer = document.getElementById('categories-container');
            categoriesContainer.innerHTML = categories.map(cat => `
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="products.php?category=${cat.category}" class="category-card">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-${cat.icon} fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">${cat.name}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            `).join('');

            // Display featured products (first 6 products)
            const products = getProducts().slice(0, 6);
            const featuredContainer = document.getElementById('featured-products');
            if (products.length > 0) {
                featuredContainer.innerHTML = products.map(product => `
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 product-card">
                            <img src="${product.image_url}" class="card-img-top" alt="${product.name}" style="height: 250px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x300?text=Product+Image'">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text text-muted flex-grow-1">${product.description.substring(0, 100)}...</p>
                                <div class="mt-auto">
                                    <p class="card-text">
                                        <strong class="text-primary">${formatCurrency(product.price)}</strong>
                                        ${product.stock > 0 
                                            ? `<span class="badge bg-success ms-2">In Stock</span>` 
                                            : '<span class="badge bg-danger ms-2">Out of Stock</span>'}
                                    </p>
                                    <a href="product.php?id=${product.id}" class="btn btn-primary w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        });
    </script>
</body>
</html>


