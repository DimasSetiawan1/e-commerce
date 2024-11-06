-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_kl03
CREATE DATABASE IF NOT EXISTS `db_kl03` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_kl03`;

-- Dumping structure for table db_kl03.admin_03
CREATE TABLE IF NOT EXISTS `admin_03` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_kl03.admin_03: ~0 rows (approximately)
DELETE FROM `admin_03`;
INSERT INTO `admin_03` (`id`, `email`, `name`, `password`, `created_at`) VALUES
	(1, 'dimas@gmail.com', 'dimas', '$2y$10$UGdG5W9O5ca5hWsNKUiKkOF51vPKXrY/acxb5qKXmZmGyvjZ5d8cO', '2024-10-28 13:02:48');

-- Dumping structure for table db_kl03.cart_03
CREATE TABLE IF NOT EXISTS `cart_03` (
  `id` int NOT NULL AUTO_INCREMENT,
  `productid` int NOT NULL DEFAULT '0',
  `user` int NOT NULL DEFAULT '0',
  `quantity` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productid` (`productid`,`user`),
  UNIQUE KEY `productid_2` (`productid`,`user`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_kl03.cart_03: ~8 rows (approximately)
DELETE FROM `cart_03`;
INSERT INTO `cart_03` (`id`, `productid`, `user`, `quantity`, `created_at`) VALUES
	(28, 10, 2, 1, '2020-12-09 18:52:32'),
	(29, 11, 2, 1, '2020-12-09 18:52:33'),
	(34, 23, 4, 1, '2024-10-28 12:22:20'),
	(35, 22, 4, 1, '2024-10-28 12:22:23'),
	(37, 1, 18, 2, '2024-11-03 18:00:59'),
	(38, 25, 18, 1, '2024-11-04 18:01:10'),
	(39, 3, 18, 1, '2024-11-05 15:52:15'),
	(40, 2, 18, 2, '2024-11-06 06:16:39');

-- Dumping structure for table db_kl03.orderitems_03
CREATE TABLE IF NOT EXISTS `orderitems_03` (
  `id` int NOT NULL AUTO_INCREMENT,
  `oid` int NOT NULL DEFAULT '0',
  `ptitle` varchar(50) NOT NULL,
  `price` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_kl03.orderitems_03: ~4 rows (approximately)
DELETE FROM `orderitems_03`;
INSERT INTO `orderitems_03` (`id`, `oid`, `ptitle`, `price`) VALUES
	(1, 1, 'Casual Style ', '500'),
	(2, 2, 'Casual Style ', '500'),
	(3, 3, 'Wedding Dress(Blue)', '2000'),
	(4, 4, 'Wedding Formal Wear Suit', '990');

-- Dumping structure for table db_kl03.orders_03
CREATE TABLE IF NOT EXISTS `orders_03` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `title` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_kl03.orders_03: ~4 rows (approximately)
DELETE FROM `orders_03`;
INSERT INTO `orders_03` (`id`, `user`, `address`, `created_at`, `status`, `title`, `quantity`) VALUES
	(1, '2', 'ttttrtrtret', '2020-12-09 18:40:05', '', NULL, NULL),
	(2, '2', 'ttttrtrtret', '2020-12-09 18:44:09', '', NULL, NULL),
	(3, '3', 'jalan pendidikan I', '2024-10-25 09:22:15', '', NULL, NULL),
	(4, '4', 'asd', '2024-10-27 14:20:29', '', NULL, NULL);

-- Dumping structure for table db_kl03.products_03
CREATE TABLE IF NOT EXISTS `products_03` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `stock` int NOT NULL,
  `price` bigint NOT NULL DEFAULT '0',
  `img` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_kl03.products_03: ~24 rows (approximately)
DELETE FROM `products_03`;
INSERT INTO `products_03` (`id`, `title`, `stock`, `price`, `img`, `category`, `created_at`) VALUES
	(1, 'kurta', 10, 800, 'yellow.jpg', 'Women', '2020-12-09 01:40:06'),
	(2, 'Western Women Tops', 2, 450, 'top3.jpeg', 'Women', '2024-11-01 09:20:07'),
	(3, 'Patiala Suit', 3, 1500, 'w25.jpg', 'Women', '2024-11-01 09:24:27'),
	(4, 'Saree Party Wear', 10, 2250, 'w24.jpg', 'Women', '2024-11-02 06:23:36'),
	(5, 'Long Anarkali Suit ', 0, 3000, 'w20.jpg', 'Women', '2020-12-09 02:49:43'),
	(6, 'Designer Kurtis', 0, 750, 'w19.jpg', 'Women', '2020-12-09 02:49:43'),
	(7, 'White Formal Suit', 0, 1500, 'w22.jpg', 'Women', '2020-12-09 02:52:06'),
	(8, 'Lehenga Choli Set', 0, 5000, 'w23.jpg', 'Women', '2020-12-09 02:53:40'),
	(9, 'Casual Style ', 0, 500, 'm1.jpg', 'Mens', '2020-12-09 02:56:29'),
	(10, '5 Smart Formal Outfit', 0, 1000, 'm2.jpg', 'Mens', '2020-12-09 02:58:11'),
	(11, 'Wedding Formal Wear Suit', 0, 990, 'm3.jpg', 'Mens', '2020-12-09 03:00:21'),
	(12, 'Plains Casual Shirt', 0, 450, 'm8.jpg', 'Mens', '2020-12-09 03:01:29'),
	(13, 'Plain Round Neck Casual', 0, 430, 'm9.jpg', 'Mens', '2020-12-09 03:03:33'),
	(14, 'Simple Kurta Rs.700', 0, 700, 'm4.jpg', 'Mens', '2020-12-09 03:05:17'),
	(15, 'Designer Kurta', 0, 850, 'm5.jpg', 'Mens', '2020-12-09 03:07:05'),
	(16, 'Party Wear Dhoti Sherwani', 0, 999, 'm7.jpg', 'Mens', '2020-12-09 03:08:13'),
	(17, 'Gown', 0, 800, 'kid1.jpg', 'Kids', '2020-12-09 03:09:21'),
	(18, 'Short Sleeve T-shirt', 0, 600, 'kid2.jpg', 'Kids', '2020-12-09 03:10:27'),
	(19, 'Wedding Dress(Blue)', 0, 2000, 'kid3.jpg', 'Kids', '2020-12-09 03:11:33'),
	(20, 'Track Suit', 0, 700, 'kid5.jpg', 'Kids', '2020-12-09 03:13:02'),
	(21, 'Flower Girl Dress', 0, 850, 'kid7.jpg', 'Kids', '2020-12-09 03:14:09'),
	(22, 'Girls Frock(Black) ', 0, 900, 'kid%206.jpg', 'Kids', '2020-12-09 03:15:49'),
	(23, 'Black & Marron Dhoti Kurta', 0, 1200, 'kid8.jpg', 'Kids', '2020-12-09 03:21:24'),
	(24, 'Angrakha Style Jacket', 0, 2000, 'kid9.jpg', 'Kids', '2020-12-09 03:23:04'),
	(25, 'Fayrany Baju Gamis Remaja dan Ibu Katun Hitam', 9, 256000, 'wDLJNxvM3k.jpg', 'Women', '2024-11-03 12:56:35');

-- Dumping structure for table db_kl03.users_03
CREATE TABLE IF NOT EXISTS `users_03` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_kl03.users_03: ~3 rows (approximately)
DELETE FROM `users_03`;
INSERT INTO `users_03` (`id`, `name`, `email`, `mobile`, `password`, `created_at`) VALUES
	(11, 'risti', 'risti@gmail.com', '123', '$2y$10$A5cVfpW5BylFjltAWYMt7./e5d3An.8Zo3/vxMfh5kW0Y5YkE87A2', '2024-10-28 06:48:34'),
	(18, 'dimas', 'dimas@gmail.com', '123', '$2y$10$9sc1IB0tpwc0L0pfC6c3c.2erOAVVF4uSYmkF8/rXDepXVXQSTTB6', '2024-10-28 08:10:26'),
	(19, 'dimas', 'dimas1@gmail.com', '213', '$2y$10$IvBmYBTWdZBCTePy7q859Ogne6eavyx2kAZ3GMsOM8j59XJ/3lnvS', '2024-10-28 08:12:59');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
