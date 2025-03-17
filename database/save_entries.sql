-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 07:11 AM
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
-- Database: `save_entries`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `description`, `created_at`) VALUES
(1, 'first brands', 'first brand added', '2025-03-08 18:07:01'),
(2, 'second brand added', 'brand added', '2025-03-08 18:14:26'),
(3, 'dummy brand', 'dummy brand added', '2025-03-08 18:25:18'),
(4, 'sdfsfsdfsdf', 'sfsdfsf', '2025-03-12 03:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` text NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('stock','non-stock') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`, `type`, `created_at`) VALUES
(1, 'first product', ' first product added', 'stock', '2025-03-08 18:06:47'),
(2, 'second category', 'description added', 'stock', '2025-03-08 18:14:00'),
(3, 'hardware', 'sdfds', 'stock', '2025-03-08 18:23:44'),
(5, 'scrap', '', '', '2025-03-17 05:28:00'),
(6, 'mouse', '', 'stock', '2025-03-17 05:30:10');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` text NOT NULL,
  `company_phone` varchar(50) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `company_name`, `company_address`, `company_phone`, `company_email`, `logo_path`, `created_at`, `updated_at`) VALUES
(1, 'Kamran & Sons Pvt. Ltd', 'Hall Road Lahore', '03225598741', 'info@yourcompany.pk', '/assets/images/company-logo.png', '2025-03-10 19:37:52', '2025-03-10 19:41:16');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(512) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `closing_balance` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `address`, `contact_number`, `closing_balance`, `created_at`, `updated_at`) VALUES
(1, 'imran', 'lahore cantt', '03224487954', 0.00, '2025-03-08 18:49:59', '2025-03-08 18:49:59'),
(2, 'qasim', 'karachi', '03225578964', 57011988.00, '2025-03-08 18:50:10', '2025-03-17 06:06:14'),
(3, 'javed', 'karachi', '03255487454', 4687840.00, '2025-03-12 02:58:25', '2025-03-15 03:47:12'),
(4, 'usman', 'lahore', '03224487854', 8130500.00, '2025-03-17 04:55:03', '2025-03-17 06:08:33');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_date` datetime NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL CHECK (`price` is null or `price` >= 0),
  `cost` decimal(12,2) DEFAULT NULL,
  `stock_quantity` int(10) UNSIGNED DEFAULT NULL CHECK (`stock_quantity` is null or `stock_quantity` >= 0),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `sku` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) DEFAULT NULL,
  `stock_status` enum('Stock','Non Stock') DEFAULT 'Non Stock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `product_name`, `description`, `price`, `cost`, `stock_quantity`, `created_at`, `updated_at`, `sku`, `unique_id`, `stock_status`) VALUES
(1, 2, 2, 'first product', 'sdfsfs', 23424.00, NULL, 32423, '2025-03-08 18:15:15', '2025-03-08 18:26:59', 'sdfsf', NULL, 'Stock'),
(4, NULL, NULL, 'ram', NULL, NULL, NULL, NULL, '2025-03-08 18:26:15', NULL, NULL, NULL, 'Stock'),
(6, 3, 3, 'socket', NULL, NULL, NULL, NULL, '2025-03-08 18:43:06', '2025-03-12 03:17:26', NULL, '34434343', 'Non Stock'),
(10, 3, 4, 'sdfsfsfsfsdf', 'sdfdsfsfsfsd', 234234.00, 2342424242.00, 4454, '2025-03-12 03:17:12', '2025-03-12 03:20:01', 'w234234', 'bcd88', 'Stock'),
(11, NULL, NULL, 'new product', NULL, 55.00, 55.00, 55, '2025-03-13 02:51:09', '2025-03-13 02:51:22', '4543555', '345353hgfhf', 'Stock'),
(12, NULL, NULL, 'dffdjsjdfsdjf', NULL, NULL, NULL, NULL, '2025-03-13 08:20:42', NULL, NULL, NULL, 'Stock'),
(13, 5, 3, 'laptop', 'new product added', 23232.00, 2323.00, 22222, '2025-03-17 05:28:12', NULL, '234343', 'usj3343', 'Stock'),
(15, 3, 1, 'mobile cable wire', 'i have added the description', 12121.00, 1235.00, 55, '2025-03-17 05:48:26', NULL, '2123', 'sdfd12121', 'Stock'),
(17, NULL, NULL, 'health product', NULL, 123.00, NULL, 0, '2025-03-17 06:05:48', NULL, NULL, NULL, 'Stock'),
(18, 5, 2, 'mobile product', 'new description added', 3500.00, NULL, 12545, '2025-03-17 06:07:20', NULL, '232', '2323', 'Stock'),
(19, 3, 2, 'product for sale', NULL, NULL, NULL, 0, '2025-03-17 06:08:16', NULL, NULL, NULL, 'Stock'),
(20, 5, 3, 'skldfjsklfjkl', NULL, 2342342.00, NULL, 0, '2025-03-17 06:10:52', NULL, '887', 'jsdhfshfkj', 'Non Stock');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_number` varchar(50) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `quotation_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','approved','rejected','converted') NOT NULL DEFAULT 'pending',
  `validity_period` int(11) DEFAULT 30 COMMENT 'Validity in days',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `quotation_number`, `customer_id`, `quotation_date`, `total_amount`, `discount`, `tax`, `final_amount`, `status`, `validity_period`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'QUO-20250315-ccb37', 3, '2025-03-12 00:00:00', 282188.00, 0.00, 56.44, 282244.44, 'converted', 35, 'done', 1, '2025-03-15 04:08:10', '2025-03-17 05:04:06'),
(3, 'QUO-20250317-0cb7a', 4, '2025-03-11 00:00:00', 2883022.00, 0.00, 0.00, 2883022.00, 'converted', 30, '', 1, '2025-03-17 04:56:35', '2025-03-17 04:57:15'),
(4, 'QUO-20250317-b177c', 3, '2025-03-18 00:00:00', 0.00, 0.00, 0.00, 0.00, 'approved', 30, '', 1, '2025-03-17 05:06:54', '2025-03-17 05:08:39'),
(5, 'QUO-20250317-b3fe9', 4, '0000-00-00 00:00:00', 0.00, 0.00, 0.00, 0.00, 'pending', 30, '', 1, '2025-03-17 06:11:10', '2025-03-17 06:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_items`
--

INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `quantity`, `unit_price`, `discount`, `total_price`, `created_at`) VALUES
(15, 3, 1, 123, 23424.00, 0.00, 2881152.00, '2025-03-17 04:57:15'),
(16, 3, 11, 34, 55.00, 0.00, 1870.00, '2025-03-17 04:57:15'),
(17, 3, 4, 1, 0.00, 0.00, 0.00, '2025-03-17 04:57:15'),
(18, 1, 1, 12, 23424.00, 0.00, 281088.00, '2025-03-17 05:04:06'),
(19, 1, 11, 20, 55.00, 0.00, 1100.00, '2025-03-17 05:04:06'),
(20, 1, 4, 0, 0.00, 0.00, 0.00, '2025-03-17 05:04:06'),
(21, 1, 6, 0, 0.00, 0.00, 0.00, '2025-03-17 05:04:06'),
(24, 4, 4, 0, 0.00, 0.00, 0.00, '2025-03-17 05:08:39'),
(25, 4, 12, 0, 0.00, 0.00, 0.00, '2025-03-17 05:08:39'),
(26, 5, 10, 0, 234234.00, 0.00, 0.00, '2025-03-17 06:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'Administrator with full system access', '2025-03-01 22:31:29', NULL, NULL),
(2, 'manager', 'Manager with elevated privileges', '2025-03-01 22:31:29', NULL, NULL),
(3, 'user', 'Standard user with basic access', '2025-03-01 22:31:29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES
(1, 1, '2025-03-01 22:31:29'),
(1, 2, '2025-03-01 22:31:29'),
(1, 3, '2025-03-01 22:31:29'),
(1, 4, '2025-03-01 22:31:29'),
(1, 5, '2025-03-01 22:31:29'),
(1, 6, '2025-03-01 22:31:29'),
(1, 7, '2025-03-01 22:31:29'),
(1, 8, '2025-03-01 22:31:29'),
(2, 4, '2025-03-01 22:31:29'),
(2, 5, '2025-03-01 22:31:29'),
(2, 6, '2025-03-01 22:31:29'),
(2, 7, '2025-03-01 22:31:29'),
(2, 8, '2025-03-01 22:31:29'),
(3, 5, '2025-03-01 22:31:29'),
(3, 6, '2025-03-01 22:31:29'),
(3, 8, '2025-03-01 22:31:29');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `sale_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','paid','partially_paid') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_number`, `customer_id`, `sale_date`, `total_amount`, `discount`, `tax`, `final_amount`, `payment_status`, `payment_method`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(39, 'INV-20250317-12f56', 4, '0000-00-00 00:00:00', 8130500.00, 0.00, 0.00, 8130500.00, 'pending', 'cash', '', 1, '2025-03-17 06:08:33', '2025-03-17 06:08:33');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `discount`, `total_price`, `created_at`) VALUES
(95, 39, 18, 2323, 3500.00, 0.00, 8130500.00, '2025-03-17 06:08:33'),
(96, 39, 12, 0, 0.00, 0.00, 0.00, '2025-03-17 06:08:33'),
(97, 39, 19, 0, 0.00, 0.00, 0.00, '2025-03-17 06:08:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_verified` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `is_active`, `is_verified`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', 'admin', 'admin@gmail.com', '$2y$10$67WtxFJjkDuDPtRsYJ81feeChEykm58Hdt.JkkTGjkhP/bV1NDLam', 'admin', 'admin', 1, 1, '2025-02-22 03:37:42', '2025-02-22 03:37:42', NULL),
(38, 'e48e2b60fcf32212299d3591e7b08823', 'hafizusman23', 'usman4317782@gmail.com', '$2y$10$qxRADVg/It3p359cpJ9/LelxDHxePmD8OBMks7FVs3.TpPxFMcrUW', 'hafiz muhammad', 'usman', 1, 1, '2025-03-01 22:15:46', '2025-03-01 22:15:56', '2025-03-01 22:15:56'),
(39, '8b51a6887d94568e792d4e01df7a987a', 'usman123', 'usman@gmail.com', '$2y$10$5CqP62YXvjcvT.AzBQxirOAVt5l0HAXOcFrE/wVfuwuuF4tUWbodq', 'qasim', 'ali', 1, 1, '2025-03-01 22:16:27', '2025-03-01 22:16:40', NULL),
(40, '54d7764111120a9f1a82ebe9770474dc', 'imran123', 'imran@hotmail.com', '$2y$10$iiV78Tb91F4buT7AaUjYhuho/rDy.hAcz.iJfA8Uduir.UVyTlNSu', 'imran', 'shahid', 1, 1, '2025-03-01 22:23:06', '2025-03-01 22:23:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_metadata`
--

CREATE TABLE `user_metadata` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(50) NOT NULL,
  `meta_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(512) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `name`, `address`, `contact_number`, `created_at`, `updated_at`) VALUES
(1, 'osama', 'multan road lahore', '032255487545', '2025-03-08 18:50:23', '2025-03-08 18:50:23'),
(2, 'yaseen', 'multan main city multan', '03225587954', '2025-03-08 18:50:38', '2025-03-08 18:50:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brand_name` (`brand_name`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_category_name` (`category_name`) USING HASH,
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `idx_customer_name` (`name`),
  ADD KEY `idx_customer_contact` (`contact_number`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_name` (`product_name`),
  ADD UNIQUE KEY `unique_id` (`unique_id`),
  ADD KEY `idx_products_filter` (`category_id`,`brand_id`,`price`),
  ADD KEY `fk_product_brand` (`brand_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `idx_products_search` (`product_name`,`description`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotation_number` (`quotation_number`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id` (`quotation_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_metadata`
--
ALTER TABLE `user_metadata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD KEY `idx_vendor_name` (`name`),
  ADD KEY `idx_vendor_contact` (`contact_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_metadata`
--
ALTER TABLE `user_metadata`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_sale` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `fk_payments_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quotations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
