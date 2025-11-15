// Authentication JavaScript
// Handles user registration, login, logout, and session management

// Register user
function registerUser(name, email, password, confirmPassword) {
    if (!name || !email || !password || !confirmPassword) {
        showToast('All fields required', 'danger');
        return false;
    }
    if (password !== confirmPassword) {
        showToast('Passwords do not match', 'danger');
        return false;
    }
    if (password.length < 6) {
        showToast('Password must be 6+ characters', 'danger');
        return false;
    }
    if (!email.includes('@')) {
        showToast('Invalid email', 'danger');
        return false;
    }
    
    const users = getUsers();
    if (users.find(u => u.email === email)) {
        showToast('Email already exists', 'danger');
        return false;
    }
    
    users.push({
        id: getNextId(users),
        name: name,
        email: email,
        password: hashPassword(password),
        is_admin: 0
    });
    saveUsers(users);
    showToast('Registration successful!', 'success');
    return true;
}

// Login user
function loginUser(email, password) {
    if (!email || !password) {
        showToast('Email and password required', 'danger');
        return false;
    }
    
    const users = getUsers();
    const user = users.find(u => u.email === email && u.password === hashPassword(password));
    
    if (!user) {
        showToast('Invalid email or password', 'danger');
        return false;
    }
    
    setCurrentUser(user);
    if (user.is_admin === 1) {
        setCurrentAdmin(user);
        showToast('Admin login successful!', 'success');
        setTimeout(() => redirectTo('admin/index.html'), 500);
    } else {
        showToast('Login successful!', 'success');
        setTimeout(() => redirectTo('index.html'), 500);
    }
    return true;
}

// Admin login
function loginAdmin(email, password) {
    if (!email || !password) {
        showToast('Email and password required', 'danger');
        return false;
    }
    
    const users = getUsers();
    const user = users.find(u => u.email === email && u.password === hashPassword(password));
    
    if (!user) {
        showToast('Invalid email or password', 'danger');
        return false;
    }
    if (user.is_admin !== 1) {
        showToast('Admin access required', 'danger');
        return false;
    }
    
    setCurrentAdmin(user);
    setCurrentUser(user);
    showToast('Admin login successful!', 'success');
    setTimeout(() => {
        redirectTo('admin/index.html');
    }, 500);

    return true;
}

// Logout
function logout() {
    setCurrentUser(null);
    setCurrentAdmin(null);
    redirectTo('index.html');
}

// Check if user is logged in
function isLoggedIn() {
    return getCurrentUser() !== null;
}

// Check if user is admin
function isAdmin() {
    const user = getCurrentUser();
    return user && user.is_admin === 1;
}

// Require login (redirect if not logged in)
function requireLogin() {
    if (!isLoggedIn()) {
        showToast('Please login to continue', 'warning');
        redirectTo('login.html');
        return false;
    }
    return true;
}

// Require admin (redirect if not admin)
function requireAdmin() {
    if (!isAdmin()) {
        showToast('Admin access required', 'danger');
        redirectTo('index.html');
        return false;
    }
    return true;
}

// Update navigation based on login status
function updateNavigation() {
    const currentUser = getCurrentUser();
    const navUserMenu = document.getElementById('nav-user-menu');
    const navLoginLink = document.getElementById('nav-login-link');
    const navRegisterLink = document.getElementById('nav-register-link');
    const navCartLink = document.getElementById('nav-cart-link');
    const navAdminLink = document.getElementById('nav-admin-link');

    if (currentUser) {
        // User is logged in
        if (navUserMenu) {
            navUserMenu.style.display = 'block';
            const userName = navUserMenu.querySelector('.nav-user-name');
            if (userName) userName.textContent = currentUser.name;
        }
        if (navLoginLink) navLoginLink.style.display = 'none';
        if (navRegisterLink) navRegisterLink.style.display = 'none';
        if (navCartLink) navCartLink.style.display = 'block';

        // Show admin link if admin
        if (isAdmin() && navAdminLink) {
            navAdminLink.style.display = 'block';
        }
    } else {
        // User is not logged in
        if (navUserMenu) navUserMenu.style.display = 'none';
        if (navLoginLink) navLoginLink.style.display = 'block';
        if (navRegisterLink) navRegisterLink.style.display = 'block';
        if (navCartLink) navCartLink.style.display = 'none';
        if (navAdminLink) navAdminLink.style.display = 'none';
    }
}

// Initialize navigation on page load
document.addEventListener('DOMContentLoaded', function() {
    updateNavigation();
});

