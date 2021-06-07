-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2020 at 06:53 AM
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
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `product_id` int(11) NOT NULL,
  `product_seller_id` int(11) DEFAULT NULL,
  `productimage` text DEFAULT NULL,
  `product_name` varchar(60) DEFAULT NULL,
  `product_price` int(6) DEFAULT NULL,
  `product_unit` int(1) NOT NULL DEFAULT 1,
  `product_price_currency` varchar(3) DEFAULT NULL,
  `product_brand` text DEFAULT NULL,
  `product_category` varchar(70) DEFAULT NULL,
  `product_sub_category` varchar(70) DEFAULT NULL,
  `product_status` varchar(10) NOT NULL DEFAULT 'Active',
  `product_store` text DEFAULT NULL,
  `product_description` longtext DEFAULT NULL,
  `product_creation_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_modification_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedby` int(10) DEFAULT NULL,
  `product_catalogue_id` int(11) DEFAULT NULL,
  `warranty_duration` int(11) DEFAULT NULL,
  `warranty_days_mon_yr` varchar(6) DEFAULT NULL,
  `warrant_type` varchar(15) DEFAULT 'Not Applicable',
  `valid_from` datetime NOT NULL DEFAULT current_timestamp(),
  `product_model` text DEFAULT NULL,
  `product_offer_price` int(11) DEFAULT NULL,
  `discount_type` varchar(15) DEFAULT 'Not Applicable',
  `discount` int(5) DEFAULT 0,
  `tax_type` varchar(15) DEFAULT 'Not Applicable',
  `tax_percent` int(11) DEFAULT 0,
  `free_shipping` int(1) DEFAULT 0,
  `cancellation_available` int(1) DEFAULT 0,
  `cash_on_delivery` int(1) DEFAULT 1,
  `return_available` int(1) DEFAULT 0,
  `product_inventory` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_details`
--

INSERT INTO `product_details` (`product_id`, `product_seller_id`, `productimage`, `product_name`, `product_price`, `product_unit`, `product_price_currency`, `product_brand`, `product_category`, `product_sub_category`, `product_status`, `product_store`, `product_description`, `product_creation_datetime`, `product_modification_datetime`, `updatedby`, `product_catalogue_id`, `warranty_duration`, `warranty_days_mon_yr`, `warrant_type`, `valid_from`, `product_model`, `product_offer_price`, `discount_type`, `discount`, `tax_type`, `tax_percent`, `free_shipping`, `cancellation_available`, `cash_on_delivery`, `return_available`, `product_inventory`) VALUES
(1, 2, 'defaultpic.jpg', 'redmi', 1000, 1, 'INR', 'redmi', 'electronics', 'mobiles', 'Active', 'a-z', 'wonderful', '2020-09-13 10:57:23', '2020-09-13 10:57:23', 1, 2, 2, 'Months', 'Gaurantee', '2020-09-13 16:27:23', '123', 20, NULL, NULL, '-', 10, 0, 0, 0, 0, 1),
(5, 2, 'defaultpic.jpg', 'iron boxes', 1000, 1, 'INR', 'lotus', 'electronics', 'iron box', 'Active', 'a-z', 'good', '2020-09-13 16:08:13', '2020-09-29 03:53:31', 2, 2, 2, 'Months', 'Warranty', '2020-09-16 11:01:14', '123', 300, 'Percentage', 2, '-', 10, 0, 0, 0, 0, 0),
(8, 2, 'defaultpic.jpg', 'Redmi note 4', 13000, 1, 'INR', '', '', '', 'Active', '', 'Awesome', '2020-09-16 03:17:52', '2020-09-16 03:17:52', 2, 2, 0, 'Days', '', '2020-09-16 08:47:52', '', 0, NULL, NULL, '', 0, 0, 0, 0, 0, 0),
(9, 2, 'defaultpic.jpg', 'new', 100, 1, 'INR', NULL, NULL, NULL, 'Active', NULL, 'good', '2020-09-16 04:41:26', '2020-09-16 04:41:26', 2, 2, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(10, 2, 'defaultpic.jpg', 'new2', 400, 1, 'INR', NULL, NULL, NULL, 'Active', NULL, 'good', '2020-09-16 05:12:24', '2020-09-16 05:12:24', 2, 2, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(11, 2, 'defaultpic.jpg', 'Blue Box 1', 12000, 1, 'INR', 'Boxes', 'cat1', 'cat1 sub', 'Active', '', 'Average', '2020-09-16 06:49:56', '2020-09-16 06:54:50', 2, 2, 0, 'Months', '', '2020-09-16 12:24:50', '', 100, NULL, NULL, '', 0, 0, 0, 0, 0, 0),
(12, 2, 'defaultpic.jpg', 'Redmi note 3', 13000, 1, 'INR', '', 'cat1', 'sub1', 'Active', '', 'Good', '2020-09-16 07:02:08', '2020-09-17 13:21:08', 2, 2, 0, '', 'Gaurantee', '2020-09-17 18:51:08', '', 0, NULL, NULL, 'GST', 20, 0, 1, 0, 0, 1),
(13, 2, 'defaultpic.jpg', 'Lenovo Vibe', 11000, 1, 'INR', '', '', '', 'Inactive', '', 'Its good in use', '2020-09-16 08:00:51', '2020-09-18 04:54:21', 2, 2, 0, '', 'Gaurantee', '2020-09-18 10:24:21', '', 0, NULL, NULL, 'GST', 0, 1, 1, 1, 1, 0),
(15, 5, 'defaultpic.jpg', 'Vibe k5', 15000, 1, 'INR', 'Lenovo', '', '', 'Active', '', 'good', '2020-09-16 12:05:12', '2020-09-16 12:06:24', 5, 15, 0, '', '', '2020-09-16 17:36:24', '', 100, NULL, NULL, '', 0, 0, 1, 1, 1, 0),
(16, 5, 'defaultpic.jpg', 'vibe k', 14000, 1, 'INR', NULL, NULL, NULL, 'Active', NULL, 'good', '2020-09-16 12:17:49', '2020-09-16 12:17:49', 5, 13, NULL, NULL, NULL, '2020-09-16 17:47:49', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1),
(17, 2, 'defaultpic.jpg', 'Apple', 120, 1, 'INR', NULL, NULL, NULL, 'Active', NULL, 'Good', '2020-09-17 04:14:39', '2020-09-17 04:14:39', 10, 13, NULL, NULL, NULL, '2020-09-17 09:44:39', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0),
(18, 2, 'defaultpic.jpg', 'Mango', 100, 1, 'INR', '', '', '', 'Active', '', 'good', '2020-09-17 04:15:27', '2020-09-17 04:42:15', 10, 13, 0, 'Years', '', '2020-09-17 10:12:15', '', 0, NULL, NULL, '', 200, 1, 1, 1, 1, 0),
(20, 2, 'defaultpic.jpg', 'product', 50, 1, 'INR', NULL, NULL, NULL, 'Active', NULL, '', '2020-09-18 12:33:06', '2020-09-18 12:33:06', 10, 13, NULL, NULL, NULL, '2020-09-18 18:03:06', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0),
(21, 2, 'defaultpic.jpg', 'Lenovo', 11000, 1, 'INR', NULL, NULL, NULL, 'Inactive', NULL, 'good', '2020-09-18 13:50:10', '2020-09-18 13:50:10', 10, 23, NULL, NULL, NULL, '2020-09-18 19:20:10', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0),
(22, 10, 'defaultpic.jpg', 'Redmi 3', 13000, 1, 'INR', '', '', '', 'Active', '', 'Good', '2020-09-18 14:54:24', '2020-09-18 15:02:54', 10, 13, 0, 'Years', 'Warranty', '2020-09-18 20:32:54', '', 0, NULL, NULL, 'GST', 0, 1, 1, 1, 1, 1),
(23, 2, 'defaultpic.jpg', 'Oppo', 15000, 1, 'INR', NULL, NULL, NULL, 'Active', NULL, 'not cool', '2020-09-18 15:02:07', '2020-09-18 15:02:07', 10, 13, NULL, NULL, NULL, '2020-09-18 20:32:07', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0),
(24, 2, 'defaultpic.jpg', 'new', 200, 1, 'INR', NULL, NULL, NULL, 'Active', NULL, 'good', '2020-09-21 04:26:21', '2020-09-21 04:26:21', 2, 2, NULL, NULL, NULL, '2020-09-21 09:56:21', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0),
(25, 2, 'defaultpic.jpg', 'Pottato', 30, 2, 'INR', 'vegi', 'vegitables', 'subvegi', 'Inactive', NULL, 'Fresh pottatoes', '2020-09-21 07:07:48', '2020-09-29 11:18:04', 2, 34, 1, 'Days', 'Gaurantee', '2020-09-21 12:37:48', 'vegi', NULL, 'Flat', 2, 'Percentage', 1, 1, 1, 1, 0, 1),
(26, 2, 'defaultpic.jpg', 'Tomato', 80, 1, 'INR', NULL, 'vagitables', 'sub-vegi', 'Active', NULL, 'fresh', '2020-09-21 07:08:20', '2020-09-29 09:53:25', 2, 34, NULL, NULL, NULL, '2020-09-21 12:38:20', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0),
(30, 2, 'defaultpic.jpg', 'Onion', 22, 1, 'INR', NULL, NULL, NULL, 'Active', NULL, 'cool', '2020-09-21 07:08:47', '2020-09-21 07:08:47', 2, 34, NULL, NULL, NULL, '2020-09-21 12:38:47', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0),
(31, 2, 'defaultpic.jpg', 'new1', 1200, 1, 'INR', '', '', '', 'Active', '', 'good', '2020-09-22 05:02:43', '2020-09-22 05:03:27', 2, 2, 0, 'Days', 'Gaurantee', '2020-09-22 10:33:27', '', 0, NULL, NULL, 'GST', 0, 1, 1, 1, 1, 1),
(32, 2, 'defaultpic.jpg', 'good', 10, 1, 'INR', NULL, NULL, NULL, 'Inactive', NULL, 'new product', '2020-09-22 05:04:17', '2020-09-22 05:04:17', 2, 34, NULL, NULL, NULL, '2020-09-22 10:34:17', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0),
(33, 2, 'defaultpic.jpg', 'new12', 12, 1, 'INR', NULL, 'smartphone', 'subsmartphone', 'Inactive', NULL, '', '2020-09-22 05:04:49', '2020-09-29 03:47:41', 2, 34, NULL, NULL, NULL, '2020-09-22 10:34:49', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1),
(34, 2, 'defaultpic.jpg', 'newp', 130, 3, 'INR', 'newbrand', 'c1', 'sub1', 'Active', NULL, 'good', '2020-09-22 05:05:15', '2020-09-29 11:11:47', 2, 2, 2, 'Years', 'Gaurantee', '2020-09-22 10:35:15', 'newmodel', NULL, 'Percentage', 2, 'Percentage', 5, 0, 1, 0, 0, 0),
(35, 2, 'defaultpic.jpg', 'new blue box', 800, 5, 'INR', NULL, NULL, NULL, 'Active', NULL, 'New Boxes Available', '2020-09-30 13:16:44', '2020-10-09 12:59:18', 2, 2, NULL, NULL, NULL, '2020-09-30 18:46:44', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
