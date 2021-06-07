-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 05, 2020 at 12:20 PM
-- Server version: 5.5.16
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Table structure for table `wallet_order`
--

CREATE TABLE `wallet_order` (
  `id` bigint(20) NOT NULL,
  `seller_id` bigint(20) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `amount` double NOT NULL,
  `wallet_opening_balance` double NOT NULL,
  `wallet_closing_balance` double NOT NULL,
  `payment_reference` varchar(11) DEFAULT NULL,
  `order_status` varchar(10) NOT NULL DEFAULT 'Draft',
  `gateway_response_status` varchar(10) DEFAULT NULL,
  `created_date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_date_time` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallet_order`
--

INSERT INTO `wallet_order` (`id`, `seller_id`, `order_id`, `amount`, `wallet_opening_balance`, `wallet_closing_balance`, `payment_reference`, `order_status`, `gateway_response_status`, `created_date_time`, `payment_date_time`) VALUES
(35, 1, '2020110213160013635', 220, 200, -216.255, '150', 'Completed', 'Completed', '2020-11-02 07:46:00', '2020-11-02 01:23:52'),
(36, 1, '2020110214083147699', 100, 200, 183.745, '150', 'Completed', 'Completed', '2020-11-02 08:38:31', '2020-11-02 01:23:52'),
(37, 1, '2020110214401883782', 114, 200, -102.255, '601397', 'Draft', 'SUCCESS', '2020-11-02 09:10:18', '2020-11-02 14:40:44'),
(38, 1, '2020110218073789217', 50, 200, 0, '', 'Draft', '', '2020-11-02 12:37:37', '0000-00-00 00:00:00'),
(39, 1, '2020110218075639962', 100, 200, 283.745, '601874', 'Completed', 'SUCCESS', '2020-11-02 12:37:56', '2020-11-02 18:08:33'),
(40, 1, '2020110309444813003', 150, 200, 433.745, '602374', 'Completed', 'SUCCESS', '2020-11-03 04:14:48', '2020-11-03 09:45:24'),
(41, 28, '2020110311012240088', 100, 200, 0, '', 'Draft', '', '2020-11-03 05:31:22', '0000-00-00 00:00:00'),
(42, 28, '2020110311020382038', 100, 200, 0, '', 'Draft', '', '2020-11-03 05:32:03', '0000-00-00 00:00:00'),
(43, 28, '2020110311032549725', 230, 200, 100, '150', 'Completed', 'Completed', '2020-11-03 05:33:25', '2020-11-02 01:23:52'),
(44, 1, '2020110311065286371', 100, 100, 633.745, '150', 'Completed', 'Completed', '2020-11-03 05:36:52', '2020-11-02 01:23:52'),
(45, 28, '2020110311211591952', 100, 200, 100, '602490', 'Completed', 'SUCCESS', '2020-11-03 05:51:15', '2020-11-03 11:21:45'),
(46, 58, '2020110311315673208', 100, 200, 100, '602524', 'Completed', 'SUCCESS', '2020-11-03 06:01:56', '2020-11-03 11:32:24'),
(47, 28, '2020110316333376323', 150, 200, 150, '603154', 'Completed', 'SUCCESS', '2020-11-03 11:03:33', '2020-11-03 16:34:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wallet_order`
--
ALTER TABLE `wallet_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wallet_order`
--
ALTER TABLE `wallet_order`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
