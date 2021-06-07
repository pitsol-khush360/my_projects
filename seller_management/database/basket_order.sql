-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2020 at 12:46 PM
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
-- Table structure for table `basket_order`
--

CREATE TABLE `basket_order` (
  `id` int(11) NOT NULL,
  `basket_order_id` varchar(20) DEFAULT NULL,
  `order_type` varchar(15) DEFAULT NULL,
  `customer_name` varchar(80) DEFAULT NULL,
  `customer_mobile` bigint(10) DEFAULT NULL,
  `customer_email` varchar(150) DEFAULT NULL,
  `total_items` int(10) DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `net_amount` float DEFAULT 0,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `seller_id` int(11) DEFAULT NULL,
  `delivery_address1` varchar(100) DEFAULT NULL,
  `delivery_address2` varchar(100) DEFAULT NULL,
  `country` int(3) DEFAULT NULL,
  `state` int(2) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `pincode` int(6) DEFAULT NULL,
  `payment_method` varchar(10) DEFAULT NULL,
  `payment_received` int(1) DEFAULT 0,
  `payment_gateway_status` varchar(15) DEFAULT NULL,
  `payment_transaction_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(15) NOT NULL DEFAULT 'Pending',
  `discount` float DEFAULT NULL,
  `promo_code` varchar(15) DEFAULT NULL,
  `delivery_charge` float DEFAULT NULL,
  `shipping` varchar(20) DEFAULT NULL,
  `awb_reference` varchar(20) DEFAULT NULL,
  `payment_reference` varchar(20) DEFAULT NULL,
  `amount_received` float DEFAULT NULL,
  `amount_received_currency` varchar(3) DEFAULT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_datetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `basket_order`
--

INSERT INTO `basket_order` (`id`, `basket_order_id`, `order_type`, `customer_name`, `customer_mobile`, `customer_email`, `total_items`, `tax_amount`, `net_amount`, `order_date`, `seller_id`, `delivery_address1`, `delivery_address2`, `country`, `state`, `city`, `pincode`, `payment_method`, `payment_received`, `payment_gateway_status`, `payment_transaction_time`, `order_status`, `discount`, `promo_code`, `delivery_charge`, `shipping`, `awb_reference`, `payment_reference`, `amount_received`, `amount_received_currency`, `created_datetime`, `updated_datetime`) VALUES
(1, '2020091512184851306', 'Prepaid', 'Vinay', 9685124784, NULL, 1, 1, 12, '2020-09-30 06:48:48', 2, '64', NULL, NULL, NULL, NULL, NULL, 'cash', 0, NULL, '2020-11-05 05:06:42', 'Pending', 10, 'abs23', 50, '', '', NULL, 100, '', '2020-09-15 06:48:48', '2020-09-15 06:48:48'),
(2, '2020091512570073891', 'COD', 'Mohan', 9685124783, NULL, 1, 1, 12, '2020-09-30 07:27:00', 2, '64', NULL, NULL, NULL, NULL, NULL, 'cash', 0, NULL, '2020-11-05 05:06:42', 'Shipped', 10, 'abs23', 50, '', '', NULL, 100, '', '2020-09-15 07:27:00', '2020-09-15 07:27:00'),
(3, '2020091515370115465', 'Prepaid', 'Rahul', 7854128547, 'rahul@gmail.com', 2, 1, 5106, '2020-09-30 10:07:01', 2, 'Jaipur', NULL, NULL, NULL, NULL, NULL, 'cash', 0, NULL, '2020-11-05 05:06:42', 'Pending', 10, 'abs23', 50, '', '', NULL, 100, '', '2020-09-15 10:07:01', '2020-09-15 10:07:01'),
(4, '2020110417270055966', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-04 11:57:00', 1, '', NULL, NULL, NULL, NULL, NULL, '', 0, NULL, '2020-11-05 05:06:42', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-04 11:57:00', '2020-11-04 11:57:00'),
(5, '2020110417295892684', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-04 11:59:58', 1, '', NULL, NULL, NULL, NULL, NULL, '', 0, NULL, '2020-11-05 05:06:42', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-04 11:59:58', '2020-11-04 11:59:58'),
(6, '2020110417374536789', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-04 12:07:45', 1, '', NULL, NULL, NULL, NULL, NULL, '', 0, NULL, '2020-11-05 05:06:42', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-04 12:07:45', '2020-11-04 12:07:45'),
(7, '2020110417474555965', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-04 12:17:45', 1, '', NULL, NULL, NULL, NULL, NULL, '', 0, NULL, '2020-11-05 05:06:42', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-04 12:17:45', '2020-11-04 12:17:45'),
(8, '2020110417533375822', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-04 12:23:33', 1, '', NULL, NULL, NULL, NULL, NULL, '', 0, NULL, '2020-11-05 05:06:42', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-04 12:23:33', '2020-11-04 12:23:33'),
(9, '2020110418015085145', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 23960, '2020-11-04 12:31:50', 1, '', NULL, NULL, NULL, NULL, NULL, '', 0, NULL, '2020-11-05 05:06:42', 'Draft', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2020-11-04 12:31:50', '2020-11-04 12:31:50'),
(10, '2020110420153258272', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 24960, '2020-11-04 14:45:32', 2, '', NULL, NULL, NULL, NULL, NULL, '', 0, NULL, '2020-11-05 05:06:42', 'Draft', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2020-11-04 14:45:32', '2020-11-04 14:45:32'),
(11, '2020110511273193594', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 24960, '2020-11-05 05:57:31', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 05:57:31', 'Draft', 1000, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2020-11-05 05:57:31', '2020-11-05 05:57:31'),
(16, '2020110511401761093', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 25960, '2020-11-05 06:10:17', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 06:10:17', 'Draft', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2020-11-05 06:10:17', '2020-11-05 06:10:17'),
(17, '2020110514225099085', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-05 08:52:50', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 08:52:50', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-05 08:52:50', '2020-11-05 08:52:50'),
(18, '2020110514272737809', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-05 08:57:27', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 08:57:27', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-05 08:57:27', '2020-11-05 08:57:27'),
(19, '2020110514310127269', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 12030, '2020-11-05 09:01:01', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 09:01:01', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-05 09:01:01', '2020-11-05 09:01:01'),
(20, '2020110514370024762', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 12030, '2020-11-05 09:07:00', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 09:07:00', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-05 09:07:00', '2020-11-05 09:07:00'),
(21, '2020110515534131420', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 2, 0, 46425.6, '2020-11-05 10:23:41', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 10:23:41', 'Draft', 3494.4, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2020-11-05 10:23:41', '2020-11-05 10:23:41'),
(22, '2020110516015381703', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 2, 0, 46425.6, '2020-11-05 10:31:53', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 10:31:53', 'Draft', 3494.4, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2020-11-05 10:31:53', '2020-11-05 10:31:53'),
(23, '2020110516231085546', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-05 10:53:10', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 10:53:10', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-05 10:53:10', '2020-11-05 10:53:10'),
(24, '2020110516415289978', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 2, 0, 24960, '2020-11-05 11:11:52', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 11:11:52', 'Draft', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2020-11-05 11:11:52', '2020-11-05 11:11:52'),
(25, '2020110516585813052', 'Prepaid', 'ram', 7458965478, 'ram@gmail.com', 1, 0, 13030, '2020-11-05 11:28:58', 2, 'jaipur', 'jaipur', 101, 33, 'jaipur', 122002, 'online', 0, NULL, '2020-11-05 11:28:58', 'Draft', 0, NULL, 50, NULL, NULL, NULL, NULL, NULL, '2020-11-05 11:28:58', '2020-11-05 11:28:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basket_order`
--
ALTER TABLE `basket_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `basket_order`
--
ALTER TABLE `basket_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
