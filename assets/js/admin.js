// Admin JavaScript
// Handles admin CRUD operations for products, orders, and users

// ============ PRODUCT MANAGEMENT ============

// Display all products in admin table
function displayAdminProducts() {
    const container = document.getElementById('admin-products-table');
    if (!container) return;

    const products = getProducts();
    
    if (products.length === 0) {
        container.innerHTML = '<tr><td colspan="7" class="text-center">No products found.</td></tr>';
        return;
    }

    container.innerHTML = products.map(product => `
        <tr>
            <td>${product.id}</td>
            <td><img src="${product.image_url}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover;"></td>
            <td>${product.name}</td>
            <td>${formatCategory(product.category)}</td>
            <td>${formatCurrency(product.price)}</td>
            <td>${product.stock}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editProduct(${product.id})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </td>
        </tr>
    `).join('');
}

// Add new product
function addProduct(formData) {
    const products = getProducts();
    
    const newProduct = {
        id: getNextId(products),
        name: formData.name,
        description: formData.description,
        price: parseFloat(formData.price),
        image_url: formData.image_url || 'https://via.placeholder.com/400x300?text=Product',
        category: formData.category,
        stock: parseInt(formData.stock)
    };

    products.push(newProduct);
    saveProducts(products);
    showToast('Product added successfully!', 'success');
    displayAdminProducts();
    return true;
}

// Edit product
function editProduct(productId) {
    const product = getProductById(productId);
    if (!product) {
        showToast('Product not found', 'danger');
        return;
    }

    // Populate form
    document.getElementById('product-id').value = product.id;
    document.getElementById('product-name').value = product.name;
    document.getElementById('product-description').value = product.description;
    document.getElementById('product-price').value = product.price;
    document.getElementById('product-image-url').value = product.image_url;
    document.getElementById('product-category').value = product.category;
    document.getElementById('product-stock').value = product.stock;

    // Show form modal or section
    const productForm = document.getElementById('product-form-section');
    if (productForm) {
        productForm.scrollIntoView({ behavior: 'smooth' });
    }

    // Change form title
    const formTitle = document.getElementById('product-form-title');
    if (formTitle) {
        formTitle.textContent = 'Edit Product';
    }

    const submitBtn = document.getElementById('product-submit-btn');
    if (submitBtn) {
        submitBtn.textContent = 'Update Product';
        submitBtn.onclick = () => updateProduct(productId);
    }
}

// Update product
function updateProduct(productId) {
    const products = getProducts();
    const productIndex = products.findIndex(p => p.id === productId);
    
    if (productIndex === -1) {
        showToast('Product not found', 'danger');
        return false;
    }

    products[productIndex] = {
        id: productId,
        name: document.getElementById('product-name').value,
        description: document.getElementById('product-description').value,
        price: parseFloat(document.getElementById('product-price').value),
        image_url: document.getElementById('product-image-url').value || 'https://via.placeholder.com/400x300?text=Product',
        category: document.getElementById('product-category').value,
        stock: parseInt(document.getElementById('product-stock').value)
    };

    saveProducts(products);
    showToast('Product updated successfully!', 'success');
    displayAdminProducts();
    resetProductForm();
    return true;
}

// Delete product
function deleteProduct(productId) {
    if (!confirm('Are you sure you want to delete this product?')) {
        return;
    }

    const products = getProducts();
    const filteredProducts = products.filter(p => p.id !== productId);
    saveProducts(filteredProducts);
    showToast('Product deleted successfully!', 'success');
    displayAdminProducts();
}

// Reset product form
function resetProductForm() {
    document.getElementById('product-id').value = '';
    document.getElementById('product-name').value = '';
    document.getElementById('product-description').value = '';
    document.getElementById('product-price').value = '';
    document.getElementById('product-image-url').value = '';
    document.getElementById('product-category').value = '';
    document.getElementById('product-stock').value = '';

    const formTitle = document.getElementById('product-form-title');
    if (formTitle) {
        formTitle.textContent = 'Add New Product';
    }

    const submitBtn = document.getElementById('product-submit-btn');
    if (submitBtn) {
        submitBtn.textContent = 'Add Product';
        submitBtn.onclick = handleProductSubmit;
    }
}

// Handle product form submission
function handleProductSubmit(event) {
    event.preventDefault();
    
    const productId = document.getElementById('product-id').value;
    if (productId) {
        updateProduct(parseInt(productId));
    } else {
        const formData = {
            name: document.getElementById('product-name').value,
            description: document.getElementById('product-description').value,
            price: document.getElementById('product-price').value,
            image_url: document.getElementById('product-image-url').value,
            category: document.getElementById('product-category').value,
            stock: document.getElementById('product-stock').value
        };
        addProduct(formData);
        event.target.reset();
    }
}

// ============ ORDER MANAGEMENT ============

// Display all orders
function displayAdminOrders() {
    const container = document.getElementById('admin-orders-table');
    if (!container) return;

    const orders = getOrders();
    const users = getUsers();
    
    if (orders.length === 0) {
        container.innerHTML = '<tr><td colspan="5" class="text-center">No orders found.</td></tr>';
        return;
    }

    container.innerHTML = orders.map(order => {
        const user = users.find(u => u.id === order.user_id);
        return `
            <tr>
                <td>#${order.id}</td>
                <td>${user ? user.name : 'Unknown User'}</td>
                <td>${user ? user.email : 'N/A'}</td>
                <td>${formatCurrency(order.total_price)}</td>
                <td>${new Date(order.order_date).toLocaleDateString()}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="viewOrderDetails(${order.id})">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

// View order details
function viewOrderDetails(orderId) {
    const orders = getOrders();
    const order = orders.find(o => o.id === orderId);
    
    if (!order) {
        showToast('Order not found', 'danger');
        return;
    }

    const products = getProducts();
    const users = getUsers();
    const user = users.find(u => u.id === order.user_id);

    let detailsHtml = `
        <h5>Order #${order.id}</h5>
        <p><strong>Customer:</strong> ${user ? user.name : 'Unknown'} (${user ? user.email : 'N/A'})</p>
        <p><strong>Order Date:</strong> ${new Date(order.order_date).toLocaleString()}</p>
        <p><strong>Total:</strong> ${formatCurrency(order.total_price)}</p>
        <hr>
        <h6>Order Items:</h6>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (order.items && order.items.length > 0) {
        order.items.forEach(item => {
            const product = products.find(p => p.id === item.product_id);
            if (product) {
                detailsHtml += `
                    <tr>
                        <td>${product.name}</td>
                        <td>${item.quantity}</td>
                        <td>${formatCurrency(item.price)}</td>
                        <td>${formatCurrency(item.price * item.quantity)}</td>
                    </tr>
                `;
            }
        });
    }

    detailsHtml += `
            </tbody>
        </table>
    `;

    // Show in modal or alert
    const modalBody = document.getElementById('order-details-modal-body');
    if (modalBody) {
        modalBody.innerHTML = detailsHtml;
        const modal = new bootstrap.Modal(document.getElementById('order-details-modal'));
        modal.show();
    } else {
        alert(detailsHtml.replace(/<[^>]*>/g, '\n'));
    }
}

// ============ USER MANAGEMENT ============

// Display all users
function displayAdminUsers() {
    const container = document.getElementById('admin-users-table');
    if (!container) return;

    const users = getUsers();
    const currentUser = getCurrentUser();
    
    if (users.length === 0) {
        container.innerHTML = '<tr><td colspan="5" class="text-center">No users found.</td></tr>';
        return;
    }

    container.innerHTML = users.map(user => `
        <tr>
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>
                ${user.is_admin === 1 
                    ? '<span class="badge bg-danger">Admin</span>' 
                    : '<span class="badge bg-secondary">User</span>'}
            </td>
            <td>
                ${user.id !== currentUser.id 
                    ? `<button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">
                           <i class="fas fa-trash"></i> Delete
                       </button>`
                    : '<span class="text-muted">Current User</span>'}
            </td>
        </tr>
    `).join('');
}

// Delete user
function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }

    const users = getUsers();
    const filteredUsers = users.filter(u => u.id !== userId);
    saveUsers(filteredUsers);
    
    // Also remove user's cart items
    let cart = getCart();
    cart = cart.filter(item => item.user_id !== userId);
    saveCart(cart);
    
    showToast('User deleted successfully!', 'success');
    displayAdminUsers();
}

// ============ DASHBOARD STATISTICS ============

// Display dashboard statistics
function displayDashboardStats() {
    const products = getProducts();
    const orders = getOrders();
    const users = getUsers();
    const totalRevenue = orders.reduce((sum, order) => sum + order.total_price, 0);

    const statsContainer = document.getElementById('dashboard-stats');
    if (!statsContainer) return;

    statsContainer.innerHTML = `
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <h2 class="text-primary">${products.length}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <h2 class="text-success">${orders.length}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="text-info">${users.length}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <h2 class="text-warning">${formatCurrency(totalRevenue)}</h2>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Initialize admin pages
function initAdminPage() {
    if (!requireAdmin()) return;

    if (document.getElementById('admin-products-table')) {
        displayAdminProducts();
    }
    if (document.getElementById('admin-orders-table')) {
        displayAdminOrders();
    }
    if (document.getElementById('admin-users-table')) {
        displayAdminUsers();
    }
    if (document.getElementById('dashboard-stats')) {
        displayDashboardStats();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('admin-products-table') || 
        document.getElementById('admin-orders-table') || 
        document.getElementById('admin-users-table') ||
        document.getElementById('dashboard-stats')) {
        initAdminPage();
    }
});

