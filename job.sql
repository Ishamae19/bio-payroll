-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 02:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job`
--

-- --------------------------------------------------------

--
-- Table structure for table `1466_000`
--

CREATE TABLE `1466_000` (
  `id` int(11) NOT NULL,
  `part_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `bdl1` int(11) DEFAULT 0,
  `bdl2` int(11) DEFAULT 0,
  `bdl3` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `1466_000`
--

INSERT INTO `1466_000` (`id`, `part_name`, `price`, `bdl1`, `bdl2`, `bdl3`) VALUES
(1, 'test', 23.00, 23, 23, 32),
(2, 'hemlegsss', 2.00, 12, 123, 123),
(4, 'test', 2.00, 12, 32, 21),
(5, 'tests', 23.00, 12, 123, 123);

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `cellphone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','secretary') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `full_name`, `username`, `address`, `cellphone`, `email`, `password_hash`, `role`) VALUES
(1, 'John Rey Ocfemia', 'jocfems', 'Antipolo City', '09153620245', 'johnrey@gmail.com', '$2y$10$rDu.yjsjJnwQXx4sLPt0qe86aFyOwHDDUuTvbMO761KSMwoTAZplO', 'admin'),
(4, 'Edgar Dollentas', 'gahrie', 'Cluster K, Bagong Nayon', '09328496425', 'edgardollentas2004@gmail.com', '$2y$10$wuJ97UXCvH9a71bwBr269ec7RmamB7HAba/4JlYyBco9UEqWV0sry', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `serialnumber` double NOT NULL,
  `fingerprint_id` int(5) NOT NULL,
  `checkindate` date NOT NULL,
  `timein` time NOT NULL,
  `timeout` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `name`, `serialnumber`, `fingerprint_id`, `checkindate`, `timein`, `timeout`) VALUES
(1, 'gahrie', 213123, 1, '2024-12-18', '00:40:03', '01:56:42'),
(2, 'gary', 21312323, 67, '2024-12-18', '02:09:14', '02:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `serialnumber` double DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `operation` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `date_hired` date NOT NULL,
  `fingerprint_id` int(11) NOT NULL,
  `fingerprint_select` tinyint(1) NOT NULL DEFAULT 0,
  `time_in` time NOT NULL,
  `del_fingerid` tinyint(1) NOT NULL DEFAULT 0,
  `add_fingerid` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `serialnumber`, `name`, `operation`, `email`, `phone`, `date_hired`, `fingerprint_id`, `fingerprint_select`, `time_in`, `del_fingerid`, `add_fingerid`) VALUES
(9, 21312323, 'gary', 'qweqwe', 'oswaldo00@example.org', '09311059092', '2024-12-19', 67, 0, '00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `job_orders`
--

CREATE TABLE `job_orders` (
  `id` int(11) NOT NULL,
  `CMT` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bundle` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_orders`
--

INSERT INTO `job_orders` (`id`, `CMT`, `quantity`, `price`, `bundle`) VALUES
(26, '1466-000', 1000.00, 50.00, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `1466_000`
--
ALTER TABLE `1466_000`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_orders`
--
ALTER TABLE `job_orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `1466_000`
--
ALTER TABLE `1466_000`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `job_orders`
--
ALTER TABLE `job_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
