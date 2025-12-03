-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: computer_store
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(20) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES 
(1,'Apple MacBook Pro 16"','M3 Max chip, 36GB Unified Memory, 1TB SSD, Liquid Retina XDR Display',3499.00,'img/products/macbook-pro-16.jpg','laptops',15,NOW()),
(2,'Apple iPhone 15 Pro Max','256GB, Titanium Design, A17 Pro Chip, 48MP Main Camera',1199.00,'img/products/iphone-15-pro-max.jpg','smartphones',25,NOW()),
(3,'Apple iPad Pro 12.9"','M2 chip, 128GB, Wi-Fi, Liquid Retina XDR Display',1099.00,'img/products/ipad-pro-12-9.jpg','tablets',20,NOW()),
(4,'Apple Watch Ultra 2','49mm Titanium Case, GPS + Cellular, 36-hour Battery Life',799.00,'img/products/apple-watch-ultra-2.jpg','wearables',30,NOW()),
(5,'Apple AirPods Pro (2nd Gen)','Active Noise Cancellation, Transparency Mode, USB-C Charging Case',249.00,'img/products/airpods-pro-2.jpg','audio',50,NOW()),
(6,'PlayStation 5 Console','825GB SSD, 4K 120Hz Gaming, DualSense Wireless Controller',499.99,'img/products/ps5-console.jpg','consoles',10,NOW()),
(7,'Xbox Series X','1TB SSD, 4K 120Hz Gaming, 12 TFLOPS Processing Power',499.99,'img/products/xbox-series-x.jpg','consoles',12,NOW()),
(8,'PlayStation 5 DualSense Controller','Haptic Feedback, Adaptive Triggers, Built-in Microphone',69.99,'img/products/ps5-controller.jpg','accessories',40,NOW()),
(9,'Xbox Wireless Controller','Carbon Black, Textured Grip, Bluetooth, Share Button',59.99,'img/products/xbox-controller.jpg','accessories',45,NOW()),
(10,'Samsung 990 PRO 2TB NVMe SSD','PCIe 4.0, Read Speeds up to 7450 MB/s, Heatsink Included',179.99,'img/products/samsung-990-pro.jpg','storage',30,NOW()),
(11,'Lian Li O11 Dynamic Evo Case','Mid-Tower, Dual Chamber Design, Tempered Glass, White',159.99,'img/products/lian-li-o11.jpg','components',15,NOW()),
(12,'Noctua NH-D15 CPU Cooler','Dual Tower, 2x NF-A15 PWM Fans, High Performance Air Cooling',109.95,'img/products/noctua-nh-d15.jpg','components',25,NOW()),
(13,'Samsung Galaxy S24 Ultra','256GB, Titanium Gray, AI Features, 200MP Camera',1299.99,'img/products/samsung-s24-ultra.jpg','smartphones',20,NOW()),
(14,'Google Pixel 8 Pro','128GB, Obsidian, Google Tensor G3, Advanced AI Camera',999.00,'img/products/pixel-8-pro.jpg','smartphones',15,NOW()),
(15,'Dell XPS 15 Laptop','15.6" OLED 3.5K, Intel i9-13900H, RTX 4060, 32GB RAM',2499.00,'img/products/dell-xps-15.jpg','laptops',10,NOW()),
(16,'Razer Blade 14 Gaming Laptop','14" QHD 240Hz, AMD Ryzen 9 7940HS, RTX 4070, 16GB RAM',2399.99,'img/products/razer-blade-14.jpg','laptops',8,NOW()),
(17,'Intel Core i9-14900K','24 Cores (8P+16E), up to 6.0 GHz, LGA1700, Unlocked',589.99,'img/products/intel-i9-14900k.jpg','components',30,NOW()),
(18,'AMD Ryzen 7 7800X3D','8 Cores, 16 Threads, 4.2 GHz, 3D V-Cache Technology',399.00,'img/products/ryzen-7-7800x3d.jpg','components',40,NOW()),
(19,'ASUS ROG Strix Z790-E Gaming WiFi','LGA1700, DDR5, PCIe 5.0, WiFi 6E, AI Overclocking',499.99,'img/products/asus-z790-motherboard.jpg','components',15,NOW()),
(20,'Logitech G Pro X Superlight 2','Wireless Gaming Mouse, 2K Polling Rate, 60g Ultra-light',159.00,'img/products/logitech-g-pro-x.jpg','accessories',50,NOW()),
(21,'SteelSeries Apex Pro TKL','Mechanical Gaming Keyboard, OmniPoint 2.0 Adjustable Switches',189.99,'img/products/steelseries-apex-pro.jpg','accessories',25,NOW()),
(22,'LG UltraGear 27" OLED Monitor','240Hz, 0.03ms GtG, QHD, G-SYNC Compatible, HDR10',999.99,'img/products/lg-oled-monitor.jpg','accessories',12,NOW()),
(23,'Sony WH-1000XM5','Wireless Noise Cancelling Headphones, 30hr Battery, Crystal Clear Calls',348.00,'img/products/sony-wh1000xm5.jpg','audio',40,NOW()),
(24,'Bose QuietComfort Ultra Earbuds','Spatial Audio, World-Class Noise Cancellation, CustomTune',299.00,'img/products/bose-qc-ultra.jpg','audio',35,NOW()),
(25,'GoPro Hero 12 Black','5.3K60 Video, HyperSmooth 6.0, HDR, Waterproof',399.99,'img/products/gopro-hero-12.jpg','cameras',25,NOW()),
(26,'Nintendo Switch OLED Model','7-inch OLED Screen, 64GB Storage, Enhanced Audio',349.99,'img/products/nintendo-switch-oled.jpg','consoles',50,NOW()),
(27,'Steam Deck OLED 1TB','HDR OLED Display, 1TB NVMe SSD, 90Hz Refresh Rate',649.00,'img/products/steam-deck-oled.jpg','consoles',20,NOW()),
(28,'ASUS ROG Swift 360Hz Monitor','24.5" FHD, 360Hz, 1ms GTG, NVIDIA G-SYNC, Esports Gaming',499.00,'img/products/asus-rog-360hz.jpg','accessories',18,NOW()),
(29,'Keychron Q1 Pro Mechanical Keyboard','Wireless Custom Mechanical Keyboard, QMK/VIA Support, Aluminum Body',199.00,'img/products/keychron-q1-pro.jpg','accessories',30,NOW()),
(30,'Razer DeathAdder V3 Pro','63g Ultra-lightweight, 30K Optical Sensor, 90hr Battery',149.99,'img/products/razer-deathadder-v3.jpg','accessories',45,NOW()),
(31,'Samsung Odyssey Neo G9','49" Curved Gaming Monitor, Mini-LED, 240Hz, 1ms',1299.99,'img/products/samsung-odyssey-g9.jpg','accessories',10,NOW());
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES 
(1, 1, 1, 5, 'The display is stunning and the battery lasts forever.', NOW()),
(2, 2, 2, 5, 'The camera system is unmatched. Titanium feels premium.', NOW()),
(3, 3, 1, 5, 'Replaced my laptop for most tasks. M2 chip is crazy fast.', NOW()),
(4, 4, 2, 5, 'Rugged and reliable. Battery life is a game changer.', NOW()),
(5, 5, 1, 4, 'Noise cancellation is magic, but they fall out of my ears sometimes.', NOW()),
(6, 6, 2, 5, 'Loading times are non-existent. The controller is immersive.', NOW()),
(7, 7, 1, 5, 'Game Pass is the best deal in gaming. Console is whisper quiet.', NOW()),
(8, 8, 2, 5, 'Haptic feedback adds a whole new layer to gaming.', NOW()),
(9, 9, 1, 4, 'Solid controller, but I prefer the Elite version.', NOW()),
(10, 10, 2, 5, 'Blazing fast load times. Worth every penny.', NOW()),
(11, 11, 1, 5, 'Easiest case I have ever built in. Looks stunning.', NOW()),
(12, 12, 2, 5, 'Keeps my CPU cool even under heavy load. Silent operation.', NOW()),
(13, 13, 2, 5, 'The AI features are actually useful. Camera is insane.', NOW()),
(14, 14, 1, 5, 'Best Android experience hands down. Photos look professional.', NOW()),
(15, 15, 2, 4, 'Beautiful screen, but gets a bit hot under load.', NOW()),
(16, 16, 1, 5, 'Perfect balance of power and portability. Build quality is top notch.', NOW()),
(17, 17, 2, 5, 'Overkill for gaming but amazing for video editing.', NOW()),
(18, 18, 1, 5, 'The best gaming CPU on the market. Efficiency is unreal.', NOW()),
(19, 19, 2, 5, 'Feature rich board with great VRMs for overclocking.', NOW()),
(20, 20, 1, 5, 'So light it feels like holding nothing. Sensor is flawless.', NOW()),
(21, 21, 2, 4, 'Switches feel great but the software is a bit buggy.', NOW()),
(22, 22, 1, 5, 'OLED is a game changer. True blacks make everything pop.', NOW()),
(23, 23, 2, 5, 'Best noise cancelling headphones I have ever used.', NOW()),
(24, 24, 1, 4, 'Sound quality is amazing, fit is a bit tricky.', NOW()),
(25, 25, 2, 5, 'HyperSmooth is incredible. Footage looks cinematic.', NOW()),
(26, 26, 2, 5, 'The OLED screen makes a huge difference. Games look vibrant.', NOW()),
(27, 27, 1, 5, 'It is like having a gaming PC in your hands. OLED screen is beautiful.', NOW()),
(28, 28, 2, 5, '360Hz is buttery smooth. Competitive advantage is real.', NOW()),
(29, 29, 1, 5, 'Typing feel is premium. Aluminum build is heavy and solid.', NOW()),
(30, 30, 2, 5, 'Perfect shape and weight. My aim has improved.', NOW()),
(31, 31, 1, 5, 'This monitor is an experience. Immersion is next level.', NOW());
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin@example.com','$2y$10$5HNPlKBtaBakvJ5ndQzwKeyyopWFSQDQPUSfg27NRD./EwDrhyNti',1,'2025-12-03 17:24:56'),(2,'Regular User','user@example.com','$2y$10$5HNPlKBtaBakvJ5ndQzwKeyyopWFSQDQPUSfg27NRD./EwDrhyNti',0,'2025-12-03 17:24:56');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-03 12:25:35
