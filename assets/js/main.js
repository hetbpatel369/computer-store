// Main JavaScript file for Computer Store
// Handles localStorage management, sample data initialization, and utility functions

// Initialize data
function initializeData() {
    // Set up users (only if not exists)
    if (!localStorage.getItem('users')) {
        const users = [
            { id: 1, name: 'Admin User', email: 'admin@admin.com', password: hashPassword('admin123'), is_admin: 1 },
            { id: 2, name: 'Test User', email: 'user@test.com', password: hashPassword('user123'), is_admin: 0 }
        ];
        localStorage.setItem('users', JSON.stringify(users));
    }

    // Initialize products
    const defaultProducts = [
        // Desktops
        {
            id: 1,
            name: 'Gaming Desktop PC - RTX 4080',
            description: 'High-performance gaming desktop with NVIDIA RTX 4080, Intel i7-13700K, 32GB DDR5 RAM, 1TB NVMe SSD',
            price: 2499.99,
            image_url: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=600&h=400&fit=crop',
            category: 'desktops',
            stock: 15
        },
        {
            id: 2,
            name: 'Workstation Desktop - Professional',
            description: 'Professional workstation with AMD Ryzen 9 7950X, 64GB DDR5 RAM, 2TB NVMe SSD, NVIDIA RTX A4000',
            price: 3499.99,
            image_url: 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=600&h=400&fit=crop',
            
            category: 'desktops',
            stock: 8
        },
        {
            id: 3,
            name: 'Budget Desktop PC',
            description: 'Affordable desktop with Intel i5-12400, 16GB DDR4 RAM, 512GB SSD, Integrated Graphics',
            price: 699.99,
            image_url: 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&h=400&fit=crop',
            category: 'desktops',
            stock: 25
        },
        // Graphics Cards
        {
            id: 4,
            name: 'NVIDIA GeForce RTX 4090',
            description: '24GB GDDR6X, 384-bit, Ray Tracing, DLSS 3.0, PCIe 4.0',
            price: 1599.99,
            image_url: 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=600&h=400&fit=crop',
            category: 'graphics-cards',
            stock: 12
        },
        {
            id: 5,
            name: 'NVIDIA GeForce RTX 4080',
            description: '16GB GDDR6X, 256-bit, Ray Tracing, DLSS 3.0, PCIe 4.0',
            price: 1199.99,
            image_url: 'https://images.unsplash.com/photo-1625842268584-8f3296236761?w=600&h=400&fit=crop',
            category: 'graphics-cards',
            stock: 18
        },
        {
            id: 6,
            name: 'AMD Radeon RX 7900 XTX',
            description: '24GB GDDR6, 384-bit, Ray Tracing, FSR 3.0, PCIe 4.0',
            price: 999.99,
            image_url: 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&h=400&fit=crop',
            category: 'graphics-cards',
            stock: 20
        },
        {
            id: 7,
            name: 'NVIDIA GeForce RTX 4070',
            description: '12GB GDDR6X, 192-bit, Ray Tracing, DLSS 3.0, PCIe 4.0',
            price: 599.99,
            image_url: 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=600&h=400&fit=crop',
            category: 'graphics-cards',
            stock: 30
        },
        // Memory
        {
            id: 8,
            name: 'Corsair Vengeance DDR5 32GB (2x16GB)',
            description: 'DDR5 5600MHz, CL36, RGB Lighting, Intel XMP 3.0',
            price: 129.99,
            image_url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=600&h=400&fit=crop',
            category: 'memory',
            stock: 50
        },
        {
            id: 9,
            name: 'G.Skill Trident Z5 DDR5 64GB (2x32GB)',
            description: 'DDR5 6000MHz, CL30, RGB Lighting, AMD EXPO & Intel XMP',
            price: 249.99,
            image_url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=600&h=400&fit=crop',
            category: 'memory',
            stock: 35
        },
        {
            id: 10,
            name: 'Corsair Vengeance LPX DDR4 16GB (2x8GB)',
            description: 'DDR4 3200MHz, CL16, Low Profile, Intel XMP 2.0',
            price: 59.99,
            image_url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=600&h=400&fit=crop',
            category: 'memory',
            stock: 75
        },
        {
            id: 11,
            name: 'Kingston Fury Beast DDR4 32GB (2x16GB)',
            description: 'DDR4 3600MHz, CL18, RGB Lighting, Plug and Play',
            price: 99.99,
            image_url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=600&h=400&fit=crop',
            category: 'memory',
            stock: 60
        },
        // Laptops
        {
            id: 12,
            name: 'Gaming Laptop - RTX 4070',
            description: '17.3" FHD 144Hz, Intel i7-13700HX, RTX 4070, 32GB DDR5, 1TB SSD',
            price: 1899.99,
            image_url: 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&h=400&fit=crop',
            category: 'laptops',
            stock: 10
        },
        {
            id: 13,
            name: 'Business Laptop - Ultrabook',
            description: '14" 2K Display, Intel i7-1355U, 16GB LPDDR5, 512GB SSD, 12hr Battery',
            price: 1299.99,
            image_url: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&h=400&fit=crop',
            category: 'laptops',
            stock: 15
        },
        {
            id: 14,
            name: 'Budget Laptop',
            description: '15.6" FHD, AMD Ryzen 5 7530U, 8GB DDR4, 256GB SSD, Integrated Graphics',
            price: 499.99,
            image_url: 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=600&h=400&fit=crop',
            category: 'laptops',
            stock: 20
        },
        // Accessories
        {
            id: 15,
            name: 'Mechanical Gaming Keyboard',
            description: 'RGB Backlit, Cherry MX Blue Switches, Full Size, USB-C',
            price: 129.99,
            image_url: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600&h=400&fit=crop',
            category: 'accessories',
            stock: 40
        },
        {
            id: 16,
            name: 'Wireless Gaming Mouse',
            description: 'RGB Lighting, 16000 DPI, Wireless 2.4GHz, 70hr Battery',
            price: 79.99,
            image_url: 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=600&h=400&fit=crop',
            category: 'accessories',
            stock: 55
        },
        {
            id: 17,
            name: '27" 4K Gaming Monitor',
            description: '4K UHD, 144Hz, IPS Panel, HDR400, FreeSync/G-Sync Compatible',
            price: 449.99,
            image_url: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=600&h=400&fit=crop',
            category: 'accessories',
            stock: 25
        },
        {
            id: 18,
            name: 'Gaming Headset',
            description: '7.1 Surround Sound, RGB Lighting, Noise Cancelling Mic, USB',
            price: 99.99,
            image_url: 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=600&h=400&fit=crop',
            category: 'accessories',
            stock: 45
        }
    ];
    
    // Always update products with latest images
    localStorage.setItem('products', JSON.stringify(defaultProducts));
    
    // Initialize cart and orders if empty
    if (!localStorage.getItem('cart')) {
        localStorage.setItem('cart', JSON.stringify([]));
    }
    if (!localStorage.getItem('orders')) {
        localStorage.setItem('orders', JSON.stringify([]));
    }
}

// Simple password hash (use password_hash in PHP)
function hashPassword(password) {
    let hash = 0;
    for (let i = 0; i < password.length; i++) {
        hash = ((hash << 5) - hash) + password.charCodeAt(i);
        hash = hash & hash;
    }
    return hash.toString();
}

// Get data
function getUsers() {
    return JSON.parse(localStorage.getItem('users') || '[]');
}

function getProducts() {
    return JSON.parse(localStorage.getItem('products') || '[]');
}

function getCart() {
    return JSON.parse(localStorage.getItem('cart') || '[]');
}

function getOrders() {
    return JSON.parse(localStorage.getItem('orders') || '[]');
}

// Save data
function saveUsers(users) {
    localStorage.setItem('users', JSON.stringify(users));
}

function saveProducts(products) {
    localStorage.setItem('products', JSON.stringify(products));
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function saveOrders(orders) {
    localStorage.setItem('orders', JSON.stringify(orders));
}

// Current user
function getCurrentUser() {
    const user = localStorage.getItem('currentUser');
    return user ? JSON.parse(user) : null;
}

function setCurrentUser(user) {
    if (user) {
        localStorage.setItem('currentUser', JSON.stringify(user));
    } else {
        localStorage.removeItem('currentUser');
    }
}

function getCurrentAdmin() {
    const admin = localStorage.getItem('currentAdmin');
    return admin ? JSON.parse(admin) : null;
}

function setCurrentAdmin(admin) {
    if (admin) {
        localStorage.setItem('currentAdmin', JSON.stringify(admin));
    } else {
        localStorage.removeItem('currentAdmin');
    }
}

// Go to page
function redirectTo(url) {
    window.location.href = url;
}

// Show message
function showToast(message, type = 'info') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    container.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}

// Format money
function formatCurrency(amount) {
    return '$' + amount.toFixed(2);
}

// Get URL parameter
function getUrlParameter(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
}

// Get next ID
function getNextId(items) {
    if (items.length === 0) return 1;
    return Math.max(...items.map(i => i.id)) + 1;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeData();
});

