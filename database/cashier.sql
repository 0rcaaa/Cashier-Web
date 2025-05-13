-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 13, 2025 at 02:13 PM
-- Server version: 8.0.42-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'default.jpg',
  `role` enum('Admin','Cashier') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `username`, `password`, `image`, `role`, `token`, `created_at`, `updated_at`) VALUES
(1, '0rca@gmail.com', '0rca', '$2y$10$4c2qu4eOfb3dT6MAOEHTBe4HkV1EXgA5ia6NRuXQfKVB3gBZQN3IK', 'src/assets/images/profiles/1746957898_6820764a21a81.jpg', 'Admin', '3bc037e1818908a64575a0e3f5146221427e32cd664462b08c2289ca47f888d6', '2025-02-26 17:29:08', '2025-05-12 18:49:50'),
(2, 'baltix@gmail.com', 'baltix', '$2y$10$vdEOlAI0VTk2v6KrSojukuWNI0w.TgCcWZuC78VJGEB/8EEWf/w8y', 'src/assets/images/profiles/1746957898_6820764a21a81.jpg', 'Cashier', NULL, '2025-05-11 17:04:58', '2025-05-11 17:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(3, 'Channel'),
(2, 'IndoFood'),
(1, 'Mayora');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Elektronik'),
(2, 'Fashion'),
(3, 'Makanan');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `percentage` decimal(10,0) NOT NULL,
  `points_required` int NOT NULL,
  `exp_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `title`, `percentage`, `points_required`, `exp_at`, `created_at`, `updated_at`) VALUES
(0, '', 0, 0, '2025-05-13 15:48:07', '2025-05-13 15:48:07', '2025-05-13 15:51:16'),
(2, 'test', 1, 1, '2025-05-31 00:00:00', '2025-05-13 17:39:12', '2025-05-13 17:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `points` int UNSIGNED DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `exp_at` datetime NOT NULL DEFAULT ((now() + interval 2 month))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `phone`, `password`, `points`, `created_at`, `updated_at`, `exp_at`) VALUES
(0, 'default', '000000', '', 0, '2025-05-05 20:01:48', '2025-05-13 17:47:29', '2035-07-27 17:45:16'),
(1, 'Andika', '123', '', 0, '2025-02-26 23:25:31', '2025-02-26 23:25:31', '2025-07-13 17:45:16'),
(3, 'baltix', '312321', '$2y$10$Dqc.zLEEMJDp2A02QfFHtOx7gYyXeOIFNf11Y2ezv4iKCyTdVqRMK', 0, '2025-05-13 18:49:58', '2025-05-13 18:49:58', '2025-07-13 18:49:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `member_id` int UNSIGNED DEFAULT NULL,
  `total_items` int NOT NULL,
  `total_price` decimal(10,0) NOT NULL,
  `status` enum('paid','pending','declined') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `member_id`, `total_items`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 119999, 'paid', '2025-05-12 01:08:43', '2025-05-12 01:15:43'),
(2, 1, 0, 4, 108000, 'pending', '2025-05-12 16:14:27', '2025-05-12 16:14:27'),
(3, 1, 0, 3, 80000, 'paid', '2025-05-12 16:15:58', '2025-05-12 16:16:07'),
(4, 1, 1, 3, 175990, 'paid', '2025-05-12 16:21:00', '2025-05-12 16:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `order_fid` int NOT NULL,
  `product_fid` int UNSIGNED NOT NULL,
  `qty` int NOT NULL,
  `total_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_fid`, `product_fid`, `qty`, `total_price`) VALUES
(1, 1, 1, 1, 19999),
(2, 1, 3, 1, 100000),
(3, 2, 12, 3, 32000),
(4, 2, 11, 1, 12000),
(5, 3, 5, 2, 34000),
(6, 3, 11, 1, 12000),
(7, 4, 4, 1, 56000),
(8, 4, 10, 1, 99990),
(9, 4, 2, 1, 20000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `brand_id` int NOT NULL,
  `price` decimal(10,0) UNSIGNED NOT NULL,
  `stock` int UNSIGNED NOT NULL,
  `production_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default.jpg',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `uniqcode` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `brand_id`, `price`, `stock`, `production_date`, `expiration_date`, `image`, `description`, `uniqcode`, `created_at`, `updated_at`) VALUES
(1, 'Kacamata Bintang', 1, 1, 19999, 99, NULL, NULL, 'src/assets/images/product/1746947714_68204e82d575d.jpg', 'kacamata skena', '28076654', '2025-05-11 14:15:14', '2025-05-12 01:15:43'),
(2, 'Gelang Bintang', 2, 3, 20000, 199, NULL, NULL, 'src/assets/images/product/1746986221_6820e4edc0bbe.jpg', 'gelang skena', '22657658', '2025-05-12 00:57:01', '2025-05-12 16:21:08'),
(3, 'earphone', 1, 3, 100000, 199, NULL, NULL, 'src/assets/images/product/1746986259_6820e5139c5e9.jpg', 'Budek', '94587366', '2025-05-12 00:57:39', '2025-05-12 01:15:43'),
(4, 'Hoodie ', 2, 3, 56000, 199, NULL, NULL, 'src/assets/images/product/1746986298_6820e53a0be32.jpg', 'Hoodie skena', '67587749', '2025-05-12 00:58:18', '2025-05-12 16:21:08'),
(5, 'Long Pants', 2, 3, 34000, 198, NULL, NULL, 'src/assets/images/product/1746986332_6820e55c7bfc3.jpg', 'Celana Oblong', '47782915', '2025-05-12 00:58:52', '2025-05-12 16:16:07'),
(10, 'Converst Black Shoes', 2, 3, 99990, 199, NULL, NULL, 'src/assets/images/product/1746986702_6820e6ce2e8b6.jpeg', 'Sepatu Ireng', '83348143', '2025-05-12 01:05:02', '2025-05-12 16:21:08'),
(11, 'Saturn Necklace', 2, 3, 12000, 199, NULL, NULL, 'src/assets/images/product/1746986755_6820e70370dd0.jpg', 'Gelang Besi', '00384592', '2025-05-12 01:05:55', '2025-05-12 16:16:07'),
(12, 'ACE Ear Piercing', 2, 3, 32000, 200, NULL, NULL, 'src/assets/images/product/1746986809_6820e739b7264.jpg', 'Anting ACE ONE Piece', '33657605', '2025-05-12 01:06:49', '2025-05-12 01:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `order_fid` int NOT NULL,
  `transaction_code` varchar(255) NOT NULL,
  `payment_method` enum('cash','debit','credit','ewallet','') NOT NULL,
  `discount_id` int NOT NULL,
  `cash` double NOT NULL,
  `exchange` double NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_fid`, `transaction_code`, `payment_method`, `discount_id`, `cash`, `exchange`, `date`) VALUES
(1, 1, '20250511-7263', 'cash', 0, 200000, 80001, '2025-05-12 01:15:43'),
(2, 3, '20250512-2581', 'cash', 0, 89999, 9999, '2025-05-12 16:16:07'),
(3, 4, '20250512-7133', 'cash', 0, 200000, 24010, '2025-05-12 16:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `verify_tokens`
--

CREATE TABLE `verify_tokens` (
  `id` int NOT NULL,
  `fid_acc` int UNSIGNED NOT NULL,
  `token` varchar(4) NOT NULL,
  `exp_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`member_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_fid` (`order_fid`),
  ADD KEY `product_fid` (`product_fid`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniqcode` (`uniqcode`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_fid` (`order_fid`),
  ADD KEY `discount_id` (`discount_id`);

--
-- Indexes for table `verify_tokens`
--
ALTER TABLE `verify_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fid_acc` (`fid_acc`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `verify_tokens`
--
ALTER TABLE `verify_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_fid`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_fid`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`order_fid`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `verify_tokens`
--
ALTER TABLE `verify_tokens`
  ADD CONSTRAINT `verify_tokens_ibfk_1` FOREIGN KEY (`fid_acc`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
