-- phpMyAdmin SQL Dump
-- Online Banking System Database
-- Database: `onlinebanking`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `onlinebanking`
--
CREATE DATABASE IF NOT EXISTS `onlinebanking` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `onlinebanking`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 10000.00,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping sample data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `user_name`, `user_email`, `phone`, `password`, `balance`, `created_at`) VALUES
(1, '1001', 'John Doe', 'john@example.com', '1234567890', 'password123', 50000.00, NOW()),
(2, '1002', 'Jane Smith', 'jane@example.com', '0987654321', 'password123', 75000.00, NOW()),
(3, '1003', 'Mike Johnson', 'mike@example.com', '5551234567', 'password123', 30000.00, NOW()),
(4, '1004', 'Sarah Williams', 'sarah@example.com', '5559876543', 'password123', 45000.00, NOW()),
(5, '1005', 'David Brown', 'david@example.com', '5555555555', 'password123', 60000.00, NOW());

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `sno` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(100) NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sample transaction data
--

INSERT INTO `transaction` (`sno`, `sender`, `receiver`, `balance`, `datetime`) VALUES
(1, 'John Doe', 'Jane Smith', 5000.00, NOW()),
(2, 'Jane Smith', 'Mike Johnson', 2500.00, NOW());

