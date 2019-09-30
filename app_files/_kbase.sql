-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: kbase
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.18.04.1-log

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
-- Table structure for table `Border Dimension`
--

DROP TABLE IF EXISTS `Border Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Border Dimension` (
  `Border Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Border` geometry NOT NULL,
  `Border Wrap Level` tinyint(4) NOT NULL DEFAULT '0',
  `Border Type` enum('Country','Country First Division','Country Second Division','Country Third Division','Country Forth Division','Country Fifth Division') NOT NULL,
  PRIMARY KEY (`Border Key`),
  SPATIAL KEY `sp_index` (`Border`),
  KEY `Border Wrap Level` (`Border Wrap Level`),
  KEY `Border Type` (`Border Type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Commodity Code Dimension`
--

DROP TABLE IF EXISTS `Commodity Code Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Commodity Code Dimension` (
  `Commodity Code` int(8) unsigned NOT NULL,
  `Commodity Description` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `Commodity Units` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`Commodity Code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Alias Dimension`
--

DROP TABLE IF EXISTS `Country Alias Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Alias Dimension` (
  `Country Alias Code` varchar(3) NOT NULL,
  `Country Alias` varchar(255) NOT NULL,
  `Country Alias Type` enum('Mispelling','Short Name','Other Language','Alias','Error') NOT NULL DEFAULT 'Alias',
  UNIQUE KEY `Country Alias` (`Country Alias`(36),`Country Alias Code`),
  KEY `Country Alias Type` (`Country Alias Type`),
  KEY `Country Alias Code` (`Country Alias Code`),
  KEY `Country Alias_2` (`Country Alias`(36))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Border`
--

DROP TABLE IF EXISTS `Country Border`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Border` (
  `Country Border` multipolygon NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Currency Bridge`
--

DROP TABLE IF EXISTS `Country Currency Bridge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Currency Bridge` (
  `Country Code` varchar(3) CHARACTER SET utf8 NOT NULL,
  `Currency Code` varchar(3) CHARACTER SET utf8 NOT NULL,
  `Notes` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `Valid` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'Yes',
  UNIQUE KEY `Country Code` (`Country Code`,`Currency Code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Dimension`
--

DROP TABLE IF EXISTS `Country Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Dimension` (
  `Country Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Country 2 Alpha Code` char(2) NOT NULL,
  `Country Code` char(3) NOT NULL,
  `Country Numeric Code` smallint(5) unsigned DEFAULT NULL,
  `Country Name` varchar(255) NOT NULL,
  `Country Local Name` varchar(255) DEFAULT NULL,
  `Country Telephone Code` varchar(6) NOT NULL,
  `Country Telephone Code Metadata` text,
  `Country Official Name` varchar(500) NOT NULL,
  `Continent` enum('Unknown','Asia','Europe','Africa','Oceania','Antarctica','America') DEFAULT 'Unknown',
  `Continent Code` char(4) NOT NULL,
  `World Region` char(26) NOT NULL,
  `World Region Code` char(4) NOT NULL,
  `Country Surface` float(10,2) NOT NULL DEFAULT '0.00',
  `Country Creation Date` datetime DEFAULT NULL,
  `Country Disolution Date` datetime DEFAULT NULL,
  `Country Population` int(11) NOT NULL DEFAULT '0',
  `Country Life Expectancy` float(3,1) DEFAULT NULL,
  `Country GNP` float(10,2) DEFAULT NULL,
  `Country GNPold` float(10,2) DEFAULT NULL,
  `Country Native Name` varchar(255) NOT NULL,
  `Country Goverment Form` char(45) NOT NULL,
  `Country Head of State` char(60) DEFAULT NULL,
  `Country Capital Name` varchar(255) DEFAULT NULL,
  `Country Currency Code` varchar(3) DEFAULT NULL,
  `Country Currency Name` varchar(255) DEFAULT NULL,
  `Country TLD` varchar(16) DEFAULT NULL,
  `Country Telephone National Access Code` varchar(1) NOT NULL,
  `Country Has Primary Division` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Country Has Secondary Division` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Country Has Postal Code` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Country Input Primary Division` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Country Input Secondary Division` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Country Input Postal Code` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Country Primary Division Label` varchar(512) NOT NULL,
  `Country Secondary Division Label` varchar(512) NOT NULL,
  `Country Postal Code Format` varchar(512) DEFAULT NULL,
  `Country Postal Code Regex` varchar(254) DEFAULT NULL,
  `Country Languages` varchar(512) DEFAULT NULL,
  `Country Neighbours` varchar(512) DEFAULT NULL,
  `Country Data Last Update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `European Union` enum('Yes','No') NOT NULL DEFAULT 'No',
  `EC Fiscal VAT Area` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Country Display Address Field` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Country Display Telephone Field` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Country Key`),
  KEY `gg` (`Country Code`),
  KEY `sd` (`Country 2 Alpha Code`),
  KEY `Country Telephone Code` (`Country Telephone Code`),
  KEY `a` (`Country Name`),
  KEY `EC Fiscal VAT Area` (`EC Fiscal VAT Area`),
  KEY `Country Display Address Field` (`Country Display Address Field`),
  KEY `Country Display Telephone Field` (`Country Display Telephone Field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Fifth Division Dimension`
--

DROP TABLE IF EXISTS `Country Fifth Division Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Fifth Division Dimension` (
  `Country Fifth Division Code` varchar(20) NOT NULL DEFAULT '',
  `Country Fifth Division FIPS` varchar(16) DEFAULT NULL,
  `Country Fifth Division HASC` varchar(16) DEFAULT NULL,
  `Geography Key` mediumint(7) unsigned NOT NULL DEFAULT '0' COMMENT 'integer id of record in geonames database',
  `Country Fifth Division Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point (utf8)',
  `Country Fifth Division ASCII Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point in plain ascii characters',
  `Country Code` char(3) NOT NULL,
  `Country 2 Alpha Code` char(2) DEFAULT NULL COMMENT 'ISO-3166 2-letter country code',
  `Country First Division Code` varchar(64) NOT NULL,
  `Country Second Division Code` varchar(64) NOT NULL,
  `Country Third Division Code` varchar(64) NOT NULL,
  `Country Forth Division Code` varchar(64) NOT NULL,
  `Country Fifth Division Type` varchar(64) DEFAULT NULL,
  `Country Fifth Division Local Type` varchar(64) DEFAULT NULL,
  `Country Fifth Division Latitude` double DEFAULT NULL COMMENT 'latitude in decimal degrees',
  `Country Fifth Division Longitude` double DEFAULT NULL COMMENT 'longitude in decimal degrees',
  `Country Fifth Division Population` bigint(20) unsigned DEFAULT NULL,
  `Country Fifth Division Elevation` int(11) unsigned DEFAULT NULL COMMENT 'in meter',
  `Country Fifth Division Average Elevation` int(11) unsigned DEFAULT NULL COMMENT 'average elevation of 30''x30'' (ca 900mx900m) area in meters',
  `Country Fifth Division Timezone` varchar(255) DEFAULT NULL COMMENT 'the timezone id (see file timeZone.txt',
  `Country Fifth Division Modification Date` date DEFAULT NULL COMMENT 'date of last modification',
  `GADM Key` mediumint(8) unsigned DEFAULT NULL,
  `Border Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Country Fifth Division Code`),
  KEY `Country Fifth Division 2 Alpha Country Code` (`Country 2 Alpha Code`),
  KEY `Country Fifth Division Name` (`Country Fifth Division Name`(12)),
  KEY `Border Key` (`Border Key`),
  KEY `Country Second Division Code` (`Country Second Division Code`(12)),
  KEY `Country First Division Code` (`Country First Division Code`(12)),
  KEY `Country Third Division Code` (`Country Third Division Code`(12)),
  KEY `Country Forth Division Code` (`Country Forth Division Code`(12))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country First Division Dimension`
--

DROP TABLE IF EXISTS `Country First Division Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country First Division Dimension` (
  `Country First Division Code` varchar(20) NOT NULL DEFAULT '',
  `Country First Division FIPS` varchar(16) DEFAULT NULL,
  `Country First Division HASC` varchar(16) DEFAULT NULL,
  `Geography Key` mediumint(7) unsigned NOT NULL DEFAULT '0' COMMENT 'integer id of record in geonames database',
  `Country First Division Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point (utf8)',
  `Country First Division ASCII Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point in plain ascii characters',
  `Country Code` char(3) NOT NULL,
  `Country 2 Alpha Code` char(2) DEFAULT NULL COMMENT 'ISO-3166 2-letter country code',
  `Country First Division Type` varchar(64) DEFAULT NULL,
  `Country First Division Local Type` varchar(64) DEFAULT NULL,
  `Country First Division Latitude` double DEFAULT NULL COMMENT 'latitude in decimal degrees',
  `Country First Division Longitude` double DEFAULT NULL COMMENT 'longitude in decimal degrees',
  `Country First Division Population` bigint(20) unsigned DEFAULT NULL,
  `Country First Division Elevation` int(11) unsigned DEFAULT NULL COMMENT 'in meter',
  `Country First Division Average Elevation` int(11) unsigned DEFAULT NULL COMMENT 'average elevation of 30''x30'' (ca 900mx900m) area in meters',
  `Country First Division Timezone` varchar(255) DEFAULT NULL COMMENT 'the timezone id (see file timeZone.txt',
  `Country First Division Modification Date` date DEFAULT NULL COMMENT 'date of last modification',
  `GADM Key` mediumint(8) unsigned DEFAULT NULL,
  `Border Key` mediumint(9) unsigned DEFAULT NULL,
  PRIMARY KEY (`Country First Division Code`),
  KEY `Country First Division 2 Alpha Country Code` (`Country 2 Alpha Code`),
  KEY `Country First Division Name` (`Country First Division Name`(12)),
  KEY `Country Code` (`Country Code`),
  KEY `Border Key` (`Border Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Forth Division Dimension`
--

DROP TABLE IF EXISTS `Country Forth Division Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Forth Division Dimension` (
  `Country Forth Division Code` varchar(20) NOT NULL DEFAULT '',
  `Country Forth Division FIPS` varchar(16) DEFAULT NULL,
  `Country Forth Division HASC` varchar(16) DEFAULT NULL,
  `Geography Key` mediumint(7) unsigned NOT NULL DEFAULT '0' COMMENT 'integer id of record in geonames database',
  `Country Forth Division Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point (utf8)',
  `Country Forth Division ASCII Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point in plain ascii characters',
  `Country Code` char(3) NOT NULL,
  `Country 2 Alpha Code` char(2) DEFAULT NULL COMMENT 'ISO-3166 2-letter country code',
  `Country First Division Code` varchar(64) NOT NULL,
  `Country Second Division Code` varchar(64) NOT NULL,
  `Country Third Division Code` varchar(64) NOT NULL,
  `Country Forth Division Type` varchar(64) DEFAULT NULL,
  `Country Forth Division Local Type` varchar(64) DEFAULT NULL,
  `Country Forth Division Latitude` double DEFAULT NULL COMMENT 'latitude in decimal degrees',
  `Country Forth Division Longitude` double DEFAULT NULL COMMENT 'longitude in decimal degrees',
  `Country Forth Division Population` bigint(20) unsigned DEFAULT NULL,
  `Country Forth Division Elevation` int(11) unsigned DEFAULT NULL COMMENT 'in meter',
  `Country Forth Division Average Elevation` int(11) unsigned DEFAULT NULL COMMENT 'average elevation of 30''x30'' (ca 900mx900m) area in meters',
  `Country Forth Division Timezone` varchar(255) DEFAULT NULL COMMENT 'the timezone id (see file timeZone.txt',
  `Country Forth Division Modification Date` date DEFAULT NULL COMMENT 'date of last modification',
  `GADM Key` mediumint(8) unsigned DEFAULT NULL,
  `Border Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Country Forth Division Code`),
  KEY `Country Forth Division 2 Alpha Country Code` (`Country 2 Alpha Code`),
  KEY `Country Forth Division Name` (`Country Forth Division Name`(12)),
  KEY `Country Code` (`Country Code`),
  KEY `Country First Division Code` (`Country First Division Code`),
  KEY `Country Second Division Code` (`Country Second Division Code`),
  KEY `Country Third Division Code` (`Country Third Division Code`),
  KEY `Border Key` (`Border Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Second Division Dimension`
--

DROP TABLE IF EXISTS `Country Second Division Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Second Division Dimension` (
  `Country Second Division Code` varchar(20) NOT NULL DEFAULT '',
  `Country Second Division FIPS` varchar(16) DEFAULT NULL,
  `Country Second Division HASC` varchar(16) DEFAULT NULL,
  `Geography Key` mediumint(7) unsigned NOT NULL DEFAULT '0' COMMENT 'integer id of record in geonames database',
  `Country Second Division Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point (utf8)',
  `Country Second Division ASCII Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point in plain ascii characters',
  `Country Code` char(3) NOT NULL,
  `Country 2 Alpha Code` char(2) DEFAULT NULL COMMENT 'ISO-3166 2-letter country code',
  `Country First Division Code` varchar(64) NOT NULL,
  `Country Second Division Type` varchar(64) DEFAULT NULL,
  `Country Second Division Local Type` varchar(64) DEFAULT NULL,
  `Country Second Division Latitude` double DEFAULT NULL COMMENT 'latitude in decimal degrees',
  `Country Second Division Longitude` double DEFAULT NULL COMMENT 'longitude in decimal degrees',
  `Country Second Division Population` bigint(20) unsigned DEFAULT NULL,
  `Country Second Division Elevation` int(11) unsigned DEFAULT NULL COMMENT 'in meter',
  `Country Second Division Average Elevation` int(11) unsigned DEFAULT NULL COMMENT 'average elevation of 30''x30'' (ca 900mx900m) area in meters',
  `Country Second Division Timezone` varchar(255) DEFAULT NULL COMMENT 'the timezone id (see file timeZone.txt',
  `Country Second Division Modification Date` date DEFAULT NULL COMMENT 'date of last modification',
  `GADM Key` mediumint(8) unsigned DEFAULT NULL,
  `Border Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Country Second Division Code`),
  KEY `Country Second Division 2 Alpha Country Code` (`Country 2 Alpha Code`),
  KEY `Country Second Division Name` (`Country Second Division Name`(12)),
  KEY `Country Code` (`Country Code`),
  KEY `Country First Division Code` (`Country First Division Code`(12)),
  KEY `Border Key` (`Border Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Specific Date Outrigger`
--

DROP TABLE IF EXISTS `Country Specific Date Outrigger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Specific Date Outrigger` (
  `Date Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Date` datetime NOT NULL,
  `Country Key` mediumint(9) NOT NULL,
  `Country Name` varchar(255) NOT NULL,
  `Country Code` varchar(3) NOT NULL,
  `Civil Holiday Flag` tinyint(1) NOT NULL,
  `Civil Holiday Name` varchar(255) NOT NULL,
  `Religious Holiday Flag` tinyint(1) NOT NULL,
  `Religoous Holiday Name` varchar(255) NOT NULL,
  PRIMARY KEY (`Date Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country Third Division Dimension`
--

DROP TABLE IF EXISTS `Country Third Division Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country Third Division Dimension` (
  `Country Third Division Code` varchar(20) NOT NULL DEFAULT '',
  `Country Third Division HASC` varchar(16) DEFAULT NULL,
  `Geography Key` mediumint(7) unsigned NOT NULL DEFAULT '0' COMMENT 'integer id of record in geonames database',
  `Country Third Division Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point (utf8)',
  `Country Third Division ASCII Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point in plain ascii characters',
  `Country Code` char(3) NOT NULL,
  `Country 2 Alpha Code` char(2) DEFAULT NULL COMMENT 'ISO-3166 2-letter country code',
  `Country First Division Code` varchar(64) NOT NULL,
  `Country Second Division Code` varchar(64) NOT NULL,
  `Country Third Division Type` varchar(64) DEFAULT NULL,
  `Country Third Division Local Type` varchar(64) DEFAULT NULL,
  `Country Third Division Latitude` double DEFAULT NULL COMMENT 'latitude in decimal degrees',
  `Country Third Division Longitude` double DEFAULT NULL COMMENT 'longitude in decimal degrees',
  `Country Third Division Population` bigint(20) unsigned DEFAULT NULL,
  `Country Third Division Elevation` int(11) unsigned DEFAULT NULL COMMENT 'in meter',
  `Country Third Division Average Elevation` int(11) unsigned DEFAULT NULL COMMENT 'average elevation of 30''x30'' (ca 900mx900m) area in meters',
  `Country Third Division Timezone` varchar(255) DEFAULT NULL COMMENT 'the timezone id (see file timeZone.txt',
  `Country Third Division Modification Date` date DEFAULT NULL COMMENT 'date of last modification',
  `GADM Key` mediumint(8) unsigned DEFAULT NULL,
  `Border Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Country Third Division Code`),
  KEY `Country Third Division 2 Alpha Country Code` (`Country 2 Alpha Code`),
  KEY `Country Third Division Name` (`Country Third Division Name`(12)),
  KEY `Country Code` (`Country Code`),
  KEY `Country First Division Code` (`Country First Division Code`(12)),
  KEY `Country Second Division Code` (`Country Second Division Code`(14)),
  KEY `Country Third Division Border` (`Border Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Currency Dimension`
--

DROP TABLE IF EXISTS `Currency Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Currency Dimension` (
  `Currency Code` varchar(3) NOT NULL,
  `Currency Name` varchar(64) NOT NULL,
  `Currency Symbol` varchar(16) DEFAULT NULL,
  `Currency Flag` varchar(6) DEFAULT NULL,
  `Currency Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Currency Status` enum('Active','Historic') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`Currency Code`),
  KEY `Currency Status` (`Currency Status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Currency Exchange Dimension`
--

DROP TABLE IF EXISTS `Currency Exchange Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Currency Exchange Dimension` (
  `Currency Pair` varchar(6) NOT NULL,
  `Exchange` float NOT NULL,
  `Currency Exchange Last Updated` datetime NOT NULL,
  `Currency Exchange Source` varchar(255) NOT NULL,
  PRIMARY KEY (`Currency Pair`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Date Country Event`
--

DROP TABLE IF EXISTS `Date Country Event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Date Country Event` (
  `Date Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Date` datetime NOT NULL,
  `Country Key` mediumint(9) NOT NULL,
  `Country Name` varchar(255) NOT NULL,
  `Country Code` varchar(3) NOT NULL,
  `Civil Holiday Flag` tinyint(1) NOT NULL,
  `Civil Holiday Name` varchar(255) NOT NULL,
  `Religious Holiday Flag` tinyint(1) NOT NULL,
  `Religoous Holiday Name` varchar(255) NOT NULL,
  PRIMARY KEY (`Date Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Date Dimension`
--

DROP TABLE IF EXISTS `Date Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Date Dimension` (
  `Date Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `Week First Day` date NOT NULL,
  `Week Last Day` date NOT NULL,
  `Open to Public` enum('Yes','No') NOT NULL,
  `Working Day` enum('Yes','No') NOT NULL,
  `Selling Seasson` enum('Christmas','Winter','Spring','Mothers Day','Summer','Automn') NOT NULL COMMENT 'Name of the retailing season.',
  `Mayor External Event` varchar(255) NOT NULL,
  `Mayor Internal Event` varchar(255) NOT NULL,
  PRIMARY KEY (`Date Key`),
  UNIQUE KEY `Date` (`Date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ECB Currency Exchange Dimension`
--

DROP TABLE IF EXISTS `ECB Currency Exchange Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ECB Currency Exchange Dimension` (
  `ECB Currency Exchange Date` date NOT NULL,
  `ECB Currency Exchange Currency Pair` varchar(6) NOT NULL,
  `ECB Currency Exchange Rate` double NOT NULL,
  UNIQUE KEY `Date` (`ECB Currency Exchange Date`,`ECB Currency Exchange Currency Pair`),
  KEY `Currency Pair` (`ECB Currency Exchange Currency Pair`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `First Name Dimension`
--

DROP TABLE IF EXISTS `First Name Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `First Name Dimension` (
  `First Name Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `First Name` varchar(255) NOT NULL,
  `Gender` enum('Male','Female','Unknown') NOT NULL DEFAULT 'Unknown',
  PRIMARY KEY (`First Name Key`),
  UNIQUE KEY `unq` (`First Name`),
  KEY `x` (`First Name`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Geography Alias Dimension`
--

DROP TABLE IF EXISTS `Geography Alias Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Geography Alias Dimension` (
  `Alias Key` int(10) unsigned NOT NULL,
  `Geography Key` int(10) unsigned NOT NULL,
  `Language Code` varchar(7) DEFAULT NULL,
  `Alias` varchar(200) NOT NULL,
  `Is Prefered Name` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Is Short Name` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Alias Key`),
  KEY `Geography Key` (`Geography Key`),
  KEY `Alias` (`Alias`(16))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Geography Dimension`
--

DROP TABLE IF EXISTS `Geography Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Geography Dimension` (
  `Geography Key` mediumint(7) unsigned NOT NULL COMMENT 'integer id of record in geonames database',
  `Geography Name` varchar(200) NOT NULL COMMENT 'name of geographical point (utf8)',
  `Geography ASCII Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point in plain ascii characters',
  `Geography Latitude` float NOT NULL COMMENT 'latitude in decimal degrees',
  `Geography Longitude` float NOT NULL COMMENT 'longitude in decimal degrees',
  `Geography Feature Class` varchar(1) DEFAULT NULL COMMENT 'see http://www.geonames.org/export/codes.html',
  `Geography Feature Code` varchar(10) DEFAULT NULL COMMENT 'see http://www.geonames.org/export/codes.html',
  `Geography 2 Alpha Country Code` varchar(2) DEFAULT NULL COMMENT 'ISO-3166 2-letter country code',
  `Geography Alternate Country Codes` varchar(60) DEFAULT NULL COMMENT 'alternate country codes, comma separated, ISO-3166 2-letter country code',
  `Geography Country Primary Division` varchar(20) DEFAULT NULL COMMENT 'fipscode (subject to change to iso code), isocode for the us and ch, see file admin1Codes.txt for display names of this code',
  `Geography Country Secondary Division` varchar(80) DEFAULT NULL COMMENT 'code for the second administrative division, a county in the US, see file admin2Codes.txt',
  `Geography Country Third Division` varchar(20) DEFAULT NULL COMMENT 'code for third level administrative division',
  `Geography Country Forth Division` varchar(20) DEFAULT NULL COMMENT 'code for fourth level administrative division',
  `Geography Population` bigint(20) unsigned NOT NULL,
  `Geography Elevation` int(11) unsigned DEFAULT NULL COMMENT 'in meter',
  `Geography Average Elevation` int(11) unsigned DEFAULT NULL COMMENT 'average elevation of 30''x30'' (ca 900mx900m) area in meters',
  `Geography Timezone` varchar(255) DEFAULT NULL COMMENT 'the timezone id (see file timeZone.txt',
  `Geography Modification Date` date NOT NULL COMMENT 'date of last modificatio',
  PRIMARY KEY (`Geography Key`),
  KEY `Geography Feature Class` (`Geography Feature Class`),
  KEY `Geography Feature Code` (`Geography Feature Code`),
  KEY `Geography 2 Alpha Country Code` (`Geography 2 Alpha Country Code`),
  KEY `Geography Name` (`Geography Name`(16))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `HM Revenue and Customs Currency Exchange Dimension`
--

DROP TABLE IF EXISTS `HM Revenue and Customs Currency Exchange Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HM Revenue and Customs Currency Exchange Dimension` (
  `Date` date NOT NULL,
  `Currency Pair` varchar(6) CHARACTER SET utf8 NOT NULL,
  `Exchange` float NOT NULL DEFAULT '1',
  UNIQUE KEY `Date_2` (`Date`,`Currency Pair`),
  KEY `Date` (`Date`),
  KEY `Currency Pair` (`Currency Pair`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `History Currency Exchange Dimension`
--

DROP TABLE IF EXISTS `History Currency Exchange Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `History Currency Exchange Dimension` (
  `Date` date NOT NULL,
  `Currency Pair` varchar(6) NOT NULL,
  `Exchange` double NOT NULL,
  UNIQUE KEY `Date` (`Date`,`Currency Pair`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `IP Geo Dimension`
--

DROP TABLE IF EXISTS `IP Geo Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `IP Geo Dimension` (
  `IP Geo A` mediumint(8) DEFAULT NULL,
  `IP Geo B` mediumint(8) DEFAULT NULL,
  `IP Geo C` mediumint(8) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `Country Code` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `IP Geolocation`
--

DROP TABLE IF EXISTS `IP Geolocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `IP Geolocation` (
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Latitude` float DEFAULT NULL,
  `Longitude` float DEFAULT NULL,
  `Location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Country Code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Region Code` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Region Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Town` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Postal Code` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`IP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Incoterm Dimension`
--

DROP TABLE IF EXISTS `Incoterm Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Incoterm Dimension` (
  `Incoterm Code` varchar(3) NOT NULL,
  `Incoterm Name` varchar(255) NOT NULL,
  `Incoterm Transport Type` enum('All','Sea') NOT NULL,
  PRIMARY KEY (`Incoterm Code`),
  KEY `Incoterm Transport Type` (`Incoterm Transport Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Language Country Bridge`
--

DROP TABLE IF EXISTS `Language Country Bridge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Language Country Bridge` (
  `Language Key` mediumint(8) unsigned NOT NULL,
  `Country Key` mediumint(8) unsigned NOT NULL,
  `Locale Code` varchar(16) NOT NULL,
  `Language Code` varchar(2) NOT NULL,
  `Country 2 Alpha Code` varchar(2) NOT NULL,
  `Mother Tongle Population` float NOT NULL,
  `Fluid Population` float NOT NULL,
  `EAI Implemantation` float NOT NULL,
  `EAI Access` tinyint(1) NOT NULL,
  `EAI Locale Code` varchar(16) NOT NULL,
  KEY `Language Key` (`Language Key`,`Country Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Language Dimension`
--

DROP TABLE IF EXISTS `Language Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Language Dimension` (
  `Language ISO 639-1 Code` varchar(2) NOT NULL,
  `Language ISO 639-3 Code` varchar(3) NOT NULL,
  `Language ISO 639-2B Code` varchar(3) NOT NULL,
  `Language ISO 639-2T Code` text NOT NULL,
  `Language Name` varchar(60) NOT NULL,
  `Language Original Name` varchar(60) DEFAULT NULL,
  `Language Scope` char(1) NOT NULL,
  `Language Type` char(1) NOT NULL,
  `Language Comment` varchar(255) NOT NULL,
  PRIMARY KEY (`Language ISO 639-3 Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Military Base Dimension`
--

DROP TABLE IF EXISTS `Military Base Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Military Base Dimension` (
  `Military Base Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Military Base Country Key` mediumint(8) unsigned DEFAULT NULL,
  `Military Base Geographic Country Key` mediumint(8) unsigned DEFAULT NULL,
  `Military Base Name` varchar(255) DEFAULT NULL,
  `Military Base Location` varchar(255) DEFAULT NULL,
  `Military Base Type` varchar(255) DEFAULT NULL,
  `Military Base Post Code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Military Base Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Month Dimension`
--

DROP TABLE IF EXISTS `Month Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Month Dimension` (
  `Year Month` mediumint(8) unsigned NOT NULL,
  `First Day` date NOT NULL,
  `Last Day` date NOT NULL,
  `Month` tinyint(4) NOT NULL,
  PRIMARY KEY (`Year Month`,`First Day`),
  UNIQUE KEY `Last Day` (`Last Day`),
  KEY `First Day` (`First Day`),
  KEY `Year Month` (`Year Month`),
  KEY `Month` (`Month`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Neighbour Dimension`
--

DROP TABLE IF EXISTS `Neighbour Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Neighbour Dimension` (
  `Neighbour A Code` varchar(16) NOT NULL,
  `Neighbour B Code` varchar(16) NOT NULL,
  `Neighbour A Level` enum('Country','Country First Division','Country Second Division','Country Third Division','Country Forth Division','Country Fifth Division') NOT NULL,
  `Neighbour B Level` enum('Country','Country First Division','Country Second Division','Country Third Division','Country Forth Division','Country Fifth Division') NOT NULL,
  PRIMARY KEY (`Neighbour A Code`,`Neighbour B Code`),
  KEY `Neighbour A Level` (`Neighbour A Level`),
  KEY `Neighbour B Level` (`Neighbour B Level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Quarter Dimension`
--

DROP TABLE IF EXISTS `Quarter Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Quarter Dimension` (
  `Year Quarter` varchar(5) NOT NULL,
  `First Day` date NOT NULL,
  PRIMARY KEY (`Year Quarter`,`First Day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Salutation Dimension`
--

DROP TABLE IF EXISTS `Salutation Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Salutation Dimension` (
  `Salutation Key` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Salutation` varchar(10) NOT NULL,
  `Language Code` varchar(5) NOT NULL DEFAULT 'en',
  `Gender` enum('Male','Female','Unknown') NOT NULL DEFAULT 'Unknown',
  `Relevance` smallint(5) unsigned NOT NULL DEFAULT '2',
  PRIMARY KEY (`Salutation Key`),
  UNIQUE KEY `u` (`Salutation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Surname Dimension`
--

DROP TABLE IF EXISTS `Surname Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Surname Dimension` (
  `Surname Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Surname` varchar(255) NOT NULL,
  PRIMARY KEY (`Surname Key`),
  UNIQUE KEY `u` (`Surname`),
  KEY `xx` (`Surname`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Tax Category Dimension`
--

DROP TABLE IF EXISTS `Tax Category Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tax Category Dimension` (
  `Tax Category Key` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Tax Category Type` enum('Standard','Zero','Unknown','Reduced','Exempt','Outside','EU_VTC') NOT NULL DEFAULT 'Standard',
  `Tax Category Type Name` varchar(64) NOT NULL,
  `Tax Category Code` varchar(3) NOT NULL,
  `Tax Category Name` varchar(255) NOT NULL,
  `Tax Category Rate` decimal(8,6) NOT NULL,
  `Composite` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Composite Metadata` varchar(255) DEFAULT NULL,
  `Tax Category Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Tax Category Country Code` varchar(3) NOT NULL DEFAULT 'UNK',
  `Tax Category Default` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Tax Category Key`),
  UNIQUE KEY `Tax Category Code` (`Tax Category Code`,`Tax Category Country Code`),
  KEY `Tax Category Type` (`Tax Category Type Name`),
  KEY `Composite` (`Composite`),
  KEY `Tax Category Active` (`Tax Category Active`),
  KEY `Tax Category Country Code` (`Tax Category Country Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Telephone Local Code`
--

DROP TABLE IF EXISTS `Telephone Local Code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Telephone Local Code` (
  `Telephone Local Code Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Telephone Local Code` varchar(8) NOT NULL,
  `Telephone Local Code Location` varchar(60) NOT NULL,
  `Telephone Local Code Country Code` varchar(3) NOT NULL,
  PRIMARY KEY (`Telephone Local Code Key`),
  UNIQUE KEY `Telephone Local Code` (`Telephone Local Code`,`Telephone Local Code Country Code`,`Telephone Local Code Location`),
  KEY `Telephone Local Code Country Code` (`Telephone Local Code Country Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Town Dimension`
--

DROP TABLE IF EXISTS `Town Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Town Dimension` (
  `Geography Key` mediumint(7) unsigned NOT NULL COMMENT 'integer id of record in geonames database',
  `Town Name` varchar(200) NOT NULL COMMENT 'name of geographical point (utf8)',
  `Town ASCII Name` varchar(200) DEFAULT NULL COMMENT 'name of geographical point in plain ascii characters',
  `Town Latitude` double NOT NULL COMMENT 'latitude in decimal degrees',
  `Town Longitude` double NOT NULL COMMENT 'longitude in decimal degrees',
  `Town Feature Code` varchar(10) DEFAULT NULL COMMENT 'see http://www.geonames.org/export/codes.html',
  `Country Code` char(3) DEFAULT NULL,
  `Country 2 Alpha Code` char(2) DEFAULT NULL COMMENT 'ISO-3166 2-letter country code',
  `Town Alternate Country Codes` varchar(60) DEFAULT NULL COMMENT 'alternate country codes, comma separated, ISO-3166 2-letter country code',
  `Country First Division Code` varchar(20) DEFAULT NULL COMMENT 'fipscode (subject to change to iso code), isocode for the us and ch, see file admin1Codes.txt for display names of this code',
  `Country Second Division Code` varchar(80) DEFAULT NULL COMMENT 'code for the second administrative division, a county in the US, see file admin2Codes.txt',
  `Country Third Division Code` varchar(20) DEFAULT NULL COMMENT 'code for third level administrative division',
  `Country Forth Division Code` varchar(20) DEFAULT NULL COMMENT 'code for fourth level administrative division',
  `Country Fifth Division Code` varchar(16) DEFAULT NULL,
  `Town Population` bigint(20) unsigned NOT NULL,
  `Town Elevation` int(11) unsigned DEFAULT NULL COMMENT 'in meter',
  `Town Average Elevation` int(11) unsigned DEFAULT NULL COMMENT 'average elevation of 30''x30'' (ca 900mx900m) area in meters',
  `Town Timezone` varchar(255) DEFAULT NULL COMMENT 'the timezone id (see file timeZone.txt',
  `Town Modification Date` date NOT NULL COMMENT 'date of last modification',
  PRIMARY KEY (`Geography Key`),
  KEY `Town Feature Code` (`Town Feature Code`),
  KEY `Town 2 Alpha Country Code` (`Country 2 Alpha Code`),
  KEY `Town Name` (`Town Name`(12)),
  KEY `Town Country Primary Division` (`Country First Division Code`(6)),
  KEY `Country Code` (`Country Code`),
  KEY `Country Second Division Code` (`Country Second Division Code`(12)),
  KEY `Country Third Division Code` (`Country Third Division Code`(12)),
  KEY `Country Forth Division Code` (`Country Forth Division Code`(12)),
  KEY `Country Fifth Division Code` (`Country Fifth Division Code`(12))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Agent`
--

DROP TABLE IF EXISTS `User Agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Agent` (
  `User Agent Hash` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `User Agent` text COLLATE utf8_unicode_ci NOT NULL,
  `Software` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Software Details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Device` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `OS Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Icon` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Data` text COLLATE utf8_unicode_ci,
  `Status` enum('OK','Error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'OK',
  PRIMARY KEY (`User Agent Hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Agent Dimension`
--

DROP TABLE IF EXISTS `User Agent Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Agent Dimension` (
  `User Agent Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `User Agent Name` varchar(255) DEFAULT NULL,
  `User Agent String` text NOT NULL,
  `User Agent Description` text,
  `User Agent Type` enum('Browser','Bot','Spam','Proxy','Other','Librarie','Cloud Platform','Feed Reader','Offline Browser','Console','LineChecker','Mobile Browser','Validator','E-Mail Collector') DEFAULT NULL,
  `User Agent Family` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`User Agent Key`),
  UNIQUE KEY `User Agent String_2` (`User Agent String`(300)),
  KEY `User Agent String` (`User Agent String`(64)),
  KEY `User Agent Type` (`User Agent Type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Week Dimension`
--

DROP TABLE IF EXISTS `Week Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Week Dimension` (
  `Year Week` varchar(6) NOT NULL,
  `First Day` date NOT NULL,
  `Last Day` date NOT NULL,
  `Year Week Normalized` varchar(6) NOT NULL,
  `Week Normalized` smallint(4) NOT NULL,
  `Normalized Median Day` date NOT NULL,
  `Normalized Last Day` date NOT NULL,
  `Year` smallint(6) NOT NULL,
  `Week` smallint(6) NOT NULL,
  PRIMARY KEY (`Year Week`,`First Day`),
  KEY `Year` (`Year`,`Week`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `World Region Dimension`
--

DROP TABLE IF EXISTS `World Region Dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `World Region Dimension` (
  `World Region Code` varchar(4) NOT NULL,
  `World Region` varchar(255) NOT NULL,
  `AMMAP Settings` text,
  PRIMARY KEY (`World Region Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-09-30  7:24:00
