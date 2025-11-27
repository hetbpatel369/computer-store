<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="../index.php"><i class="fas fa-desktop"></i> Admin Panel</a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Theme Switcher -->
                <li class="nav-item me-3">
                    <div class="btn-group" role="group" aria-label="Theme switcher">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-theme-value="light">Light</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-theme-value="dark">Dark</button>
                    </div>
                </li>

                <!-- Links -->
                <li class="nav-item"><a class="nav-link" href="../index.php">View Site</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>