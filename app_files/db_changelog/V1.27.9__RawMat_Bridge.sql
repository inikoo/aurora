-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 10.0.2.2
-- Generation Time: Feb 26, 2021 at 03:24 PM
-- Server version: 8.0.19-10
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aroma`
--

-- --------------------------------------------------------

--
-- Table structure for table `Production Part Raw Material Bridge`
--

CREATE TABLE `Production Part Raw Material Bridge` (
                                                       `Production Part Raw Material Key` mediumint UNSIGNED NOT NULL,
                                                       `Production Part Raw Material Production Part Key` mediumint UNSIGNED NOT NULL,
                                                       `Production Part Raw Material Raw Material Key` mediumint UNSIGNED DEFAULT NULL,
                                                       `Production Part Raw Material Ratio` decimal(20,6) NOT NULL DEFAULT '1.000000',
                                                       `Production Part Raw Material Note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Production Part Raw Material Bridge`
--
ALTER TABLE `Production Part Raw Material Bridge`
    ADD PRIMARY KEY (`Production Part Raw Material Key`),
    ADD UNIQUE KEY `Production Part Raw Material Production Part Key` (`Production Part Raw Material Production Part Key`,`Production Part Raw Material Raw Material Key`),
    ADD UNIQUE KEY `Production Part Raw Material Raw Material Key` (`Production Part Raw Material Raw Material Key`),
    ADD KEY `Production Part Raw Material P_2` (`Production Part Raw Material Production Part Key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Production Part Raw Material Bridge`
--
ALTER TABLE `Production Part Raw Material Bridge`
    MODIFY `Production Part Raw Material Key` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;