-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 19, 2020 at 07:24 PM
-- Server version: 5.7.17-log
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sysel`
--
CREATE DATABASE IF NOT EXISTS `sysel` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sysel`;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(10) UNSIGNED NOT NULL,
  `street` varchar(64) NOT NULL,
  `city` varchar(45) NOT NULL,
  `country` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `street`, `city`, `country`) VALUES
(1, ' Elphinstone Road 304', 'Mumbai', 'India'),
(2, '6611 Zuni Rd SE', 'Albuquerque', 'USA'),
(3, 'Mostní 11', 'Most', 'Czech'),
(4, 'Zahradní 20', 'Liberec', 'Czech');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(64) DEFAULT NULL,
  `forname` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `middlename` varchar(45) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `address_id` int(11) NOT NULL,
  `added` date NOT NULL,
  `note` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `company_name`, `forname`, `surname`, `middlename`, `title`, `email`, `phone`, `address_id`, `added`, `note`) VALUES
(1, NULL, 'Dan', 'Šroubek', NULL, NULL, 'dšr@mail.cz', '800000000', 3, '2020-05-05', NULL),
(2, NULL, 'Ujo', 'Pompo', NULL, NULL, 'pom@rumbco.cz', '700000000', 4, '2020-06-10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(10) UNSIGNED NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_detail_id` int(11) NOT NULL,
  `added` date NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `warehouse_id`, `item_detail_id`, `added`, `order_id`, `status`) VALUES
(4, 1, 1, '2020-02-01', NULL, 1),
(5, 1, 2, '2020-02-01', NULL, 1),
(6, 1, 3, '2020-04-15', NULL, 1),
(7, 1, 2, '2020-01-08', NULL, 3),
(8, 1, 1, '2020-02-01', NULL, 1),
(9, 1, 1, '2020-02-01', NULL, 1),
(10, 1, 1, '2020-02-01', NULL, 1),
(11, 1, 1, '2020-02-01', NULL, 1),
(12, 1, 1, '2020-02-01', NULL, 1),
(13, 1, 1, '2020-02-01', NULL, 1),
(14, 1, 1, '2020-02-01', NULL, 1),
(15, 1, 1, '2020-02-01', NULL, 1),
(16, 3, 1, '2020-02-01', NULL, 1),
(17, 3, 1, '2020-02-01', NULL, 1),
(18, 3, 1, '2020-02-01', NULL, 1),
(19, 3, 1, '2020-02-01', NULL, 1),
(20, 3, 2, '2020-02-01', NULL, 1),
(21, 3, 2, '2020-02-01', NULL, 1),
(22, 3, 2, '2020-02-01', NULL, 1),
(23, 3, 2, '2020-02-01', NULL, 1),
(24, 3, 2, '2020-02-01', NULL, 1),
(25, 1, 2, '2020-02-01', NULL, 1),
(26, 1, 2, '2020-02-01', NULL, 1),
(27, 1, 2, '2020-02-01', NULL, 1),
(28, 1, 2, '2020-02-01', NULL, 1),
(29, 1, 3, '2020-04-15', NULL, 1),
(30, 1, 3, '2020-04-15', NULL, 1),
(31, 1, 3, '2020-04-15', NULL, 1),
(32, 1, 3, '2020-04-15', NULL, 1),
(33, 1, 3, '2020-04-15', NULL, 1),
(34, 1, 3, '2020-04-15', NULL, 1),
(35, 1, 3, '2020-04-15', NULL, 1),
(36, 1, 2, '2020-01-08', NULL, 3),
(37, 1, 2, '2020-01-08', NULL, 3),
(38, 1, 2, '2020-01-08', NULL, 3),
(39, 1, 2, '2020-01-08', NULL, 3),
(40, 1, 2, '2020-01-08', NULL, 3),
(41, 3, 2, '2020-01-08', NULL, 3),
(42, 3, 2, '2020-01-08', NULL, 3),
(43, 3, 2, '2020-01-08', NULL, 3),
(44, 1, 1, '2020-05-04', 1, 2),
(45, 1, 1, '2020-05-04', 1, 2),
(46, 1, 1, '2020-05-04', 1, 2),
(47, 1, 1, '2020-05-04', 1, 2),
(48, 1, 1, '2020-05-04', 1, 2),
(49, 1, 2, '2020-05-04', 1, 2),
(50, 1, 2, '2020-05-04', 1, 2),
(51, 1, 2, '2020-05-04', 1, 2),
(52, 1, 2, '2020-05-04', 1, 2),
(53, 1, 2, '2020-05-04', 1, 2),
(54, 1, 3, '2020-05-04', 1, 2),
(55, 3, 1, '2020-06-06', NULL, 1),
(56, 3, 1, '2020-06-06', NULL, 1),
(57, 3, 1, '2020-06-06', NULL, 1),
(58, 3, 1, '2020-06-06', NULL, 1),
(59, 3, 1, '2020-06-06', NULL, 1),
(60, 3, 1, '2020-06-06', NULL, 1),
(61, 3, 2, '2020-06-06', NULL, 1),
(62, 3, 2, '2020-06-06', NULL, 1),
(63, 3, 2, '2020-06-06', NULL, 1),
(64, 3, 2, '2020-06-06', NULL, 1),
(65, 3, 2, '2020-06-06', NULL, 1),
(66, 1, 4, '2020-06-10', NULL, 1),
(67, 1, 4, '2020-06-10', NULL, 1),
(68, 1, 4, '2020-06-10', NULL, 1),
(69, 1, 4, '2020-06-10', NULL, 1),
(70, 1, 4, '2020-06-10', NULL, 1),
(71, 1, 4, '2020-06-10', NULL, 1),
(72, 1, 4, '2020-06-10', NULL, 1),
(73, 1, 4, '2020-06-10', NULL, 1),
(74, 1, 4, '2020-06-10', NULL, 1),
(75, 1, 4, '2020-06-10', NULL, 1),
(76, 1, 5, '2020-06-10', NULL, 1),
(77, 1, 5, '2020-06-10', NULL, 1),
(78, 1, 5, '2020-06-10', NULL, 1),
(79, 1, 5, '2020-06-10', NULL, 1),
(80, 1, 5, '2020-06-10', NULL, 1),
(81, 1, 5, '2020-06-10', NULL, 1),
(82, 1, 5, '2020-06-10', NULL, 1),
(83, 1, 5, '2020-06-10', NULL, 1),
(84, 1, 5, '2020-06-10', NULL, 1),
(85, 1, 5, '2020-06-10', NULL, 1),
(86, 1, 5, '2020-06-10', NULL, 1),
(87, 1, 5, '2020-06-10', NULL, 1),
(88, 1, 5, '2020-06-10', NULL, 1),
(89, 1, 5, '2020-06-10', NULL, 1),
(90, 1, 5, '2020-06-10', NULL, 1),
(91, 1, 5, '2020-06-10', NULL, 1),
(92, 1, 5, '2020-06-10', NULL, 1),
(93, 1, 5, '2020-06-10', NULL, 1),
(94, 1, 5, '2020-06-10', NULL, 1),
(95, 1, 5, '2020-06-10', NULL, 1),
(96, 1, 5, '2020-06-10', NULL, 1),
(97, 1, 5, '2020-06-10', NULL, 1),
(98, 1, 5, '2020-06-10', NULL, 1),
(99, 1, 5, '2020-06-10', NULL, 1),
(100, 1, 5, '2020-06-10', NULL, 1),
(101, 1, 5, '2020-06-10', NULL, 1),
(102, 1, 5, '2020-06-10', NULL, 1),
(103, 1, 5, '2020-06-10', NULL, 1),
(104, 1, 5, '2020-06-10', NULL, 1),
(105, 1, 5, '2020-06-10', NULL, 1),
(106, 3, 4, '2020-06-10', NULL, 1),
(107, 3, 4, '2020-06-10', NULL, 1),
(108, 3, 4, '2020-06-10', NULL, 1),
(109, 3, 4, '2020-06-10', NULL, 1),
(110, 3, 4, '2020-06-10', NULL, 1),
(111, 3, 5, '2020-06-10', NULL, 1),
(112, 3, 5, '2020-06-10', NULL, 1),
(113, 3, 5, '2020-06-10', NULL, 1),
(114, 3, 5, '2020-06-10', NULL, 1),
(115, 3, 5, '2020-06-10', NULL, 1),
(116, 3, 5, '2020-06-10', NULL, 1),
(117, 3, 5, '2020-06-10', NULL, 1),
(118, 3, 5, '2020-06-10', NULL, 1),
(119, 3, 5, '2020-06-10', NULL, 1),
(120, 3, 5, '2020-06-10', NULL, 1),
(121, 3, 5, '2020-06-10', NULL, 1),
(122, 3, 5, '2020-06-10', NULL, 1),
(123, 3, 5, '2020-06-10', NULL, 1),
(124, 3, 5, '2020-06-10', NULL, 1),
(125, 3, 5, '2020-06-10', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `item_detail`
--

CREATE TABLE `item_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `area` float NOT NULL,
  `manufacturer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item_detail`
--

INSERT INTO `item_detail` (`id`, `name`, `area`, `manufacturer_id`) VALUES
(1, 'šrouby m5x15 200 ks', 0.5, 1),
(2, 'matky m5 hex 200 ks', 0.5, 1),
(3, 'úhelník 100x20x2 100 ks', 20, 1),
(4, 'pracovní rukacice M 20 ks', 4, 2),
(5, 'Rýč 1 ks', 1, 1),
(6, 'Rozvodový řemen 1 ks', 0.3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(45) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `address_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `name`, `email`, `phone`, `address_id`) VALUES
(1, 'Trusty engineer', 'orders@trusty_en.in', '800000000', 1),
(2, 'Bean seamstress', 'Manuel_Varga@mex.us', '800000001', 2);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) NOT NULL,
  `added` datetime NOT NULL,
  `last_edited` datetime DEFAULT NULL,
  `note` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `client_id`, `added`, `last_edited`, `note`, `status`) VALUES
(1, 1, '2020-05-05 11:00:00', NULL, NULL, 2),
(2, 2, '2020-06-10 07:00:00', '2020-06-10 11:00:00', 'spěchá', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_has_item`
--

CREATE TABLE `order_has_item` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_has_item`
--

INSERT INTO `order_has_item` (`id`, `order_id`, `item_id`, `amount`) VALUES
(1, 1, 1, 5),
(2, 1, 2, 5),
(3, 1, 3, 1),
(4, 2, 4, 3),
(5, 2, 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `area` int(11) NOT NULL,
  `created` date NOT NULL,
  `last_edited` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id`, `name`, `area`, `created`, `last_edited`) VALUES
(1, 'sklad 1', 1500, '2020-04-23', NULL),
(3, 'sklad 3', 2000, '2020-04-23', NULL),
(9, 'sklad 2', 2500, '2020-04-25', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `war_id` (`warehouse_id`),
  ADD KEY `itd_id` (`item_detail_id`),
  ADD KEY `ord_id` (`order_id`);

--
-- Indexes for table `item_detail`
--
ALTER TABLE `item_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `order_has_item`
--
ALTER TABLE `order_has_item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `item_detail`
--
ALTER TABLE `item_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_has_item`
--
ALTER TABLE `order_has_item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
