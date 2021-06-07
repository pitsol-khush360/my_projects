-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2020 at 08:08 AM
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
-- Table structure for table `product_default_settings`
--

CREATE TABLE `product_default_settings` (
  `id` int(10) NOT NULL,
  `seller_id` int(10) DEFAULT NULL,
  `product_category` varchar(80) DEFAULT NULL,
  `discount_type` varchar(15) DEFAULT 'Not Applicable',
  `discount_percent` int(3) DEFAULT 0,
  `tax_type` varchar(15) DEFAULT 'Not Applicable',
  `tax_percent` int(3) DEFAULT 0,
  `free_shipping` int(1) NOT NULL DEFAULT 0,
  `return_available` int(1) NOT NULL DEFAULT 0,
  `cash_on_delivery` int(1) NOT NULL DEFAULT 1,
  `warrant_type` varchar(20) DEFAULT 'Not Applicable',
  `warrant_duration` int(3) DEFAULT NULL,
  `warranty_days_mon_yr` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_default_settings`
--

INSERT INTO `product_default_settings` (`id`, `seller_id`, `product_category`, `discount_type`, `discount_percent`, `tax_type`, `tax_percent`, `free_shipping`, `return_available`, `cash_on_delivery`, `warrant_type`, `warrant_duration`, `warranty_days_mon_yr`) VALUES
(1, 2, 'Smartphones Category1', 'Flat', 20, 'GST', 3, 0, 0, 1, 'Warranty', 1, 'Years'),
(3, 18, NULL, 'Not Applicable', 0, 'Not Applicable', 0, 0, 0, 1, 'Not Applicable', 0, '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_default_settings`
--
ALTER TABLE `product_default_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_default_settings`
--
ALTER TABLE `product_default_settings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
