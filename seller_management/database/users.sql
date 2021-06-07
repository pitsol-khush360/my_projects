-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2020 at 03:24 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role` int(1) NOT NULL DEFAULT 2,
  `mobile` bigint(10) DEFAULT NULL,
  `password` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_password` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT 'A',
  `created_datetime` timestamp NULL DEFAULT current_timestamp(),
  `updated_datetime` timestamp NULL DEFAULT current_timestamp(),
  `mobile_verified` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accept_terms_and_conditions` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role`, `mobile`, `password`, `old_password`, `username`, `business_name`, `status`, `created_datetime`, `updated_datetime`, `mobile_verified`, `accept_terms_and_conditions`) VALUES
(1, 2, 9685124785, '12345', NULL, 'mohan12', 'Mohan Kumar', 'I', '2020-09-09 09:54:51', '2020-09-09 09:54:51', NULL, NULL),
(2, 2, 7458965478, '12345678', '123456789', 'satvik', 'ram', 'A', '2020-09-12 03:29:05', '2020-09-30 12:37:20', NULL, NULL),
(4, 2, 7854125874, '12345678', NULL, 'shyam', 'shyam', 'A', '2020-09-16 11:50:17', '2020-09-16 11:50:17', NULL, NULL),
(5, 2, 7896547854, '1234', NULL, 'gopal', 'gopal', 'A', '2020-09-16 12:03:07', '2020-09-16 12:03:07', NULL, NULL),
(6, 2, 7845214785, '1254', NULL, NULL, 'new', 'A', '2020-09-17 03:25:24', '2020-09-17 03:25:24', NULL, NULL),
(7, 2, 7854125896, '7854', NULL, NULL, 'new1', 'A', '2020-09-17 03:25:46', '2020-09-17 03:25:46', NULL, NULL),
(10, 2, 7412589632, '12345', NULL, '', 'shyam1', 'A', '2020-09-17 04:04:59', '2020-09-17 04:04:59', NULL, NULL),
(11, 2, 7412583698, '12345', NULL, NULL, 'ganesh', 'A', '2020-09-18 17:12:56', '2020-09-18 17:12:56', NULL, NULL),
(12, 2, 7412563258, '12345678', NULL, NULL, 'ganesh', 'A', '2020-09-23 14:29:15', '2020-09-23 14:29:15', NULL, NULL),
(13, 2, 7412583692, '12345678', NULL, 'rohan547', 'rohan', 'A', '2020-10-03 11:44:13', '2020-10-03 11:44:13', NULL, NULL),
(14, 2, 9852147856, '12345678', NULL, 'ram353', 'ram', 'A', '2020-10-06 12:41:22', '2020-10-06 12:41:22', 'Yes', 'Yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
