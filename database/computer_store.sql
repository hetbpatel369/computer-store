-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 05:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `computer_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(20) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `category`, `stock`, `created_at`) VALUES
(1, 'Gaming Desktop PC - RTX 4080', 'High-performance gaming desktop with NVIDIA RTX 4080, Intel i7-13700K, 32GB DDR5 RAM, 1TB NVMe SSD', 2499.99, 'img/products/Gaming Desktop PC.webp', 'desktops', 20, '2025-11-20 16:12:53'),
(2, 'Workstation Desktop - Professional', 'Professional workstation with AMD Ryzen 9 7950X, 64GB DDR5 RAM, 2TB NVMe SSD, NVIDIA RTX A4000', 3499.99, 'img/products/Workstation Desktop.jpg', 'desktops', 8, '2025-11-20 16:12:53'),
(3, 'Budget Desktop PC', 'Affordable desktop with Intel i5-12400, 16GB DDR4 RAM, 512GB SSD, Integrated Graphics', 699.99, 'img/products/Budget Desktop PC.jpg', 'desktops', 24, '2025-11-20 16:12:53'),
(4, 'NVIDIA GeForce RTX 4090', '24GB GDDR6X, 384-bit, Ray Tracing, DLSS 3.0, PCIe 4.0', 1599.99, 'img/products/NVIDIA GeForce RTX 4090.webp', 'graphics-cards', 12, '2025-11-20 16:12:53'),
(5, 'NVIDIA GeForce RTX 4080', '16GB GDDR6X, 256-bit, Ray Tracing, DLSS 3.0, PCIe 4.0', 1199.99, 'img/products/NVIDIA GeForce RTX 4080.png', 'graphics-cards', 18, '2025-11-20 16:12:53'),
(6, 'AMD Radeon RX 7900 XTX', '24GB GDDR6, 384-bit, Ray Tracing, FSR 3.0, PCIe 4.0', 999.99, 'img/products/AMD Radeon RX 7900 XTX.png', 'graphics-cards', 20, '2025-11-20 16:12:53'),
(7, 'NVIDIA GeForce RTX 4070', '12GB GDDR6X, 192-bit, Ray Tracing, DLSS 3.0, PCIe 4.0', 599.99, 'img/products/NVIDIA GeForce RTX 4070.jpg', 'graphics-cards', 30, '2025-11-20 16:12:53'),
(8, 'Corsair Vengeance DDR5 32GB (2x16GB)', 'DDR5 5600MHz, CL36, RGB Lighting, Intel XMP 3.0', 129.99, 'img/products/Corsair Vengeance DDR5 32GB.avif', 'memory', 50, '2025-11-20 16:12:53'),
(9, 'G.Skill Trident Z5 DDR5 64GB (2x32GB)', 'DDR5 6000MHz, CL30, RGB Lighting, AMD EXPO & Intel XMP', 249.99, 'img/products/G.Skill Trident Z5 DDR5 64GB.jpg', 'memory', 35, '2025-11-20 16:12:53'),
(10, 'Corsair Vengeance LPX DDR4 16GB (2x8GB)', 'DDR4 3200MHz, CL16, Low Profile, Intel XMP 2.0', 59.99, 'img/products/Corsair Vengeance LPX DDR4 16GB.jpg', 'memory', 75, '2025-11-20 16:12:53'),
(11, 'Kingston Fury Beast DDR4 32GB (2x16GB)', 'DDR4 3600MHz, CL18, RGB Lighting, Plug and Play', 99.99, 'img/products/Kingston Fury Beast DDR4 32GB.jpg', 'memory', 60, '2025-11-20 16:12:53'),
(12, 'Gaming Laptop - RTX 4070', '17.3\" FHD 144Hz, Intel i7-13700HX, RTX 4070, 32GB DDR5, 1TB SSD', 1899.99, 'img/products/Gaming Laptop - RTX 4070.jpg', 'laptops', 10, '2025-11-20 16:12:53'),
(13, 'Business Laptop - Ultrabook', '14\" 2K Display, Intel i7-1355U, 16GB LPDDR5, 512GB SSD, 12hr Battery', 1299.99, 'img/products/Business Laptop - Ultrabook.jpg', 'laptops', 15, '2025-11-20 16:12:53'),
(14, 'Budget Laptop', '15.6\" FHD, AMD Ryzen 5 7530U, 8GB DDR4, 256GB SSD, Integrated Graphics', 499.99, 'img/products/Budget Laptop.webp', 'laptops', 20, '2025-11-20 16:12:53'),
(15, 'Mechanical Gaming Keyboard', 'RGB Backlit, Cherry MX Blue Switches, Full Size, USB-C', 129.99, 'img/products/Mechanical Gaming Keyboard.jpg', 'accessories', 40, '2025-11-20 16:12:53'),
(16, 'Wireless Gaming Mouse', 'RGB Lighting, 16000 DPI, Wireless 2.4GHz, 70hr Battery', 79.99, 'img/products/Wireless Gaming Mouse.avif', 'accessories', 55, '2025-11-20 16:12:53'),
(17, '27\" 4K Gaming Monitor', '4K UHD, 144Hz, IPS Panel, HDR400, FreeSync/G-Sync Compatible', 449.99, 'img/products/4K Gaming Monitor.jpeg', 'accessories', 24, '2025-11-20 16:12:53'),
(18, 'Gaming Headset', '7.1 Surround Sound, RGB Lighting, Noise Cancelling Mic, USB', 250.00, 'img/products/Gaming Headset.webp', 'accessories', 43, '2025-11-20 16:12:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `created_at`) VALUES
(1, 'Admin User', 'admin@admin.com', '$2y$10$u7dI2EvVzo26bHI00tCvd.IB3R2blbq2UF3mofbOemJ8w3Yi1yD6e', 1, '2025-11-20 16:12:53'),
(7, 'Test User', 'newtest@example.com', '$2y$10$8qEg9QfzXYTC30OPf35D0ODIMfLJIDy5XB104ErfDk1PhZRh.APsi', 0, '2025-11-27 07:30:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
