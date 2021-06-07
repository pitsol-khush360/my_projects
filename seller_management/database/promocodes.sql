-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2020 at 03:11 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emart`
--

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `id` int(10) NOT NULL,
  `seller_id` int(10) DEFAULT NULL,
  `catalogue_id` int(10) DEFAULT NULL,
  `product_id` int(10) DEFAULT NULL,
  `promo_code` varchar(20) DEFAULT NULL,
  `minimum_order_amount` float NOT NULL DEFAULT 0,
  `discount_type` varchar(15) DEFAULT NULL,
  `discount_value` int(5) NOT NULL DEFAULT 0,
  `is_active` varchar(3) NOT NULL DEFAULT 'Yes',
  `expiry_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_datetime` timestamp NULL DEFAULT current_timestamp(),
  `updated_datetime` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promocodes`
--

INSERT INTO `promocodes` (`id`, `seller_id`, `catalogue_id`, `product_id`, `promo_code`, `minimum_order_amount`, `discount_type`, `discount_value`, `is_active`, `expiry_date`, `created_datetime`, `updated_datetime`) VALUES
(4, 1, 1, 5, 'abc126', 0, NULL, 0, 'Yes', '2020-09-04 13:32:00', NULL, NULL),
(6, 1, 2, 4, 'abc128', 0, NULL, 0, 'Yes', '2020-08-31 16:59:00', '2020-08-31 16:55:49', '2020-08-31 16:55:49'),
(7, 1, 1, 1, 'abc129', 0, NULL, 0, 'Yes', '2020-09-02 04:02:00', '2020-09-01 04:02:34', '2020-09-01 04:02:34'),
(8, 1, 2, 1, 'abc130', 0, NULL, 0, 'Yes', '2020-09-02 04:02:00', '2020-09-01 04:02:53', '2020-09-01 04:02:53'),
(9, 1, 3, 1, 'abc131', 0, NULL, 0, 'Yes', '2020-09-03 04:03:00', '2020-09-01 04:03:10', '2020-09-01 04:03:10'),
(10, 1, 3, 2, 'abc130', 0, NULL, 0, 'Yes', '2020-09-01 04:05:00', '2020-09-01 04:03:57', '2020-09-01 04:03:57'),
(13, 1, 2, 7, 'abc128', 0, NULL, 0, 'Yes', '2020-09-10 08:50:25', '2020-09-01 04:04:49', '2020-09-07 04:23:31'),
(19, 1, 3, 7, 'abc132', 0, NULL, 0, 'Yes', '2020-09-10 13:28:00', '2020-09-07 12:53:47', '2020-09-07 13:28:33'),
(20, 1, 2, 7, 'abc131', 0, NULL, 0, 'Yes', '2020-09-10 12:13:00', '2020-09-07 13:29:29', '2020-09-08 12:13:36'),
(23, 1, 1, 7, 'abc127', 0, NULL, 0, 'Yes', '2020-09-25 13:07:00', '2020-09-08 12:22:23', '2020-09-09 13:07:43'),
(25, 1, 1, 6, 'abc126', 0, NULL, 0, 'Yes', '2020-09-08 13:32:00', '2020-09-09 13:08:07', '2020-09-10 13:32:54'),
(26, 2, 1, 1, 'abc120', 0, NULL, 0, 'Yes', '2020-09-15 13:25:00', '2020-09-12 13:25:48', '2020-09-12 13:25:48'),
(27, 2, 1, 1, 'abc122', 0, NULL, 0, 'Yes', '2020-09-16 06:50:00', '2020-09-14 06:49:55', '2020-09-14 06:50:16'),
(28, 3, 1, 1, 'abc120', 0, NULL, 0, 'Yes', '2020-09-18 11:29:00', '2020-09-16 11:29:12', '2020-09-16 11:29:12'),
(29, 3, 1, 3, 'abc121', 0, NULL, 0, 'Yes', '2020-09-14 11:29:00', '2020-09-16 11:29:28', '2020-09-16 11:29:49'),
(30, 5, 1, 2, 'abc120', 0, NULL, 0, 'Yes', '2020-09-18 12:08:00', '2020-09-16 12:08:16', '2020-09-16 12:08:59'),
(31, 10, 23, 2, 'abc123', 0, NULL, 0, 'Yes', '2020-09-16 14:23:00', '2020-09-18 14:23:55', '2020-09-18 14:23:55'),
(32, 10, 24, 2, 'abc124', 0, NULL, 0, 'Yes', '2020-09-17 14:24:00', '2020-09-18 14:24:16', '2020-09-18 14:24:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
