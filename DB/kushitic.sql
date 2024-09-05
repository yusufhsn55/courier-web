-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2024 at 05:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kushitic`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `consignment_id` int(11) DEFAULT NULL,
  `complaint_text` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `customer_id`, `consignment_id`, `complaint_text`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'test complaint', 'Open', '2024-08-05 10:28:20', '2024-08-05 11:21:41'),
(2, 3, 3, 'test complaint', 'Open', '2024-08-05 10:28:20', '2024-08-05 11:21:41'),
(3, 3, 3, 'qwerty', 'Open', '2024-08-06 09:01:53', '2024-08-06 09:01:53'),
(4, 1, NULL, 'qwerty', 'Open', '2024-08-06 12:11:36', '2024-08-06 12:11:36'),
(5, 1, NULL, 'qwerty', 'Open', '2024-08-06 12:14:19', '2024-08-06 12:14:19'),
(6, 1, 3, 'qwertyui', 'Open', '2024-08-06 12:41:05', '2024-08-06 12:41:05');

-- --------------------------------------------------------

--
-- Table structure for table `consignments`
--

CREATE TABLE `consignments` (
  `consignment_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `tracking_number` varchar(255) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `dispatcher_id` int(11) DEFAULT NULL,
  `dispatcher_pickup_status` varchar(100) DEFAULT NULL,
  `delivery_status` varchar(100) DEFAULT NULL,
  `pickup_address` text NOT NULL,
  `delivery_address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delivery_proof` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consignments`
--

INSERT INTO `consignments` (`consignment_id`, `package_id`, `tracking_number`, `sender_id`, `receiver_id`, `dispatcher_id`, `dispatcher_pickup_status`, `delivery_status`, `pickup_address`, `delivery_address`, `created_at`, `updated_at`, `delivery_proof`) VALUES
(3, 3, 'TRK-66a21103d1b854.83856493', 1, 1, 17, 'confirmed', 'delivered', 'test', 'werr', '2024-07-25 08:46:59', '2024-08-07 14:27:45', 'pending'),
(4, 4, 'TRK-66b0a24a6305c5.29255019', 1, 4, 17, 'confirmed', 'delivered', '', '', '2024-08-05 09:58:34', '2024-08-08 09:33:16', 'pending'),
(12, 4, 'TRK-66b1c99f611b92.74125213', 1, 3, 17, 'confirmed', 'delivered', 'qwertyuio', 'qwertyui', '2024-08-06 06:58:39', '2024-08-08 09:59:45', 'uploads/66b497113c7fb_aaaa.pdf'),
(13, 5, 'TRK-66b4c672176a05.35831425', 1, 5, 7, 'pending', 'created', 'wertysdfghjklhghhj', 'asdfghjk', '2024-08-08 13:21:54', '2024-08-08 14:05:05', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `courier_dispatchers`
--

CREATE TABLE `courier_dispatchers` (
  `dispatcher_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `national_id` varchar(50) NOT NULL,
  `vehicle_type` varchar(200) NOT NULL,
  `vehicle_registration_number` varchar(50) NOT NULL,
  `date_joined` date NOT NULL DEFAULT curdate(),
  `status` enum('Active','Inactive','Suspended') NOT NULL DEFAULT 'Active',
  `profile_picture` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(100) NOT NULL,
  `emergency_contact_phone` varchar(15) NOT NULL,
  `emergency_contact_relationship` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courier_dispatchers`
--

INSERT INTO `courier_dispatchers` (`dispatcher_id`, `first_name`, `last_name`, `email`, `phone_number`, `address`, `city`, `postal_code`, `country`, `national_id`, `vehicle_type`, `vehicle_registration_number`, `date_joined`, `status`, `profile_picture`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relationship`) VALUES
(7, 'Joy', 'maki', 'freemanduke254@gmail.com', '074512563', 'nair', 'nairobi', '010101', 'Kenya', '23741245', 'Vans', 'KCB 145C', '2024-07-24', 'Active', NULL, 'TEST', '012356478', 'TEST'),
(17, 'ODHIAMBO', 'NGOCHE', 'ngocheian@gmail.com', '0741073146', 'Utawala Along Eastern Bypass Rd', 'Nairobi Municipality', '00100', 'Kenya', '37104147', 'Cars', 'KBC234C', '2024-08-07', 'Inactive', NULL, 'Tunya Reefs ', '0741073146', 'Couple');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `first_name`, `last_name`, `email`, `phone`, `created_at`) VALUES
(1, 3, 'Customer', 'Test', 'customer@gmail.com', '0701214120', '2024-07-23 07:23:35'),
(2, 9, 'Karua', 'tino', 'ngocheian@gmail.com', '0701214121', '2024-07-25 07:48:16'),
(3, 10, 'IAN', 'ODHIAMBO', 'ngocheian@gmail.com', '0741073146', '2024-07-29 07:31:37'),
(4, 13, 'Tuck', 'smith', 'freemanduke254@gmail.com', '0741073146', '2024-08-06 18:15:47'),
(5, 18, 'Ian', 'Ngoche', 'ngocheian@gmail.com', '0701214121', '2024-08-08 12:56:42'),
(6, 19, 'dff', '', 'freemanduk254@gmail.com', '0701214121', '2024-08-08 15:15:50');

-- --------------------------------------------------------

--
-- Table structure for table `employees_tbl`
--

CREATE TABLE `employees_tbl` (
  `id` int(11) NOT NULL,
  `emply_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_tbl`
--

INSERT INTO `employees_tbl` (`id`, `emply_id`, `first_name`, `last_name`, `email`, `phone`, `created_at`) VALUES
(1, 2, 'Ian', 'Ngoche', 'ngocheian@gmail.com', '0701214121', '2024-07-16 15:27:03'),
(2, 3, 'Courier', 'Manager', 'manager@admin.com', '0701214121', '2024-07-20 18:26:23'),
(4, 5, 'Tuck', 'smith', 'fremanduke254@gmail.com', '0701214121', '2024-07-20 18:28:30'),
(5, 6, 'Ian', 'Ngoche', 'freemanduke254@gmail.com', '0741073146', '2024-07-21 19:45:43'),
(6, 8, 'Jane', 'Akinyi', 'jane@jane.com', '0701214121', '2024-07-25 07:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `notice_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `notice_id`, `message`, `status`, `created_at`) VALUES
(1, 3, 101, 'Dispatcher request for tracking number TRK-66b11fd98e8e00.16516278', 'Unread', '2024-08-05 19:25:15'),
(2, 3, 101, 'Dispatcher request for tracking number TRK-66b1c99f611b92.74125213', 'Unread', '2024-08-06 06:59:41'),
(3, 3, 101, 'Dispatcher request for tracking number TRK-66b1c99f611b92.74125213', 'Unread', '2024-08-06 09:49:27'),
(4, 3, 101, 'Dispatcher request for tracking number TRK-66b4c672176a05.35831425', 'Unread', '2024-08-08 14:01:07'),
(5, 3, 101, 'Dispatcher request for tracking number TRK-66b4c672176a05.35831425', 'Unread', '2024-08-08 14:05:09');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `customer_id`, `product_name`, `description`, `weight`, `dimensions`, `value`, `status`, `created_at`, `updated_at`) VALUES
(3, 1, 'NIKE AIR F1', 'Iconic Design: Features the classic Air Force 1 silhouette that\'s been a staple since 1982.\r\nLuxurious Details: Embellished with gold accents and a unique lace charm for a touch of elegance.\r\nDurable Construction: Crafted from premium materials, ensu', 1.50, '21111cm', 'Pay on Delivery', 'Pending', '2024-08-05 07:32:42', '2024-08-05 07:32:42'),
(4, 1, 'NIKE AIR F1', 'Iconic Design: Features the classic Air Force 1 silhouette that\'s been a staple since 1982.\r\nLuxurious Details: Embellished with gold accents and a unique lace charm for a touch of elegance.\r\nDurable Construction: Crafted from premium materials, ensu', 1.50, '21111cm', 'Pay on Delivery', 'Pending', '2024-08-05 07:48:12', '2024-08-05 07:48:12'),
(5, 1, 'Dress', 'wwwwww', 0.00, 'ddd', 'Paid', 'Pending', '2024-08-08 12:10:54', '2024-08-08 12:10:54');

-- --------------------------------------------------------

--
-- Table structure for table `receiver`
--

CREATE TABLE `receiver` (
  `id` int(11) NOT NULL,
  `Receiver_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receiver`
--

INSERT INTO `receiver` (`id`, `Receiver_id`, `first_name`, `last_name`, `email`, `phone`, `created_at`) VALUES
(1, 4, 'Receiver', 'Test', 'receiver@gmail.com', '0701214121', '2024-07-23 04:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`id`, `role`, `username`, `password`, `created_at`, `Status`) VALUES
(1, 'Admin', 'Admin', '$2y$10$0QnGdSCTZS5vrd4aT7BdAO//fdHNQ9UeU7hODuJ9uACRx1GbbIUUm', '2024-07-19 19:48:23', 1),
(2, 'manager', 'manager', 'manager@123', '2024-07-19 19:49:00', 1),
(3, 'Customer', 'customer', '$2y$10$9nRla9v7/ORvPmyw/7w/kO4J6eRz4m.ev6Xd32c1wFbuOYM8x.E/6', '2024-07-23 07:23:35', 1),
(6, 'Dispatcher', 'CD', '$2y$10$xTEE4rGqJceA1q72SlirDehSx3DJ3l2UCiwX/9TM.qUxyu.pX7ogK', '2024-07-24 19:17:18', 1),
(7, 'Dispatcher', 'CDISPATCHER', '$2y$10$w36VGJcVH2bbt/YQw5EgPeLNmiDbu3Ra/aojkqn3iwNSL4yK/7a.q', '2024-07-24 19:19:53', 1),
(8, 'Courier Manager', 'jane', '$2y$10$vqxtIDuHC.aa81u9kf1Hru3WPoyQHY/n8FGmOJKukMK3pm.iOhCVO', '2024-07-25 07:47:09', 1),
(9, 'Customer', 'karua', '$2y$10$yHBHFCT5.fyRVpn2bTveAO5oZn3SH31BdaKzHV9RfxUbAgHAP6Sxa', '2024-07-25 07:48:16', 1),
(17, 'Dispatcher', 'Courier', '$2y$10$KulP7CSRteCv0EoyUBXPa.QTTmbPxmbq1DL6tdr6uKWnwHStWVZvy', '2024-08-07 13:49:35', 1),
(18, 'Customer', 'ian727', '$2y$10$g.lrm7nr77.3GNKSbzQduuM2DddwKlr9RFaUFtOUsQlkMKKSpeBWK', '2024-08-08 12:56:42', 1),
(19, 'Customer', 'Admingggggg', '$2y$10$v6DBqAP9YCrie5tWY8zKl.ntqLMWwFLwiQDm4dTEKgApe.848.pdm', '2024-08-08 15:15:49', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `package_id` (`consignment_id`);

--
-- Indexes for table `consignments`
--
ALTER TABLE `consignments`
  ADD PRIMARY KEY (`consignment_id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `courier_dispatchers`
--
ALTER TABLE `courier_dispatchers`
  ADD PRIMARY KEY (`dispatcher_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD UNIQUE KEY `national_id` (`national_id`),
  ADD UNIQUE KEY `vehicle_registration_number` (`vehicle_registration_number`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employees_tbl`
--
ALTER TABLE `employees_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_ibfk_1` (`emply_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `receiver`
--
ALTER TABLE `receiver`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Receiver_id` (`Receiver_id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `consignments`
--
ALTER TABLE `consignments`
  MODIFY `consignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `courier_dispatchers`
--
ALTER TABLE `courier_dispatchers`
  MODIFY `dispatcher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees_tbl`
--
ALTER TABLE `employees_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `receiver`
--
ALTER TABLE `receiver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`consignment_id`) REFERENCES `packages` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
