-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2020 at 02:35 PM
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
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` bigint(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `mobile` bigint(20) DEFAULT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `resolution_remarks` text DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `seller_id`, `mobile`, `subject`, `description`, `resolution_remarks`, `status`, `created_date`) VALUES
(1, 2, 7036017291, 'Asking Functionality', '2020:09:22: mahesh how it works\n  2020:09:24:\n fs;ldfl;es\n  2020:09:24:\n Please Tell Me ................', 'By Going To Navigation', 4, '2020-09-22 05:55:54'),
(2, 2, 7458965478, 'Issue In Seller Profile, i just wanted to ask like what can we do with seller pr', 'Seller Profile Is Not Accessible', NULL, 1, '2020-09-23 10:58:05'),
(3, 2, 7458965478, 'New issue', 'new issue found', NULL, 1, '2020-09-23 12:41:46'),
(4, 2, 7458965478, 'about seller promocode', 'how to access seller promocode page ?\n  2020:09:24:\n new', 'Click on promocodes in navigation', 1, '2020-09-24 03:56:32'),
(5, 2, 7458965478, 'about product management', 'how to access product management ?', 'By navigation', 2, '2020-09-24 04:06:35'),
(6, 2, 7458965478, 'about dashboard', 'what are the icons on dashboards ?\n  2020:09:24:\n ya what icons ?', 'icons ', 4, '2020-09-24 11:58:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
