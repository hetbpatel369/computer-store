// Cart JavaScript
// Handles shopping cart operations (add, remove, update quantities)

// Add to cart
function addToCart(productId, quantity = 1) {
    if (!isLoggedIn()) {
        showToast('Please login first', 'warning');
        redirectTo('login.html');
        return false;
    }
    
    const product = getProductById(productId);
    if (!product || product.stock < quantity) {
        showToast('Product not available', 'danger');
        return false;
    }
    
    const user = getCurrentUser();
    let cart = getCart();
    const item = cart.find(i => i.user_id === user.id && i.product_id === productId);
    
    if (item) {
        if (item.quantity + quantity > product.stock) {
            showToast('Stock limit reached', 'danger');
            return false;
        }
        item.quantity += quantity;
    } else {
        cart.push({
            id: getNextId(cart),
            user_id: user.id,
            product_id: productId,
            quantity: quantity
        });
    }
    
    saveCart(cart);
    updateCartCount();
    showToast('Added to cart!', 'success');
    return true;
}

// Add to cart from product detail page
function addToCartFromDetail(productId) {
    const quantityInput = document.getElementById('quantity');
    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
    addToCart(productId, quantity);
}

// Remove item from cart
function removeFromCart(cartItemId) {
    const cart = getCart();
    const filteredCart = cart.filter(item => item.id !== cartItemId);
    saveCart(filteredCart);
    showToast('Item removed from cart', 'info');
    displayCart();
    updateCartCount();
}

// Update cart item quantity
function updateCartQuantity(cartItemId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(cartItemId);
        return;
    }

    const cart = getCart();
    const item = cart.find(i => i.id === cartItemId);
    if (!item) return;

    const product = getProductById(item.product_id);
    if (!product) return;

    if (newQuantity > product.stock) {
        showToast(`Only ${product.stock} items available in stock`, 'danger');
        displayCart();
        return;
    }

    item.quantity = newQuantity;
    saveCart(cart);
    displayCart();
    updateCartCount();
}

// Get user's cart items
function getUserCart() {
    const currentUser = getCurrentUser();
    if (!currentUser) return [];

    const cart = getCart();
    return cart.filter(item => item.user_id === currentUser.id);
}

// Get cart items with product details
function getCartItemsWithDetails() {
    const userCart = getUserCart();
    const products = getProducts();
    
    return userCart.map(cartItem => {
        const product = products.find(p => p.id === cartItem.product_id);
        return {
            ...cartItem,
            product: product
        };
    }).filter(item => item.product); // Filter out items with missing products
}

// Calculate cart total
function calculateCartTotal() {
    const cartItems = getCartItemsWithDetails();
    return cartItems.reduce((total, item) => {
        return total + (item.product.price * item.quantity);
    }, 0);
}

// Display cart
function displayCart() {
    const container = document.getElementById('cart-items');
    if (!container) return;

    const cartItems = getCartItemsWithDetails();

    if (cartItems.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Add some products to get started!</p>
                <a href="products.html" class="btn btn-primary">Browse Products</a>
            </div>
        `;
        
        // Hide checkout button if present
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) checkoutBtn.style.display = 'none';
        
        return;
    }

    container.innerHTML = cartItems.map(item => `
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-2">
                    <img src="${item.product.image_url}" class="img-fluid rounded-start" alt="${item.product.name}" style="height: 150px; width: 100%; object-fit: cover;">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">${item.product.name}</h5>
                        <p class="card-text text-muted">${formatCurrency(item.product.price)} each</p>
                        <div class="d-flex align-items-center">
                            <label class="me-2">Quantity:</label>
                            <input type="number" class="form-control" value="${item.quantity}" min="1" max="${item.product.stock}" 
                                   style="width: 80px;" onchange="updateCartQuantity(${item.id}, parseInt(this.value))">
                            <span class="ms-3 text-muted">Stock: ${item.product.stock}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.id})">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                        <div class="mt-auto">
                            <strong class="text-primary">${formatCurrency(item.product.price * item.quantity)}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    // Display cart summary
    const total = calculateCartTotal();
    const summaryContainer = document.getElementById('cart-summary');
    if (summaryContainer) {
        summaryContainer.innerHTML = `
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${formatCurrency(total)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>${formatCurrency(total * 0.1)}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-primary">${formatCurrency(total * 1.1)}</strong>
                    </div>
                    <a href="checkout.html" class="btn btn-primary w-100" id="checkout-btn">Proceed to Checkout</a>
                </div>
            </div>
        `;
    } else {
        // Show checkout button if summary container doesn't exist
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.style.display = 'block';
            checkoutBtn.onclick = () => redirectTo('checkout.html');
        }
    }
}

// Update cart count in navigation
function updateCartCount() {
    const cartItems = getUserCart();
    const count = cartItems.reduce((sum, item) => sum + item.quantity, 0);
    
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? 'inline' : 'none';
    });
}

// Clear cart (after order placement)
function clearCart() {
    const currentUser = getCurrentUser();
    if (!currentUser) return;

    let cart = getCart();
    cart = cart.filter(item => item.user_id !== currentUser.id);
    saveCart(cart);
    updateCartCount();
}

// Initialize cart page
function initCartPage() {
    if (!requireLogin()) return;
    displayCart();
    updateCartCount();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    if (document.getElementById('cart-items')) {
        initCartPage();
    }
});

