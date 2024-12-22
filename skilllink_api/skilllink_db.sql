-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2024 at 10:08 AM
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
-- Database: `skilllink_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_tbl`
--

CREATE TABLE `accounts_tbl` (
  `userId` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` text DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts_tbl`
--

INSERT INTO `accounts_tbl` (`userId`, `username`, `password`, `token`, `isdeleted`) VALUES
(8, 'john_doe4', '$2y$10$NmRlNGQwYzNmZTUzZWVmN.C0mAYM7Rga0rDbnq2ZzKIOIDEXtHXgO', 'ZWRkMTMyZjk2ODBlNzA5NjQxZTZlZTE2NTNlNzQyZWRkMmUwODAwNDMyYzA2MzBlNDhhMjdmNGE4MTk1YWFjMw==', 0),
(9, 'mac10', '$2y$10$Yjc4N2E0OWZiNTE2NzU5O.18RH3EouI4w7RGnDNevFsyhK0Qd0yaa', 'ZjBiMTBiZWRkMGFiMjYzOGE5YWVlZmYxZDRmNTNkYzdlMDBhYWY0MTM0ZDJhMDc4NjU5MzhjMjVmYmZhODlmNg==', 0),
(10, 'john_doe', '$2y$10$MWE5ZTljNzg2NGMyYmIwOOfa3AaDiGftIgBH8mWwvDx8coOVmVnA2', NULL, 0),
(11, 'john_doe1', '$2y$10$N2NjNWQzYjVlMjFkMDcxO.jIaBmIe6FovBTDKPf5lyrS7DRICLcAe', NULL, 0),
(12, 'john_doe2', '$2y$10$MTRhNDEwNTU3YzYwNzM2Mu/JV2X8SLWUi5IP2lN/nMkupp3yiaqCy', NULL, 0),
(16, 'kamote', '$2y$10$MGQ1YzM1NTUyMGY3MGUwM.bgRIlPNhgORz2RCB/HZEmpF4EGNddwO', NULL, 0),
(17, 'john_doe111', '$2y$10$Y2IwY2MzYzEyNDQ5NDM5N.OfTcDJ7oiAZUkAE5iReprt2j6nSZOYm', NULL, 0),
(25, 'john_doe2222', '$2y$10$NmE0NWM5OTgyYTVhNzc4ZeEb3cfzxy6LzUwPNRR1ZO/GYioMCX1mG', NULL, 0),
(27, 'testusername', '$2y$10$ZTFlNTJlYmY2YjhhNDVlNuFTD2t/Q62rBllxMPY0lVW6YgltVPNJC', NULL, 0),
(31, 'john_doe111111', '$2y$10$YjM1Mjc0YjgxYmRjM2U5NeWqUIUs68BwmbdWlmVskqXSFEeaoXD0.', 'MGUzZmE0YmE2ZTNmYmUwOTFhMWE2ZGFkNTBlOTU3NjU0NzQ1ODllNzU1ZjE3NTgwMzViMWFhZjYzNTA3NzMzNQ==', 0),
(33, 'Richard', '$2y$10$ZTU0MDdjMmEzMThjMzM5ZejA1Ynlo3hAkHr7chF.gYPdKX2yFnKHC', 'MGY1ZGU2OGY3YTA4NTBhOTllZjc0MmNmNDljNzQ0NzcxZDNlMTEzZTM3YWE3MWNjZDcwNTdlMzc4NGIzMWNhNw==', 0),
(34, 'john_doe1111111', '$2y$10$M2MwOWJkODFlZjE2NTEwZOcHRFfNdNccnTIOuQJS8.HxE0xdXtfcu', 'MjJkZDUxOWQ5OWRjZTYzZWQ5ODM4OWExZjEzM2YxODAxZDY0MDM3ZjBlMzkwMDQzMjhjZjkwODI1NDQ5MTRiZQ==', 0);

--
-- Triggers `accounts_tbl`
--
DELIMITER $$
CREATE TRIGGER `after_user_insert` AFTER INSERT ON `accounts_tbl` FOR EACH ROW BEGIN

    INSERT INTO balance_tbl (userId, username, balance)
    VALUES (NEW.userId, NEW.username, 0); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `balance_tbl`
--

CREATE TABLE `balance_tbl` (
  `userId` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `balance` float(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `balance_tbl`
--

INSERT INTO `balance_tbl` (`userId`, `username`, `balance`) VALUES
(8, 'john_doe4', 0.00),
(9, 'mac10', 0.00),
(10, 'john_doe', 0.00),
(11, 'john_doe1', 0.00),
(12, 'john_doe2', 0.00),
(16, 'kamote', 2100.00),
(17, 'john_doe111', 15700.00),
(25, 'john_doe2222', 2000.00),
(27, 'testusername', 0.00),
(31, 'john_doe111111', 0.00),
(33, 'Richard', 0.00),
(34, 'john_doe1111111', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `bookings_tbl`
--

CREATE TABLE `bookings_tbl` (
  `bookingId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `offerId` int(11) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `booking_status` enum('pending','confirmed','cancelled','completed') NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') NOT NULL,
  `payment_type` enum('credit_card','bank_transfer','cash','paypal','other') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `customer_notes` text DEFAULT NULL,
  `staffId` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cancellation_date` date DEFAULT NULL,
  `iscancelled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings_tbl`
--

INSERT INTO `bookings_tbl` (`bookingId`, `userId`, `offerId`, `start_time`, `end_time`, `booking_status`, `payment_status`, `payment_type`, `total_amount`, `transaction_id`, `payment_date`, `customer_notes`, `staffId`, `location`, `created_at`, `updated_at`, `cancellation_date`, `iscancelled`) VALUES
(1, 1, 1, NULL, NULL, 'pending', 'pending', 'cash', 10000.00, NULL, NULL, 'accept mo', 1, 'tondo', '2024-12-10 02:23:18', '2024-12-10 15:18:03', NULL, 1),
(2, 1, 1, NULL, NULL, 'pending', 'pending', 'cash', 10000.00, NULL, NULL, 'accept mo', 1, 'tondo', '2024-12-10 02:23:30', '2024-12-22 08:36:50', '2024-12-22', 1),
(3, 8, 33, NULL, NULL, 'pending', 'pending', 'cash', 10000.00, NULL, NULL, 'accept mo', 9, 'tondo', '2024-12-10 02:23:30', '2024-12-22 08:39:33', NULL, 0),
(4, 1, 1, NULL, NULL, 'pending', 'pending', 'cash', 10000.00, NULL, NULL, 'accept mo', 1, 'tondo', '2024-12-10 02:23:30', '2024-12-10 02:23:30', NULL, 0),
(5, 1, 1, NULL, NULL, 'confirmed', 'completed', 'cash', 10000.00, NULL, NULL, 'accept mo', 1, 'tondo', '2024-12-10 02:23:31', '2024-12-10 02:58:21', '2024-12-10', 0),
(6, 0, 0, NULL, NULL, 'pending', 'pending', 'credit_card', 0.00, NULL, NULL, NULL, 0, '', '2024-12-10 12:10:34', '2024-12-10 12:10:34', NULL, 0),
(7, 1, 1, NULL, NULL, 'pending', 'pending', 'cash', 10000.00, NULL, NULL, 'accept mo', 1, 'tondo', '2024-12-10 15:20:38', '2024-12-10 15:20:38', NULL, 0),
(8, 8, 1, NULL, NULL, 'pending', 'pending', 'cash', 10000.00, NULL, NULL, 'accept mo', 1, 'tondo', '2024-12-18 16:50:36', '2024-12-18 16:50:36', NULL, 0),
(9, 8, 33, NULL, NULL, 'pending', 'pending', 'cash', 10000.00, NULL, NULL, 'accept mo', 9, 'tondo', '2024-12-22 08:37:41', '2024-12-22 08:37:41', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `messages_tbl`
--

CREATE TABLE `messages_tbl` (
  `messageId` int(11) NOT NULL,
  `fromUserId` int(11) NOT NULL,
  `toUserId` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` text NOT NULL DEFAULT 'Sent',
  `created_at` datetime DEFAULT current_timestamp(),
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages_tbl`
--

INSERT INTO `messages_tbl` (`messageId`, `fromUserId`, `toUserId`, `message`, `status`, `created_at`, `isdeleted`) VALUES
(1, 8, 25, 'I love kamote', 'Sent', '2024-12-10 11:23:57', 1),
(10, 8, 17, 'I hate kamote', 'Sent', '2024-12-10 11:40:48', 0),
(11, 8, 17, 'I enjoy eating kamote', 'Sent', '2024-12-10 11:41:33', 0),
(12, 8, 12, 'I enjoy eating kamote', 'Sent', '2024-12-10 12:00:31', 0),
(13, 8, 12, 'test', 'Sent', '2024-12-10 18:37:16', 0),
(14, 8, 12, 'test', 'Sent', '2024-12-10 18:38:27', 0),
(15, 8, 12, 'test', 'Sent', '2024-12-10 18:38:43', 0),
(16, 8, 12, 'test', 'Sent', '2024-12-10 18:40:44', 0),
(17, 8, 12, 'test', 'Sent', '2024-12-10 18:40:45', 0),
(18, 8, 12, 'test', 'Sent', '2024-12-10 18:40:46', 0),
(19, 8, 12, 'test', 'Sent', '2024-12-10 18:40:47', 0),
(20, 8, 12, 'test', 'Sent', '2024-12-10 18:40:48', 0),
(21, 8, 12, '', 'Sent', '2024-12-10 18:41:43', 0),
(22, 8, 17, 'test', 'Sent', '2024-12-11 08:51:53', 0),
(23, 8, 17, 'Hi! I\'m interested in your service.', 'Sent', '2024-12-19 00:51:23', 0),
(24, 8, 17, 'Hi! I\'m interested in your service.', 'Sent', '2024-12-19 00:51:25', 0),
(25, 8, 16, 'Hi! I\'m interested in your service.', 'Sent', '2024-12-19 00:51:33', 0),
(26, 8, 16, 'Hi! I\'m interested in your service.', 'Sent', '2024-12-19 00:51:33', 1),
(27, 8, 16, 'Hi! I\'m interested in your service.', 'Sent', '2024-12-19 00:55:27', 1),
(28, 8, 9, 'Hotdogs', 'Sent', '2024-12-22 16:41:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `requests_tbl`
--

CREATE TABLE `requests_tbl` (
  `requestId` int(25) NOT NULL,
  `seekerUserId` int(25) NOT NULL,
  `requestTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` int(25) NOT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests_tbl`
--

INSERT INTO `requests_tbl` (`requestId`, `seekerUserId`, `requestTitle`, `description`, `price`, `duration`, `isdeleted`) VALUES
(23, 8, 'Graphic Designs', 'Looking for a designer to create a logo.', 200, '2 weeks', 1),
(24, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(25, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(26, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(27, 33, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(28, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(29, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(30, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews_tbl`
--

CREATE TABLE `reviews_tbl` (
  `reviewId` int(11) NOT NULL,
  `fromUserId` int(11) NOT NULL,
  `toUserId` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `created_at` date DEFAULT current_timestamp(),
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews_tbl`
--

INSERT INTO `reviews_tbl` (`reviewId`, `fromUserId`, `toUserId`, `rating`, `comment`, `created_at`, `isdeleted`) VALUES
(1, 8, 12, 5, 'Improved service after feedback!', '2024-12-10', 0),
(6, 8, 17, 4, 'Sir loudel is my idol', '2024-12-10', 0),
(7, 8, 17, 5, 'very nice work.', '2024-12-11', 0),
(8, 8, 17, 5, 'Excellent service!', '2024-12-19', 0),
(9, 8, 17, 5, 'Excellent service!', '2024-12-19', 0),
(10, 8, 9, 5, 'Excellent service!', '2024-12-19', 0),
(11, 8, 9, 5, 'Excellent service!', '2024-12-19', 1),
(12, 8, 16, 5, 'Excellent service!', '2024-12-19', 1),
(13, 8, 9, 5, 'Excellent service!', '2024-12-22', 0),
(14, 8, 9, 5, 'Excellent service!', '2024-12-22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `services_tbl`
--

CREATE TABLE `services_tbl` (
  `offerId` int(25) NOT NULL,
  `providerUserId` int(25) NOT NULL,
  `offerTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services_tbl`
--

INSERT INTO `services_tbl` (`offerId`, `providerUserId`, `offerTitle`, `description`, `price`, `duration`, `isdeleted`) VALUES
(33, 25, 'Web Development11', 'Building custom websites for small businesses.', 500, '1 month', 1),
(34, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 1),
(35, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(36, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(37, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(38, 8, 'Graphic Design', 'Looking for a designer to create a logo.', 200, '2 weeks', 0),
(39, 33, 'Web Development', 'Building custom websites for small businesses.', 500, '1 month', 0),
(40, 33, 'Web Development', 'Building custom websites for small businesses.', 500, '1 month', 0),
(41, 25, 'Web Development', 'Building custom websites for small businesses.', 500, '1 month', 0),
(42, 25, 'Web Development', 'Building custom websites for small businesses.', 500, '1 month', 0),
(43, 33, 'Web Development', 'Building custom websites for small businesses.', 500, '1 month', 0),
(44, 25, 'Web Development', 'Building custom websites for small businesses.', 500, '1 month', 0),
(45, 25, 'Web Development', 'Building custom websites for small businesses.', 500, '1 month', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions_tbl`
--

CREATE TABLE `transactions_tbl` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('deposit','withdrawal','transfer_out','transfer_in') NOT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions_tbl`
--

INSERT INTO `transactions_tbl` (`id`, `user_id`, `amount`, `type`, `from_user_id`, `created_at`) VALUES
(57, 16, 50.00, 'transfer_in', 16, '2024-12-10 05:58:39'),
(58, 17, 1000.00, 'transfer_in', 16, '2024-12-10 05:58:39'),
(59, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:00:03'),
(60, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:00:03'),
(61, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:00:39'),
(62, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:00:39'),
(63, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:00:47'),
(64, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:00:47'),
(65, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:00:57'),
(66, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:00:57'),
(67, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:01:50'),
(68, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:01:50'),
(69, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:02:23'),
(70, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:02:23'),
(71, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:02:28'),
(72, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:02:28'),
(73, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:03:31'),
(74, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:03:31'),
(75, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:04:58'),
(76, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:04:58'),
(77, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:05:54'),
(78, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:05:54'),
(79, 16, -1000.00, 'transfer_out', 25, '2024-12-10 06:06:11'),
(80, 25, 1000.00, 'transfer_in', 16, '2024-12-10 06:06:11'),
(81, 16, 1000.00, 'deposit', NULL, '2024-12-10 07:15:22'),
(82, 16, 1000.00, 'deposit', NULL, '2024-12-10 07:15:36'),
(83, 16, 1000.00, 'deposit', NULL, '2024-12-10 07:15:48'),
(84, 16, 1200.00, 'deposit', NULL, '2024-12-10 07:16:26'),
(85, 16, 1200.00, 'deposit', NULL, '2024-12-10 07:16:35'),
(86, 16, 1200.00, 'deposit', NULL, '2024-12-10 07:16:36'),
(87, 16, 1200.00, 'deposit', NULL, '2024-12-10 07:16:36'),
(88, 16, 1200.00, 'deposit', NULL, '2024-12-10 07:16:37'),
(89, 16, 1200.00, 'deposit', NULL, '2024-12-10 07:16:46'),
(90, 16, -1200.00, 'withdrawal', NULL, '2024-12-10 07:17:15'),
(91, 16, -1200.00, 'withdrawal', NULL, '2024-12-10 07:17:15'),
(92, 16, -1200.00, 'withdrawal', NULL, '2024-12-10 07:17:16'),
(93, 16, -12600.00, 'withdrawal', NULL, '2024-12-10 07:17:29'),
(94, 16, -12600.00, 'withdrawal', NULL, '2024-12-10 07:17:33'),
(95, 16, 0.00, 'withdrawal', NULL, '2024-12-10 07:17:40'),
(96, 16, 0.00, 'withdrawal', NULL, '2024-12-10 07:17:41'),
(97, 16, 0.00, 'withdrawal', NULL, '2024-12-10 07:17:42'),
(98, 16, 0.00, 'withdrawal', NULL, '2024-12-10 07:21:26'),
(99, 16, -10000.00, 'withdrawal', NULL, '2024-12-10 07:21:32'),
(100, 16, -10000.00, 'withdrawal', NULL, '2024-12-10 07:21:32'),
(101, 16, -10000.00, 'withdrawal', NULL, '2024-12-10 07:21:33'),
(102, 16, -10000.00, 'withdrawal', NULL, '2024-12-10 07:21:34'),
(103, 16, 12600.00, 'deposit', NULL, '2024-12-10 07:21:46'),
(104, 16, 12600.00, 'deposit', NULL, '2024-12-10 07:21:47'),
(105, 16, -12601.00, 'withdrawal', NULL, '2024-12-10 07:21:56'),
(106, 16, -12601.00, 'withdrawal', NULL, '2024-12-10 07:21:57'),
(107, 16, -12601.00, 'withdrawal', NULL, '2024-12-10 07:21:58'),
(108, 16, -12601.00, 'withdrawal', NULL, '2024-12-10 07:21:58'),
(110, 16, -1000.00, 'transfer_out', 17, '2024-12-10 19:12:31'),
(111, 17, 1000.00, 'transfer_in', 16, '2024-12-10 19:12:31'),
(112, 16, -1000.00, 'transfer_out', 17, '2024-12-10 19:14:24'),
(113, 17, 1000.00, 'transfer_in', 16, '2024-12-10 19:14:24'),
(114, 16, -1000.00, 'transfer_out', 17, '2024-12-10 19:18:09'),
(115, 17, 1000.00, 'transfer_in', 16, '2024-12-10 19:18:09'),
(116, 16, -1000.00, 'transfer_out', 17, '2024-12-10 19:20:20'),
(117, 17, 1000.00, 'transfer_in', 16, '2024-12-10 19:20:20'),
(118, 16, -1000.00, 'transfer_out', 17, '2024-12-10 19:22:06'),
(119, 17, 1000.00, 'transfer_in', 16, '2024-12-10 19:22:06'),
(120, 16, -1000.00, 'transfer_out', 17, '2024-12-10 19:22:13'),
(121, 17, 1000.00, 'transfer_in', 16, '2024-12-10 19:22:13'),
(122, 17, 700.00, 'deposit', 16, '2024-12-10 21:49:00'),
(123, 16, 6900.00, 'deposit', NULL, '2024-12-10 22:14:11'),
(127, 16, 6900.00, 'deposit', NULL, '2024-12-10 22:58:23'),
(128, 16, 0.00, 'withdrawal', NULL, '2024-12-10 22:59:49'),
(129, 16, -6900.00, 'withdrawal', NULL, '2024-12-10 22:59:54'),
(130, 16, -1000.00, 'transfer_out', 17, '2024-12-10 23:02:56'),
(131, 17, 1000.00, 'transfer_in', 16, '2024-12-10 23:02:56'),
(137, 16, 500.00, 'transfer_in', 17, '2024-12-11 02:56:38'),
(138, 16, 500.00, 'transfer_in', 17, '2024-12-11 02:56:40'),
(139, 16, 500.00, 'transfer_in', 17, '2024-12-11 02:56:41'),
(150, 16, 500.00, 'transfer_in', 17, '2024-12-11 02:57:55'),
(151, 16, 500.00, 'deposit', NULL, '2024-12-11 03:07:47'),
(152, 16, -500.00, 'transfer_out', 17, '2024-12-11 08:45:35'),
(153, 17, 500.00, 'transfer_in', 16, '2024-12-11 08:45:35'),
(154, 16, 500.00, 'transfer_in', 17, '2024-12-19 00:46:57'),
(155, 16, 500.00, 'transfer_in', 17, '2024-12-19 00:47:04'),
(156, 16, 500.00, 'transfer_in', 17, '2024-12-19 00:47:05'),
(157, 16, 500.00, 'transfer_in', 17, '2024-12-19 00:48:36'),
(158, 16, 500.00, 'transfer_in', 17, '2024-12-19 00:48:59'),
(159, 16, -100.00, 'transfer_out', 17, '2024-12-19 00:54:02'),
(160, 17, 100.00, 'transfer_in', 16, '2024-12-19 00:54:02'),
(161, 16, -100.00, 'transfer_out', 17, '2024-12-19 00:54:03'),
(162, 17, 100.00, 'transfer_in', 16, '2024-12-19 00:54:03'),
(163, 16, 50.00, 'transfer_in', 16, '2024-12-22 15:51:56'),
(164, 16, 200.00, 'deposit', NULL, '2024-12-22 16:04:16'),
(165, 16, 200.00, 'deposit', NULL, '2024-12-22 16:04:18'),
(166, 16, 200.00, 'deposit', NULL, '2024-12-22 16:04:32'),
(167, 16, -200.00, 'withdrawal', NULL, '2024-12-22 16:04:50'),
(168, 16, -200.00, 'withdrawal', NULL, '2024-12-22 16:05:18'),
(169, 16, -200.00, 'withdrawal', NULL, '2024-12-22 16:05:20'),
(170, 16, -200.00, 'withdrawal', NULL, '2024-12-22 16:05:21'),
(171, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:24'),
(172, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:25'),
(173, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:25'),
(174, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:25'),
(175, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:26'),
(176, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:26'),
(177, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:27'),
(178, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:29'),
(179, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:29'),
(180, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:29'),
(181, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:30'),
(182, 16, -10000.00, 'withdrawal', NULL, '2024-12-22 16:05:30'),
(183, 16, -100.00, 'transfer_out', 16, '2024-12-22 16:06:47'),
(184, 16, 100.00, 'transfer_in', 16, '2024-12-22 16:06:47');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `userId` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `roles` enum('seeker','provider','admin') NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`userId`, `email`, `firstname`, `lastname`, `roles`, `isdeleted`) VALUES
(8, 'john@example.com', 'John', 'Doe', 'admin', 0),
(9, 'john12@example.com', 'John', 'Doe', 'seeker', 0),
(10, 'testmail@example.com', 'John', 'Doe', 'seeker', 0),
(11, 'testmail1@example.com', 'John', 'Doe', 'seeker', 0),
(12, 'testmail2@example.com', 'John', 'Doe', 'provider', 0),
(16, 'kamote@example.com', 'John', 'Doe', 'seeker', 0),
(17, 'john@example1111.com', 'John', 'Doe', 'provider', 0),
(25, 'john@22.com', 'John', 'Doe', 'seeker', 0),
(27, 'john@example.com', 'John', 'Doe', 'seeker', 0),
(31, 'john111@example.com', 'John', 'Doe', 'seeker', 0),
(33, 'Richard@example.com', 'Richard', 'De Ocampo', 'admin', 0),
(34, 'john1111@example.com', 'John', 'Doe', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_tbl`
--
ALTER TABLE `accounts_tbl`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `balance_tbl`
--
ALTER TABLE `balance_tbl`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userId` (`userId`,`username`);

--
-- Indexes for table `bookings_tbl`
--
ALTER TABLE `bookings_tbl`
  ADD PRIMARY KEY (`bookingId`);

--
-- Indexes for table `messages_tbl`
--
ALTER TABLE `messages_tbl`
  ADD PRIMARY KEY (`messageId`),
  ADD KEY `messaging_tbl_ibfk_1` (`fromUserId`),
  ADD KEY `messaging_tbl_ibfk_2` (`toUserId`);

--
-- Indexes for table `requests_tbl`
--
ALTER TABLE `requests_tbl`
  ADD PRIMARY KEY (`requestId`);

--
-- Indexes for table `reviews_tbl`
--
ALTER TABLE `reviews_tbl`
  ADD PRIMARY KEY (`reviewId`),
  ADD KEY `reviews_tbl_ibfk_1` (`fromUserId`),
  ADD KEY `reviews_tbl_ibfk_2` (`toUserId`);

--
-- Indexes for table `services_tbl`
--
ALTER TABLE `services_tbl`
  ADD PRIMARY KEY (`offerId`);

--
-- Indexes for table `transactions_tbl`
--
ALTER TABLE `transactions_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `from_user_id` (`from_user_id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts_tbl`
--
ALTER TABLE `accounts_tbl`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `bookings_tbl`
--
ALTER TABLE `bookings_tbl`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `messages_tbl`
--
ALTER TABLE `messages_tbl`
  MODIFY `messageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `requests_tbl`
--
ALTER TABLE `requests_tbl`
  MODIFY `requestId` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `reviews_tbl`
--
ALTER TABLE `reviews_tbl`
  MODIFY `reviewId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `services_tbl`
--
ALTER TABLE `services_tbl`
  MODIFY `offerId` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `transactions_tbl`
--
ALTER TABLE `transactions_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balance_tbl`
--
ALTER TABLE `balance_tbl`
  ADD CONSTRAINT `balance_tbl_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `accounts_tbl` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages_tbl`
--
ALTER TABLE `messages_tbl`
  ADD CONSTRAINT `messaging_tbl_ibfk_1` FOREIGN KEY (`fromUserId`) REFERENCES `users_tbl` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `messaging_tbl_ibfk_2` FOREIGN KEY (`toUserId`) REFERENCES `users_tbl` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `reviews_tbl`
--
ALTER TABLE `reviews_tbl`
  ADD CONSTRAINT `reviews_tbl_ibfk_1` FOREIGN KEY (`fromUserId`) REFERENCES `users_tbl` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_tbl_ibfk_2` FOREIGN KEY (`toUserId`) REFERENCES `users_tbl` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `transactions_tbl`
--
ALTER TABLE `transactions_tbl`
  ADD CONSTRAINT `transactions_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `balance_tbl` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_tbl_ibfk_2` FOREIGN KEY (`from_user_id`) REFERENCES `balance_tbl` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD CONSTRAINT `fk_userId` FOREIGN KEY (`userId`) REFERENCES `accounts_tbl` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
