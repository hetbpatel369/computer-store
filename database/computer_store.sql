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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Gaming Desktop PC - RTX 4080','High-performance gaming desktop with NVIDIA RTX 4080, Intel i7-13700K, 32GB DDR5 RAM, 1TB NVMe SSD',2499.99,'img/products/Gaming Desktop PC.webp','desktops',20,'2025-11-20 16:12:53'),(2,'Workstation Desktop - Professional','Professional workstation with AMD Ryzen 9 7950X, 64GB DDR5 RAM, 2TB NVMe SSD, NVIDIA RTX A4000',3499.99,'img/products/Workstation Desktop.jpg','desktops',8,'2025-11-20 16:12:53'),(3,'Budget Desktop PC','Affordable desktop with Intel i5-12400, 16GB DDR4 RAM, 512GB SSD, Integrated Graphics',699.99,'img/products/Budget Desktop PC.jpg','desktops',24,'2025-11-20 16:12:53'),(4,'NVIDIA GeForce RTX 4090','24GB GDDR6X, 384-bit, Ray Tracing, DLSS 3.0, PCIe 4.0',1599.99,'img/products/NVIDIA GeForce RTX 4090.webp','graphics-cards',12,'2025-11-20 16:12:53'),(5,'NVIDIA GeForce RTX 4080','16GB GDDR6X, 256-bit, Ray Tracing, DLSS 3.0, PCIe 4.0',1199.99,'img/products/NVIDIA GeForce RTX 4080.png','graphics-cards',18,'2025-11-20 16:12:53'),(6,'AMD Radeon RX 7900 XTX','24GB GDDR6, 384-bit, Ray Tracing, FSR 3.0, PCIe 4.0',999.99,'img/products/AMD Radeon RX 7900 XTX.png','graphics-cards',20,'2025-11-20 16:12:53'),(7,'NVIDIA GeForce RTX 4070','12GB GDDR6X, 192-bit, Ray Tracing, DLSS 3.0, PCIe 4.0',599.99,'img/products/NVIDIA GeForce RTX 4070.jpg','graphics-cards',30,'2025-11-20 16:12:53'),(8,'Corsair Vengeance DDR5 32GB (2x16GB)','DDR5 5600MHz, CL36, RGB Lighting, Intel XMP 3.0',129.99,'img/products/Corsair Vengeance DDR5 32GB.avif','memory',50,'2025-11-20 16:12:53'),(9,'G.Skill Trident Z5 DDR5 64GB (2x32GB)','DDR5 6000MHz, CL30, RGB Lighting, AMD EXPO & Intel XMP',249.99,'img/products/G.Skill Trident Z5 DDR5 64GB.jpg','memory',35,'2025-11-20 16:12:53'),(10,'Corsair Vengeance LPX DDR4 16GB (2x8GB)','DDR4 3200MHz, CL16, Low Profile, Intel XMP 2.0',59.99,'img/products/Corsair Vengeance LPX DDR4 16GB.jpg','memory',75,'2025-11-20 16:12:53'),(11,'Kingston Fury Beast DDR4 32GB (2x16GB)','DDR4 3600MHz, CL18, RGB Lighting, Plug and Play',99.99,'img/products/Kingston Fury Beast DDR4 32GB.jpg','memory',60,'2025-11-20 16:12:53'),(12,'Gaming Laptop - RTX 4070','17.3\" FHD 144Hz, Intel i7-13700HX, RTX 4070, 32GB DDR5, 1TB SSD',1899.99,'img/products/Gaming Laptop - RTX 4070.jpg','laptops',10,'2025-11-20 16:12:53'),(13,'Business Laptop - Ultrabook','14\" 2K Display, Intel i7-1355U, 16GB LPDDR5, 512GB SSD, 12hr Battery',1299.99,'img/products/Business Laptop - Ultrabook.jpg','laptops',15,'2025-11-20 16:12:53'),(14,'Budget Laptop','15.6\" FHD, AMD Ryzen 5 7530U, 8GB DDR4, 256GB SSD, Integrated Graphics',499.99,'img/products/Budget Laptop.webp','laptops',20,'2025-11-20 16:12:53'),(15,'Mechanical Gaming Keyboard','RGB Backlit, Cherry MX Blue Switches, Full Size, USB-C',129.99,'img/products/Mechanical Gaming Keyboard.jpg','accessories',40,'2025-11-20 16:12:53'),(16,'Wireless Gaming Mouse','RGB Lighting, 16000 DPI, Wireless 2.4GHz, 70hr Battery',79.99,'img/products/Wireless Gaming Mouse.avif','accessories',55,'2025-11-20 16:12:53'),(17,'27\" 4K Gaming Monitor','4K UHD, 144Hz, IPS Panel, HDR400, FreeSync/G-Sync Compatible',449.99,'img/products/4K Gaming Monitor.jpeg','accessories',24,'2025-11-20 16:12:53'),(18,'Gaming Headset','7.1 Surround Sound, RGB Lighting, Noise Cancelling Mic, USB',250.00,'img/products/Gaming Headset.webp','accessories',43,'2025-11-20 16:12:53');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
INSERT INTO `reviews` (`product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES 
(1, 9, 5, 'Absolute beast of a machine! Runs everything on ultra settings.', NOW()),
(1, 8, 4, 'Great performance, but a bit pricey.', NOW()),
(17, 9, 5, 'The colors on this monitor are amazing. HDR looks great.', NOW()),
(17, 8, 5, 'Perfect for gaming and work. 144Hz makes a huge difference.', NOW()),
(12, 9, 4, 'Solid laptop for the price. Battery life could be better.', NOW());
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (8,'Admin User','admin@example.com','$2y$10$5HNPlKBtaBakvJ5ndQzwKeyyopWFSQDQPUSfg27NRD./EwDrhyNti',1,'2025-12-03 17:24:56'),(9,'Regular User','user@example.com','$2y$10$5HNPlKBtaBakvJ5ndQzwKeyyopWFSQDQPUSfg27NRD./EwDrhyNti',0,'2025-12-03 17:24:56');
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
