-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2020 at 11:36 AM
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
-- Table structure for table `product_catalogue`
--

CREATE TABLE `product_catalogue` (
  `catalogue_id` int(11) NOT NULL,
  `catalogue_seller_id` int(10) DEFAULT NULL,
  `catalogue_Name` varchar(70) DEFAULT NULL,
  `creation_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `modification_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedby` int(10) DEFAULT NULL,
  `catalogue_status` varchar(10) NOT NULL DEFAULT 'Active',
  `catalogue_image` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_catalogue`
--

INSERT INTO `product_catalogue` (`catalogue_id`, `catalogue_seller_id`, `catalogue_Name`, `creation_datetime`, `modification_datetime`, `updatedby`, `catalogue_status`, `catalogue_image`) VALUES
(2, 2, 'Smartphones', '2020-09-02 05:57:43', '2020-09-11 10:34:34', 12, 'Inactive', NULL),
(13, 2, 'electronics', '2020-09-11 15:10:50', '2020-09-11 15:10:50', 3, 'Inactive', 'electronics3.jpg'),
(14, 4, 'electronics', '2020-09-11 15:14:44', '2020-09-11 15:14:44', 4, 'Active', 'electronics4.jpg'),
(15, 5, 'electronics', '2020-09-11 15:17:42', '2020-09-11 15:17:42', 5, 'Active', 'electronics5.jpg'),
(16, 2, 'Boxes', '2020-09-11 16:15:13', '2020-09-11 16:15:13', 3, 'Active', 'electronics3.jpg'),
(18, 3, 'Smartphones', '2020-09-16 11:30:35', '2020-09-16 11:30:35', 3, 'Inactive', 'defaultpic.jpg'),
(19, 5, 'Smartphoness', '2020-09-16 12:03:53', '2020-09-16 12:04:20', 5, 'Active', 'defaultpic.jpg'),
(20, 5, 'fruits', '2020-09-16 12:19:05', '2020-09-16 12:19:05', 5, 'Active', 'defaultpic.jpg'),
(21, 5, 'My Catalogue', '2020-09-16 13:21:04', '2020-09-16 13:21:04', 5, 'Active', 'defaultpic.jpg'),
(23, 10, 'Fruits 12', '2020-09-17 04:14:02', '2020-09-18 14:23:22', 10, 'Active', 'defaultpic.jpg'),
(24, 10, 'Smartphones', '2020-09-18 14:22:35', '2020-09-18 14:22:35', 10, 'Active', 'defaultpic.jpg'),
(25, 10, 'Redmi', '2020-09-18 14:27:49', '2020-09-18 14:27:49', 10, 'Active', 'defaultpic.jpg'),
(26, 10, 'remi', '2020-09-18 14:29:03', '2020-09-18 14:29:03', 10, 'Active', 'defaultpic.jpg'),
(27, 10, 'new', '2020-09-18 14:31:34', '2020-09-18 14:31:34', 10, 'Active', 'defaultpic.jpg'),
(28, 10, 'new1', '2020-09-18 14:33:57', '2020-09-18 14:33:57', 10, 'Active', 'defaultpic.jpg'),
(29, 10, 'new', '2020-09-18 14:34:36', '2020-09-18 14:34:36', 10, 'Active', 'defaultpic.jpg'),
(30, 10, 'new', '2020-09-18 14:35:58', '2020-09-18 14:35:58', 10, 'Active', 'defaultpic.jpg'),
(31, 10, 'new', '2020-09-18 14:37:07', '2020-09-18 14:37:07', 10, 'Active', 'defaultpic.jpg'),
(32, 10, 'new3', '2020-09-18 14:42:00', '2020-09-18 14:42:00', 10, 'Active', 'defaultpic.jpg'),
(33, 10, 'new4', '2020-09-18 14:43:14', '2020-09-18 14:43:14', 10, 'Active', 'defaultpic.jpg'),
(34, 2, 'Vegitables', '2020-09-21 07:07:00', '2020-09-21 07:07:00', 2, 'Active', 'defaultpic.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_catalogue`
--
ALTER TABLE `product_catalogue`
  ADD PRIMARY KEY (`catalogue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_catalogue`
--
ALTER TABLE `product_catalogue`
  MODIFY `catalogue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
