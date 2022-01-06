-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 06, 2022 at 12:59 PM
-- Server version: 8.0.26-16
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dw`
--

-- --------------------------------------------------------

--
-- Table structure for table `Order Source Dimension`
--

CREATE TABLE `Order Source Dimension` (
                                          `Order Source Key` int UNSIGNED NOT NULL,
                                          `Order Source Type` varchar(255) DEFAULT NULL,
                                          `Order Source Code` varchar(255) DEFAULT NULL,
                                          `Order Source Name` varchar(255) DEFAULT NULL,
                                          `Order Source Option Key` int UNSIGNED DEFAULT NULL,
                                          `Order Source Locked` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Order Source Dimension`
--

INSERT INTO `Order Source Dimension` (`Order Source Key`, `Order Source Type`, `Order Source Code`, `Order Source Name`, `Order Source Option Key`, `Order Source Locked`) VALUES
                                                                                                                                                                               (1, 'Internet', 'Internet', 'Internet', NULL, 'Yes'),
                                                                                                                                                                               (2, 'Internet', 'Internet', 'Internet', NULL, 'Yes'),
                                                                                                                                                                               (3, 'Call', 'Tele', 'Call', NULL, 'Yes'),
                                                                                                                                                                               (4, 'Store', 'Person', 'Store', NULL, 'Yes'),
                                                                                                                                                                               (5, 'Other', 'Other', 'Other', NULL, 'Yes'),
                                                                                                                                                                               (6, 'Email', 'Tele', 'Email', NULL, 'Yes'),
                                                                                                                                                                               (7, 'Fax', 'Tele', 'Fax', NULL, 'Yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Order Source Dimension`
--
ALTER TABLE `Order Source Dimension`
    ADD PRIMARY KEY (`Order Source Key`),
    ADD KEY `Order Source Type` (`Order Source Type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Order Source Dimension`
--
ALTER TABLE `Order Source Dimension`
    MODIFY `Order Source Key` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
