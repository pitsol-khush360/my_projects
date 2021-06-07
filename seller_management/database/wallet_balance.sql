-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2020 at 01:57 PM
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
-- Table structure for table `wallet_balance`
--

CREATE TABLE `wallet_balance` (
  `id` bigint(20) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `opening_balance` double NOT NULL,
  `closing_balance` double NOT NULL,
  `balance_currency` varchar(4) DEFAULT NULL,
  `value_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `creation_datetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallet_balance`
--

INSERT INTO `wallet_balance` (`id`, `seller_id`, `opening_balance`, `closing_balance`, `balance_currency`, `value_date`, `creation_datetime`) VALUES
(1, 1, 0, 500, 'INR', '2020-10-19 18:30:00', '2020-10-20 08:22:47'),
(2, 1, 500, 245.425, 'INR', '2020-10-22 18:30:00', '2020-10-23 08:24:00'),
(25, 1, 245.425, -305.93, 'INR', '2020-10-28 18:30:00', '2020-10-29 11:30:03'),
(26, 1, -305.93, -316.255, 'INR', '2020-10-29 18:30:00', '2020-10-30 06:34:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wallet_balance`
--
ALTER TABLE `wallet_balance`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wallet_balance`
--
ALTER TABLE `wallet_balance`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
