// Products JavaScript
// Handles product browsing, filtering, search, and display

// Display products on page
function displayProducts(products, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (products.length === 0) {
        container.innerHTML = '<div class="col-12"><p class="text-center text-muted">No products found.</p></div>';
        return;
    }

    container.innerHTML = products.map(product => `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 product-card">
                <img src="${product.image_url}" class="card-img-top" alt="${product.name}" style="height: 250px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x300?text=Product+Image'">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text text-muted flex-grow-1">${product.description.substring(0, 100)}...</p>
                    <div class="mt-auto">
                        <p class="card-text">
                            <strong class="text-primary">${formatCurrency(product.price)}</strong>
                            ${product.stock > 0 
                                ? `<span class="badge bg-success ms-2">In Stock (${product.stock})</span>` 
                                : '<span class="badge bg-danger ms-2">Out of Stock</span>'}
                        </p>
                        <a href="product.html?id=${product.id}" class="btn btn-primary w-100 mb-2">View Details</a>
                        ${isLoggedIn() && product.stock > 0 
                            ? `<button class="btn btn-outline-success w-100" onclick="addToCart(${product.id})">Add to Cart</button>` 
                            : ''}
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Display single product detail
function displayProductDetail(product) {
    if (!product) {
        document.querySelector('.container').innerHTML = '<div class="alert alert-danger">Product not found.</div>';
        return;
    }

    const container = document.getElementById('product-detail');
    if (!container) return;

    container.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <img src="${product.image_url}" class="img-fluid rounded" alt="${product.name}" onerror="this.src='https://via.placeholder.com/600x400?text=Product+Image'">
            </div>
            <div class="col-md-6">
                <h1>${product.name}</h1>
                <p class="text-muted">Category: <span class="badge bg-secondary">${formatCategory(product.category)}</span></p>
                <h3 class="text-primary mb-3">${formatCurrency(product.price)}</h3>
                <p class="lead">${product.description}</p>
                <div class="mb-3">
                    ${product.stock > 0 
                        ? `<span class="badge bg-success fs-6">In Stock (${product.stock} available)</span>` 
                        : '<span class="badge bg-danger fs-6">Out of Stock</span>'}
                </div>
                ${product.stock > 0 ? `
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" id="quantity" class="form-control" value="1" min="1" max="${product.stock}" style="width: 100px;">
                    </div>
                    ${isLoggedIn() 
                        ? `<button class="btn btn-primary btn-lg" onclick="addToCartFromDetail(${product.id})">Add to Cart</button>` 
                        : '<a href="login.html" class="btn btn-primary btn-lg">Login to Add to Cart</a>'}
                ` : '<button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>'}
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <h3>Related Products</h3>
                <div id="related-products" class="row"></div>
            </div>
        </div>
    `;

    // Display related products
    const relatedProducts = getProducts()
        .filter(p => p.category === product.category && p.id !== product.id)
        .slice(0, 3);
    displayProducts(relatedProducts, 'related-products');
}

// Get product by ID
function getProductById(id) {
    const products = getProducts();
    return products.find(p => p.id === parseInt(id));
}

// Filter products by category
function filterProductsByCategory(category) {
    const products = getProducts();
    if (!category || category === 'all') {
        return products;
    }
    return products.filter(p => p.category === category);
}

// Search products
function searchProducts(query) {
    const products = getProducts();
    if (!query) return products;
    
    const lowerQuery = query.toLowerCase();
    return products.filter(p => 
        p.name.toLowerCase().includes(lowerQuery) ||
        p.description.toLowerCase().includes(lowerQuery) ||
        p.category.toLowerCase().includes(lowerQuery)
    );
}

// Sort products
function sortProducts(products, sortBy) {
    const sorted = [...products];
    switch(sortBy) {
        case 'price-low':
            return sorted.sort((a, b) => a.price - b.price);
        case 'price-high':
            return sorted.sort((a, b) => b.price - a.price);
        case 'name-asc':
            return sorted.sort((a, b) => a.name.localeCompare(b.name));
        case 'name-desc':
            return sorted.sort((a, b) => b.name.localeCompare(a.name));
        default:
            return sorted;
    }
}

// Format category name for display
function formatCategory(category) {
    return category.split('-').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ');
}

// Get all categories
function getCategories() {
    const products = getProducts();
    const categories = [...new Set(products.map(p => p.category))];
    return categories;
}

// Display category filter
function displayCategoryFilter(containerId, currentCategory = 'all') {
    const container = document.getElementById(containerId);
    if (!container) return;

    const categories = getCategories();
    container.innerHTML = `
        <div class="list-group">
            <a href="products.html" class="list-group-item list-group-item-action ${currentCategory === 'all' ? 'active' : ''}">
                All Categories
            </a>
            ${categories.map(cat => `
                <a href="products.html?category=${cat}" class="list-group-item list-group-item-action ${currentCategory === cat ? 'active' : ''}">
                    ${formatCategory(cat)}
                </a>
            `).join('')}
        </div>
    `;
}

// Initialize products page
function initProductsPage() {
    const category = getUrlParameter('category') || 'all';
    const searchQuery = getUrlParameter('search') || '';
    const sortBy = getUrlParameter('sort') || 'default';

    let products = filterProductsByCategory(category);
    
    if (searchQuery) {
        products = searchProducts(searchQuery);
    }

    products = sortProducts(products, sortBy);

    displayProducts(products, 'products-container');
    displayCategoryFilter('category-filter', category);

    // Update search input if present
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.value = searchQuery;
    }

    // Update sort select if present
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.value = sortBy;
    }
}

// Initialize product detail page
function initProductDetailPage() {
    const productId = getUrlParameter('id');
    if (!productId) {
        showToast('Product ID is required', 'danger');
        redirectTo('products.html');
        return;
    }

    const product = getProductById(productId);
    displayProductDetail(product);
}

// Handle search form submission
function handleSearch(event) {
    event.preventDefault();
    const searchInput = document.getElementById('search-input');
    const query = searchInput ? searchInput.value : '';
    const category = getUrlParameter('category') || 'all';
    
    if (query) {
        redirectTo(`products.html?search=${encodeURIComponent(query)}&category=${category}`);
    } else {
        redirectTo(`products.html?category=${category}`);
    }
}

// Handle sort change
function handleSortChange() {
    const sortSelect = document.getElementById('sort-select');
    const category = getUrlParameter('category') || 'all';
    const search = getUrlParameter('search') || '';
    
    if (sortSelect) {
        const sortBy = sortSelect.value;
        let url = `products.html?sort=${sortBy}`;
        if (category !== 'all') url += `&category=${category}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        redirectTo(url);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('products-container')) {
        initProductsPage();
    }
    if (document.getElementById('product-detail')) {
        initProductDetailPage();
    }
});

