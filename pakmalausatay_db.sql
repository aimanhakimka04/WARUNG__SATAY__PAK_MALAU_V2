-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 01:31 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pakmalausatay_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `client_ip` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `client_ip`, `user_id`, `product_id`, `qty`) VALUES
(317, '', 21, 27, 1),
(319, '', 16, 32, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `icon_classname` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `icon_classname`, `status`) VALUES
(13, 'Satay', NULL, 1),
(14, 'Main course', NULL, 1),
(15, 'Soups', NULL, 1),
(16, 'Side Dishes', NULL, 1),
(17, 'Beverages', NULL, 1),
(18, 'Add-On', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_data`
--

CREATE TABLE `chatbot_data` (
  `id` int(11) NOT NULL,
  `section` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `options` text NOT NULL,
  `urls` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_data`
--

INSERT INTO `chatbot_data` (`id`, `section`, `title`, `options`, `urls`) VALUES
(6, 'chatinit', '[\"Hello <span class=\'emoji\'> \\ud83d\\udc4b<\\/span>\",\"Welcome to Warung Satay Pak Malau\",\"How can I assist you today?\"]', '[\"View Menu\\ud83c\\udf74\",\"Place an Order\",\"Check Order Status\",\"Ask About Our Promotions\",\"Customer Service\"]', '[]'),
(11, 'view', '[\"You can select the \\ud83c\\udf7d\\ufe0f Menu option from the header to go to the \\ud83d\\udccb menu page. On that page, you can choose \\ud83d\\udcc2 categories from the header bar, or use the \\ud83d\\udd0d search function to find specific \\ud83d\\uded2 products.\"]', '[]', '[]'),
(12, 'place', '[\"\\ud83c\\udf1f Welcome to Our Food Ordering System! \\ud83c\\udf1f\\r\\n    \\r\\n    <p>\\ud83c\\udf7d\\ufe0f <strong>Select Your Favorite Food:<\\/strong><br>\\r\\n    Choose from our delicious menu!<\\/p>\\r\\n\\r\\n    <p>\\ud83e\\udd62 <strong>Choose the Quantity:<\\/strong><br>\\r\\n    How much can you eat? \\ud83d\\ude0b<\\/p>\\r\\n\\r\\n    <p>\\ud83e\\udd64 <strong>Select Your Preferred Drink:<\\/strong><br>\\r\\n    Pair your meal with a refreshing drink!<\\/p>\\r\\n\\r\\n    <p>\\ud83d\\uded2 <strong>Add Items to Your Cart:<\\/strong><br>\\r\\n    Once you\\u2019ve made your selections, hit that <strong>Add to Cart<\\/strong> button!<\\/p>\\r\\n\\r\\n    <p>\\ud83d\\udce6 <strong>Proceed to the Cart Page:<\\/strong><br>\\r\\n    Review your order carefully to make sure everything looks perfect!<\\/p>\\r\\n\\r\\n    <p>\\u2705 <strong>Complete the Checkout Process:<\\/strong><br>\\r\\n    Finish up and get ready to enjoy your meal! \\ud83c\\udf74<\\/p>\"]', '[]', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `chatrooms`
--

CREATE TABLE `chatrooms` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `msg` varchar(200) NOT NULL,
  `created_on` datetime NOT NULL,
  `role_user` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `chatrooms`
--

INSERT INTO `chatrooms` (`id`, `userid`, `msg`, `created_on`, `role_user`) VALUES
(1, 16, 'hello', '2024-10-05 18:25:09', 'customer'),
(2, 16, 'hello', '2024-10-05 18:26:07', 'customer'),
(3, 16, 'hello', '2024-10-05 18:29:15', 'customer'),
(4, 16, 'hello', '2024-10-05 18:29:49', 'customer'),
(5, 16, 'hello', '2024-10-05 18:30:35', 'customer'),
(6, 16, 'hello', '2024-10-05 18:30:41', 'customer'),
(7, 16, 'hello', '2024-10-05 18:32:40', 'customer'),
(8, 16, 'hello', '2024-10-05 18:32:43', 'customer'),
(9, 16, 'hello', '2024-10-05 18:40:54', 'customer'),
(10, 16, 'hello', '2024-10-05 19:00:49', 'customer'),
(11, 16, 'hello', '2024-10-05 19:26:00', 'customer'),
(12, 16, 'hello', '2024-10-05 19:26:03', 'customer'),
(13, 16, 'hello', '2024-10-05 19:58:05', 'customer'),
(14, 16, 'hello', '2024-10-05 19:58:13', 'customer'),
(15, 16, 'hello', '2024-10-05 20:00:20', 'customer'),
(16, 16, 'hello', '2024-10-05 20:00:22', 'customer'),
(17, 16, 'hello', '2024-10-05 20:00:31', 'customer'),
(18, 16, 'hello', '2024-10-05 20:00:36', 'customer'),
(19, 16, 'hello', '2024-10-05 20:39:06', 'customer'),
(20, 16, 'hello', '2024-10-05 20:39:44', 'customer'),
(21, 16, 'hello', '2024-10-05 20:49:29', 'customer'),
(22, 16, 'hello', '2024-10-05 20:54:08', 'customer'),
(23, 16, 'hello', '2024-10-05 20:57:17', 'customer'),
(24, 16, 'hello', '2024-10-05 21:09:08', 'customer'),
(25, 16, 'hello', '2024-10-05 21:09:47', 'customer'),
(26, 16, 'hello', '2024-10-05 21:10:44', 'customer'),
(27, 16, 'hello', '2024-10-05 21:11:40', 'customer'),
(28, 16, 'hello', '2024-10-05 21:13:46', 'customer'),
(29, 16, 'hello', '2024-10-05 21:15:55', 'customer'),
(30, 16, 'hello', '2024-10-05 21:15:58', 'customer'),
(31, 16, 'hello', '2024-10-06 18:04:18', 'customer'),
(32, 16, 'hello', '2024-10-06 18:10:03', 'customer'),
(33, 16, 'hello', '2024-10-06 18:10:15', 'customer'),
(34, 16, 'hello', '2024-10-06 18:10:28', 'customer'),
(35, 16, 'hello', '2024-10-06 18:13:42', 'customer'),
(36, 16, 'hello', '2024-10-06 18:13:47', 'customer'),
(37, 16, 'hello', '2024-10-06 18:13:52', 'customer'),
(38, 16, 'hello', '2024-10-06 18:39:04', 'customer'),
(39, 16, 'hello', '2024-10-06 18:39:15', 'customer'),
(40, 16, 'hello', '2024-10-06 19:43:25', 'customer'),
(41, 16, 'hello', '2024-10-06 19:43:55', 'customer'),
(42, 16, 'hello', '2024-10-06 19:44:04', 'customer'),
(43, 16, 'hello', '2024-10-06 19:44:26', 'customer'),
(44, 16, 'hello', '2024-10-06 19:44:36', 'customer'),
(45, 16, 'hello', '2024-10-06 19:44:45', 'customer'),
(46, 16, 'hello', '2024-10-06 19:44:52', 'customer'),
(47, 16, 'hello', '2024-10-06 19:45:05', 'customer'),
(48, 16, 'hello', '2024-10-06 19:45:13', 'customer'),
(49, 16, 'hello', '2024-10-06 19:45:18', 'customer'),
(50, 16, 'hello', '2024-10-07 17:10:56', 'customer'),
(51, 16, 'hello', '2024-10-07 17:19:42', 'customer'),
(52, 16, 'hello', '2024-10-07 17:44:52', 'customer'),
(53, 16, 'hello', '2024-10-07 19:56:19', 'customer'),
(54, 16, 'hello', '2024-10-07 19:57:14', 'customer'),
(55, 16, 'hello', '2024-10-07 20:14:09', 'customer'),
(56, 16, 'hello', '2024-10-07 20:14:20', 'customer'),
(57, 16, 'hello', '2024-10-07 20:15:31', 'customer'),
(58, 16, 'hello', '2024-10-07 20:15:44', 'customer'),
(59, 16, 'hello', '2024-10-07 20:15:59', 'customer'),
(60, 16, 'hello', '2024-10-07 20:17:37', 'customer'),
(61, 16, 'hello', '2024-10-07 20:17:50', 'customer'),
(62, 16, 'hello', '2024-10-07 20:19:16', 'customer'),
(63, 16, 'hello', '2024-10-07 20:19:32', 'customer'),
(64, 16, 'hello', '2024-10-07 20:22:06', 'customer'),
(65, 16, 'hello', '2024-10-07 20:22:18', 'customer'),
(66, 16, 'hello', '2024-10-07 20:22:57', 'customer'),
(67, 16, 'hello', '2024-10-07 20:43:25', 'customer'),
(68, 18, 'HELLO', '2024-10-07 21:27:55', 'admin'),
(69, 16, 'hello', '2024-10-07 21:29:06', 'customer'),
(70, 19, 'HELLO', '2024-10-07 21:29:13', 'admin'),
(71, 16, 'hello', '2024-10-07 21:29:17', 'customer'),
(72, 19, 'HELLO', '2024-10-07 21:29:22', 'admin'),
(73, 18, 'HELLO', '2024-10-07 21:29:32', 'admin'),
(74, 18, 'HELLO', '2024-10-07 21:29:40', 'admin'),
(75, 18, 'HELLO', '2024-10-07 21:29:48', 'admin'),
(76, 19, 'HELLO', '2024-10-07 21:30:12', 'admin'),
(77, 0, 'HELLO', '2024-10-07 21:30:24', 'admin'),
(78, 0, 'HELLO', '2024-10-07 21:30:30', 'admin'),
(79, 16, 'hello', '2024-10-07 21:30:45', 'customer'),
(80, 0, 'HELLO', '2024-10-07 21:30:48', 'admin'),
(81, 16, 'hello', '2024-10-07 21:32:10', 'customer'),
(82, 16, 'hello', '2024-10-10 17:52:36', 'customer'),
(83, 16, 'hello', '2024-10-10 17:54:48', 'customer'),
(84, 16, 'hello', '2024-10-10 17:56:44', 'customer'),
(85, 16, 'hello', '2024-10-10 18:04:26', 'customer'),
(86, 16, 'hello', '2024-10-10 18:30:13', 'customer'),
(87, 16, 'hello', '2024-10-10 18:33:03', 'customer'),
(88, 16, 'hello', '2024-10-10 18:46:53', 'customer'),
(89, 16, 'hello', '2024-10-10 18:53:55', 'customer'),
(90, 16, 'hello', '2024-10-10 19:00:23', 'customer'),
(91, 16, 'hello', '2024-10-10 19:01:01', 'customer'),
(92, 16, 'hello', '2024-10-10 19:02:13', 'customer'),
(93, 16, 'hello', '2024-10-10 19:02:41', 'customer'),
(94, 16, 'hello', '2024-10-10 19:06:06', 'customer'),
(95, 16, 'hello', '2024-10-10 19:10:57', 'customer'),
(96, 16, 'hello', '2024-10-10 20:32:48', 'customer'),
(97, 16, 'hello', '2024-10-10 20:35:17', 'customer'),
(98, 16, 'hello', '2024-10-10 20:38:42', 'customer'),
(99, 16, 'hello', '2024-10-13 07:01:09', 'customer'),
(100, 16, 'hello', '2024-10-13 07:01:28', 'customer'),
(101, 16, 'hello', '2024-10-13 07:02:50', 'customer'),
(102, 0, 'HELLO', '2024-10-13 07:03:11', 'admin'),
(103, 16, 'hello', '2024-10-13 07:03:19', 'customer'),
(104, 0, 'HELLO', '2024-10-13 07:03:30', 'admin'),
(105, 0, 'HELLO', '2024-10-13 07:14:30', 'admin'),
(106, 0, 'HELLO', '2024-10-13 07:16:25', 'admin'),
(107, 16, 'hello', '2024-10-13 07:17:44', 'customer'),
(108, 16, 'hello', '2024-10-13 07:26:49', 'customer'),
(109, 16, 'hello', '2024-10-13 07:27:32', 'customer'),
(110, 16, 'hello', '2024-10-13 07:51:31', 'customer'),
(111, 16, 'hello', '2024-10-13 07:53:30', 'customer'),
(112, 16, 'hello', '2024-10-13 07:55:05', 'customer'),
(113, 16, 'hello', '2024-10-13 07:55:26', 'customer'),
(114, 16, 'hello', '2024-10-13 07:56:12', 'customer'),
(115, 16, 'hello', '2024-10-13 16:07:22', 'customer'),
(116, 16, 'hello', '2024-10-13 18:33:22', 'customer'),
(117, 16, 'hello', '2024-10-13 18:35:57', 'customer'),
(118, 1, 'HELLO', '2024-10-13 20:10:31', 'admin'),
(119, 2, 'HELLO', '2024-10-13 20:10:35', 'admin'),
(120, 2, 'HELLO', '2024-10-13 20:10:38', 'admin'),
(121, 1, 'HELLO', '2024-10-13 20:10:49', 'admin'),
(122, 1, 'HELLO', '2024-10-13 20:10:52', 'admin'),
(123, 2, 'HELLO', '2024-10-13 20:10:57', 'admin'),
(124, 1, 'HELLO', '2024-10-13 20:11:02', 'admin'),
(125, 1, 'HELLO', '2024-10-13 20:12:31', 'admin'),
(126, 1, 'HELLO', '2024-10-13 20:13:22', 'admin'),
(127, 1, 'HELLO', '2024-10-13 20:13:25', 'admin'),
(128, 1, 'HELLO', '2024-10-13 20:13:27', 'admin'),
(129, 2, 'HELLO', '2024-10-13 20:13:31', 'admin'),
(130, 2, 'HELLO', '2024-10-13 20:13:34', 'admin'),
(131, 2, 'HELLO', '2024-10-13 20:13:39', 'admin'),
(132, 1, 'HELLO', '2024-10-13 20:15:43', 'admin'),
(133, 1, 'HELLO', '2024-10-13 20:15:49', 'admin'),
(134, 1, 'HELLO', '2024-10-13 20:15:54', 'admin'),
(135, 1, 'HELLO', '2024-10-13 20:24:05', 'admin'),
(136, 1, 'HELLO', '2024-10-13 20:24:07', 'admin'),
(137, 1, 'HELLO', '2024-10-13 20:24:09', 'admin'),
(138, 1, 'HELLO', '2024-10-13 20:25:26', 'admin'),
(139, 1, 'HELLO', '2024-10-13 20:25:33', 'admin'),
(140, 1, 'HELLO', '2024-10-13 20:25:36', 'admin'),
(141, 16, 'hello', '2024-10-13 20:25:50', 'customer'),
(142, 1, 'HELLO', '2024-10-13 20:26:02', 'admin'),
(143, 16, 'hello', '2024-10-13 20:26:11', 'customer'),
(144, 2, 'HELLO', '2024-10-13 20:26:18', 'admin'),
(145, 16, 'hello', '2024-10-13 20:26:24', 'customer'),
(146, 1, 'HELLO', '2024-10-13 20:41:34', 'admin'),
(147, 1, 'HELLO', '2024-10-13 20:41:36', 'admin'),
(148, 1, 'HELLO', '2024-10-13 20:41:52', 'admin'),
(149, 2, 'HELLO', '2024-10-13 20:41:55', 'admin'),
(150, 2, 'HELLO', '2024-10-13 20:50:08', 'admin'),
(151, 2, 'HELLO', '2024-10-13 20:50:11', 'admin'),
(152, 2, 'HELLO', '2024-10-13 20:50:13', 'admin'),
(153, 30, 'hello', '2024-10-13 22:53:41', 'admin'),
(154, 30, 'hello', '2024-10-13 23:10:41', 'admin'),
(155, 30, 'hello', '2024-10-13 23:17:21', 'admin'),
(156, 30, 'hello', '2024-10-13 23:18:26', 'admin'),
(157, 23, 'hello', '2024-10-13 23:21:15', 'customer'),
(158, 30, 'hello', '2024-10-13 23:22:06', 'admin'),
(159, 30, 'hello', '2024-10-13 23:22:59', 'admin'),
(160, 16, 'hello', '2024-10-13 23:23:55', 'customer'),
(161, 16, 'hello', '2024-10-13 23:24:13', 'customer'),
(162, 16, 'hello', '2024-10-13 23:24:33', 'customer'),
(163, 16, 'hello', '2024-10-13 23:24:57', 'customer'),
(164, 16, 'hello', '2024-10-13 23:25:28', 'customer'),
(165, 16, 'hello', '2024-10-14 17:52:30', 'customer'),
(166, 16, 'hello', '2024-10-14 17:54:35', 'customer'),
(167, 16, 'hello', '2024-10-14 18:15:21', 'customer'),
(168, 16, 'hello', '2024-10-14 18:17:19', 'customer'),
(169, 16, 'hello', '2024-10-14 18:21:46', 'customer'),
(170, 16, 'hello', '2024-10-14 18:22:29', 'customer'),
(171, 2, 'hello', '2024-10-15 03:36:23', 'admin'),
(172, 1, 'hello', '2024-10-15 03:36:36', 'admin'),
(173, 30, 'hello', '2024-10-15 06:14:05', 'admin'),
(174, 30, 'hello', '2024-10-15 06:14:41', 'admin'),
(175, 23, 'hello', '2024-10-15 06:15:05', 'customer'),
(176, 1, 'HELLO', '2024-10-15 08:13:07', 'admin'),
(177, 16, 'hello', '2024-10-15 14:16:00', 'customer'),
(178, 16, 'hello', '2024-10-15 14:16:14', 'customer'),
(179, 16, 'hello', '2024-10-15 14:40:13', 'customer'),
(180, 1, 'hello', '2024-10-15 15:35:27', 'admin'),
(181, 22, 'hello', '2024-10-15 17:27:29', 'admin'),
(182, 23, 'hello', '2024-10-15 17:35:44', 'admin'),
(183, 30, 'hello', '2024-10-15 18:24:08', 'admin'),
(184, 30, 'hello', '2024-10-15 18:24:16', 'admin'),
(185, 30, 'hello', '2024-10-15 18:47:57', 'admin'),
(186, 30, 'hello', '2024-10-15 18:48:14', 'admin'),
(187, 30, 'hello', '2024-10-15 18:49:03', 'admin'),
(188, 30, 'hello', '2024-10-15 19:00:52', 'admin'),
(189, 30, 'hello', '2024-10-15 19:31:13', 'admin'),
(190, 30, 'hello', '2024-10-16 07:57:08', 'admin'),
(191, 30, 'hello', '2024-10-16 07:57:25', 'admin'),
(192, 30, 'hello', '2024-10-16 07:58:00', 'admin'),
(193, 30, 'hello', '2024-10-16 07:59:49', 'admin'),
(194, 30, 'hello', '2024-10-16 07:59:56', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `chatsecurity`
--

CREATE TABLE `chatsecurity` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `user_token` varchar(100) NOT NULL,
  `role` int(11) DEFAULT NULL COMMENT '0 = customer \r\n1 = staff',
  `status` int(11) DEFAULT NULL COMMENT '0 - PENDING \r\n1- IN LIVE \r\n2-COMPLETE',
  `created_at` datetime DEFAULT current_timestamp(),
  `user_connection_id` int(5) NOT NULL,
  `link` varchar(100) NOT NULL,
  `staffid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatsecurity`
--

INSERT INTO `chatsecurity` (`id`, `userid`, `user_token`, `role`, `status`, `created_at`, `user_connection_id`, `link`, `staffid`) VALUES
(22, 16, '16_20241017082741_1018', 0, 0, '2024-10-17 14:27:41', 143, 'ws://localhost:8080?token=16_20241017082741_1018', 1),
(23, 23, '23_20241015081457_7761', 0, 0, '2024-10-15 14:15:02', 0, 'ws://localhost:8080?token=23_20241015081457_7761', 1),
(24, 30, '30_20241017111127_6304', 1, 0, '2024-10-17 17:11:27', 188, 'ws://localhost:8080?token=30_20241017111127_6304', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chat_message`
--

CREATE TABLE `chat_message` (
  `message_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `chat_message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('sent','delivered','seen') DEFAULT 'sent',
  `sender_type` int(11) NOT NULL COMMENT '0-admin\r\n1-customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_message`
--

INSERT INTO `chat_message` (`message_id`, `from_user_id`, `to_user_id`, `chat_message`, `timestamp`, `status`, `sender_type`) VALUES
(1, 30, 16, 'Hello, how can I help you today?', '2024-10-13 19:59:26', 'sent', 0),
(2, 16, 30, 'I have a question about my order.', '2024-10-13 19:59:26', '', 1),
(3, 30, 16, 'Can you provide me with your order number?', '2024-10-13 19:59:26', 'sent', 0),
(4, 16, 30, 'My order number is #12345.', '2024-10-13 19:59:26', '', 1),
(5, 30, 16, 'Thank you for the information. I will check it now.', '2024-10-13 19:59:26', 'sent', 0),
(6, 16, 30, 'Great! Let me know if you need any more details.', '2024-10-13 19:59:26', '', 1),
(7, 30, 22, 'hello', '2024-10-15 12:47:57', 'sent', 0),
(8, 30, 22, 'hello', '2024-10-15 12:48:14', 'sent', 0),
(9, 30, 22, 'hello', '2024-10-15 12:49:03', 'sent', 0),
(10, 30, 16, 'hello', '2024-10-15 13:00:52', 'sent', 0),
(11, 30, 16, 'hello', '2024-10-15 13:31:13', 'sent', 0),
(12, 30, 16, 'hello', '2024-10-16 01:57:08', 'sent', 0),
(13, 30, 16, 'hello', '2024-10-16 01:57:25', 'sent', 0),
(14, 30, 16, 'hello', '2024-10-16 01:58:00', 'sent', 0),
(15, 30, 16, 'hello', '2024-10-16 01:59:49', 'sent', 0),
(16, 30, 16, 'hello', '2024-10-16 01:59:56', 'sent', 0),
(17, 30, 16, 'hello', '2024-10-16 03:29:52', 'sent', 0),
(18, 30, 16, 'hello', '2024-10-16 03:31:39', 'sent', 0),
(19, 30, 16, 'hello', '2024-10-16 03:39:30', 'sent', 0),
(20, 30, 16, 'hello', '2024-10-16 03:40:42', 'sent', 0),
(21, 30, 16, 'hello', '2024-10-16 01:41:18', 'sent', 0),
(22, 30, 16, 'hello', '2024-10-16 03:41:18', 'sent', 0),
(23, 30, 16, 'apa khabar', '2024-10-16 01:41:28', 'sent', 0),
(24, 30, 16, 'apa khabar', '2024-10-16 03:41:28', 'sent', 0),
(25, 30, 0, 'hello', '2024-10-16 01:54:55', 'sent', 0),
(26, 30, 16, 'hello', '2024-10-16 03:55:41', 'sent', 0),
(27, 30, 16, 'hello', '2024-10-16 01:55:41', 'sent', 0),
(28, 30, 0, 'hello', '2024-10-16 01:59:31', 'sent', 0),
(29, 30, 16, 'hello', '2024-10-16 02:00:41', 'sent', 0),
(30, 30, 16, 'hello', '2024-10-16 04:00:41', 'sent', 0),
(31, 30, 16, 'helo', '2024-10-16 04:01:18', 'sent', 0),
(32, 30, 16, 'helo', '2024-10-16 02:01:18', 'sent', 0),
(33, 30, 16, 'user_connection_id', '2024-10-16 04:02:50', 'sent', 0),
(34, 30, 16, 'user_connection_id', '2024-10-16 02:02:50', 'sent', 0),
(35, 30, 16, 'hello', '2024-10-16 02:24:08', 'sent', 0),
(36, 30, 16, 'hello', '2024-10-16 04:24:08', 'sent', 0),
(37, 30, 16, 'hello', '2024-10-16 02:24:31', 'sent', 0),
(38, 30, 16, 'hello', '2024-10-16 04:24:31', 'sent', 0),
(39, 30, 16, 'hello', '2024-10-16 02:32:46', 'sent', 0),
(40, 30, 16, 'hello', '2024-10-16 04:32:46', 'sent', 0),
(41, 30, 16, 'hello', '2024-10-16 02:32:59', 'sent', 0),
(42, 30, 16, 'hello', '2024-10-16 04:32:59', 'sent', 0),
(43, 30, 16, 'hello', '2024-10-16 02:33:19', 'sent', 0),
(44, 30, 16, 'hello', '2024-10-16 04:33:19', 'sent', 0),
(45, 30, 16, 'eh', '2024-10-16 02:33:31', 'sent', 0),
(46, 30, 16, 'eh', '2024-10-16 04:33:31', 'sent', 0),
(47, 30, 16, 'eh', '2024-10-16 02:33:49', 'sent', 0),
(48, 30, 16, 'eh', '2024-10-16 04:33:49', 'sent', 0),
(49, 0, 0, 'hello', '2024-10-16 09:29:09', 'sent', 1),
(50, 0, 0, 'hello', '2024-10-16 09:29:19', 'sent', 1),
(51, 0, 30, 'hello', '2024-10-16 09:35:09', 'sent', 0);

-- --------------------------------------------------------

--
-- Table structure for table `chat_user_table`
--

CREATE TABLE `chat_user_table` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `user_email` varchar(250) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_profile` varchar(100) NOT NULL,
  `user_status` enum('Disabled','Enable') NOT NULL,
  `user_created_on` datetime NOT NULL,
  `user_verification_code` varchar(100) NOT NULL,
  `user_login_status` enum('Logout','Login') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forgot_password`
--

CREATE TABLE `forgot_password` (
  `fp_id` int(11) NOT NULL,
  `fp_email` varchar(50) NOT NULL,
  `fp_status` int(11) NOT NULL,
  `fp_requestTime` datetime NOT NULL,
  `fp_submitTime` datetime NOT NULL,
  `fp_url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forgot_password`
--

INSERT INTO `forgot_password` (`fp_id`, `fp_email`, `fp_status`, `fp_requestTime`, `fp_submitTime`, `fp_url`) VALUES
(504, 'aimanhakimka@gmail.com', 1, '2024-07-02 03:15:52', '0000-00-00 00:00:00', 'c788123fb30100446ba312d2d35217371611c6f79792578a0cab83888609d0ee'),
(505, 'aimanhakimka@gmail.com', 1, '2024-07-02 11:59:12', '0000-00-00 00:00:00', 'a16847e3a6f269f280bf7a296f951653e82e09166be8ba67631a1b9f3c655bcf');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `mobile` text NOT NULL,
  `email` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = confirm \r\n0- verification \r\n2- cancel\r\n3- ready delivery\r\n4 = in delivery\r\n5-complete',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `orderrequest` text NOT NULL,
  `payment_method` text NOT NULL,
  `collect_method` text NOT NULL,
  `delivery_fee` float(100,2) NOT NULL,
  `total_fee` float(100,2) NOT NULL,
  `collect_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `address`, `mobile`, `email`, `status`, `date`, `orderrequest`, `payment_method`, `collect_method`, `delivery_fee`, `total_fee`, `collect_time`) VALUES
(200, 'Jian Hau,Tan', '2.2493224,102.27769044785956', '6011266935', 'aimanhakimka@gmail.com', 4, '2024-07-03 00:19:12', '', 'cash', 'delivery', 3.00, 51.00, '3/7/2024 18:00'),
(201, 'Jian Hau,Tan', '2.211194884155262,102.25236063863798', '6011266935', 'aimanhakimka@gmail.com', 2, '2024-07-03 01:40:03', '', 'cash', 'selfCollect', 0.00, 4.56, '3/7/2024 18:00'),
(202, 'Jian Hau,Tan', '2.2493224,102.27769044785956', '6011266935', 'aimanhakimka@gmail.com', 5, '2024-07-03 00:08:35', '', 'cash', 'delivery', 3.00, 8.90, '3/7/2024 17:00'),
(203, 'Jian Hau,Tan', '2.2493224,102.27769044785956', '6011266935', 'aimanhakimka@gmail.com', 4, '2024-07-03 00:08:43', '', 'cash', 'delivery', 3.00, 14.10, '3/7/2024 17:00'),
(204, 'Jian Hau,Tan', '2.2493224,102.27769044785956', '6011266935', 'aimanhakimka@gmail.com', 4, '2024-07-03 01:56:47', '', 'cash', 'delivery', 3.00, 33.20, '3/7/2024 20:00'),
(205, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 01:18:48', '', 'paypal', 'E-Wallet', 0.00, 20.00, ' '),
(206, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-03 00:08:53', '', 'paypal', 'E-Wallet', 0.00, 50.00, '3/7/2024 17:00'),
(207, 'aimanhakimka 22', '2.2493224,102.27769044785956', '6011266935', 'aimanhakimka@gmail.com', 5, '2024-07-03 00:09:23', 'Need kuah', 'ewallet', 'delivery', 3.00, 27.70, '2024-07-03 03:30:46'),
(208, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 03:50:26', '', 'paypal', 'E-Wallet', 0.00, 20.00, ' '),
(209, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 03:55:58', '', 'paypal', 'E-Wallet', 0.00, 20.00, ' '),
(210, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 04:44:39', '', 'paypal', 'E-Wallet', 0.00, 20.00, ' '),
(211, 'aimanhakimka ', '2.2493224,102.27769044785956', '6011266935', 'aimanhakimka@gmail.com', 5, '2024-07-03 01:55:46', 'No need kuah', 'ewallet', 'delivery', 3.00, 16.30, '2024-07-03 11:56:05'),
(212, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 14:13:14', '', 'paypal', 'E-Wallet', 0.00, 20.00, ' '),
(213, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 15:24:20', '', 'paypal', 'E-Wallet', 0.00, 20.00, ' '),
(214, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 15:36:31', '', 'paypal', 'E-Wallet', 0.00, 20.00, ' '),
(215, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 15:41:22', '', 'paypal', 'E-Wallet', 0.00, 10.00, ' '),
(216, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-02 15:55:24', '', 'paypal', 'E-Wallet', 0.00, 10.00, ' '),
(217, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-03 00:09:36', '', 'paypal', 'E-Wallet', 0.00, 20.00, '3/7/2024 18:00'),
(218, 'aimanhakimka,', 'digital', '', 'aimanhakimka@gmail.com', 0, '2024-07-03 01:16:06', '', 'paypal', 'E-Wallet', 0.00, 20.00, ' '),
(219, 'AIMAN,HAKIM', 'digital', '', 'aimanhakimka04@gmail.com', 0, '2024-07-03 01:26:20', '', 'paypal', 'E-Wallet', 0.00, 13.00, '3/7/2024 17:00'),
(220, 'AIMAN HAKIM', '2.2493224,102.27769044785956', '6017501931', 'aimanhakimka04@gmail.com', 5, '2024-07-03 01:38:33', 'Need kuah', 'ewallet', 'delivery', 3.00, 15.30, '2024-07-03 03:27:02'),
(221, 'AIMAN,HAKIM', 'digital', '6017501931', 'aimanhakimka04@gmail.com', 0, '2024-07-03 01:53:42', '', 'paypal', 'E-Wallet', 3.00, 13.00, '3/7/2024 17:00'),
(222, 'aimanhakimka,', '2.2493224,102.27769044785956', '6011266935', 'aimanhakimka@gmail.com', 5, '2024-07-03 01:57:22', '', 'cash', 'delivery', 3.00, 6.60, '3/7/2024 17:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`id`, `order_id`, `product_id`, `qty`) VALUES
(203, 200, 34, 6),
(204, 200, 26, 5),
(205, 200, 36, 2),
(206, 200, 27, 1),
(207, 200, 37, 1),
(208, 200, 55, 4),
(209, 200, 56, 3),
(210, 200, 57, 3),
(211, 200, 58, 3),
(212, 200, 25, 5),
(213, 201, 32, 1),
(214, 202, 36, 1),
(215, 202, 33, 1),
(216, 203, 36, 1),
(217, 203, 31, 1),
(218, 203, 40, 1),
(219, 203, 34, 1),
(220, 204, 41, 1),
(221, 204, 55, 4),
(222, 204, 56, 4),
(223, 204, 57, 5),
(224, 204, 58, 6),
(225, 204, 25, 13),
(226, 207, 40, 1),
(227, 207, 55, 1),
(228, 207, 56, 1),
(229, 207, 57, 1),
(230, 207, 58, 1),
(231, 207, 26, 9),
(232, 207, 29, 1),
(233, 211, 36, 1),
(234, 211, 55, 1),
(235, 211, 56, 1),
(236, 211, 57, 2),
(237, 211, 58, 1),
(238, 211, 25, 5),
(239, 220, 36, 1),
(240, 220, 25, 7),
(241, 222, 27, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `payer_id` varchar(255) NOT NULL,
  `payer_email` varchar(255) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_id`, `payer_id`, `payer_email`, `amount`, `currency`, `payment_status`) VALUES
(196, 'PAYID-M2BVKXA0AC48358ER1525419', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(197, 'PAYID-M2BVPYA37A58975ET5076829', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 50.00, 'MYR', 'approved'),
(198, 'PAYID-M2BXR4A46241861K0610472Y', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(199, 'PAYID-M2BXURQ0X491136LH465805N', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(200, 'PAYID-M2BYLJA41208818K8246812C', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(201, 'PAYID-M2CAV3I8K811180D7135914H', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(202, 'PAYID-M2CAV3I8K811180D7135914H', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(203, 'PAYID-M2CB42A1T342163P96676131', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(204, 'PAYID-M2CB7GQ4FV49204UE3468909', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 10.00, 'MYR', 'approved'),
(205, 'PAYID-M2CCFYQ85U14601FX035300D', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 10.00, 'MYR', 'approved'),
(206, 'PAYID-M2CJCKA3V020466RX5009424', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(207, 'PAYID-M2CKMNA2M994457FL1605143', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 20.00, 'MYR', 'approved'),
(208, 'PAYID-M2CKRMQ9T2473528A651804F', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 13.00, 'MYR', 'approved'),
(209, 'PAYID-M2CK6GA4LN46637PR9268406', 'JFTSCAZL28NPJ', 'aimanhakimka@gmail.com', 3.42, 'MYR', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL,
  `img_path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0= unavailable, 2 Available',
  `total_sold` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `category_id`, `name`, `description`, `price`, `quantity`, `img_path`, `status`, `total_sold`) VALUES
(26, 13, 'Sate Daging', 'Sate Daging + Timun + Bawang + Kuah Kacang', 1, 200, '1715350440_photo1715350416.jpeg', 1, 2),
(27, 14, 'Nasi Lemak Kukus Biasa', 'Nasi Lemak + Telur mata', 3, 50, '1715351580_nasi lemak.jpg', 1, 3),
(28, 14, 'Nasi Lemak Kukus Ayam', 'Nasi Lemak + Ayam Goreng', 5, 50, '1715352000_NLA.jpg', 1, 4),
(29, 14, 'Nasi Lemak Kukus Special', 'Nasi Lemak + Ayam Goreng + Telur Mata', 6, 50, '1715353320_nasi-lemak.webp', 1, 5),
(30, 15, 'Bihun Sup', 'Bihun Sup + Sambal Kicap', 4, 50, '1715353740_bihun-sup.jpg', 1, 6),
(31, 15, 'Mee Sup', 'Mee Sup + Sambal Kicap', 4, 50, '1715355420_mee sup.jpg', 1, 7),
(32, 15, 'Kuetiau', 'Kuetiau + Sambal Kicap', 4, 50, '1715355780_kuetiaw.jpg', 1, 8),
(33, 15, 'Soto Sup', 'Soto Sup + Sambal Kicap', 4, 50, '1715355960_soto.jpg', 1, 9),
(34, 16, 'Bubur Som Som', 'Diperbuat dari tepung beras dan disaji bersama gula merah.', 3, 50, '1715356380_bubur', 1, 10),
(35, 16, 'Kole Kacang', 'Diperbuat dari kacang hijau dan gula merah.', 3, 50, '1715357040_kole kacang.jpg', 1, NULL),
(36, 17, 'Kopi O Panas', '', 1, 30, '1715358480_photo1715358211.jpeg', 1, NULL),
(37, 17, 'Kopi O Sejuk', '', 1.5, 30, '1715399340_photo1715359233.jpeg', 1, NULL),
(39, 17, 'Teh O Panas', '', 1, 30, '1715358780_photo1715358168.jpeg', 1, NULL),
(40, 17, 'Teh O Sejuk', '', 1.5, 30, '1715399400_photo1715359233 (1).jpeg', 1, NULL),
(41, 17, 'Kopi Panas', '', 1.5, 30, '1715359080_photo1715358168 (1).jpeg', 1, NULL),
(42, 17, 'Kopi Sejuk', '', 2, 30, '1715399580_photo1715359233 (4).jpeg', 1, NULL),
(43, 17, 'Teh Panas', '', 1.5, 30, '1715359260_photo1715358168 (2).jpeg', 1, NULL),
(44, 17, 'Teh Sejuk', '', 2, 30, '1715399520_photo1715359233 (3).jpeg', 1, NULL),
(45, 17, 'Milo O Panas', '', 1.5, 30, '1715359440_photo1715358706.jpeg', 1, NULL),
(46, 17, 'Milo O Sejuk', '', 2, 30, '1715399580_photo1715359233 (5).jpeg', 1, NULL),
(47, 17, 'Milo Panas', '', 2, 30, '1715399640_photo1715358707.jpeg', 1, NULL),
(48, 17, 'Milo Sejuk', '', 2.5, 30, '1715399700_photo1715359233 (6).jpeg', 1, NULL),
(49, 17, 'Nescafe O Panas', '', 1.5, 30, '1715399760_photo1715358707 (1).jpeg', 1, NULL),
(50, 17, 'Nescafe O Sejuk', '', 2, 30, '1715399820_photo1715359233 (7).jpeg', 1, NULL),
(51, 17, 'Nescafe Panas', '', 2, 30, '1715399940_photo1715358707 (2).jpeg', 1, NULL),
(52, 17, 'Nescafe Sejuk', '', 2.5, 30, '1715400000_photo1715359233 (8).jpeg', 1, NULL),
(53, 17, 'Laichikang', '', 5, 30, '1715400000_photo1715398418.jpeg', 1, NULL),
(54, 17, 'Bandung Cincau', '', 3, 30, '1715400060_photo1715359234.jpeg', 1, NULL),
(55, 18, 'Timun', '', 0.4, 100, '1715400360_6bc763c3-manfaat-timun.webp', 1, NULL),
(56, 18, 'Bawang', '', 0.4, 100, '1715400420_xggiq43yr6y7bqiochq9.jpg', 1, NULL),
(57, 18, 'Nasi Impit', '', 0.5, 100, '1715400540_images.jpg', 1, NULL),
(58, 18, 'Kuah Kacang', '', 1, 100, '1715400600_images (1).jpg', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rate_id` int(11) NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `user_name` text NOT NULL,
  `order_id` varchar(30) NOT NULL,
  `rate` int(11) NOT NULL,
  `datentime` timestamp NULL DEFAULT current_timestamp(),
  `description` text NOT NULL,
  `img_comment` varchar(3000) NOT NULL,
  `status_comment` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=hide,2=show'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rate_id`, `user_id`, `user_name`, `order_id`, `rate`, `datentime`, `description`, `img_comment`, `status_comment`) VALUES
(15, '16', 'aimanhakimka ', '207', 4, '2024-07-02 02:06:02', 'sangat sedap tapi lambat', 'order1.jpeg', 2),
(16, '16', 'aimanhakimka ', '211', 4, '2024-07-02 10:10:44', 'good', 'order2.jpeg', 2),
(17, '21', 'AIMAN HAKIM', '220', 5, '2024-07-03 01:38:53', 'Good', 'order2.jpeg', 2),
(18, '16', 'aimanhakimka ', '222', 1, '2024-07-03 01:57:39', 'lambat dan tak sedap', 'order4.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `rider`
--

CREATE TABLE `rider` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `status_delivery` int(11) NOT NULL COMMENT '0 - ! delivery\r\n1 - delivery \r\n\r\n2 - complete',
  `last_location` varchar(1000) DEFAULT NULL,
  `complete_proof` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rider`
--

INSERT INTO `rider` (`id`, `staff_id`, `order_id`, `rate_id`, `status_delivery`, `last_location`, `complete_proof`) VALUES
(40, 32, 202, 0, 2, '2.932736,101.6496128', 1),
(41, 29, 203, 0, 2, '2.2493634,102.2770455', 0),
(42, 24, 204, 0, 2, '2.2146371269233156,102.25400726327115', 1),
(43, 24, 200, 0, 2, '2.2494927540668206,102.27698728516786', 0),
(44, 24, 207, 0, 2, '2.2509050151978895,102.27727930487363', 2),
(45, 24, 211, 0, 2, '2.2151155926318276,102.25492686811914', 1),
(46, 24, 220, 0, 2, '2.2271278343593215,102.26316061752574', 1),
(47, 24, 222, 0, 2, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL,
  `Billboard1` text NOT NULL,
  `Billboard2` text NOT NULL,
  `Billboard3` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`, `Billboard1`, `Billboard2`, `Billboard3`) VALUES
(1, 'Warung Satay Pak Malau ', 'pakmalau@gmail.com', '60148183793', '1717150080_Satay pak malau.png', '&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;span style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;b style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;i style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;Warung Satay Pak Malau is a well-known satay (grilled skewered meat) stall located in Melaka, Malaysia. It&rsquo;s famous for its delicious satay, particularly chicken and beef satay, served with peanut sauce and rice cakes (ketupat). Many locals and tourists alike visit Gerai Sate Pak Malau to enjoy its flavorful and tender skewered meats.&amp;nbsp;&lt;br style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;i love satay&lt;/i&gt;&lt;/b&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; text-align: justify;&quot;&gt;&lt;b&gt;&lt;i&gt;&lt;br&gt;&lt;/i&gt;&lt;/b&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;/p&gt;&lt;h2 style=&quot;font-size:28px;background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/h2&gt;&lt;p&gt;&lt;/p&gt;', '1710923460_MOst Wanted.gif', '1710995160_20off.gif', '1710855720_Delivery satay To your home.gif');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `item_number` varchar(50) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_price` float(10,2) DEFAULT NULL,
  `item_price_currency` varchar(10) DEFAULT NULL,
  `payer_id` varchar(50) DEFAULT NULL,
  `payer_name` varchar(50) DEFAULT NULL,
  `payer_email` varchar(50) DEFAULT NULL,
  `payer_country` varchar(20) DEFAULT NULL,
  `merchant_id` varchar(255) DEFAULT NULL,
  `merchant_email` varchar(50) DEFAULT NULL,
  `order_id` varchar(50) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `paid_amount` float(10,2) NOT NULL,
  `paid_amount_currency` varchar(10) NOT NULL,
  `payment_source` varchar(50) DEFAULT NULL,
  `payment_status` varchar(25) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `username` text NOT NULL,
  `password` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=admin , 2 = staff',
  `phone_no` int(11) NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=male , 2 = female',
  `email` varchar(100) NOT NULL,
  `crider` tinyint(1) NOT NULL COMMENT '1= can rider\r\n2= cant rider'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`, `phone_no`, `gender`, `email`, `crider`) VALUES
(24, 'aiman hakim', 'aimanhakimka', '$2y$10$cbfCHklLmJbN.jhDL1vvCeCndFxQKr6R58oSK.OxoXLX9IZlFNjyq', 2, 175019310, 1, 'aimanhakimka@gmail.com', 0),
(29, 'jianhau', 'jianhau', '$2y$10$CM.lcakpiVv0QuyI.jdXK.cGrsZnEO4Xtx.Y0/uM5ZI7WyIHR/NzW', 2, 2147483647, 1, 'hhh41626@gmail.com', 0),
(30, 'Admin', 'admin', '$2y$10$dhxcDr5ogmrT2x3j1QhgAuWhWfZ7S0PqvIE2cOQdA.8.dFNyw5ujO', 1, 2147483647, 1, 'admin@gmail.com', 0),
(32, 'Staff', 'Staff', '$2y$10$WvYeLTuiwumCzToSTxa6UeKMQ40pe7.HNXuxftRku8eL.iATdyqta', 2, 2147483647, 1, 'staff@gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `address` varchar(300) NOT NULL,
  `oauth_id` varchar(255) NOT NULL,
  `last_login` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `walletbalance` double(10,2) DEFAULT 0.00,
  `img_user` text NOT NULL,
  `discount_point` double(10,2) NOT NULL DEFAULT 0.00,
  `hide_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`user_id`, `first_name`, `last_name`, `email`, `password`, `mobile`, `address`, `oauth_id`, `last_login`, `created_at`, `walletbalance`, `img_user`, `discount_point`, `hide_status`) VALUES
(16, 'aimanhakimka', '', 'aimanhakimka@gmail.com', '$2y$10$c1eQpsGJ1qXaoJMlgVCMy.q6wzLj8JZXlDU//n2Dal8NTNHaOANra', '6011266935', 'MMU', '104179142730606696752', '2024-10-17 01:35:35', '2024-07-01 04:26:03', 24.56, '', 20.00, 0),
(17, 'Jian Hau', 'Tan', 'hhh41626@gmail.com', '$2y$10$MH1HO3SpiO9Q82lAqb5mmueGM6w5Q3Rbuhp.xBmobKny6DNpxlBoO', '6011266935', 'Duyong', '', '0000-00-00 00:00:00', '2024-07-01 05:21:40', 0.00, '', 0.00, 0),
(18, 'Jian Hau', 'Tan', 'qioip27@gmail.com', '$2y$10$dTCVH6eo6iFuQja1sX5FpORROIDWEIvBFE6yft5W98JTH5QkqmbNS', '6011266935', 'Taman Desa Duyong', '', '0000-00-00 00:00:00', '2024-07-01 06:05:15', 0.00, '', 0.00, 0),
(20, 'aiman', 'hakim', 'aimanhakimka0456@gmail.com', '$2y$10$BXzL8eoQotbWTNFvbjbPue0s9BBZ9OXRw9whC7gV2usXf5r4A7RPa', '6012000151', 'MMU Melaka', '', '0000-00-00 00:00:00', '2024-07-02 04:39:00', 0.00, '', 0.00, 0),
(21, 'AIMAN', 'HAKIM', 'aimanhakimka04@gmail.com', '$2y$10$lpQyM3ala463xIy3swAldOeqEholqls4zU.0xQDoMfuXta.gg7ISi', '6017501931', 'MMU MELAKA', '', '0000-00-00 00:00:00', '2024-07-03 01:13:24', 13.70, '', 14.00, 0),
(22, 'aimandemochat', 'aimandemochat', 'aimandemochat@gmail.com', '$2y$10$3KmnPV2uHpr07O.ab/F6qu4sMrreHEvmKIG1pgbDHwV1diRG0jy9W', '6017501931', 'none', '', '0000-00-00 00:00:00', '2024-08-04 18:11:41', 0.00, '', 0.00, 0),
(23, 'Wolf&Rabbit', '', 'wolfrabbit0405@gmail.com', '', '', '', '105061357754965703472', '2024-10-15 14:14:57', '2024-09-22 13:29:44', 0.00, '', 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `v_id` int(11) NOT NULL,
  `v_code` text DEFAULT NULL,
  `v_point` double(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`v_id`, `v_code`, `v_point`) VALUES
(15, '2725500716', 1.00),
(16, '8812390861', 1.00),
(17, '3586242096', 1.00),
(18, '5409134689', 1.00),
(19, '3579132595', 1.00),
(20, '4159470798', 1.00),
(21, '4240068101', 1.00),
(22, '7151082083', 1.00),
(23, '0206798681', 1.00),
(24, '3592344322', 1.00),
(25, '8266896517', 1.00),
(26, '9758244499', 1.00),
(27, '0540497973', 1.00),
(28, '5425487136', 1.00),
(29, '4745771812', 1.00),
(30, '2963521451', 1.00),
(31, '0160050892', 1.00),
(32, '9235441167', 1.00),
(33, '4364960452', 1.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_data`
--
ALTER TABLE `chatbot_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatrooms`
--
ALTER TABLE `chatrooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatsecurity`
--
ALTER TABLE `chatsecurity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `chat_user_table`
--
ALTER TABLE `chat_user_table`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`fp_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rate_id`);

--
-- Indexes for table `rider`
--
ALTER TABLE `rider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`v_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `chatbot_data`
--
ALTER TABLE `chatbot_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `chatrooms`
--
ALTER TABLE `chatrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `chatsecurity`
--
ALTER TABLE `chatsecurity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `chat_message`
--
ALTER TABLE `chat_message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `chat_user_table`
--
ALTER TABLE `chat_user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `fp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=506;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `rider`
--
ALTER TABLE `rider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
