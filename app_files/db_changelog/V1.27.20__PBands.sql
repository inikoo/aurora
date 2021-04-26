

-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 10.0.2.2
-- Generation Time: Apr 26, 2021 at 09:10 AM
-- Server version: 8.0.22-13
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `es`
--

-- --------------------------------------------------------

--
-- Table structure for table `ITF Picking Band Bridge`
--

CREATE TABLE `ITF Picking Band Bridge` (
                                           `ITF Picking Band Key` int NOT NULL,
                                           `ITF Picking Band ITF Key` int UNSIGNED NOT NULL,
                                           `ITF Picking Band Type` enum('Picking','Packing') NOT NULL,
                                           `ITF Picking Band Picking Band Key` smallint UNSIGNED DEFAULT NULL,
                                           `ITF Picking Band Picking Band Historic Key` mediumint UNSIGNED DEFAULT NULL,
                                           `ITF Picking Band Amount` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Picking Band Dimension`
--

CREATE TABLE `Picking Band Dimension` (
                                          `Picking Band Key` smallint UNSIGNED NOT NULL,
                                          `Picking Band Warehouse Key` smallint UNSIGNED NOT NULL,
                                          `Picking Band Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
                                          `Picking Band Type` enum('Picking','Packing') NOT NULL,
                                          `Picking Band Name` varchar(255) NOT NULL,
                                          `Picking Band Amount` decimal(8,3) NOT NULL,
                                          `Picking Band From` datetime NOT NULL,
                                          `Picking Band To` datetime DEFAULT NULL,
                                          `Picking Band Number Delivery Notes` mediumint UNSIGNED NOT NULL DEFAULT '0',
                                          `Picking Band Quantity Processed` mediumint UNSIGNED NOT NULL DEFAULT '0',
                                          `Picking Band Amount Out` decimal(12,2) NOT NULL DEFAULT '0.00',
                                          `Picking Band Number History Records` mediumint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Picking Band Historic Fact`
--

CREATE TABLE `Picking Band Historic Fact` (
                                              `Picking Band Historic Key` mediumint UNSIGNED NOT NULL,
                                              `Picking Band Historic Band Key` smallint UNSIGNED DEFAULT NULL,
                                              `Picking Band Historic Type` enum('Picking','Packing') NOT NULL,
                                              `Picking Band Historic Name` varchar(255) NOT NULL,
                                              `Picking Band Historic Amount` decimal(8,3) NOT NULL,
                                              `Picking Band Historic Created` datetime DEFAULT NULL,
                                              `Picking Band Historic Updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Picking Band History Bridge`
--

CREATE TABLE `Picking Band History Bridge` (
                                               `Picking Band Key` mediumint UNSIGNED NOT NULL,
                                               `History Key` int UNSIGNED NOT NULL,
                                               `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
                                               `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
                                               `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Changes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ITF Picking Band Bridge`
--
ALTER TABLE `ITF Picking Band Bridge`
    ADD PRIMARY KEY (`ITF Picking Band Key`),
    ADD UNIQUE KEY `ITF Picking Band ITF Key_2` (`ITF Picking Band ITF Key`,`ITF Picking Band Type`),
    ADD KEY `ITF Picking Band Picking Band Key` (`ITF Picking Band Picking Band Key`),
    ADD KEY `ITF Picking Band Picking Band Historic Key` (`ITF Picking Band Picking Band Historic Key`),
    ADD KEY `ITF Picking Band ITF Key` (`ITF Picking Band ITF Key`);

--
-- Indexes for table `Picking Band Dimension`
--
ALTER TABLE `Picking Band Dimension`
    ADD PRIMARY KEY (`Picking Band Key`),
    ADD KEY `Picking Band Status` (`Picking Band Status`),
    ADD KEY `Picking Band Warehouse Key` (`Picking Band Warehouse Key`);

--
-- Indexes for table `Picking Band Historic Fact`
--
ALTER TABLE `Picking Band Historic Fact`
    ADD PRIMARY KEY (`Picking Band Historic Key`),
    ADD UNIQUE KEY `Picking Band Historic Band Key` (`Picking Band Historic Band Key`,`Picking Band Historic Name`,`Picking Band Historic Amount`);

--
-- Indexes for table `Picking Band History Bridge`
--
ALTER TABLE `Picking Band History Bridge`
    ADD PRIMARY KEY (`Picking Band Key`,`History Key`),
    ADD KEY `Picking Band Key` (`Picking Band Key`),
    ADD KEY `History Key` (`History Key`),
    ADD KEY `Deletable` (`Deletable`),
    ADD KEY `Type` (`Type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ITF Picking Band Bridge`
--
ALTER TABLE `ITF Picking Band Bridge`
    MODIFY `ITF Picking Band Key` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Picking Band Dimension`
--
ALTER TABLE `Picking Band Dimension`
    MODIFY `Picking Band Key` smallint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Picking Band Historic Fact`
--
ALTER TABLE `Picking Band Historic Fact`
    MODIFY `Picking Band Historic Key` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
