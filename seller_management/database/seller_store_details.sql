-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2020 at 08:58 AM
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
-- Table structure for table `seller_store_details`
--

CREATE TABLE `seller_store_details` (
  `store_id` int(10) NOT NULL,
  `store_seller_id` int(10) DEFAULT NULL,
  `store_name` varchar(100) DEFAULT NULL,
  `store_email` varchar(100) DEFAULT NULL,
  `store_mobile` bigint(10) DEFAULT NULL,
  `store_phone` bigint(10) DEFAULT NULL,
  `store_address` varchar(150) DEFAULT NULL,
  `store_country` int(5) DEFAULT NULL,
  `store_state` int(5) DEFAULT NULL,
  `store_city` varchar(50) DEFAULT NULL,
  `store_pin` int(6) DEFAULT NULL,
  `store_status` int(1) NOT NULL DEFAULT 1,
  `store_verified` int(1) NOT NULL DEFAULT 2,
  `created_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedby` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seller_store_details`
--

INSERT INTO `seller_store_details` (`store_id`, `store_seller_id`, `store_name`, `store_email`, `store_mobile`, `store_phone`, `store_address`, `store_country`, `store_state`, `store_city`, `store_pin`, `store_status`, `store_verified`, `created_datetime`, `updated_datetime`, `updatedby`) VALUES
(1, 1, 'Mohan Store 1', 'mohan12@gmail.com', 9874858745, 9874858743, 'Store address 1', 101, 1, 'Jodhpur', 122001, 1, 2, '2020-09-09 09:59:44', '2020-09-09 09:59:44', NULL),
(2, 3, 'Mohan Kumar Store', 'mohan@gmail.com', 9078965478, 9078965478, 'Jaipur', 101, 33, 'jaipur', 122002, 1, 2, '2020-09-16 11:26:08', '2020-09-16 11:27:21', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `seller_store_details`
--
ALTER TABLE `seller_store_details`
  ADD PRIMARY KEY (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `seller_store_details`
--
ALTER TABLE `seller_store_details`
  MODIFY `store_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
