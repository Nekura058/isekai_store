-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 16, 2025 at 10:32 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isekai_db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `last_login`) VALUES
(2, 'fido', '$2y$10$421InEA/aq4FBi1Djj/tMOAEXpeaURclEu6wEjrpJsbtdPouhA.y2', '2025-02-04 21:49:13', '2025-02-15 20:46:06');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Electronics', 'Electronic devices and gadgets', '2025-02-15 02:10:49'),
(2, 'Clothing', 'Apparel and accessories', '2025-02-15 02:10:49'),
(3, 'Books', 'Fiction, non-fiction, and educational materials', '2025-02-15 02:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `order_date`, `status`) VALUES
(1, 3, 119.97, '2025-02-15 13:06:30', 'Pending'),
(2, 5, 999.99, '2025-02-15 13:42:32', 'Delivered'),
(3, 5, 119.97, '2025-02-15 13:42:55', 'Delivered'),
(9, 5, 185.72, '2025-02-15 20:19:18', 'Delivered'),
(8, 5, 812.79, '2025-02-15 17:56:52', 'Delivered'),
(6, 5, 1039.98, '2025-02-15 14:51:42', 'Delivered'),
(7, 5, 1039.98, '2025-02-15 14:52:06', 'Delivered'),
(10, 5, 25.76, '2025-02-15 20:20:16', 'Delivered'),
(11, 5, 25.76, '2025-02-15 20:21:04', 'Delivered'),
(12, 5, 50.00, '2025-02-15 20:25:38', 'Delivered'),
(13, 5, 50.00, '2025-02-15 20:30:22', 'Delivered'),
(14, 5, 50.00, '2025-02-15 20:31:41', 'Delivered'),
(15, 5, 50.00, '2025-02-15 20:38:29', 'Delivered'),
(16, 5, 25.76, '2025-02-15 20:38:58', 'Delivered'),
(17, 5, 50.00, '2025-02-15 20:40:02', 'Delivered'),
(20, 5, 50.00, '2025-02-15 23:16:13', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 3, 39.99),
(2, 2, 2, 1, 999.99),
(3, 3, 1, 3, 39.99),
(4, 4, 1, 40, 39.99),
(5, 5, 2, 738385, 999.99),
(6, 6, 1, 1, 39.99),
(7, 6, 2, 1, 999.99),
(8, 7, 1, 1, 39.99),
(9, 7, 2, 1, 999.99),
(10, 8, 1, 1, 39.99),
(11, 8, 2, 30, 25.76),
(12, 9, 1, 4, 39.99),
(13, 9, 2, 1, 25.76),
(14, 10, 2, 1, 25.76),
(15, 11, 2, 1, 25.76),
(16, 12, 3, 1, 50.00),
(17, 13, 3, 1, 50.00),
(18, 14, 3, 1, 50.00),
(19, 15, 3, 1, 50.00),
(20, 16, 2, 1, 25.76),
(21, 17, 3, 1, 50.00),
(22, 18, 3, 1, 50.00),
(23, 19, 3, 1, 50.00),
(24, 20, 3, 1, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `stock` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `created_at`, `stock`) VALUES
(1, 3, 'python book', 'Powerful Object-Oriented Programming book', 39.99, 'python book.jpg', '2025-02-15 01:50:53', 75),
(2, 1, 'A45', 'A very beautiful phone owned by nekura', 25.76, 'A45.jpg', '2025-02-15 13:13:53', 21),
(3, 1, 'Samsung laptop', 'basically a laptop', 50.00, 'Samsung Laptop.jpg', '2025-02-15 15:37:34', 22),
(4, 2, 'Naru T-shirts', 'Popular T-shirts made for Naruto fans', 10.25, 'naru shirt.avif', '2025-02-15 21:58:32', 100),
(5, 2, 'Tuxido', 'A manly custom made by legends', 500.00, 'tuxido.jpg', '2025-02-15 22:00:36', 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `phone_number`, `address`, `email`, `password`, `created_at`) VALUES
(1, 'Mufeed Mamadan', 'mufeed', '+256744370328', 'bari', 'mufeed.kodika@gmail.com', '$2y$10$baJyF06Oq7UGdKeKl3oCR.4wfNFgRSOxifhYyKkw8/kiry68ZJ80u', '2025-02-04 20:43:01'),
(5, 'Ahmed', 'nekura', '+249119766776', 'lamab', '7moody@gmail.com', '$2y$10$8eKqga/Kd1XLEeztrXq0veEDQfTOHa3ck02tyOP9d0T/qBH5CFAxy', '2025-02-15 13:35:30'),
(7, 'hilmey', 'lostboy', '404', 'his dad', 'hilmey@gmail.com', '$2y$10$.IofNbUbBmik/1Wk7qyp/.xucPZecnbOs7BFqq53wEjtDLpNLFGOi', '2025-02-15 22:42:11');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
