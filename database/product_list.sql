-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2024 at 02:25 AM
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
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL,
  `img_path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0= unavailable, 2 Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `category_id`, `name`, `description`, `price`, `quantity`, `img_path`, `status`) VALUES
(9, 7, 'Satay daging', 'Test this is satay', 1, 50, '1708662960_sate daging.jpg', 0),
(10, 7, 'Nasi ayam', 'nasi ayam', 6, 50, '1708663320_nasi ayam.png', 1),
(11, 7, 'Nasi ayam goreng', 'Nasi ayam goreng', 6, 50, '1708663740_Nasi-Goreng-Ayam.jpg', 1),
(12, 7, 'Nasi lemak biasa(telur mata)', 'Nasi lemak biasa(telur mata)', 3.5, 50, '1708663860_nasi lemak biasa.png', 1),
(13, 7, 'Nasi lemak ayam', 'Nasi lemak ayam', 6, 50, '1708664160_nasi lemak ayam.webp', 1),
(14, 7, 'Nasi lemak special', 'Nasi lemak special', 7.5, 50, '1708675260_nasi lemak ayam special.jpg', 1),
(15, 7, 'Nasi impit', 'Nasi impit', 1, 50, '1708675500_Nasi impit.jpeg', 1),
(16, 7, 'Satay ayam', 'The most famous dish in warung pak malau', 1, 50, '1711674960_satay ayam.jpeg', 1),
(17, 7, 'Satay ikan', 'Satay ikan', 1, 0, '1711674960_satay ikan.jpeg', 1),
(18, 7, 'Satay Special', 'With ayam,ikan,lembu, dan unta', 2.5, 0, '1711675020_satay special.jpg', 1),
(19, 7, 'Satay buaya', 'Satay Buaya yang u kena cuba sekarang', 1.7, 50, '1711675080_satay buaya.jpg', 1),
(20, 7, 'Satay unta', 'Daging unta yang famous di arab', 1.8, 50, '1711675140_satay unta.jpeg', 1),
(21, 7, 'Satay arnab', 'comel tapi sedap', 1.5, 50, '1711675200_satay rabbit.jpeg', 1),
(22, 7, 'Satay Kambing', 'Satay Kambing', 1, 50, '1711675260_satay kambing.jpeg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
