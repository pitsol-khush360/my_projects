-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2020 at 01:59 PM
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
-- Table structure for table `product_library`
--

CREATE TABLE `product_library` (
  `product_id` int(10) NOT NULL,
  `collection_id` int(5) DEFAULT NULL,
  `product_name` varchar(30) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `image_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_library`
--

INSERT INTO `product_library` (`product_id`, `collection_id`, `product_name`, `product_description`, `image_name`) VALUES
(1, 1, 'Pottato', 'Fresh Pottatoes are available here from within this vegetable collection', '/images/product_library/pottato.jpg'),
(2, 1, 'ladyfinger', NULL, '/images/product_library/ladyfinger.jpg'),
(3, 2, 'Mango', 'Newly brought', '/images/product_library/mango.jpg'),
(4, 2, 'Apple', 'Fresh', '/images/product_library/apple.jpg'),
(5, 1, 'Chilli', 'Fresh', '/images/product_library/chilli.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_library`
--
ALTER TABLE `product_library`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_library`
--
ALTER TABLE `product_library`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
