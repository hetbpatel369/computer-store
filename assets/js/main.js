// Main JavaScript file for Computer Store
// Handles utility functions

// Format money
function formatCurrency(amount) {
    return '$' + amount.toFixed(2);
}

// Get URL parameter
function getUrlParameter(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
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

// Dark mode toggle logic is in darkmodetoggle.js
