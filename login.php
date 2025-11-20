<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Computer Store</title>
    
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
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="card-title text-center mb-4">Login</h2>
                        <form id="login-form" method="POST" onsubmit="handleLogin(event)">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember-me" name="remember_me">
                                <label class="form-check-label" for="remember-me">
                                    Remember me
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                            <div class="text-center">
                                <p>Don't have an account? <a href="register.php">Register here</a></p>
                                <p><a href="admin/index.php">Admin Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
    <script>
        function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            loginUser(email, password);
        }

        // Redirect if already logged in
        document.addEventListener('DOMContentLoaded', function() {
            if (isLoggedIn()) {
                if (isAdmin()) {
                    redirectTo('admin/index.php');
                } else {
                    redirectTo('index.php');
                }
            }
        });
    </script>
</body>
</html>


