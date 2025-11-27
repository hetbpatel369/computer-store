-- Next Gen Tech Database Schema
-- This SQL file matches the localStorage structure and is ready for MySQL/phpMyAdmin

-- Create database (uncomment if needed)
-- CREATE DATABASE IF NOT EXISTS nextgentech;
-- USE nextgentech;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(500),
    category VARCHAR(50) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    shipping_name VARCHAR(100),
    shipping_address TEXT,
    shipping_city VARCHAR(100),
    shipping_zip VARCHAR(20),
    shipping_phone VARCHAR(20),
    payment_method VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, is_admin) VALUES
('Admin User', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1)
ON DUPLICATE KEY UPDATE name=name;

INSERT INTO products (name, description, price, image_url, category, stock) VALUES
('Gaming Desktop PC - RTX 4080', 'High-performance gaming desktop with NVIDIA RTX 4080, Intel i7-13700K, 32GB DDR5 RAM, 1TB NVMe SSD', 2499.99, 'https://via.placeholder.com/400x300?text=Gaming+Desktop', 'desktops', 15),
('Workstation Desktop - Professional', 'Professional workstation with AMD Ryzen 9 7950X, 64GB DDR5 RAM, 2TB NVMe SSD, NVIDIA RTX A4000', 3499.99, 'https://via.placeholder.com/400x300?text=Workstation+PC', 'desktops', 8),
('Budget Desktop PC', 'Affordable desktop with Intel i5-12400, 16GB DDR4 RAM, 512GB SSD, Integrated Graphics', 699.99, 'https://via.placeholder.com/400x300?text=Budget+Desktop', 'desktops', 25),
('NVIDIA GeForce RTX 4090', '24GB GDDR6X, 384-bit, Ray Tracing, DLSS 3.0, PCIe 4.0', 1599.99, 'https://via.placeholder.com/400x300?text=RTX+4090', 'graphics-cards', 12),
('NVIDIA GeForce RTX 4080', '16GB GDDR6X, 256-bit, Ray Tracing, DLSS 3.0, PCIe 4.0', 1199.99, 'https://via.placeholder.com/400x300?text=RTX+4080', 'graphics-cards', 18),
('AMD Radeon RX 7900 XTX', '24GB GDDR6, 384-bit, Ray Tracing, FSR 3.0, PCIe 4.0', 999.99, 'https://via.placeholder.com/400x300?text=RX+7900+XTX', 'graphics-cards', 20),
('NVIDIA GeForce RTX 4070', '12GB GDDR6X, 192-bit, Ray Tracing, DLSS 3.0, PCIe 4.0', 599.99, 'https://via.placeholder.com/400x300?text=RTX+4070', 'graphics-cards', 30),
('Corsair Vengeance DDR5 32GB (2x16GB)', 'DDR5 5600MHz, CL36, RGB Lighting, Intel XMP 3.0', 129.99, 'https://via.placeholder.com/400x300?text=DDR5+32GB', 'memory', 50),
('G.Skill Trident Z5 DDR5 64GB (2x32GB)', 'DDR5 6000MHz, CL30, RGB Lighting, AMD EXPO & Intel XMP', 249.99, 'https://via.placeholder.com/400x300?text=DDR5+64GB', 'memory', 35),
('Corsair Vengeance LPX DDR4 16GB (2x8GB)', 'DDR4 3200MHz, CL16, Low Profile, Intel XMP 2.0', 59.99, 'https://via.placeholder.com/400x300?text=DDR4+16GB', 'memory', 75),
('Kingston Fury Beast DDR4 32GB (2x16GB)', 'DDR4 3600MHz, CL18, RGB Lighting, Plug and Play', 99.99, 'https://via.placeholder.com/400x300?text=DDR4+32GB', 'memory', 60),
('Gaming Laptop - RTX 4070', '17.3" FHD 144Hz, Intel i7-13700HX, RTX 4070, 32GB DDR5, 1TB SSD', 1899.99, 'https://via.placeholder.com/400x300?text=Gaming+Laptop', 'laptops', 10),
('Business Laptop - Ultrabook', '14" 2K Display, Intel i7-1355U, 16GB LPDDR5, 512GB SSD, 12hr Battery', 1299.99, 'https://via.placeholder.com/400x300?text=Business+Laptop', 'laptops', 15),
('Budget Laptop', '15.6" FHD, AMD Ryzen 5 7530U, 8GB DDR4, 256GB SSD, Integrated Graphics', 499.99, 'https://via.placeholder.com/400x300?text=Budget+Laptop', 'laptops', 20),
('Mechanical Gaming Keyboard', 'RGB Backlit, Cherry MX Blue Switches, Full Size, USB-C', 129.99, 'https://via.placeholder.com/400x300?text=Gaming+Keyboard', 'accessories', 40),
('Wireless Gaming Mouse', 'RGB Lighting, 16000 DPI, Wireless 2.4GHz, 70hr Battery', 79.99, 'https://via.placeholder.com/400x300?text=Gaming+Mouse', 'accessories', 55),
('27" 4K Gaming Monitor', '4K UHD, 144Hz, IPS Panel, HDR400, FreeSync/G-Sync Compatible', 449.99, 'https://via.placeholder.com/400x300?text=4K+Monitor', 'accessories', 25),
('Gaming Headset', '7.1 Surround Sound, RGB Lighting, Noise Cancelling Mic, USB', 99.99, 'https://via.placeholder.com/400x300?text=Gaming+Headset', 'accessories', 45)
ON DUPLICATE KEY UPDATE name=name;

