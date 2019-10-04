-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: box
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.19.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `API Box Request Dimension`
--

DROP TABLE IF EXISTS `API Box Request Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `API Box Request Dimension` (
  `API Box Request Key` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `API Box Request Box Key` mediumint(9) NOT NULL,
  `API Box Request Date` datetime NOT NULL,
  `API Box Request IP` varchar(64) NOT NULL,
  `API Box Request Metadata` json DEFAULT NULL,
  PRIMARY KEY (`API Box Request Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Box Dimension`
--

DROP TABLE IF EXISTS `Box Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Box Dimension` (
  `Box Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Box ID` varchar(64) DEFAULT NULL,
  `Box Registered Date` datetime DEFAULT NULL,
  `Box Set up Date` datetime DEFAULT NULL,
  `Box Model` varchar(64) DEFAULT NULL,
  `Box Aurora Account Code` varchar(64) DEFAULT NULL,
  `Box Aurora Account Data` json DEFAULT NULL,
  PRIMARY KEY (`Box Key`),
  UNIQUE KEY `Box ID` (`Box ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Fail API Box Request Dimension`
--

DROP TABLE IF EXISTS `Fail API Box Request Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Fail API Box Request Dimension` (
  `Fail API Box Request Key` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Fail API Box Request IP` varchar(255) DEFAULT NULL,
  `Fail API Box Request Date` datetime DEFAULT NULL,
  `Fail API Box Request Type` enum('API Key Missing','API Key No Match','Invalid API Key') NOT NULL,
  PRIMARY KEY (`Fail API Box Request Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-05  1:54:59
