<?php
require_once 'db/conn.php'; // Include database connection
$pageTitle = 'Computer Store - Home';
include 'includes/header.php'; // Include HTML header
include 'includes/navbar.php'; // Include Navigation bar
?>

<!-- Hero Section: Main visual area with video background -->
<section class="hero-section">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="hero-video">
        <source src="img/media/Hero-Section-Video.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="hero-overlay"></div> <!-- Dark overlay for text readability -->

    <!-- Hero Content -->
    <div class="hero-content container text-center text-white">
        <h1 class="display-3 fw-bold text-shadow mb-4">Next Gen Tech</h1>
        <p class="lead text-shadow mb-5">Upgrade your setup with the latest and greatest hardware.</p>
        <a href="products.php" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold shadow">Shop Now</a>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Featured Products</h2>
        <div class="row g-4" id="featured-products">
            <?php
            // --- FETCH FEATURED PRODUCTS ---
            // We want to display a few products on the home page to attract users.
            // The LIMIT 4 clause ensures we only get the first 4 products.
            $sql = "SELECT * FROM products LIMIT 4";
            $result = mysqli_query($conn, $sql);

            // Check if the query was successful
            if ($result) {
                // Check if any products were returned
                if (mysqli_num_rows($result) > 0) {
                    // Loop through each row (product) in the result set
                    while ($product = mysqli_fetch_assoc($result)) {
                        // We use htmlspecialchars() to prevent XSS attacks when outputting data
                        ?>
                        <!-- Product Card -->
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 product-card border-0 shadow-sm">
                                <!-- Product Image (Clickable) -->
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <div class="overflow-hidden">
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top"
                                            style="height: 200px; object-fit: contain; background-color: white;">
                                    </div>
                                </a>
                                <!-- Product Details -->
                                <div class="card-body d-flex flex-column text-center">
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    </a>
                                    <p class="card-text text-primary fw-bold mb-3">
                                        $<?php echo number_format($product['price'], 2); ?></p>
                                    <!-- Buy Now Button -->
                                    <a href="product.php?id=<?php echo $product['id']; ?>"
                                        class="btn btn-dark w-100 mt-auto rounded-pill">Buy Now</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="text-center">No products found.</p>';
                }
            } else {
                // Display error if query fails (useful for debugging)
                echo "Error: " . mysqli_error($conn);
            }
            ?>
        </div>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-4">About Us</h2>
                <p class="lead text-muted mb-4">
                    This is a Term Project of Internet Tools Course by Ben Kam
                </p>
                <hr class="w-25 mx-auto mb-4 border-primary" style="opacity: 1; height: 3px;">
                <p class="text-muted">
                    This Project Was Developed By Het Patel, Zenil Babaria and Pranjal
                </p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; // Include Footer ?>