-- MySQL dump 10.13  Distrib 5.6.31, for osx10.11 (x86_64)
--
-- Host: localhost    Database: aurora
-- ------------------------------------------------------
-- Server version	5.6.31
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `API Key Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `API Key Dimension` (
  `API Key Key` mediumint(8) unsigned NOT NULL,
  `API Key Code` varchar(8) NOT NULL,
  `API Key Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `API Key User Key` mediumint(8) unsigned NOT NULL,
  `API Key Valid From` datetime DEFAULT NULL,
  `API Key Valid To` datetime DEFAULT NULL,
  `API Key Hash` varchar(255) NOT NULL,
  `API Key Allowed Requests per Hour` mediumint(8) unsigned NOT NULL DEFAULT '3600',
  `API Key Allowed IP` varchar(255) DEFAULT NULL,
  `API Key Scope` varchar(64) NOT NULL,
  `API Key Metadata` text NOT NULL,
  `API Key Successful Requests` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `API Key Failed Attempt Requests` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `API Key Failed Access Request` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `API Key Failed IP Requests` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `API Key Failed Time Limit Requests` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `API Key Failed Operation Request` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `API Key Last Request Date` datetime DEFAULT NULL,
  PRIMARY KEY (`API Key Key`),
  KEY `API Key User Key` (`API Key User Key`),
  KEY `API Key Code` (`API Key Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `API Request Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `API Request Dimension` (
  `API Key Key` mediumint(9) NOT NULL,
  `Date` datetime NOT NULL,
  `HTTP Method` enum('POST','GET','PUT','DELETE') NOT NULL,
  `Response` enum('OK','Fail_Attempt','Fail_Access','Fail_TimeLimit','Fail_Operation','Fail_IP') NOT NULL,
  `Response Code` varchar(8) NOT NULL,
  `IP` varchar(64) NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Account Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Account Dimension` (
  `Account Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Account Code` varchar(16) NOT NULL,
  `Account Name` varchar(245) NOT NULL,
  `Account Stores` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Account Websites` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Account Warehouses` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Account Employees` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Account Menu Label` varchar(8) NOT NULL,
  `Account Country Code` varchar(3) NOT NULL DEFAULT 'GBR',
  `Account Country 2 Alpha Code` varchar(2) NOT NULL DEFAULT 'GB',
  `Account Locale` varchar(5) NOT NULL DEFAULT 'en_GB',
  `Account Currency` varchar(3) NOT NULL DEFAULT 'GBP',
  `Account Currency Symbol` varchar(16) NOT NULL DEFAULT '£',
  `Account Timezone` varchar(64) NOT NULL DEFAULT 'Europe/London',
  `Account Company Key` mediumint(8) unsigned NOT NULL,
  `Inikoo Public URL` varchar(255) NOT NULL,
  `Inikoo Version` varchar(32) NOT NULL,
  `Short Message` varchar(512) NOT NULL,
  `SR Category Key` mediumint(9) DEFAULT NULL,
  `Account Invoice Public ID Format` varchar(32) NOT NULL DEFAULT '%05d',
  `Account Invoice Last Invoice Public ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Account Invoice Last Refund Public ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Account Refund Public ID Format` varchar(32) NOT NULL DEFAULT '%05d',
  `Apply Tax Method` enum('Per Item','Per Total') NOT NULL DEFAULT 'Per Item',
  `Account HR Start Year` varchar(5) NOT NULL DEFAULT '01-01',
  `Account State` enum('InProcess','Active') NOT NULL DEFAULT 'InProcess',
  `Account Creation Date` datetime DEFAULT NULL,
  `Account Setup Metadata` text,
  `Account System Public URL` varchar(255) NOT NULL,
  `Account System Version` varchar(32) NOT NULL,
  `Account SR Category Key` mediumint(8) unsigned DEFAULT NULL,
  `Account Apply Tax Method` enum('Per Item','Per Total') NOT NULL DEFAULT 'Per Item',
  `Account Part Family Category Key` mediumint(9) DEFAULT NULL,
  `Account Suppliers Terms and Conditions` text,
  PRIMARY KEY (`Account Key`),
  KEY `Account Currency` (`Account Currency`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Account History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Account History Bridge` (
  `Account Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes',
  PRIMARY KEY (`Account Key`,`History Key`),
  KEY `Account Key` (`Account Key`),
  KEY `History Key` (`History Key`),
  KEY `Deletable` (`Deletable`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Address Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Address Bridge` (
  `Address Key` mediumint(8) unsigned NOT NULL,
  `Subject Type` enum('Customer','Contact','Staff','Company','Supplier','User','Store') NOT NULL DEFAULT 'Contact',
  `Subject Key` mediumint(8) unsigned NOT NULL,
  `Address Type` enum('Work','Home','Other','Shop','Unknown','Warehouse','Office') NOT NULL DEFAULT 'Unknown',
  `Address Function` enum('Shipping','Contact','Billing','Other','Unknown','Collect') NOT NULL DEFAULT 'Contact',
  `Is Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Is Main` enum('Yes','No') NOT NULL DEFAULT 'No',
  UNIQUE KEY `u` (`Address Key`,`Subject Key`,`Subject Type`,`Address Function`,`Address Type`),
  KEY `Address Key` (`Address Key`),
  KEY `FK` (`Subject Key`),
  KEY `Is Active` (`Is Active`),
  KEY `Is Main` (`Is Main`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Address Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Address Dimension` (
  `Address Key` mediumint(8) unsigned NOT NULL,
  `Address Description` varchar(255) NOT NULL,
  `Address Street Number` varchar(255) DEFAULT NULL,
  `Address Street Number Position` enum('Left','Right') NOT NULL DEFAULT 'Left',
  `Address Internal` varchar(255) DEFAULT NULL COMMENT 'Office, Apartment',
  `Address Building` varchar(255) DEFAULT NULL,
  `Address Street Name` varchar(255) DEFAULT NULL,
  `Address Street Type` varchar(255) DEFAULT NULL,
  `Address Street Direction` varchar(255) DEFAULT NULL,
  `Address Town Second Division` varchar(255) DEFAULT NULL,
  `Address Town First Division` varchar(255) DEFAULT NULL,
  `Address Town` varchar(255) DEFAULT NULL,
  `Address Town Key` mediumint(8) unsigned DEFAULT NULL,
  `Address Country First Division` varchar(255) DEFAULT NULL,
  `Address Country First Division Code` varchar(16) DEFAULT '0',
  `Address Country Second Division` varchar(255) DEFAULT NULL,
  `Address Country Second Division Code` varchar(16) DEFAULT '',
  `Address Country Third Division` varchar(255) DEFAULT NULL,
  `Address Country Third Division Code` varchar(16) DEFAULT '0',
  `Address Country Key` smallint(6) NOT NULL,
  `Address Country Name` varchar(255) DEFAULT NULL,
  `Address Country Code` varchar(3) DEFAULT NULL,
  `Address Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Address World Region` varchar(255) DEFAULT NULL,
  `Address Continent` enum('Unkown','America','Africa','Asia','Europe','Oceania','Antarctica') DEFAULT NULL,
  `Address Postal Code` varchar(255) DEFAULT NULL,
  `Address First Postal Code` varchar(255) DEFAULT NULL,
  `Address Second Postal Code` varchar(255) DEFAULT NULL,
  `Address Postal Code Separator` varchar(1) DEFAULT NULL,
  `Address Fuzzy` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Address Fuzzy Type` enum('All','Country','World Region','Town','Street','Post Code') DEFAULT NULL,
  `Address Location` varchar(255) DEFAULT NULL,
  `Address Data Creation` datetime NOT NULL,
  `Address Data Last Update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Military Address` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Military Installation Address` varchar(255) DEFAULT NULL,
  `Military Installation Name` varchar(255) DEFAULT NULL,
  `Military Installation Type` varchar(255) DEFAULT NULL,
  `Military Installation Location` varchar(255) DEFAULT NULL,
  `Military Installation Country Key` mediumint(8) unsigned DEFAULT '0',
  `Address Plain` text NOT NULL,
  `Address Main Telephone Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Address Main Plain Telephone` varchar(64) NOT NULL,
  `Address Main XHTML Telephone` varchar(64) NOT NULL,
  `Address Main FAX Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Address Main Plain FAX` varchar(64) NOT NULL,
  `Address Main XHTML FAX` varchar(64) NOT NULL,
  `Address Contact` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Address Key`),
  KEY `Militaty Address` (`Military Address`),
  KEY `Fuzzy Address` (`Address Fuzzy`),
  KEY `Address Fuzzy Type` (`Address Fuzzy Type`),
  KEY `Address Country Key` (`Address Country Key`),
  KEY `Address Postal Code` (`Address Postal Code`(12)),
  KEY `Address Town` (`Address Town`(30)),
  KEY `Address Country Code` (`Address Country Code`),
  KEY `Address Location` (`Address Location`(64)),
  KEY `Address Continent` (`Address Continent`),
  KEY `Address World Region` (`Address World Region`(64)),
  KEY `Address Main Telephone Key` (`Address Main Telephone Key`),
  KEY `Address Main FAX Key` (`Address Main FAX Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Address Telecom Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Address Telecom Bridge` (
  `Address Key` mediumint(8) unsigned NOT NULL,
  `Telecom Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Address Key`,`Telecom Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Agent Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Agent Dimension` (
  `Agent Key` mediumint(8) unsigned NOT NULL,
  `Agent Products Origin Country Code` varchar(3) DEFAULT NULL,
  `Agent Code` varchar(16) NOT NULL,
  `Agent Name` varchar(255) DEFAULT NULL,
  `Agent File As` varchar(255) NOT NULL,
  `Agent Fiscal Name` varchar(255) DEFAULT NULL,
  `Agent Main XHTML Address` varchar(1024) DEFAULT '',
  `Agent Main Plain Address` varchar(1024) DEFAULT NULL,
  `Agent Main Country Key` smallint(5) unsigned NOT NULL,
  `Agent Main Country Code` varchar(3) NOT NULL,
  `Agent Main Country` varchar(255) NOT NULL,
  `Agent Main Location` varchar(255) DEFAULT NULL,
  `Agent Main XHTML Email` varchar(1024) DEFAULT NULL,
  `Agent Main Plain Email` varchar(255) NOT NULL,
  `Agent Location` varchar(255) DEFAULT NULL,
  `Agent Contact Address Recipient` varchar(255) DEFAULT NULL,
  `Agent Contact Address Organization` varchar(255) DEFAULT NULL,
  `Agent Contact Address Line 1` varchar(255) DEFAULT NULL,
  `Agent Contact Address Line 2` varchar(255) DEFAULT NULL,
  `Agent Contact Address Sorting Code` varchar(255) DEFAULT NULL,
  `Agent Contact Address Postal Code` varchar(255) DEFAULT NULL,
  `Agent Contact Address Dependent Locality` varchar(255) DEFAULT NULL,
  `Agent Contact Address Locality` varchar(255) DEFAULT NULL,
  `Agent Contact Address Administrative Area` varchar(255) DEFAULT NULL,
  `Agent Contact Address Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Agent Contact Address Checksum` varchar(64) DEFAULT NULL,
  `Agent Contact Address Formatted` text,
  `Agent Contact Address Postal Label` text,
  `Agent Main Email Key` mediumint(8) unsigned DEFAULT NULL,
  `Agent Company Name` varchar(255) DEFAULT NULL,
  `Agent Main Contact Name` varchar(255) DEFAULT NULL,
  `Agent QQ` varchar(64) DEFAULT NULL,
  `Agent Preferred Contact Number` enum('Mobile','Telephone') NOT NULL DEFAULT 'Mobile',
  `Agent Preferred Contact Number Formatted Number` varchar(255) DEFAULT NULL,
  `Agent Main Plain Mobile` varchar(255) DEFAULT NULL,
  `Agent Main XHTML Mobile` varchar(255) DEFAULT NULL,
  `Agent Main XHTML Telephone` varchar(255) DEFAULT NULL,
  `Agent Main Telephone Key` mediumint(8) unsigned DEFAULT NULL,
  `Agent Main Plain Telephone` varchar(255) NOT NULL,
  `Agent Main XHTML FAX` varchar(100) DEFAULT NULL,
  `Agent Main FAX Key` mediumint(8) unsigned DEFAULT NULL,
  `Agent Main Plain FAX` varchar(100) NOT NULL DEFAULT '',
  `Agent Website` varchar(255) NOT NULL,
  `Agent Default Incoterm` varchar(3) DEFAULT NULL,
  `Agent Default Port of Export` varchar(255) DEFAULT NULL,
  `Agent Default Port of Import` varchar(255) DEFAULT NULL,
  `Agent Default PO Terms and Conditions` text,
  `Agent Show Warehouse TC in PO` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Agent Active Agent Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Discontinued Agent Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Unknown Stock Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Agent For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Not For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent To Be Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Average Delivery Days` float DEFAULT NULL,
  `Agent Number Suppliers` smallint(5) unsigned NOT NULL,
  `Agent Purchase Orders` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Open Purchase Orders` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Purchase Delivery Notes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Purchase Invoices` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Valid From` datetime DEFAULT NULL,
  `Agent Valid To` datetime DEFAULT NULL,
  `Agent Sticky Note` text,
  `Agent Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Agent Stock Value` decimal(14,2) DEFAULT NULL,
  `Agent Default Currency Code` varchar(3) NOT NULL DEFAULT 'USD',
  `Agent Number Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Agent Number Surplus Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Agent Number Optimal Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Agent Number Low Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Agent Number Critical Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Agent Number Out Of Stock Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Agent Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Agent History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Agent History Bridge` (
  `Agent Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Asset') NOT NULL,
  PRIMARY KEY (`Agent Key`,`History Key`),
  KEY `Agent Key` (`Agent Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Agent Other Email Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Agent Other Email Dimension` (
  `Agent Other Email Key` mediumint(8) unsigned NOT NULL,
  `Agent Other Email Agent Key` mediumint(8) unsigned NOT NULL,
  `Agent Other Email Email` varchar(255) NOT NULL,
  `Agent Other Email Label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Agent Other Email Key`),
  KEY `Agent Other Email Agent Key` (`Agent Other Email Agent Key`,`Agent Other Email Email`),
  KEY `Agent Other Email Email` (`Agent Other Email Email`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Agent Other Telephone Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Agent Other Telephone Dimension` (
  `Agent Other Telephone Key` mediumint(8) unsigned NOT NULL,
  `Agent Other Telephone Agent Key` mediumint(8) unsigned NOT NULL,
  `Agent Other Telephone Number` varchar(255) NOT NULL,
  `Agent Other Telephone Formatted Number` varchar(255) NOT NULL,
  `Agent Other Telephone Label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Agent Other Telephone Key`),
  KEY `Agent Other Telephone Agent Key` (`Agent Other Telephone Agent Key`,`Agent Other Telephone Number`),
  KEY `Agent Other Telephone Number` (`Agent Other Telephone Number`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Agent Purchase Order Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Agent Purchase Order Dimension` (
  `Agent Purchase Order Key` mediumint(8) unsigned NOT NULL,
  `Agent Purchase Order Agent Key` mediumint(8) unsigned DEFAULT NULL,
  `Agent Purchase Order Supplier Key` mediumint(8) unsigned DEFAULT NULL,
  `Agent Purchase Order Agent Address` text,
  `Agent Purchase Order Supplier Address` text,
  `Agent Purchase Order Creation Date` datetime DEFAULT NULL COMMENT 'Date when the purchase order where first placed',
  `Agent Purchase Order Submitted Date` datetime DEFAULT NULL,
  `Agent Purchase Order Estimated Receiving Date` datetime DEFAULT NULL,
  `Agent Purchase Order Received Date` datetime DEFAULT NULL,
  `Agent Purchase Order Checked Date` datetime DEFAULT NULL,
  `Agent Purchase Order Dispatched Date` datetime DEFAULT NULL,
  `Agent Purchase Order Cancelled Date` datetime DEFAULT NULL,
  `Agent Purchase Order Last Updated Date` datetime DEFAULT NULL COMMENT 'Lastest Date when Adding/Modify Purchase Order Transaction or Data',
  `Agent Purchase Order Public ID` varchar(255) DEFAULT NULL,
  `Agent Purchase Order File As` varchar(255) DEFAULT NULL,
  `Agent Purchase Order State` enum('InProcess','Submitted','Received','Checked','Dispatched','Cancelled') NOT NULL DEFAULT 'InProcess',
  `Agent Purchase Order Number Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Purchase Order Ordered Number Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Purchase Order Number Supplier Delivery Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Purchase Order Number Placed Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Purchase Order Items Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Items Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Shipping Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Charges Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Net Credited Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Total Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Shipping Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Charges Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Tax Credited Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Total Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Total Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Total To Pay Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Agent Purchase Order Supplier Message` text,
  `Agent Purchase Order Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Agent Purchase Order Currency Exchange` float NOT NULL DEFAULT '1',
  `Agent Purchase Order Cancel Note` text,
  `Agent Purchase Order Terms and Conditions` text,
  `Agent Purchase Order Weight` float DEFAULT NULL,
  `Agent Purchase Order CBM` float DEFAULT NULL,
  `Agent Purchase Order Missing Weights` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Purchase Order Missing CBMs` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Agent Purchase Order Metadata` text,
  PRIMARY KEY (`Agent Purchase Order Key`),
  KEY `Agent Purchase Order Supplier Key` (`Agent Purchase Order Supplier Key`),
  KEY `Agent Purchase Order Agent Key` (`Agent Purchase Order Agent Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Agent Supplier Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Agent Supplier Bridge` (
  `Agent Supplier Agent Key` mediumint(8) unsigned NOT NULL,
  `Agent Supplier Supplier Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Agent Supplier Agent Key`,`Agent Supplier Supplier Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Attachment Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Attachment Bridge` (
  `Attachment Bridge Key` mediumint(8) unsigned NOT NULL,
  `Attachment Key` mediumint(8) unsigned NOT NULL,
  `Subject` enum('Part','Staff','Customer Communications','Customer History Attachment','Product History Attachment','Part History Attachment','Part MSDS','Product MSDS','Supplier Product MSDS','Product Info Sheet','Purchase Order History Attachment','Purchase Order','Supplier Delivery Note History Attachment','Supplier Delivery Note','Supplier Invoice History Attachment','Supplier Invoice','Order Note History Attachment','Delivery Note History Attachment','Invoice History Attachment','Supplier') NOT NULL,
  `Subject Key` int(10) unsigned NOT NULL,
  `Attachment Subject Type` enum('Other','CV','Contract','Invoice','PurchaseOrder','Catalogue','MSDS') NOT NULL DEFAULT 'Other',
  `Attachment Caption` text,
  `Attachment File Original Name` varchar(255) DEFAULT NULL,
  `Attachment Public` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Attachment Bridge Key`),
  UNIQUE KEY `Attachment Key_2` (`Attachment Key`,`Subject`,`Subject Key`),
  KEY `Attachment Key` (`Attachment Key`),
  KEY `Subject` (`Subject`,`Subject Key`),
  KEY `Attachment Public` (`Attachment Public`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Attachment Bridge History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Attachment Bridge History Bridge` (
  `Attachment Bridge Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') NOT NULL,
  PRIMARY KEY (`Attachment Bridge Key`,`History Key`),
  KEY `Attachment Bridge Key` (`Attachment Bridge Key`),
  KEY `Attachment Bridge Key_2` (`Attachment Bridge Key`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Attachment Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Attachment Dimension` (
  `Attachment Key` mediumint(8) unsigned NOT NULL,
  `Attachment MIME Type` varchar(255) NOT NULL,
  `Attachment Type` enum('PDF','Image','Compresed','Spreadsheet','Text','Word','Other') NOT NULL DEFAULT 'Other',
  `Attachment Data` longblob NOT NULL,
  `Attachment File Checksum` varchar(32) NOT NULL,
  `Attachment File Size` mediumint(8) unsigned NOT NULL,
  `Attachment Thumbnail Image Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Attachment Key`),
  UNIQUE KEY `Attachment Checksum` (`Attachment File Checksum`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Audit Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Audit Dimension` (
  `Data Audit Key` mediumint(8) unsigned NOT NULL,
  `Data Audit Error Free ETL` enum('Yes','No') NOT NULL,
  `Data Audit Missing Data` enum('Yes','No') NOT NULL,
  `Data Audit ETL Description` text NOT NULL,
  `Data Audit ETL Group` varchar(255) NOT NULL,
  `Data Audit Confidence Score` float NOT NULL COMMENT 'Subjetive estimate of the accurace of the data, range for 0-1',
  `Data Audit ETL Software` varchar(1024) NOT NULL COMMENT 'Staging Extract-Transformation-load (ETL) software',
  `Data Source` varchar(255) NOT NULL,
  PRIMARY KEY (`Data Audit Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Barcode Asset Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Barcode Asset Bridge` (
  `Barcode Asset Barcode Key` mediumint(9) DEFAULT NULL,
  `Barcode Asset Status` enum('Assigned','Historic') NOT NULL DEFAULT 'Assigned',
  `Barcode Asset Type` enum('Product','Part') DEFAULT NULL,
  `Barcode Asset Key` mediumint(9) DEFAULT NULL,
  `Barcode Asset Assigned Date` datetime DEFAULT NULL,
  `Barcode Asset Withdrawn Date` datetime DEFAULT NULL,
  KEY `Barcode Asset Status` (`Barcode Asset Status`),
  KEY `Barcode Asset Type` (`Barcode Asset Type`,`Barcode Asset Key`,`Barcode Asset Status`,`Barcode Asset Barcode Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Barcode Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Barcode Deleted Dimension` (
  `Barcode Deleted Key` mediumint(9) NOT NULL,
  `Barcode Deleted Type` enum('EAN') NOT NULL DEFAULT 'EAN',
  `Barcode Deleted Number` bigint(20) unsigned NOT NULL,
  `Barcode Deleted Sticky Note` text,
  PRIMARY KEY (`Barcode Deleted Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Barcode Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Barcode Dimension` (
  `Barcode Key` mediumint(9) NOT NULL,
  `Barcode Status` enum('Available','Used','Reserved') NOT NULL DEFAULT 'Available',
  `Barcode Type` enum('EAN') NOT NULL DEFAULT 'EAN',
  `Barcode Number` bigint(20) unsigned NOT NULL,
  `Barcode Used From` datetime DEFAULT NULL,
  `Barcode Sticky Note` text,
  PRIMARY KEY (`Barcode Key`),
  UNIQUE KEY `Barcode Number` (`Barcode Number`),
  KEY `Barcode Status` (`Barcode Status`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Barcode History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Barcode History Bridge` (
  `Barcode Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Asset') NOT NULL,
  PRIMARY KEY (`Barcode Key`,`History Key`),
  KEY `Barcode Key` (`Barcode Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Billing To Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Billing To Dimension` (
  `Billing To Key` mediumint(8) unsigned NOT NULL,
  `Billing To Contact Name` varchar(255) DEFAULT NULL,
  `Billing To Company Name` varchar(255) DEFAULT NULL,
  `Billing To Line 1` varchar(255) NOT NULL,
  `Billing To Line 2` varchar(255) NOT NULL,
  `Billing To Line 3` varchar(255) NOT NULL,
  `Billing To Town` varchar(255) NOT NULL,
  `Billing To Line 4` varchar(255) NOT NULL,
  `Billing To Postal Code` varchar(20) NOT NULL,
  `Billing To Country Name` varchar(80) NOT NULL,
  `Billing To XHTML Address` text,
  `Billing To Telephone` varchar(255) DEFAULT NULL,
  `Billing To Email` varchar(255) DEFAULT NULL,
  `Billing To Country Key` mediumint(8) unsigned DEFAULT NULL,
  `Billing To Country Code` varchar(3) NOT NULL DEFAULT 'UNK',
  `Billing To Country 2 Alpha Code` varchar(3) NOT NULL DEFAULT 'XX',
  PRIMARY KEY (`Billing To Key`),
  KEY `Billing To Country Key` (`Billing To Country Key`),
  KEY `Billing To Country Code` (`Billing To Country Code`),
  KEY `Billing To Country 2 Alpha Code` (`Billing To Country 2 Alpha Code`),
  KEY `Billing To Postal Code` (`Billing To Postal Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Campaign Deal Schema`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Campaign Deal Schema` (
  `Deal Schema Key` mediumint(8) unsigned NOT NULL,
  `Campaign Key` mediumint(8) unsigned NOT NULL,
  `Deal Name` varchar(255) NOT NULL,
  `Deal Trigger` enum('Product','Order','Family') NOT NULL,
  `Deal Allowance Type` enum('Percentage Off','Get Free','Waive Charge','Get Same Free') DEFAULT NULL,
  `Deal Allowance Target` enum('Product','Order','Shipping','Charge','Family','Department') DEFAULT NULL,
  `Deal Allowance Description` varchar(255) DEFAULT NULL,
  `Deal Allowance Lock` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Deal Allowance Metadata` varchar(255) NOT NULL,
  `Deal Replace` enum('same target','same tigger','same target and tigger','deal','none') NOT NULL DEFAULT 'none',
  `Deal Replace Metadata` varchar(4096) DEFAULT NULL,
  PRIMARY KEY (`Deal Schema Key`),
  KEY `Campaign Key` (`Campaign Key`),
  KEY `Deal Name` (`Deal Name`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Category Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Category Bridge` (
  `Category Key` mediumint(8) unsigned NOT NULL,
  `Subject` enum('Product','Supplier','Customer','Family','Invoice','Part','Category') NOT NULL,
  `Subject Key` mediumint(8) unsigned NOT NULL,
  `Other Note` varchar(255) DEFAULT NULL,
  `Category Head Key` mediumint(8) unsigned DEFAULT NULL,
  UNIQUE KEY `Category Key` (`Category Key`,`Subject`,`Subject Key`),
  KEY `Subject` (`Subject`),
  KEY `Subject Key` (`Subject Key`),
  KEY `Category Head Key` (`Category Head Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Category Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Category Deleted Dimension` (
  `Category Deleted Key` smallint(11) unsigned NOT NULL,
  `Category Deleted Branch Type` enum('Root','Head','Node') NOT NULL DEFAULT 'Node',
  `Category Deleted Store Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Deleted Warehouse Key` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Category Deleted XHTML Branch Tree` varchar(1024) DEFAULT NULL,
  `Category Deleted Plain Branch Tree` varchar(1024) DEFAULT NULL,
  `Category Deleted Deep` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Category Deleted Children` mediumint(9) NOT NULL DEFAULT '0',
  `Category Deleted Code` varchar(64) NOT NULL,
  `Category Deleted Label` varchar(255) NOT NULL,
  `Category Deleted Subject` enum('Product','Supplier','Customer','Family','Invoice','Part') NOT NULL DEFAULT 'Product',
  `Category Deleted Subject Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Deleted Number Subjects` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Deleted Date` datetime NOT NULL,
  PRIMARY KEY (`Category Deleted Key`),
  KEY `Category Deleted Subject` (`Category Deleted Subject`),
  KEY `Category Deleted Name` (`Category Deleted Code`),
  KEY `Category Deleted Store Key` (`Category Deleted Store Key`),
  KEY `Category Deleted Warehouse Key` (`Category Deleted Warehouse Key`),
  KEY `Category Deleted Branch Type` (`Category Deleted Branch Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Category Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Category Dimension` (
  `Category Key` smallint(11) unsigned NOT NULL,
  `Category Branch Type` enum('Root','Head','Node') NOT NULL DEFAULT 'Node',
  `Category Subject Multiplicity` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Category Can Have Other` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Category Max Deep` smallint(5) unsigned NOT NULL DEFAULT '2',
  `Category Show Subject User Interface` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Category Show Public New Subject` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Category Show Public Edit` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Category Scope` varchar(32) DEFAULT NULL,
  `Category Store Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Warehouse Key` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Category Root Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Parent Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Position` varchar(255) NOT NULL,
  `Category XHTML Branch Tree` text,
  `Category Plain Branch Tree` text,
  `Category Deep` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Category Children` mediumint(9) NOT NULL DEFAULT '0',
  `Category Children Deep` mediumint(9) NOT NULL DEFAULT '0',
  `Category Order` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Code` varchar(64) NOT NULL,
  `Category Label` varchar(255) NOT NULL,
  `Category Default` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Category Subject` enum('Product','Supplier','Customer','Family','Invoice','Part','Category') NOT NULL DEFAULT 'Product',
  `Category Subject Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Function` text,
  `Category Function Order` mediumint(8) unsigned DEFAULT '0',
  `Category Number Subjects` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Number Active Subjects` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Number No Active Subjects` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Category Subjects Not Assigned` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Is Category Field Other` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Category Children Other` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Category Locked` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Category Sticky Note` text,
  PRIMARY KEY (`Category Key`),
  UNIQUE KEY `Category Code` (`Category Code`,`Category Root Key`),
  KEY `Category Default` (`Category Default`),
  KEY `Category Subject` (`Category Subject`),
  KEY `Category Parent Key` (`Category Parent Key`),
  KEY `Category Store Key` (`Category Store Key`),
  KEY `Category Subject Key` (`Category Subject Key`),
  KEY `Category Warehouse Key` (`Category Warehouse Key`),
  KEY `Category Branch Type` (`Category Branch Type`),
  KEY `Category Root Key` (`Category Root Key`),
  KEY `Category Locked` (`Category Locked`),
  KEY `Category Name` (`Category Code`(20)),
  KEY `Category Scope` (`Category Scope`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Category History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Category History Bridge` (
  `Category Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes',
  PRIMARY KEY (`Category Key`,`History Key`),
  KEY `Category Key` (`Category Key`),
  KEY `History Key` (`History Key`),
  KEY `Deletable` (`Deletable`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Charge Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Charge Dimension` (
  `Charge Key` mediumint(8) unsigned NOT NULL,
  `Charge Name` varchar(255) NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Charge Trigger` enum('Product','Order') DEFAULT NULL,
  `Charge Trigger Key` mediumint(8) unsigned DEFAULT NULL,
  `Charge Description` varchar(255) DEFAULT NULL,
  `Charge Public Description` text CHARACTER SET utf8mb4,
  `Charge Metadata` varchar(245) NOT NULL,
  `Charge Terms Type` enum('Order Interval','Product Quantity Ordered','Family Quantity Ordered','Total Amount','Order Number','Shipping Country','Shipping Country First Division','Order Items Gross Amount','Order Items Net Amount') DEFAULT NULL,
  `Charge Terms Description` varchar(255) DEFAULT NULL,
  `Charge Type` enum('Percentage','Amount') DEFAULT NULL,
  `Charge Terms Metadata` varchar(4096) DEFAULT NULL,
  `Charge Active` enum('Yes','No') NOT NULL,
  `Charge Begin Date` datetime DEFAULT NULL,
  `Charge Expiration Date` datetime DEFAULT NULL,
  PRIMARY KEY (`Charge Key`),
  KEY `x` (`Charge Trigger`,`Charge Trigger Key`),
  KEY `z` (`Charge Type`),
  KEY `Charge Active` (`Charge Active`),
  KEY `Store Key` (`Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Comment Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Comment Dimension` (
  `Comment Key` mediumint(10) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Comment` text NOT NULL,
  `Date Added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Comment Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Area Department Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Area Department Bridge` (
  `Area Key` smallint(5) unsigned NOT NULL,
  `Department Key` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`Area Key`,`Department Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Area Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Area Dimension` (
  `Company Area Key` mediumint(8) unsigned NOT NULL,
  `Company Key` mediumint(8) unsigned NOT NULL,
  `Company Area Code` varchar(255) DEFAULT NULL,
  `Company Area Name` varchar(255) DEFAULT NULL,
  `Company Area Description` text,
  `Company Area Number Departments` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Company Area Number Positions` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Company Area Number Employees` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Company Area Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Bridge` (
  `Company Key` mediumint(8) unsigned NOT NULL,
  `Subject Type` enum('Supplier','Customer','Contact','Account') NOT NULL DEFAULT 'Customer',
  `Subject Key` mediumint(8) unsigned NOT NULL,
  `Is Main` enum('Yes','No') NOT NULL,
  `Is Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  UNIQUE KEY `Company Key` (`Company Key`,`Subject Type`,`Subject Key`),
  KEY `Is Main` (`Is Main`),
  KEY `Subject Key` (`Subject Key`),
  KEY `Subject Type` (`Subject Type`),
  KEY `Company Key_2` (`Company Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Department Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Department Dimension` (
  `Company Department Key` mediumint(8) unsigned NOT NULL,
  `Company Key` mediumint(8) unsigned NOT NULL,
  `Company Department Code` varchar(255) DEFAULT NULL,
  `Company Department Name` varchar(255) DEFAULT NULL,
  `Company Department Description` text,
  `Company Department Number Positions` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Company Department Number Employees` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Company Department Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Department Position Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Department Position Bridge` (
  `Department Key` smallint(5) unsigned NOT NULL,
  `Position Key` smallint(5) unsigned NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Dimension` (
  `Company Key` mediumint(8) unsigned NOT NULL COMMENT '(PK)',
  `Company Name` varchar(255) DEFAULT NULL,
  `Company File As` varchar(255) DEFAULT NULL,
  `Company Fiscal Name` varchar(255) DEFAULT NULL,
  `Company Tax Number` varchar(255) DEFAULT NULL,
  `Company Registration Number` varchar(255) DEFAULT NULL,
  `Company Main Address Key` mediumint(8) unsigned DEFAULT NULL,
  `Company Main XHTML Address` varchar(1024) DEFAULT NULL,
  `Company Main Plain Address` varchar(255) NOT NULL,
  `Company Main Country Key` mediumint(9) DEFAULT NULL,
  `Company Main Country` varchar(255) DEFAULT NULL,
  `Company Main Country Code` varchar(3) DEFAULT 'UNK',
  `Company Main Location` varchar(1024) DEFAULT NULL,
  `Company Main XHTML Telephone` varchar(255) DEFAULT NULL,
  `Company Main Plain Telephone` varchar(255) NOT NULL,
  `Company Main Telephone Key` mediumint(8) unsigned DEFAULT NULL,
  `Company Main XHTML FAX` varchar(255) DEFAULT NULL,
  `Company Main Plain FAX` varchar(255) NOT NULL,
  `Company Main FAX Key` mediumint(8) unsigned DEFAULT NULL,
  `Company Main XHTML Email` varchar(255) DEFAULT NULL,
  `Company Main Plain Email` varchar(255) NOT NULL,
  `Company Main Email Key` mediumint(8) unsigned DEFAULT NULL,
  `Company Main Web Site` varchar(255) DEFAULT NULL,
  `Company Main Contact Name` varchar(255) DEFAULT NULL,
  `Company Main Contact Key` mediumint(9) DEFAULT NULL,
  `Company Accounts Payable Contact Key` mediumint(9) DEFAULT NULL,
  `Company Sales Contact Key` mediumint(9) DEFAULT NULL,
  `Company Purchases Contact Key` mediumint(9) DEFAULT NULL,
  `Company Description` varchar(4096) DEFAULT NULL,
  `Company Category` varchar(255) DEFAULT NULL,
  `Company Number Employees` enum('1-5','6-15','16-30','31-50','51-100','101-1000','1001-10000','More than 10000','Unknown') NOT NULL DEFAULT 'Unknown',
  `Company Type` enum('Unknown','Self Employed Person','Single Family','Micro','Medium','Large','Corporation') NOT NULL DEFAULT 'Unknown',
  `Company Old ID` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Company Key`),
  KEY `Company Name` (`Company Name`),
  KEY `Company File As` (`Company File As`),
  KEY `Company Type` (`Company Type`),
  KEY `Company Main Contact Key` (`Company Main Contact Key`),
  KEY `Company Old ID` (`Company Old ID`(16)),
  KEY `Company Tax Number` (`Company Tax Number`(16)),
  KEY `Company Main Email Key` (`Company Main Email Key`),
  KEY `Company Main Telephone Key` (`Company Main Telephone Key`),
  KEY `Company Main FAX Key` (`Company Main FAX Key`),
  KEY `Company Main Address Key` (`Company Main Address Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Old ID Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Old ID Bridge` (
  `Company Key` mediumint(8) unsigned NOT NULL,
  `Company Old ID` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Company Key`,`Company Old ID`),
  KEY `Company Key` (`Company Key`),
  KEY `Company Old ID` (`Company Old ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Position Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Position Dimension` (
  `Company Position Key` mediumint(8) unsigned NOT NULL,
  `Company Position Code` varchar(255) DEFAULT NULL,
  `Company Position Title` varchar(255) DEFAULT NULL,
  `Company Position Description` text,
  `Company Position Employees` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Company Position Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Position Staff Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Position Staff Bridge` (
  `Position Key` mediumint(8) unsigned NOT NULL,
  `Staff Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Position Key`,`Staff Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Company Web Site Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company Web Site Bridge` (
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Company Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Company Key`),
  KEY `Page Key` (`Page Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Configuration Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Configuration Dimension` (
  `Public Path` varchar(1024) CHARACTER SET latin1 NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Contact Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contact Bridge` (
  `Contact Key` mediumint(8) unsigned NOT NULL,
  `Subject Type` enum('Company','Supplier','Customer','Staff') NOT NULL DEFAULT 'Company',
  `Subject Key` mediumint(8) unsigned NOT NULL,
  `Is Main` enum('Yes','No') NOT NULL,
  `Is Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  UNIQUE KEY `Contact Key` (`Contact Key`,`Subject Type`,`Subject Key`),
  KEY `Is Main` (`Is Main`),
  KEY `Subject Key` (`Subject Key`),
  KEY `Subject Type` (`Subject Type`),
  KEY `Contact Key_2` (`Contact Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Contact Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contact Dimension` (
  `Contact Key` mediumint(8) unsigned NOT NULL COMMENT '(PK)',
  `Contact Tax Number` varchar(255) NOT NULL,
  `Contact Identification Number` varchar(255) NOT NULL DEFAULT '',
  `Contact Salutation` varchar(255) DEFAULT NULL,
  `Contact Name` varchar(255) DEFAULT NULL,
  `Contact File As` varchar(255) DEFAULT NULL,
  `Contact First Name` varchar(255) DEFAULT NULL,
  `Contact Surname` varchar(255) DEFAULT NULL,
  `Contact Suffix` varchar(255) DEFAULT NULL,
  `Contact Gender` enum('Unknown','Male','Female') NOT NULL DEFAULT 'Unknown',
  `Contact Informal Greeting` varchar(255) DEFAULT NULL,
  `Contact Formal Greeting` varchar(255) DEFAULT NULL,
  `Contact Profession` varchar(255) DEFAULT NULL,
  `Contact Title` varchar(255) DEFAULT NULL,
  `Contact Company Name` varchar(255) DEFAULT NULL,
  `Contact Company Key` mediumint(9) DEFAULT NULL,
  `Contact Company Department` varchar(255) DEFAULT NULL,
  `Contact Company Department Key` mediumint(9) DEFAULT NULL,
  `Contact Manager Name` varchar(255) DEFAULT NULL,
  `Contact Manager Key` mediumint(9) DEFAULT NULL,
  `Contact Assistant Name` varchar(255) DEFAULT NULL,
  `Contact Assistant Key` mediumint(9) DEFAULT NULL,
  `Contact Main Address Key` mediumint(8) unsigned DEFAULT NULL,
  `Contact Main Location` varchar(255) DEFAULT NULL,
  `Contact Main XHTML Address` varchar(1024) DEFAULT NULL,
  `Contact Main Plain Address` varchar(255) NOT NULL,
  `Contact Main Country Key` mediumint(9) DEFAULT NULL,
  `Contact Main Country` varchar(255) DEFAULT NULL,
  `Contact Main Country Code` varchar(3) DEFAULT NULL,
  `Contact Main Plain Telephone` varchar(255) NOT NULL,
  `Contact Main XHTML Telephone` varchar(64) NOT NULL,
  `Contact Main Telephone Key` mediumint(8) unsigned DEFAULT NULL,
  `Contact Main XHTML Mobile` varchar(255) DEFAULT NULL,
  `Contact Main Plain Mobile` varchar(255) NOT NULL,
  `Contact Main Mobile Key` mediumint(8) unsigned DEFAULT NULL,
  `Contact Main XHTML FAX` varchar(255) DEFAULT NULL,
  `Contact Main Plain FAX` varchar(255) NOT NULL,
  `Contact Main FAX Key` mediumint(8) unsigned DEFAULT NULL,
  `Contact Main XHTML Email` varchar(512) DEFAULT NULL,
  `Contact Main Plain Email` varchar(512) NOT NULL,
  `Contact Main Email Key` mediumint(8) unsigned DEFAULT NULL,
  `Contact Fuzzy` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Contact Old ID` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Contact Key`),
  KEY `Contact File As` (`Contact File As`),
  KEY `Company Key` (`Contact Company Key`),
  KEY `Contact Main Address Key` (`Contact Main Address Key`),
  KEY `Contact Main Telephone Key` (`Contact Main Telephone Key`),
  KEY `Contact Name` (`Contact Name`),
  KEY `Contact Main Mobile Key` (`Contact Main Mobile Key`),
  KEY `Contact Main Email Key` (`Contact Main Email Key`),
  KEY `Contact Main FAX Key` (`Contact Main FAX Key`),
  KEY `Contact Old ID` (`Contact Old ID`(16)),
  KEY `Contact Main Email` (`Contact Main Plain Email`(8)),
  KEY `Contact Surname` (`Contact Surname`(128)),
  KEY `Contact First Name` (`Contact First Name`(128))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Contact Old ID Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contact Old ID Bridge` (
  `Contact Key` mediumint(8) unsigned NOT NULL,
  `Contact Old ID` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Contact Key`,`Contact Old ID`),
  KEY `Contact Key` (`Contact Key`),
  KEY `Contact Old ID` (`Contact Old ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Contract Terms Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contract Terms Dimension` (
  `Contract Terms Key` mediumint(8) unsigned NOT NULL,
  `Contract Text` text NOT NULL,
  PRIMARY KEY (`Contract Terms Key`),
  FULLTEXT KEY `Contract Text` (`Contract Text`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Custom Field Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Custom Field Dimension` (
  `Custom Field Key` mediumint(8) unsigned NOT NULL,
  `Custom Field Table` varchar(30) NOT NULL,
  `Custom Field Name` varchar(30) NOT NULL,
  `Custom Field Type` enum('Mediumint','Text','Longtext','Enum') NOT NULL,
  `Custom Field Parent Key` mediumint(8) unsigned DEFAULT NULL,
  `Custom Field Subject` enum('Customer','Store','Deaparment','Family','Product','Part','Location','Supplier Product') NOT NULL,
  `Custom Field In New Subject` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Custom Field In Showcase` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Custom Field In Registration` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Custom Field In Profile` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Default Value` varchar(30) DEFAULT '',
  PRIMARY KEY (`Custom Field Key`),
  KEY `Custom Field Subject` (`Custom Field Subject`),
  KEY `Custom Field In Profile` (`Custom Field In Profile`),
  KEY `Custom Field Table` (`Custom Field Table`),
  KEY `Custom Field In Registration` (`Custom Field In Registration`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Billing To Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Billing To Bridge` (
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Billing To Key` mediumint(8) unsigned NOT NULL,
  `Is Principal` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Billing To Status` enum('Normal','Historic','Deleted') NOT NULL DEFAULT 'Normal',
  `Times Used` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Billing To From Date` datetime NOT NULL,
  `Billing To Last Used` datetime DEFAULT NULL,
  `Billing To Current Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Customer Key`,`Billing To Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Category History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Category History Bridge` (
  `Store Key` smallint(5) unsigned NOT NULL,
  `Category Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Type` enum('Changes','Assign') NOT NULL,
  UNIQUE KEY `Store Key` (`Store Key`,`Category Key`,`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Correlation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Correlation` (
  `Customer A Key` mediumint(8) unsigned NOT NULL,
  `Customer A Name` varchar(255) NOT NULL,
  `Customer B Key` mediumint(8) unsigned NOT NULL,
  `Customer B Name` varchar(255) NOT NULL,
  `Correlation` float NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Customer A Key`,`Customer B Key`),
  KEY `Store Key` (`Store Key`),
  KEY `Customer A` (`Customer A Key`),
  KEY `Customer B` (`Customer B Key`),
  KEY `Customer Name B` (`Customer B Name`(8)),
  KEY `Customer Name A` (`Customer A Name`(8))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Credit Card Token Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Credit Card Token Dimension` (
  `Customer Credit Card Token Key` mediumint(8) unsigned NOT NULL,
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Billing To Key` mediumint(8) unsigned NOT NULL,
  `Ship To Key` mediumint(8) unsigned NOT NULL,
  `CCUI` varchar(255) NOT NULL,
  `Metadata` text NOT NULL,
  `Vault` varchar(32) NOT NULL,
  `Valid Until` datetime NOT NULL,
  `Created` datetime NOT NULL,
  `Updated` datetime NOT NULL,
  PRIMARY KEY (`Customer Credit Card Token Key`),
  UNIQUE KEY `Token` (`Customer Key`,`Billing To Key`,`Ship To Key`,`CCUI`(64)),
  KEY `Valid Until` (`Valid Until`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Custom Field Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Custom Field Dimension` (
  `Customer Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Customer Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Deleted Dimension` (
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Customer Store Key` smallint(5) unsigned NOT NULL,
  `Customer Deleted Name` varchar(255) DEFAULT NULL,
  `Customer Deleted Contact Name` varchar(255) DEFAULT NULL,
  `Customer Deleted Email` varchar(255) DEFAULT NULL,
  `Customer Card` text NOT NULL,
  `Customer Deleted Date` datetime NOT NULL,
  `Customer Deleted Note` text NOT NULL,
  PRIMARY KEY (`Customer Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Dimension` (
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Customer Store Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Customer Level Type` enum('Normal','VIP','Partner','Staff') NOT NULL DEFAULT 'Normal',
  `Customer Main Contact Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Main Contact Name` varchar(255) DEFAULT '',
  `Customer Tax Number` varchar(64) DEFAULT NULL,
  `Customer Registration Number` varchar(255) DEFAULT NULL,
  `Customer Main XHTML Telephone` varchar(255) DEFAULT NULL,
  `Customer Main Telephone Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Main Plain Telephone` varchar(45) NOT NULL,
  `Customer Main Plain FAX` varchar(100) NOT NULL,
  `Customer Main XHTML FAX` varchar(100) DEFAULT NULL,
  `Customer Main FAX Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Main XHTML Mobile` varchar(255) DEFAULT NULL,
  `Customer Main Mobile Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Main Plain Mobile` varchar(255) NOT NULL,
  `Customer Main XHTML Email` varchar(255) DEFAULT NULL,
  `Customer Main Email Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Main Plain Email` varchar(255) NOT NULL,
  `Customer Location` varchar(255) DEFAULT NULL,
  `Customer Contact Address Recipient` varchar(255) DEFAULT NULL,
  `Customer Contact Address Organization` varchar(255) DEFAULT NULL,
  `Customer Contact Address Line 1` varchar(255) DEFAULT NULL,
  `Customer Contact Address Line 2` varchar(255) DEFAULT NULL,
  `Customer Contact Address Sorting Code` varchar(255) DEFAULT NULL,
  `Customer Contact Address Postal Code` varchar(255) DEFAULT NULL,
  `Customer Contact Address Dependent Locality` varchar(255) DEFAULT NULL,
  `Customer Contact Address Locality` varchar(255) DEFAULT NULL,
  `Customer Contact Address Administrative Area` varchar(255) DEFAULT NULL,
  `Customer Contact Address Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Customer Contact Address Checksum` varchar(64) DEFAULT NULL,
  `Customer Contact Address Formatted` text,
  `Customer Contact Address Postal Label` text,
  `Customer Invoice Address Recipient` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Organization` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Line 1` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Line 2` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Sorting Code` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Postal Code` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Dependent Locality` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Locality` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Administrative Area` varchar(255) DEFAULT NULL,
  `Customer Invoice Address Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Customer Invoice Address Checksum` varchar(64) DEFAULT NULL,
  `Customer Invoice Address Formatted` text,
  `Customer Invoice Address Postal Label` text,
  `Customer Delivery Address Recipient` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Organization` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Line 1` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Line 2` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Sorting Code` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Postal Code` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Dependent Locality` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Locality` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Administrative Area` varchar(255) DEFAULT NULL,
  `Customer Delivery Address Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Customer Delivery Address Checksum` varchar(64) DEFAULT NULL,
  `Customer Delivery Address Formatted` text,
  `Customer Delivery Address Postal Label` text,
  `Customer Main XHTML Address` text,
  `Customer Main Postal Address` text,
  `Customer Main Plain Address` varchar(255) NOT NULL DEFAULT '',
  `Customer Main Location` varchar(255) DEFAULT NULL,
  `Customer Main Address Line 1` varchar(255) DEFAULT NULL,
  `Customer Main Address Line 2` varchar(255) DEFAULT NULL,
  `Customer Main Address Line 3` varchar(255) DEFAULT NULL,
  `Customer Main Address Lines` varchar(512) DEFAULT NULL,
  `Customer Main Town` varchar(255) DEFAULT NULL,
  `Customer Main Postal Code` varchar(255) DEFAULT NULL,
  `Customer Main Plain Postal Code` varchar(64) DEFAULT NULL,
  `Customer Main Postal Code Country Second Division` varchar(128) DEFAULT NULL,
  `Customer Main Country Second Division` varchar(128) DEFAULT NULL,
  `Customer Main Country First Division` varchar(255) DEFAULT NULL COMMENT 'Primary or Secondary Country Division',
  `Customer Main Address Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Main Country` varchar(255) DEFAULT NULL,
  `Customer Main Country Key` smallint(5) unsigned DEFAULT NULL,
  `Customer Main Country Code` varchar(3) NOT NULL DEFAULT 'UNK',
  `Customer Main Country 2 Alpha Code` varchar(2) NOT NULL DEFAULT 'XX',
  `Customer Main Address Incomplete` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Customer XHTML Billing Address` text,
  `Customer Billing Address Country Code` varchar(3) DEFAULT NULL,
  `Customer Billing Address 2 Alpha Country Code` varchar(2) DEFAULT NULL,
  `Customer Billing Address Lines` varchar(255) DEFAULT NULL,
  `Customer Billing Address Line 1` varchar(255) DEFAULT NULL,
  `Customer Billing Address Line 2` varchar(255) DEFAULT NULL,
  `Customer Billing Address Line 3` varchar(255) DEFAULT NULL,
  `Customer Billing Address Town` varchar(255) DEFAULT NULL,
  `Customer Billing Address Postal Code` varchar(32) DEFAULT NULL,
  `Customer Billing Address Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Billing Address Link` enum('Contact','None') NOT NULL DEFAULT 'Contact',
  `Customer Delivery Address Link` enum('Contact','Billing','None') NOT NULL DEFAULT 'Contact',
  `Customer XHTML Main Delivery Address` text,
  `Customer Main Delivery Address Key` mediumint(9) DEFAULT NULL,
  `Customer Main Delivery Address Lines` text,
  `Customer Main Delivery Address Town` varchar(255) DEFAULT NULL,
  `Customer Main Delivery Address Postal Code` varchar(255) DEFAULT NULL,
  `Customer Main Delivery Address Region` varchar(255) DEFAULT NULL,
  `Customer Main Delivery Address Country` varchar(255) DEFAULT NULL,
  `Customer Main Delivery Address Country Code` varchar(3) NOT NULL DEFAULT 'UNK',
  `Customer Main Delivery Address Country 2 Alpha Code` varchar(2) NOT NULL DEFAULT 'XX',
  `Customer Main Delivery Address Country Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Last Ship To Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Active Ship To Records` smallint(5) unsigned DEFAULT NULL,
  `Customer Total Ship To Records` smallint(5) unsigned DEFAULT NULL,
  `Customer Last Billing To Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Active Billing To Records` smallint(5) unsigned DEFAULT NULL,
  `Customer Total Billing To Records` smallint(5) unsigned DEFAULT NULL,
  `Customer Name` varchar(255) DEFAULT NULL,
  `Customer File As` varchar(255) DEFAULT NULL,
  `Customer Fiscal Name` varchar(255) DEFAULT NULL,
  `Customer Orders` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Customer Orders Invoiced` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Customer Orders Cancelled` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Customer Orders with Shortages` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Customer Orders with Replacements` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Customer First Contacted Date` datetime DEFAULT NULL,
  `Customer First Order Date` datetime DEFAULT NULL,
  `Customer Last Order Date` datetime DEFAULT NULL,
  `Customer Last Invoiced Dispatched Date` datetime DEFAULT NULL,
  `Customer First Invoiced Order Date` datetime DEFAULT NULL,
  `Customer Last Invoiced Order Date` datetime DEFAULT NULL,
  `Customer Lost Date` datetime DEFAULT NULL,
  `Customer Order Interval` bigint(20) DEFAULT NULL COMMENT 'Average order interval messired in seconds',
  `Customer Order Interval STD` bigint(20) DEFAULT NULL COMMENT 'standard deviation',
  `Customer Net Payments` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Customer Tax Payments` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Customer Total Payments` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Customer Net Refunds` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Customer Tax Refunds` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Customer Total Refunds` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Customer Net Balance` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT 'Payments minus Refunds',
  `Customer Tax Balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Customer Profit` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Customer Outstanding Net Balance` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT 'To pay',
  `Customer Outstanding Tax Balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Customer Outstanding Total Balance` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Customer Account Balance` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Customer Next Invoice Credit Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Customer Type by Activity` enum('Active','Losing','Lost') NOT NULL DEFAULT 'Active',
  `Customer Location Type` enum('Domestic','Export') NOT NULL DEFAULT 'Domestic',
  `Customer Type` enum('Company','Person','Unknown') NOT NULL DEFAULT 'Unknown',
  `Customer Company Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Company Name` varchar(255) DEFAULT '',
  `Identified Customer` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Customer Has More Orders Than` mediumint(9) DEFAULT NULL,
  `Customer Has More Invoices Than` mediumint(9) DEFAULT NULL,
  `Customer Has Better Balance Than` mediumint(9) DEFAULT NULL,
  `Customer Is More Profiteable Than` mediumint(9) DEFAULT NULL,
  `Customer Order More Frecuently Than` mediumint(9) DEFAULT NULL,
  `Customer Older Than` mediumint(9) DEFAULT NULL,
  `Customer Orders Position` mediumint(8) unsigned DEFAULT NULL,
  `Customer Invoices Position` mediumint(8) unsigned DEFAULT NULL,
  `Customer Balance Position` mediumint(8) unsigned DEFAULT NULL,
  `Customer Profit Position` mediumint(8) unsigned DEFAULT NULL,
  `Customer Orders Top Percentage` float DEFAULT NULL,
  `Customer Invoices Top Percentage` float DEFAULT NULL,
  `Customer Balance Top Percentage` float DEFAULT NULL,
  `Customer Profits Top Percentage` float DEFAULT NULL,
  `Customer Old ID` varchar(36) DEFAULT NULL,
  `Customer Staff` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Customer Staff Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Customer Tax Category Code` varchar(64) NOT NULL DEFAULT '',
  `Customer Last Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Other','Unknown') NOT NULL DEFAULT 'Unknown',
  `Customer Usual Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Other','Unknown') NOT NULL DEFAULT 'Unknown',
  `Customer Send Newsletter` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Customer Send Email Marketing` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Customer Send Postal Marketing` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Customer Account Operative` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Customer Sticky Note` text NOT NULL,
  `Customer Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Customer With Orders` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Customer New` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Recargo Equivalencia` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Customer Preferred Contact Number` enum('Telephone','Mobile') NOT NULL DEFAULT 'Mobile',
  `Customer Preferred Contact Number Formatted Number` varchar(255) DEFAULT NULL,
  `Customer Follower On Twitter` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Customer Friend On Facebook` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Customer Tax Number Valid` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  `Customer Tax Number Validation Source` enum('Online','Staff') DEFAULT NULL,
  `Customer Tax Number Validation Message` text,
  `Customer Tax Number Details Match` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  `Customer Tax Number Validation Date` datetime DEFAULT NULL,
  `Customer Tax Number Registered Name` varchar(255) DEFAULT NULL,
  `Customer Tax Number Registered Address` text,
  `Customer Website` varchar(255) DEFAULT NULL,
  `Customer Number Web Logins` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Customer Number Web Failed Logins` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Customer Number Web Requests` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Customer Currency Code` varchar(3) DEFAULT NULL,
  `Customer Preferred Shipper Code` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`Customer Key`,`Customer Store Key`),
  KEY `Customer Other ID` (`Customer Old ID`),
  KEY `Customer Store Key` (`Customer Store Key`),
  KEY `Customer Company Key` (`Customer Company Key`),
  KEY `Customer Main Contact Key` (`Customer Main Contact Key`),
  KEY `Customer Type` (`Customer Type`),
  KEY `Customer Name` (`Customer Name`),
  KEY `Customer Type by Activity` (`Customer Type by Activity`),
  KEY `Customer Main Email Key` (`Customer Main Email Key`),
  KEY `Customer Main Address Key` (`Customer Main Address Key`),
  KEY `Customer Main FAX Key` (`Customer Main FAX Key`),
  KEY `Customer Main Telephone Key` (`Customer Main Telephone Key`),
  KEY `Customer Main Delivery Address Key` (`Customer Main Delivery Address Key`),
  KEY `Customer First Order Date` (`Customer First Order Date`),
  KEY `Customer Lost Date` (`Customer Lost Date`),
  KEY `Customer First Contacted Date` (`Customer First Contacted Date`),
  KEY `Customer Main Address Incomplete` (`Customer Main Address Incomplete`),
  KEY `Customer Account Operative` (`Customer Account Operative`),
  KEY `Customer Send Postal Marketing` (`Customer Send Postal Marketing`),
  KEY `Customer Main Plain Postal Code` (`Customer Main Plain Postal Code`),
  KEY `Customer Send Newsletter` (`Customer Send Newsletter`),
  KEY `Customer Active` (`Customer Active`),
  KEY `Customer New` (`Customer New`),
  KEY `Customer With Orders` (`Customer With Orders`),
  KEY `Customer Number Web Requests` (`Customer Number Web Requests`),
  KEY `Customer Number Web Logins` (`Customer Number Web Logins`),
  KEY `Customer Number Web Failed Logins` (`Customer Number Web Failed Logins`),
  KEY `Customer Orders Invoiced` (`Customer Orders Invoiced`),
  KEY `Customer Main Plain Telephone` (`Customer Main Plain Telephone`(6)),
  KEY `Customer Level Type` (`Customer Level Type`),
  KEY `Customer Location Type` (`Customer Location Type`),
  KEY `Customer Orders` (`Customer Store Key`,`Customer Orders`),
  KEY `Customer Net Balance` (`Customer Store Key`,`Customer Net Balance`),
  KEY `Customer Profit` (`Customer Store Key`,`Customer Profit`),
  KEY `Customer Last Dispatched Order Date` (`Customer Last Invoiced Dispatched Date`),
  KEY `Customer Billing Address Key` (`Customer Billing Address Key`),
  KEY `Customer Tax Number` (`Customer Tax Number`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Favorite Product Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Favorite Product Bridge` (
  `Customer Favorite Product Key` mediumint(8) unsigned NOT NULL,
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Site Key` smallint(5) unsigned NOT NULL,
  `Store Key` smallint(5) unsigned NOT NULL,
  `Department Key` smallint(5) unsigned NOT NULL,
  `Family Key` smallint(5) unsigned NOT NULL,
  `Date Created` datetime NOT NULL,
  PRIMARY KEY (`Customer Favorite Product Key`),
  UNIQUE KEY `Customer Key` (`Customer Key`,`Product ID`,`Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer History Bridge` (
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Orders','Changes','Attachments','WebLog','Emails') NOT NULL DEFAULT 'Notes',
  PRIMARY KEY (`Customer Key`,`History Key`),
  KEY `Customer Key` (`Customer Key`),
  KEY `History Key` (`History Key`),
  KEY `Deletable` (`Deletable`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Import Metadata`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Import Metadata` (
  `Customer Import Metadata Key` mediumint(8) unsigned NOT NULL,
  `Metadata` varchar(15) NOT NULL,
  `Import Date` datetime NOT NULL,
  PRIMARY KEY (`Customer Import Metadata Key`),
  KEY `Metadata` (`Metadata`),
  KEY `Import Date` (`Import Date`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Merge Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Merge Bridge` (
  `Merged Customer Key` mediumint(8) unsigned NOT NULL,
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Date Merged` datetime DEFAULT NULL,
  PRIMARY KEY (`Merged Customer Key`,`Customer Key`),
  KEY `Date Merged` (`Date Merged`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer No Correlated Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer No Correlated Bridge` (
  `Customer A Key` mediumint(8) unsigned NOT NULL,
  `Customer B Key` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `Customer A Key` (`Customer A Key`,`Customer B Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Other Delivery Address Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Other Delivery Address Dimension` (
  `Customer Other Delivery Address Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Delivery Address Store Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Delivery Address Customer Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Delivery Address Recipient` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Organization` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Line 1` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Line 2` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Sorting Code` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Postal Code` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Dependent Locality` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Locality` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Administrative Area` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Country 2 Alpha Code` varchar(255) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Checksum` varchar(64) NOT NULL DEFAULT '',
  `Customer Other Delivery Address Formatted` text NOT NULL,
  `Customer Other Delivery Address Postal Label` text NOT NULL,
  `Customer Other Delivery Address Label` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`Customer Other Delivery Address Key`),
  KEY `Customer Other Delivery Address Customer Key` (`Customer Other Delivery Address Customer Key`),
  KEY `Customer Other Delivery Address Store Key` (`Customer Other Delivery Address Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Other Email Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Other Email Dimension` (
  `Customer Other Email Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Email Store Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Email Customer Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Email Email` varchar(255) NOT NULL,
  `Customer Other Email Label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Customer Other Email Key`),
  KEY `Customer Other Email Customer Key` (`Customer Other Email Customer Key`,`Customer Other Email Email`),
  KEY `Customer Other Email Store Key` (`Customer Other Email Store Key`),
  KEY `Customer Other Email Email` (`Customer Other Email Email`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Other Telephone Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Other Telephone Dimension` (
  `Customer Other Telephone Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Telephone Store Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Telephone Customer Key` mediumint(8) unsigned NOT NULL,
  `Customer Other Telephone Number` varchar(255) NOT NULL,
  `Customer Other Telephone Formatted Number` varchar(255) NOT NULL,
  `Customer Other Telephone Label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Customer Other Telephone Key`),
  KEY `Customer Other Telephone Customer Key` (`Customer Other Telephone Customer Key`,`Customer Other Telephone Number`),
  KEY `Customer Other Telephone Store Key` (`Customer Other Telephone Store Key`),
  KEY `Customer Other Telephone Number` (`Customer Other Telephone Number`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Send Post`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Send Post` (
  `Customer Send Post Key` mediumint(11) NOT NULL,
  `Customer Key` mediumint(11) NOT NULL,
  `Send Post Status` enum('To Send','Send','Cancelled') NOT NULL,
  `Date Creation` datetime NOT NULL,
  `Date Send` datetime DEFAULT NULL,
  `Post Type` enum('Catalogue','Advert','Letter') NOT NULL,
  PRIMARY KEY (`Customer Send Post Key`),
  KEY `Customer Key` (`Customer Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Customer Ship To Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer Ship To Bridge` (
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Ship To Key` mediumint(8) unsigned NOT NULL,
  `Is Principal` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Ship To Status` enum('Normal','Historic','Deleted') NOT NULL DEFAULT 'Normal',
  `Times Used` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Ship To From Date` datetime NOT NULL,
  `Ship To Last Used` datetime DEFAULT NULL,
  `Ship To Current Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Customer Key`,`Ship To Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Dashboard Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Dashboard Dimension` (
  `Dashboard Key` mediumint(8) unsigned NOT NULL,
  `User key` mediumint(8) unsigned NOT NULL,
  `Dashboard Order` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Dashboard Default` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Dashboard Key`),
  KEY `User key` (`User key`),
  KEY `Dashboard Order` (`Dashboard Order`),
  KEY `Dashboard Default` (`Dashboard Default`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Dashboard Widget Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Dashboard Widget Bridge` (
  `Dashboard Widget Key` mediumint(8) unsigned NOT NULL,
  `Dashboard Key` smallint(5) unsigned NOT NULL,
  `Widget Key` mediumint(8) unsigned NOT NULL,
  `Dashboard Widget Order` float unsigned NOT NULL DEFAULT '1',
  `Dashboard Widget Height` smallint(5) unsigned DEFAULT NULL,
  `Dashboard Widget Metadata` text NOT NULL,
  PRIMARY KEY (`Dashboard Widget Key`),
  KEY `Widget Key` (`Widget Key`),
  KEY `Dashboard Key` (`Dashboard Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Data Sets Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Data Sets Dimension` (
  `Data Sets Key` smallint(6) NOT NULL,
  `Data Sets Code` varchar(64) NOT NULL,
  `Data Sets Number Sets` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Data Sets Number Items` int(10) unsigned NOT NULL DEFAULT '0',
  `Data Sets Size` double unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Data Sets Key`),
  UNIQUE KEY `Data Sets Code` (`Data Sets Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Deal Campaign Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Deal Campaign Dimension` (
  `Deal Campaign Key` mediumint(8) unsigned NOT NULL,
  `Deal Campaign Status` enum('Suspended','Active','Finish','Waiting') NOT NULL DEFAULT 'Waiting',
  `Deal Campaign Name` varchar(50) NOT NULL,
  `Deal Campaign Description` text NOT NULL,
  `Deal Campaign Store Key` mediumint(8) unsigned NOT NULL,
  `Deal Campaign Valid From` datetime DEFAULT NULL,
  `Deal Campaign Valid To` datetime DEFAULT NULL,
  `Deal Campaign Number Current Deals` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Deal Campaign Total Acc Used Orders` mediumint(8) unsigned DEFAULT '0',
  `Deal Campaign Total Acc Used Customers` mediumint(8) unsigned DEFAULT '0',
  `Deal Campaign Total Acc Applied Orders` mediumint(8) unsigned DEFAULT '0',
  `Deal Campaign Total Acc Applied Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Deal Campaign Key`),
  UNIQUE KEY `Deal Campaign Store Key` (`Deal Campaign Store Key`,`Deal Campaign Name`),
  KEY `Deal Campaign Status` (`Deal Campaign Status`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Deal Campaign History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Deal Campaign History Bridge` (
  `Deal Campaign Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Asset') NOT NULL,
  PRIMARY KEY (`Deal Campaign Key`,`History Key`),
  KEY `Deal Campaign Key` (`Deal Campaign Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Deal Component Customer Preference Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Deal Component Customer Preference Bridge` (
  `Deal Component Key` mediumint(8) unsigned NOT NULL,
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Preference Metadata` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Deal Component Key`,`Customer Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Deal Component Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Deal Component Dimension` (
  `Deal Component Key` mediumint(8) unsigned NOT NULL,
  `Deal Component Store Key` mediumint(8) unsigned NOT NULL,
  `Deal Component Campaign Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Component Deal Key` mediumint(8) unsigned NOT NULL,
  `Deal Component Status` enum('Suspended','Active','Finish','Waiting') NOT NULL DEFAULT 'Active',
  `Deal Component Trigger` enum('Department','Family','Product','Order','Customer','Customer Category','Customer List') DEFAULT NULL,
  `Deal Component Trigger Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Component Terms Type` enum('Department Quantity Ordered','Every Order','Family For Every Quantity Any Product Ordered','Department For Every Quantity Any Product Ordered','Voucher AND Order Interval','Amount AND Order Number','Amount AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Amount','Order Total Net Amount','Order Total Net Amount AND Order Number','Order Total Net Amount AND Shipping Country','Order Total Net Amount AND Order Interval','Order Items Net Amount','Order Items Net Amount AND Order Number','Order Items Net Amount AND Shipping Country','Order Items Net Amount AND Order Interval','Order Total Amount','Order Total Amount AND Order Number','Order Total Amount AND Shipping Country','Order Total Amount AND Order Interval','Order Interval','Product Quantity Ordered','Family Quantity Ordered','Order Number','Shipping Country','Voucher','Department For Every Quantity Ordered','Family For Every Quantity Ordered','Product For Every Quantity Ordered') NOT NULL,
  `Deal Component Terms Description` varchar(255) DEFAULT NULL,
  `Deal Component XHTML Terms Description Label` text,
  `Deal Component Terms Lock` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Deal Component Terms` varchar(255) DEFAULT NULL,
  `Deal Component Allowance Type` enum('Get Cheapest Free','Amount Off','Percentage Off','Get Free','Get Same Free','Credit') DEFAULT NULL,
  `Deal Component Allowance Target` enum('Product','Order','Shipping','Charge','Family','Department') DEFAULT NULL,
  `Deal Component Allowance Target Type` enum('Items','No Items') NOT NULL DEFAULT 'Items',
  `Deal Component Allowance Target Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Component Allowance Target XHTML Label` varchar(255) NOT NULL,
  `Deal Component Allowance Description` varchar(255) DEFAULT NULL,
  `Deal Component Allowance Plain Description` varchar(255) NOT NULL,
  `Deal Component Allowance XHTML Description` varchar(255) NOT NULL,
  `Deal Component XHTML Allowance Description Label` text,
  `Deal Component Allowance` varchar(255) DEFAULT NULL,
  `Deal Component Allowance Lock` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Deal Component Replace Type` enum('same target','same tigger','same target and tigger','deal','none') NOT NULL DEFAULT 'none',
  `Deal Component Replace` text,
  `Deal Component Begin Date` datetime DEFAULT NULL,
  `Deal Component Expiration Date` datetime DEFAULT NULL,
  `Deal Component Total Acc Used Orders` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Deal Component Total Acc Applied Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Deal Component Total Acc Used Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Deal Component Total Acc Applied Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Deal Component Public` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Deal Component Mirror Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Deal Component Key`),
  KEY `x` (`Deal Component Trigger`,`Deal Component Trigger Key`),
  KEY `y` (`Deal Component Allowance Target`,`Deal Component Allowance Target Key`),
  KEY `z` (`Deal Component Allowance Type`),
  KEY `Deal Key` (`Deal Component Deal Key`),
  KEY `Deal Campaign Key_2` (`Deal Component Campaign Key`),
  KEY `Deal Metadata Public` (`Deal Component Public`),
  KEY `Deal Component Allowance Target Type` (`Deal Component Allowance Target Type`),
  KEY `Deal Component Mirror Key` (`Deal Component Mirror Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Deal Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Deal Dimension` (
  `Deal Key` mediumint(8) unsigned NOT NULL,
  `Deal Campaign Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Store Key` mediumint(8) unsigned NOT NULL,
  `Deal Status` enum('Suspended','Active','Finish','Waiting') NOT NULL DEFAULT 'Waiting',
  `Deal Name` varchar(255) DEFAULT NULL,
  `Deal Label` varchar(255) DEFAULT NULL,
  `Deal Description` text,
  `Deal Term Allowances` text,
  `Deal Term Allowances Label` text,
  `Deal Trigger` enum('Order','Department','Family','Product','Customer','Customer Category','Customer List') NOT NULL,
  `Deal Trigger Key` mediumint(9) NOT NULL DEFAULT '0',
  `Deal Trigger XHTML Label` varchar(255) NOT NULL,
  `Deal Terms Type` enum('Department Quantity Ordered','Every Order','Family For Every Quantity Any Product Ordered','Department For Every Quantity Any Product Ordered','Voucher AND Order Interval','Amount AND Order Number','Amount AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Amount','Order Total Net Amount','Order Total Net Amount AND Order Number','Order Total Net Amount AND Shipping Country','Order Total Net Amount AND Order Interval','Order Items Net Amount','Order Items Net Amount AND Order Number','Order Items Net Amount AND Shipping Country','Order Items Net Amount AND Order Interval','Order Total Amount','Order Total Amount AND Order Number','Order Total Amount AND Shipping Country','Order Total Amount AND Order Interval','Order Interval','Product Quantity Ordered','Family Quantity Ordered','Order Number','Shipping Country','Voucher','Department For Every Quantity Ordered','Family For Every Quantity Ordered','Product For Every Quantity Ordered') NOT NULL,
  `Deal Terms Description` varchar(255) DEFAULT NULL,
  `Deal XHTML Terms Description Label` text,
  `Deal Terms` varchar(255) DEFAULT NULL,
  `Deal Terms Lock` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Deal Begin Date` datetime DEFAULT NULL,
  `Deal Expiration Date` datetime DEFAULT NULL,
  `Deal Total Acc Used Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Deal Total Acc Used Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Deal Total Acc Applied Orders` mediumint(8) unsigned DEFAULT '0',
  `Deal Total Acc Applied Customers` mediumint(8) unsigned DEFAULT '0',
  `Deal Remainder Email Campaign Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Number Active Components` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Deal Voucher Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Allowance Target Type` enum('Items','No Items') NOT NULL DEFAULT 'Items',
  `Deal Mirror Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Deal Key`),
  KEY `Store Key` (`Deal Store Key`),
  KEY `Campaign Name` (`Deal Name`),
  KEY `Campaign Deal Terms Lock` (`Deal Terms Lock`),
  KEY `Deal Terms Object` (`Deal Trigger`),
  KEY `Deal Campaign Key` (`Deal Campaign Key`),
  KEY `Deal Mirror Key` (`Deal Mirror Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Deal History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Deal History Bridge` (
  `Deal Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Asset') NOT NULL,
  PRIMARY KEY (`Deal Key`,`History Key`),
  KEY `Deal Key` (`Deal Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Deal Target Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Deal Target Bridge` (
  `Deal Key` mediumint(8) unsigned NOT NULL,
  `Deal Component Key` mediumint(8) unsigned NOT NULL,
  `Subject` enum('Family','Product','Charge','Shipping','Department') NOT NULL,
  `Subject Key` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `Deal Key` (`Deal Key`,`Deal Component Key`,`Subject`,`Subject Key`),
  KEY `Deal Key_2` (`Deal Key`),
  KEY `Subject` (`Subject`,`Subject Key`),
  KEY `Deal Component Key` (`Deal Component Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Delivery Note Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Delivery Note Dimension` (
  `Delivery Note Key` mediumint(8) unsigned NOT NULL,
  `Delivery Note Warehouse Key` smallint(5) unsigned NOT NULL,
  `Delivery Note Order Date Placed` datetime DEFAULT NULL,
  `Delivery Note Date Created` datetime DEFAULT NULL,
  `Delivery Note Date Start Picking` datetime DEFAULT NULL,
  `Delivery Note Date Finish Picking` datetime DEFAULT NULL,
  `Delivery Note Date Start Packing` datetime DEFAULT NULL,
  `Delivery Note Date Finish Packing` datetime DEFAULT NULL,
  `Delivery Note Date Done Approved` datetime DEFAULT NULL,
  `Delivery Note Date Dispatched Approved` datetime DEFAULT NULL,
  `Delivery Note Date` datetime DEFAULT NULL COMMENT 'Date when the DN dispatched',
  `Delivery Note Approved Done` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Delivery Note Approved To Dispatch` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Delivery Note State` enum('Picker & Packer Assigned','Picking & Packing','Packer Assigned','Ready to be Picked','Picker Assigned','Picking','Picked','Packing','Packed','Approved','Dispatched','Cancelled','Cancelled to Restock','Packed Done') NOT NULL DEFAULT 'Ready to be Picked',
  `Delivery Note XHTML State` text NOT NULL,
  `Delivery Note Waiting For Parts` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Delivery Note Assigned Picker Key` mediumint(8) unsigned DEFAULT NULL,
  `Delivery Note Assigned Picker Alias` varchar(255) NOT NULL,
  `Delivery Note Fraction Picked` float NOT NULL DEFAULT '0',
  `Delivery Note Assigned Packer Key` mediumint(8) unsigned DEFAULT NULL,
  `Delivery Note Assigned Packer Alias` varchar(255) NOT NULL,
  `Delivery Note Fraction Packed` float NOT NULL DEFAULT '0',
  `Delivery Note Type` enum('Replacement & Shortages','Order','Replacement','Shortages','Sample','Donation') NOT NULL DEFAULT 'Order',
  `Delivery Note Dispatch Method` enum('Dispatch','Collection','Unknown','NA') NOT NULL DEFAULT 'Unknown',
  `Delivery Note ID` varchar(255) DEFAULT NULL,
  `Delivery Note File As` varchar(255) DEFAULT NULL,
  `Delivery Note Title` varchar(255) DEFAULT NULL,
  `Delivery Note Store Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Delivery Note XHTML Orders` text,
  `Delivery Note XHTML Invoices` text,
  `Delivery Note Customer Key` mediumint(8) unsigned DEFAULT NULL,
  `Delivery Note Customer Name` varchar(255) NOT NULL DEFAULT 'Unknown Customer',
  `Delivery Note XHTML Pickers` varchar(255) DEFAULT NULL,
  `Delivery Note Number Pickers` smallint(5) unsigned DEFAULT NULL,
  `Delivery Note XHTML Packers` varchar(255) DEFAULT NULL,
  `Delivery Note Number Packers` smallint(5) unsigned DEFAULT NULL,
  `Delivery Note Distinct Items` smallint(5) unsigned DEFAULT NULL,
  `Delivery Note Estimated Weight` decimal(12,3) NOT NULL DEFAULT '0.000',
  `Delivery Note Weight` decimal(12,3) DEFAULT NULL COMMENT 'In kilograms',
  `Delivery Note Weight Source` enum('Estimated','Given') NOT NULL DEFAULT 'Estimated',
  `Delivery Note Customer Contact Name` varchar(255) DEFAULT NULL,
  `Delivery Note Telephone` varchar(255) DEFAULT NULL,
  `Delivery Note Email` varchar(255) DEFAULT NULL,
  `Delivery Note XHTML Ship To` text,
  `Delivery Note Ship To Key` mediumint(8) unsigned DEFAULT NULL,
  `Delivery Note Country 2 Alpha Code` varchar(2) NOT NULL DEFAULT 'XX',
  `Delivery Note Shipper Code` varchar(16) DEFAULT NULL,
  `Delivery Note Shipper Consignment` varchar(255) DEFAULT NULL,
  `Delivery Note Metadata` varchar(64) DEFAULT NULL,
  `Delivery Note Number Parcels` smallint(5) unsigned DEFAULT NULL,
  `Delivery Note Parcel Type` enum('Box','Pallet','Envelope','Small Parcel','Other','None') NOT NULL DEFAULT 'Box',
  `Delivery Note Number Boxes` smallint(5) unsigned DEFAULT NULL,
  `Delivery Note World Region Code` char(4) DEFAULT NULL,
  `Delivery Note Country Code` char(4) DEFAULT NULL,
  `Delivery Note Town` varchar(255) DEFAULT NULL,
  `Delivery Note Postal Code` varchar(64) DEFAULT NULL,
  `Delivery Note Customer Sevices Note` text NOT NULL,
  `Delivery Note Warehouse Note` text NOT NULL,
  `Delivery Note XHTML Public Message` mediumtext,
  `Delivery Note Show in Warehouse Orders` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Delivery Note Invoiced` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Delivery Note Invoiced Net DC Amount` float DEFAULT NULL,
  `Delivery Note Invoiced Shipping DC Amount` float DEFAULT NULL,
  PRIMARY KEY (`Delivery Note Key`),
  KEY `Metadata` (`Delivery Note Metadata`(12)),
  KEY `Delivery Note Type` (`Delivery Note Type`),
  KEY `Delivery Note Assigned Picker Key` (`Delivery Note Assigned Picker Key`,`Delivery Note Assigned Packer Key`),
  KEY `Delivery Note Warehouse Key` (`Delivery Note Warehouse Key`),
  KEY `Delivery Note Store Key` (`Delivery Note Store Key`),
  KEY `Delivery Note Customer Key` (`Delivery Note Customer Key`),
  KEY `Delivery Note State` (`Delivery Note State`),
  KEY `Delivery Note Show in Warehouse Orders` (`Delivery Note Show in Warehouse Orders`),
  KEY `Delivery Note Invoiced` (`Delivery Note Invoiced`),
  KEY `Delivery Note Waiting For Parts` (`Delivery Note Waiting For Parts`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Delivery Note History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Delivery Note History Bridge` (
  `Delivery Note Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Delivery Note Key`,`History Key`),
  KEY `Delivery Note Key` (`Delivery Note Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Download Attempt Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Download Attempt Dimension` (
  `Download Attempt Key` mediumint(8) unsigned NOT NULL,
  `Download Attempt Download Key` mediumint(9) NOT NULL,
  `Download Attempt User Key` mediumint(8) unsigned NOT NULL,
  `Download Attempt Date` datetime NOT NULL,
  PRIMARY KEY (`Download Attempt Key`),
  KEY `Download Attempt Download Key` (`Download Attempt Download Key`),
  KEY `Download Attempt User Key` (`Download Attempt User Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Download Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Download Dimension` (
  `Download Key` int(10) unsigned NOT NULL,
  `Download Type` varchar(255) DEFAULT NULL,
  `Download Filename` varchar(255) DEFAULT NULL,
  `Download Date` datetime DEFAULT NULL,
  `Download User Key` mediumint(8) unsigned DEFAULT NULL,
  `Download Fork Key` mediumint(8) unsigned DEFAULT NULL,
  `Download Data` longblob,
  `Download Attempts` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Download Attempt Last Date` datetime DEFAULT NULL,
  PRIMARY KEY (`Download Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Bridge` (
  `Email Key` mediumint(8) unsigned NOT NULL,
  `Subject Type` enum('Customer','Contact','Staff','Company','Supplier') NOT NULL DEFAULT 'Contact',
  `Subject Key` mediumint(8) unsigned NOT NULL,
  `Email Description` varchar(255) DEFAULT NULL,
  `Is Main` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Is Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  UNIQUE KEY `u` (`Email Key`,`Subject Key`,`Subject Type`),
  KEY `Email Key` (`Email Key`),
  KEY `FK` (`Subject Key`),
  KEY `Subject Type` (`Subject Type`),
  KEY `Is Main` (`Is Main`),
  KEY `Is Active` (`Is Active`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Campaign Content Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Campaign Content Bridge` (
  `Email Campaign Key` mediumint(8) unsigned NOT NULL,
  `Email Content Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Email Campaign Key`,`Email Content Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Campaign Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Campaign Dimension` (
  `Email Campaign Key` mediumint(8) unsigned NOT NULL,
  `Email Campaign Type` enum('Newsletter','Marketing','Reminder') NOT NULL DEFAULT 'Marketing',
  `Email Campaign Store Key` smallint(5) unsigned NOT NULL,
  `Email Campaign Creation Date` datetime NOT NULL,
  `Email Campaign Last Updated Date` datetime NOT NULL,
  `Email Campaign Start Overdue Date` datetime DEFAULT NULL,
  `Email Campaign Start Send Date` datetime DEFAULT NULL,
  `Email Campaign End Send Date` datetime DEFAULT NULL,
  `Email Campaign Name` varchar(255) NOT NULL,
  `Email Campaign Scope` text NOT NULL,
  `Email Campaign Maximum Emails` mediumint(8) unsigned DEFAULT NULL,
  `Email Campaign Status` enum('Creating','Ready','Sending','Complete') NOT NULL,
  `Email Campaign Engine` enum('Internal','External') NOT NULL DEFAULT 'Internal',
  `Email Campaign Content Type` enum('HTML','Multi HTML','Plain','HTML Template','Multi Plain','Multi HTML Template','Multi Mixed','Unknown') NOT NULL DEFAULT 'Unknown',
  `Email Campaign Number Contents` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Email Campaign Subject` varchar(64) NOT NULL,
  `Email Campaign Recipients Preview` text NOT NULL,
  `Number of Emails` smallint(5) NOT NULL DEFAULT '0',
  `Number of Read Emails` smallint(5) NOT NULL DEFAULT '0',
  `Number of Rejected Emails` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Email Campaign Key`),
  UNIQUE KEY `Email Campaign Store Key_2` (`Email Campaign Store Key`,`Email Campaign Name`),
  KEY `Email Campaign Status` (`Email Campaign Status`),
  KEY `Email Campaign Store Key` (`Email Campaign Store Key`),
  KEY `Email Campaign Type` (`Email Campaign Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Campaign Mailing List`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Campaign Mailing List` (
  `Email Campaign Mailing List Key` mediumint(8) unsigned NOT NULL,
  `Email Campaign Key` mediumint(8) unsigned NOT NULL,
  `Email Content Key` mediumint(8) unsigned NOT NULL,
  `Email Key` mediumint(8) unsigned DEFAULT NULL,
  `Email Address` varchar(255) NOT NULL,
  `Email Contact Name` varchar(255) NOT NULL,
  `Email Send Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Email Campaign Mailing List Key`),
  UNIQUE KEY `Email Address` (`Email Address`,`Email Campaign Key`),
  KEY `Email Campaign Key` (`Email Campaign Key`,`Email Key`,`Email Send Key`),
  KEY `Email Content Key` (`Email Content Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Campaign Objective Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Campaign Objective Dimension` (
  `Email Campaign Key` mediumint(8) unsigned NOT NULL,
  `Email Campaign Scope Type` enum('Context','Link') NOT NULL DEFAULT 'Context',
  `Email Campaign Objective Parent` enum('Product','Family','Department','Store','Campaign','Deal','Store Page','External Link') NOT NULL,
  `Email Campaign Objective Parent Key` mediumint(8) unsigned DEFAULT NULL,
  `Email Campaign Objective Name` varchar(255) NOT NULL,
  `Email Campaign Objective Links` mediumint(8) unsigned DEFAULT '0',
  `Email Campaign Objetive Links Clicks` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Email Campaign Objective Term` enum('Order','Buy','Visit','Use') NOT NULL,
  `Email Campaign Objetive Term Metadata` varchar(1028) NOT NULL,
  KEY `Email Campaign Scope Type` (`Email Campaign Scope Type`),
  KEY `Email Campaign Key` (`Email Campaign Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Campaign Objective Link Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Campaign Objective Link Bridge` (
  `Email Campaign Objective Key` mediumint(8) unsigned NOT NULL,
  `Email Link Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Email Campaign Objective Key`,`Email Link Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Content Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Content Dimension` (
  `Email Content Key` mediumint(8) unsigned NOT NULL,
  `Email Content Type` enum('Plain','HTML Template','HTML') DEFAULT NULL,
  `Email Content Template Type` enum('Basic','Left Column','Right Column','Postcard') NOT NULL DEFAULT 'Basic',
  `Email Content Color Scheme Key` mediumint(9) NOT NULL DEFAULT '1',
  `Email Content Color Scheme Historic Key` mediumint(8) unsigned DEFAULT NULL,
  `Email Content Subject` varchar(64) NOT NULL,
  `Email Content Text` mediumtext NOT NULL,
  `Email Content HTML` mediumtext NOT NULL,
  `Email Content Metadata` mediumtext,
  `Email Template Header Image Key` mediumint(8) unsigned DEFAULT NULL,
  `Email Content Template Postcard Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Email Content Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Content Paragraph Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Content Paragraph Dimension` (
  `Email Paragraph Key` mediumint(8) unsigned NOT NULL,
  `Email Content Key` mediumint(8) unsigned NOT NULL,
  `Paragraph Order` smallint(6) NOT NULL,
  `Paragraph Type` enum('Main','Side') CHARACTER SET latin1 NOT NULL DEFAULT 'Main',
  `Paragraph Original Type` enum('Main','Side') CHARACTER SET latin1 NOT NULL DEFAULT 'Main',
  `Paragraph Title` varchar(255) NOT NULL,
  `Paragraph Subtitle` varchar(1024) NOT NULL,
  `Paragraph Content` mediumtext NOT NULL,
  PRIMARY KEY (`Email Paragraph Key`),
  KEY `Email Content Key` (`Email Content Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Credentials Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Credentials Dimension` (
  `Email Credentials Method` enum('Amazon','SMTP','Direct') NOT NULL DEFAULT 'SMTP',
  `Email Credentials Key` mediumint(8) NOT NULL,
  `Email Provider` enum('Gmail','Other','Inikoo','PHPMail','MadMimi') NOT NULL DEFAULT 'Other',
  `Email Address Gmail` varchar(255) DEFAULT NULL,
  `Password Gmail` varchar(1024) DEFAULT NULL,
  `Incoming Mail Server` varchar(255) DEFAULT NULL,
  `Outgoing Mail Server` varchar(255) DEFAULT NULL,
  `Amazon Access Key` varchar(255) DEFAULT NULL,
  `Amazon Secret Key` varchar(255) DEFAULT NULL,
  `Email Address Amazon Mail` varchar(255) DEFAULT NULL,
  `Email Address Direct Mail` varchar(255) DEFAULT NULL,
  `Email Address Other` varchar(255) DEFAULT NULL,
  `Password Other` varchar(1024) DEFAULT NULL,
  `Login Other` varchar(255) DEFAULT NULL,
  `API Email Address MadMimi` varchar(255) DEFAULT NULL,
  `API Key MadMimi` varchar(255) DEFAULT NULL,
  `Email Address MadMimi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Email Credentials Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Credentials Scope Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Credentials Scope Bridge` (
  `Email Credentials Key` mediumint(8) unsigned NOT NULL,
  `Scope` enum('Customer Communications','Newsletters','Marketing Email','Site Registration','Bugs','Requests') NOT NULL,
  PRIMARY KEY (`Email Credentials Key`,`Scope`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Credentials Scope Mailbox`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Credentials Scope Mailbox` (
  `Email Credentials Key` mediumint(8) unsigned NOT NULL,
  `Scope` enum('Customer Communications') CHARACTER SET latin1 NOT NULL,
  `Mailbox` varchar(255) CHARACTER SET latin1 NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Credentials Site Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Credentials Site Bridge` (
  `Email Credentials Key` mediumint(8) NOT NULL,
  `Site Key` mediumint(8) NOT NULL,
  PRIMARY KEY (`Email Credentials Key`,`Site Key`),
  KEY `Site Key` (`Site Key`),
  KEY `Email Credentials Key` (`Email Credentials Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Credentials Store Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Credentials Store Bridge` (
  `Email Credentials Key` mediumint(8) unsigned NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Email Credentials Key`,`Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Credentials User Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Credentials User Bridge` (
  `Email Credentials Key` mediumint(8) unsigned NOT NULL,
  `User Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Email Credentials Key`,`User Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Dimension` (
  `Email Key` mediumint(8) unsigned NOT NULL,
  `Email` varchar(320) NOT NULL,
  `Email Contact Name` varchar(255) DEFAULT NULL,
  `Email Validated` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Email Correct` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  PRIMARY KEY (`Email Key`),
  UNIQUE KEY `Email_2` (`Email`(64)),
  KEY `Email Validated` (`Email Validated`),
  KEY `Email Verified` (`Email Correct`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Link Click Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Link Click Fact` (
  `Email Link Key` int(10) unsigned NOT NULL,
  `Email Link Click Date` datetime NOT NULL,
  `IP` varchar(16) NOT NULL,
  `OS` varchar(64) NOT NULL,
  `Browser` varchar(64) NOT NULL,
  KEY `Email Link Key` (`Email Link Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Link Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Link Dimension` (
  `Email Link Dimension Key` mediumint(8) NOT NULL,
  `Email Link URL` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Email Link Dimension Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Queue Attachement Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Queue Attachement Dimension` (
  `Attachement Key` int(11) NOT NULL,
  `Email Queue Key` int(11) NOT NULL,
  `Data` varchar(255) DEFAULT NULL,
  `FileName` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Content-Type` varchar(255) NOT NULL,
  `Disposition` varchar(255) NOT NULL,
  PRIMARY KEY (`Attachement Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Queue Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Queue Dimension` (
  `Email Queue Key` mediumint(8) NOT NULL,
  `To` varchar(255) NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Plain` longtext,
  `HTML` longtext,
  `Email Credentials Key` mediumint(8) NOT NULL,
  `BCC` varchar(1024) DEFAULT NULL,
  `Status` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Plain','HTML') NOT NULL DEFAULT 'Plain',
  PRIMARY KEY (`Email Queue Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Read Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Read Dimension` (
  `Email Read Key` mediumint(8) unsigned NOT NULL,
  `Email Credentials Key` mediumint(8) unsigned NOT NULL,
  `Email Uid` varchar(255) NOT NULL,
  `Customer Communications` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Email Read Key`),
  KEY `Email Credentials Key` (`Email Credentials Key`),
  KEY `Email Uid` (`Email Uid`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Send Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Send Dimension` (
  `Email Send Key` int(10) unsigned NOT NULL,
  `Email Send Type` enum('Test Email','Marketing','Registration','Password Reminder','Newsletter','Order Confirmation','Delivery Confirmation','Issue Reporting') NOT NULL,
  `Email Send Type Key` mediumint(9) NOT NULL DEFAULT '0',
  `Email Send Type Parent Key` mediumint(8) unsigned DEFAULT NULL,
  `Email Send Recipient Type` enum('Customer','Supplier','User','Other','Staff') NOT NULL DEFAULT 'Other',
  `Email Send Recipient Key` mediumint(8) unsigned DEFAULT NULL,
  `Email Key` mediumint(8) unsigned DEFAULT NULL,
  `Email Send Creation Date` datetime NOT NULL,
  `Email Send Date` datetime DEFAULT NULL,
  `Email Send First Read Date` datetime DEFAULT NULL,
  `Email Send Last Read Date` datetime DEFAULT NULL,
  `Email Send Number Reads` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`Email Send Key`),
  KEY `Email Key` (`Email Key`),
  KEY `Email Send Type` (`Email Send Type`),
  KEY `Email Send Parent Key` (`Email Send Recipient Key`),
  KEY `Email Send Parent Type` (`Email Send Recipient Type`),
  KEY `Email Send Type Key` (`Email Send Type Key`),
  KEY `Email Send Type Parent Key` (`Email Send Type Parent Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Send Read Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Send Read Fact` (
  `Email Send Key` int(10) unsigned NOT NULL,
  `Email Send Read Date` datetime NOT NULL,
  `IP` varchar(16) NOT NULL,
  `OS` varchar(64) NOT NULL,
  `Browser` varchar(64) NOT NULL,
  KEY `Email Send Key` (`Email Send Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Site Reminder Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Site Reminder Dimension` (
  `Email Site Reminder Key` mediumint(8) unsigned NOT NULL,
  `Email Site Reminder State` enum('Waiting','Ready','Sent','Cancelled') NOT NULL DEFAULT 'Waiting',
  `Email Site Reminder In Process` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Email Site Reminder Subject` enum('Customer','User') NOT NULL DEFAULT 'User',
  `User Key` mediumint(9) DEFAULT NULL,
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Customer Name` varchar(255) NOT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Trigger Scope` enum('Back in Stock') NOT NULL,
  `Trigger Scope Key` mediumint(8) unsigned DEFAULT NULL,
  `Trigger Scope Name` varchar(64) NOT NULL,
  `Creator Subject` enum('Staff','User') NOT NULL,
  `Creator Subject Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Creation Date` datetime NOT NULL,
  `Finish Date` datetime DEFAULT NULL,
  PRIMARY KEY (`Email Site Reminder Key`),
  KEY `Customer Site Email Reminder State` (`Email Site Reminder State`),
  KEY `User Key` (`User Key`),
  KEY `Email Site Reminder Subject` (`Email Site Reminder Subject`),
  KEY `Email Site Reminder In Process` (`Email Site Reminder In Process`),
  KEY `Email Site Reminder In Process_2` (`Email Site Reminder In Process`,`Trigger Scope`,`Trigger Scope Key`,`User Key`),
  KEY `Email Site Reminder State` (`Email Site Reminder State`,`Trigger Scope`,`Trigger Scope Key`),
  KEY `Trigger Scope Name` (`Trigger Scope Name`),
  KEY `Customer Name` (`Customer Name`(64))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Template Color Scheme Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Template Color Scheme Dimension` (
  `Email Template Color Scheme Key` mediumint(8) unsigned NOT NULL,
  `Email Template Color Scheme Name` varchar(255) NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Background Body` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `Background Header` varchar(6) NOT NULL DEFAULT '000000',
  `Background Container` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `Background Footer` varchar(6) NOT NULL DEFAULT '000000',
  `Text Header` varchar(6) NOT NULL DEFAULT '000000',
  `Text Container` varchar(6) NOT NULL DEFAULT '000000',
  `Text Footer` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `Link Header` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `Link Container` varchar(6) NOT NULL DEFAULT '000000',
  `Link Footer` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `H1` varchar(6) NOT NULL DEFAULT '000000',
  `H2` varchar(6) NOT NULL DEFAULT '000000',
  `Header Image Source` varchar(255) NOT NULL,
  PRIMARY KEY (`Email Template Color Scheme Key`),
  KEY `Store Key` (`Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Template Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Template Dimension` (
  `Email Template Key` mediumint(8) unsigned NOT NULL,
  `Email Template Name` varchar(64) NOT NULL,
  `Email Template Type` enum('Basic','Newsletter Left','Newsletter Right','Postcard') NOT NULL,
  `Email Template Metadata` mediumtext NOT NULL,
  `Email Template Source Code` mediumtext NOT NULL,
  PRIMARY KEY (`Email Template Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Template Header Image Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Template Header Image Dimension` (
  `Email Template Header Image Key` mediumint(8) unsigned NOT NULL,
  `Email Template Header Image Name` varchar(255) NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Image Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Email Template Header Image Key`),
  UNIQUE KEY `Store Key_2` (`Store Key`,`Image Key`),
  KEY `Store Key` (`Store Key`),
  KEY `Image Key` (`Image Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Template Historic Color Scheme Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Template Historic Color Scheme Dimension` (
  `Email Template Historic Color Scheme Key` mediumint(8) unsigned NOT NULL,
  `Background Body` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `Background Header` varchar(6) NOT NULL DEFAULT '000000',
  `Background Container` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `Background Footer` varchar(6) NOT NULL DEFAULT '000000',
  `Text Header` varchar(6) NOT NULL DEFAULT '000000',
  `Text Container` varchar(6) NOT NULL DEFAULT '000000',
  `Text Footer` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `Link Header` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `Link Container` varchar(6) NOT NULL DEFAULT '000000',
  `Link Footer` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `H1` varchar(6) NOT NULL DEFAULT '000000',
  `H2` varchar(6) NOT NULL DEFAULT '000000',
  PRIMARY KEY (`Email Template Historic Color Scheme Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Email Template Postcard Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Email Template Postcard Dimension` (
  `Email Template Postcard Key` mediumint(8) unsigned NOT NULL,
  `Email Template Postcard Name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Image Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Email Template Postcard Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Employee Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Employee Dimension` (
  `Employee Key` mediumint(8) unsigned NOT NULL,
  `Employee ID` varchar(255) NOT NULL COMMENT '(Natural Key)',
  `Employee Contact Key` mediumint(9) NOT NULL,
  `Employee Alias` varchar(255) DEFAULT NULL,
  `Employee Name` varchar(255) NOT NULL,
  `Employee Address` varchar(1024) NOT NULL,
  `Employee Job Grade` varchar(255) NOT NULL,
  `Employee Salary` decimal(9,2) NOT NULL,
  `Employee Salary Interval` enum('Hour','DAY','Week','Fortnight','Month','Year') NOT NULL,
  `Employee Education` varchar(255) NOT NULL,
  `Employee Original Hire Date` datetime NOT NULL,
  `Employee Last Review Date` datetime NOT NULL,
  `Employee Holidays per Year` float NOT NULL,
  `Employee Most Recent` enum('Yes','No') NOT NULL,
  `Employee Status` enum('Working','No Working') NOT NULL,
  PRIMARY KEY (`Employee Key`),
  KEY `Most Recent Transaction Indicator` (`Employee Most Recent`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Export Map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Export Map` (
  `Map Key` int(11) NOT NULL,
  `Map Name` varchar(255) NOT NULL,
  `Map Description` text,
  `Map Type` enum('Customer','Supplier') NOT NULL,
  `Map Data` longtext NOT NULL,
  `Customer Key` int(11) NOT NULL,
  `Export Header` enum('yes','no') NOT NULL,
  `Export Map Default` enum('yes','no') NOT NULL,
  `Exported Date` datetime NOT NULL,
  PRIMARY KEY (`Map Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Fork Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Fork Dimension` (
  `Fork Key` mediumint(8) unsigned NOT NULL,
  `Fork Type` enum('au_export','au_upload','au_housekeeping','export','import','edit','ping_sitemap','housekeeping','sendemail','edit_pages') NOT NULL,
  `Fork Process Data` text NOT NULL,
  `Fork Token` varchar(64) DEFAULT NULL,
  `Fork State` enum('Queued','In Process','Finished','Cancelled') NOT NULL DEFAULT 'Queued',
  `Fork Operations Done` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Fork Operations No Changed` mediumint(9) NOT NULL DEFAULT '0',
  `Fork Operations Errors` mediumint(9) NOT NULL DEFAULT '0',
  `Fork Operations Cancelled` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Fork Operations Total Operations` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Fork Scheduled Date` datetime DEFAULT NULL,
  `Fork Start Date` datetime DEFAULT NULL,
  `Fork Finished Date` datetime DEFAULT NULL,
  `Fork Cancelled Date` datetime DEFAULT NULL,
  `Fork Result` text,
  `Fork Result Metadata` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Fork Key`),
  KEY `Fork Token` (`Fork Token`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `HQ Event Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HQ Event Dimension` (
  `HQ Event Key` int(11) NOT NULL,
  `Subject` enum('Others','National Holiday','Bank Holiday','Festive Holiday') DEFAULT 'Others',
  `Location` varchar(200) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `End Time` datetime DEFAULT NULL,
  `Is All Day Event` smallint(6) NOT NULL,
  `Color` varchar(200) DEFAULT '3',
  `Recurring Rule` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`HQ Event Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `HR History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HR History Bridge` (
  `HR Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes',
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `History Currency Exchange Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `History Currency Exchange Dimension` (
  `Date` date NOT NULL,
  `Currency Pair` varchar(6) NOT NULL,
  `Exchange` decimal(18,8) NOT NULL,
  UNIQUE KEY `Date` (`Date`,`Currency Pair`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `History Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `History Dimension` (
  `History Key` int(10) unsigned NOT NULL,
  `Author Name` varchar(255) DEFAULT NULL,
  `Subject` enum('Customer','Staff','Supplier','Administrator','System') DEFAULT NULL,
  `Subject Key` mediumint(8) unsigned DEFAULT NULL,
  `Action` enum('sold_since','last_sold','first_sold','placed','wrote','deleted','edited','cancelled','charged','merged','created','associated','disassociate','register','login','logout','fail_login','password_request','password_reset','search') DEFAULT 'edited',
  `Direct Object` enum('After Sale','Delivery Note','Category','Warehouse','Warehouse Area','Shelf','Location','Company Department','Company Area','Position','Store','User','Product','Address','Customer','Note','Order','Telecom','Email','Company','Contact','FAX','Telephone','Mobile','Work Telephone','Office Fax','Supplier','Family','Department','Attachment','Supplier Product','Part','Site','Page','Invoice','Category Customer','Category Part','Category Invoice','Category Supplier','Category Product','Category Family','Purchase Order','Supplier Delivery Note','Supplier Invoice') DEFAULT NULL,
  `Direct Object Key` mediumint(8) unsigned DEFAULT '0',
  `Preposition` enum('about','','to','on','because') DEFAULT NULL,
  `Indirect Object` varchar(128) DEFAULT NULL,
  `Indirect Object Key` mediumint(8) unsigned DEFAULT NULL,
  `History Abstract` text,
  `History Details` text,
  `History Date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `User Key` mediumint(8) unsigned DEFAULT NULL,
  `Deep` enum('1','2') NOT NULL DEFAULT '1',
  `Metadata` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`History Key`,`History Date`),
  KEY `Action` (`Action`),
  KEY `History Date` (`History Date`),
  KEY `Direct Object` (`Direct Object`,`Direct Object Key`),
  KEY `Deep` (`Deep`),
  KEY `Indirect Object` (`Indirect Object`(24),`Indirect Object Key`),
  KEY `Subject` (`Subject`,`Subject Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Human Resources Spanshot Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Human Resources Spanshot Fact` (
  `Human Resources Spanshot Key` mediumint(8) unsigned NOT NULL,
  `Date Key` date NOT NULL,
  `Employee Transition Key` mediumint(9) NOT NULL,
  `Organization Key` smallint(6) NOT NULL,
  `Salary Paid` decimal(9,2) NOT NULL,
  `Worked Hours` float NOT NULL,
  `Overtime Paid` decimal(9,2) NOT NULL,
  `Overtime Hours` float NOT NULL,
  `Bonus Paid` decimal(9,2) NOT NULL,
  `Retirement Fund Paid` decimal(9,2) NOT NULL,
  `Retirement Fund Employee Contribution` decimal(10,0) NOT NULL,
  `Vacation days Accrued` float NOT NULL,
  `Vacation Days Taken` float NOT NULL,
  `Vacation Day Balance` float NOT NULL,
  `Employee Count` smallint(6) NOT NULL,
  `Transfer Count` smallint(6) NOT NULL,
  `Promotion Count` smallint(6) NOT NULL,
  PRIMARY KEY (`Human Resources Spanshot Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Image Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Image Bridge` (
  `Subject Type` enum('Site Favicon','Product','Family','Department','Store','Part','Supplier Product','Store Logo','Store Email Template Header','Store Email Postcard','Email Image','Page','Page Header','Page Footer','Page Header Preview','Page Footer Preview','Page Preview','Site Menu','Site Search','User Profile','Attachment Thumbnail') DEFAULT NULL,
  `Subject Key` mediumint(8) unsigned NOT NULL,
  `Image Key` mediumint(8) unsigned NOT NULL,
  `Is Principal` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Image Caption` varchar(1024) NOT NULL,
  UNIQUE KEY `unique` (`Subject Type`,`Subject Key`,`Image Key`),
  KEY `Subject Key` (`Subject Key`),
  KEY `Image Key` (`Image Key`),
  KEY `Subject Type` (`Subject Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Image Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Image Dimension` (
  `Image Key` mediumint(8) unsigned NOT NULL,
  `Image Data` longblob NOT NULL,
  `Image Thumbnail Data` longblob,
  `Image Small Data` longblob,
  `Image Large Data` longblob,
  `Image Filename` varchar(255) NOT NULL,
  `Image File Checksum` varchar(32) NOT NULL,
  `Image Width` smallint(5) unsigned NOT NULL,
  `Image Height` smallint(5) unsigned NOT NULL,
  `Image File Size` mediumint(8) unsigned NOT NULL,
  `Image File Format` enum('jpeg','png','gif') NOT NULL DEFAULT 'jpeg',
  `Image Original Filename` varchar(255) NOT NULL,
  `Image Public` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Last Modify Date` datetime DEFAULT NULL,
  PRIMARY KEY (`Image Key`),
  KEY `Image Checksum` (`Image File Checksum`),
  KEY `Image Public` (`Image Public`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Image Subject Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Image Subject Bridge` (
  `Image Subject Key` mediumint(8) unsigned NOT NULL,
  `Image Subject Object` enum('Store Product','Site Favicon','Product','Family','Department','Store','Part','Supplier Product','Store Logo','Store Email Template Header','Store Email Postcard','Email Image','Page','Page Header','Page Footer','Page Header Preview','Page Footer Preview','Page Preview','Site Menu','Site Search','User Profile','Attachment Thumbnail','Category','Staff') DEFAULT NULL,
  `Image Subject Object Key` mediumint(8) unsigned NOT NULL,
  `Image Subject Image Key` mediumint(8) unsigned NOT NULL,
  `Image Subject Is Principal` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Image Subject Image Caption` varchar(1024) NOT NULL,
  `Image Subject Is Public` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Image Subject Order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Image Subject Date` datetime DEFAULT NULL,
  PRIMARY KEY (`Image Subject Key`),
  UNIQUE KEY `unique` (`Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key`),
  KEY `Subject Key` (`Image Subject Object Key`),
  KEY `Image Key` (`Image Subject Image Key`),
  KEY `Subject Type` (`Image Subject Object`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Import Map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Import Map` (
  `Map Key` mediumint(8) unsigned NOT NULL,
  `Subject` varchar(64) NOT NULL,
  `Parent Key` mediumint(8) NOT NULL,
  `Parent` varchar(64) NOT NULL,
  `Map Name` varchar(255) NOT NULL,
  `Meta Data` text NOT NULL,
  PRIMARY KEY (`Map Key`),
  KEY `Scope` (`Parent`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Imported Record`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Imported Record` (
  `Imported Record Key` int(10) unsigned NOT NULL,
  `Imported Record Import State` enum('Ignored','Waiting','Importing','Imported','Error','Cancelled') NOT NULL DEFAULT 'Waiting',
  `Imported Record Date` datetime DEFAULT NULL,
  `Imported Record Index` mediumint(8) unsigned NOT NULL,
  `Imported Record Parent Key` mediumint(8) unsigned NOT NULL,
  `Imported Record Data` text NOT NULL,
  `Imported Record Subject Key` mediumint(8) unsigned DEFAULT NULL,
  `Imported Record XHTML Note` varchar(255) DEFAULT '',
  `Imported Record Note` varchar(255) DEFAULT '',
  PRIMARY KEY (`Imported Record Key`),
  KEY `Imported Records Key` (`Imported Record Parent Key`),
  KEY `Imported Record Index` (`Imported Record Index`),
  KEY `Imported Record Import State` (`Imported Record Import State`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Imported Records Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Imported Records Dimension` (
  `Imported Records Key` mediumint(9) NOT NULL,
  `Imported Records State` enum('Uploading','Review','Queued','InProcess','Finished') NOT NULL DEFAULT 'Uploading',
  `Imported Records File Checksum` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `Imported Records Creation Date` datetime NOT NULL,
  `Imported Records Start Date` datetime DEFAULT NULL,
  `Imported Records Finish Date` datetime DEFAULT NULL,
  `Imported Records Cancelled Date` datetime DEFAULT NULL,
  `Imported Records Subject` varchar(64) NOT NULL,
  `Imported Records Parent` varchar(64) NOT NULL,
  `Imported Records Parent Key` mediumint(8) unsigned DEFAULT NULL,
  `Imported Original Records` int(11) NOT NULL DEFAULT '0',
  `Imported Ignored Records` int(11) NOT NULL DEFAULT '0',
  `Imported Imported Records` int(11) NOT NULL DEFAULT '0',
  `Imported Importing Records` int(11) unsigned NOT NULL DEFAULT '0',
  `Imported Waiting Records` int(11) unsigned NOT NULL DEFAULT '0',
  `Imported Error Records` int(11) NOT NULL DEFAULT '0',
  `Imported Cancelled Records` int(10) unsigned NOT NULL DEFAULT '0',
  `Imported Records Log` longtext,
  `Imported Records Subject List Key` mediumint(8) unsigned DEFAULT NULL,
  `Imported Records Subject List Name` varchar(255) DEFAULT NULL,
  `Imported Records File Name` varchar(255) NOT NULL,
  `Imported Records File Size` int(10) unsigned NOT NULL,
  `Imported Records User Key` mediumint(8) unsigned NOT NULL,
  `Imported Records Number Columns` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Imported First Record Key` mediumint(8) unsigned DEFAULT NULL,
  `Imported Records Options Map` text,
  `Imported Records Fork Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Imported Records Key`),
  KEY `Imported Records User Key` (`Imported Records User Key`),
  KEY `Impoted Records State` (`Imported Records State`),
  KEY `Imported Records Cancelled Date` (`Imported Records Cancelled Date`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Insurance Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Insurance Dimension` (
  `Insurance Key` mediumint(8) unsigned NOT NULL,
  `Insurance Store Key` smallint(6) NOT NULL,
  `Insurance Tax Category Code` varchar(16) DEFAULT NULL,
  `Insurance Name` varchar(255) NOT NULL,
  `Insurance Description` text NOT NULL,
  `Insurance Trigger` enum('Order','Family','Product') DEFAULT NULL,
  `Insurance Trigger Key` mediumint(9) DEFAULT NULL,
  `Insurance Type` enum('Percentage','Amount') NOT NULL DEFAULT 'Amount',
  `Insurance Metadata` varchar(255) NOT NULL,
  `Insurance Terms Type` enum('Order Interval','Product Quantity Ordered','Family Quantity Ordered','Total Amount','Order Number','Shipping Country','Shipping Country First Division','Order Items Gross Amount','Order Number Products') DEFAULT NULL,
  `Insurance Terms Description` varchar(255) DEFAULT NULL,
  `Insurance Terms Metadata` varchar(255) DEFAULT NULL,
  `Insurance Active` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Insurance Begin Date` datetime DEFAULT NULL,
  `Insurance Expiration Date` datetime DEFAULT NULL,
  PRIMARY KEY (`Insurance Key`),
  KEY `Insurance Store Key` (`Insurance Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Inventory Audit Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inventory Audit Dimension` (
  `Inventory Audit Key` mediumint(8) unsigned NOT NULL,
  `Inventory Audit Type` enum('Audit','Discontinued','Out of Stock','Disassociate','Identify') NOT NULL DEFAULT 'Audit',
  `Inventory Audit Date` datetime NOT NULL,
  `Inventory Audit Part SKU` mediumint(8) unsigned NOT NULL,
  `Inventory Audit Location Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Inventory Audit Quantity` float NOT NULL,
  `Inventory Audit Note` varchar(1024) NOT NULL,
  `Inventory Audit User Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Inventory Audit Key`),
  KEY `Inventory Audit Part SKU` (`Inventory Audit Part SKU`),
  KEY `Inventory Audit Location Key` (`Inventory Audit Location Key`),
  KEY `Inventory Audit User Key` (`Inventory Audit User Key`),
  KEY `Inventory Audit Type` (`Inventory Audit Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Inventory Spanshot Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inventory Spanshot Fact` (
  `Date` date NOT NULL,
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Warehouse Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Location Key` smallint(5) unsigned DEFAULT NULL,
  `Quantity On Hand` float DEFAULT NULL,
  `Value At Cost` decimal(9,2) DEFAULT NULL,
  `Value At Day Cost` decimal(9,2) DEFAULT NULL,
  `Sold Amount` decimal(9,2) DEFAULT NULL,
  `Value Commercial` decimal(9,2) DEFAULT NULL,
  `Storing Cost` decimal(12,2) DEFAULT NULL,
  `Quantity Sold` float NOT NULL DEFAULT '0',
  `Quantity In` float NOT NULL DEFAULT '0',
  `Quantity Lost` float NOT NULL DEFAULT '0',
  `Quantity Open` float DEFAULT NULL,
  `Quantity High` float DEFAULT NULL,
  `Quantity Low` float DEFAULT NULL,
  `Value At Cost Open` float NOT NULL DEFAULT '0',
  `Value At Cost High` float NOT NULL DEFAULT '0',
  `Value At Cost Low` float NOT NULL DEFAULT '0',
  `Value At Day Cost Open` float NOT NULL DEFAULT '0',
  `Value At Day Cost High` float NOT NULL DEFAULT '0',
  `Value At Day Cost Low` float NOT NULL DEFAULT '0',
  `Value Commercial Open` float NOT NULL DEFAULT '0',
  `Value Commercial High` float NOT NULL DEFAULT '0',
  `Value Commercial Low` float NOT NULL DEFAULT '0',
  `Location Type` enum('Picking','Storing','Displaying','Unknown') NOT NULL DEFAULT 'Unknown',
  `Stock Available` decimal(3,2) NOT NULL DEFAULT '1.00',
  `Dormant 1 Year` enum('Yes','No','NA') NOT NULL DEFAULT 'NA',
  UNIQUE KEY `Date_2` (`Date`,`Part SKU`,`Location Key`),
  KEY `Part Key` (`Part SKU`),
  KEY `Date` (`Date`),
  KEY `Location Key` (`Location Key`),
  KEY `Warehouse Key` (`Warehouse Key`),
  KEY `Location Type` (`Location Type`),
  KEY `Dormant 1 Year` (`Dormant 1 Year`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Inventory Transaction Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inventory Transaction Fact` (
  `Inventory Transaction Key` int(10) unsigned NOT NULL,
  `Date Created` datetime DEFAULT NULL,
  `Date Picked` datetime DEFAULT NULL,
  `Date Packed` datetime DEFAULT NULL,
  `Date Shipped` datetime DEFAULT NULL,
  `Date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Part SKU` mediumint(8) unsigned DEFAULT NULL,
  `Delivery Note Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Product Historic Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Product ID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Warehouse Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Location Key` smallint(6) unsigned NOT NULL DEFAULT '1',
  `Inventory Transaction Record Type` enum('Movement','Helper') NOT NULL DEFAULT 'Helper',
  `Inventory Transaction Type` enum('Move','Order In Process','No Dispatched','Sale','Audit','In','Adjust','Broken','Lost','Not Found','Associate','Disassociate','Move In','Move Out','Other Out') NOT NULL,
  `Inventory Transaction Section` enum('OIP','In','Move','Out','Audit','NoDispatched','Other') NOT NULL DEFAULT 'Other',
  `Inventory Transaction Quantity` decimal(24,6) DEFAULT NULL,
  `Inventory Transaction Amount` decimal(12,3) DEFAULT NULL,
  `Inventory Transaction Weight` float DEFAULT NULL,
  `Inventory Transaction Storing Charge Amount` decimal(9,2) NOT NULL DEFAULT '0.00',
  `Required` float NOT NULL DEFAULT '0',
  `Picked` float NOT NULL DEFAULT '0',
  `Packed` float NOT NULL DEFAULT '0',
  `Out of Stock` float NOT NULL DEFAULT '0',
  `Out of Stock Lost Amount` float NOT NULL DEFAULT '0',
  `No Authorized` float NOT NULL DEFAULT '0',
  `Not Found` float NOT NULL DEFAULT '0',
  `No Picked Other` float NOT NULL DEFAULT '0',
  `Given` float NOT NULL DEFAULT '0',
  `Amount In` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Picker Key` mediumint(9) DEFAULT NULL,
  `Packer Key` mediumint(9) DEFAULT NULL,
  `Metadata` varchar(64) DEFAULT NULL,
  `User Key` mediumint(8) unsigned DEFAULT '0',
  `Note` text,
  `Picking Note` varchar(255) DEFAULT NULL,
  `History Type` enum('Details','Normal','Admin') NOT NULL DEFAULT 'Normal',
  `Event Order` tinyint(3) NOT NULL DEFAULT '0',
  `Map To Order Transaction Fact Key` int(10) unsigned DEFAULT NULL,
  `Map To Order Transaction Fact Metadata` varchar(255) DEFAULT NULL,
  `Relations` varchar(32) DEFAULT NULL,
  `Part Location Stock` float NOT NULL DEFAULT '0',
  `Part Stock` float NOT NULL DEFAULT '0',
  `Dispatch Country Code` char(3) NOT NULL DEFAULT 'UNK',
  `Out of Stock Tag` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Map To Order Transaction Fact Parts Multiplicity` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Map To Order Transaction Fact XHTML Info` text,
  KEY `Date` (`Date`),
  KEY `Part SKU` (`Part SKU`),
  KEY `Metadata` (`Metadata`(12)),
  KEY `User Key` (`User Key`),
  KEY `Location Key` (`Location Key`),
  KEY `Supplier Product Key` (`Supplier Product ID`),
  KEY `History Type` (`History Type`),
  KEY `Warehouse Key` (`Warehouse Key`),
  KEY `Delivery Note Key` (`Delivery Note Key`),
  KEY `Picker Key` (`Picker Key`),
  KEY `Packer Key` (`Packer Key`),
  KEY `Map To Order Transaction Fact Key` (`Map To Order Transaction Fact Key`),
  KEY `Dispatch Country Code` (`Dispatch Country Code`),
  KEY `Inventory Transaction Key` (`Inventory Transaction Key`),
  KEY `Out of Stock` (`Out of Stock`),
  KEY `Supplier Key` (`Supplier Key`),
  KEY `Supplier Product Historic Key` (`Supplier Product Historic Key`),
  KEY `Out of Stock Tag` (`Out of Stock Tag`),
  KEY `Inventory Transaction Section` (`Inventory Transaction Section`),
  KEY `Inventory Transaction Record Type` (`Inventory Transaction Record Type`),
  KEY `Inventory Transaction Type` (`Inventory Transaction Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Inventory Warehouse Spanshot Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inventory Warehouse Spanshot Fact` (
  `Date` date NOT NULL,
  `Warehouse Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Parts` mediumint(8) unsigned NOT NULL,
  `Locations` mediumint(8) unsigned NOT NULL,
  `Value At Cost` decimal(9,2) DEFAULT NULL,
  `Value At Day Cost` decimal(9,2) DEFAULT NULL,
  `Value Commercial` decimal(9,2) DEFAULT NULL,
  `Value At Cost Open` float NOT NULL DEFAULT '0',
  `Value At Cost High` float NOT NULL DEFAULT '0',
  `Value At Cost Low` float NOT NULL DEFAULT '0',
  `Value At Day Cost Open` float NOT NULL DEFAULT '0',
  `Value At Day Cost High` float NOT NULL DEFAULT '0',
  `Value At Day Cost Low` float NOT NULL DEFAULT '0',
  `Value Commercial Open` float NOT NULL DEFAULT '0',
  `Value Commercial High` float NOT NULL DEFAULT '0',
  `Value Commercial Low` float NOT NULL DEFAULT '0',
  `Dormant 1 Year Value At Day Cost` decimal(9,2) NOT NULL DEFAULT '0.00',
  UNIQUE KEY `Date_Warehouse` (`Date`,`Warehouse Key`),
  KEY `Date` (`Date`),
  KEY `Warehouse Key` (`Warehouse Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Category Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Category Dimension` (
  `Invoice Category Key` mediumint(8) unsigned NOT NULL,
  `Invoice Category Store Key` mediumint(8) unsigned NOT NULL,
  `Invoice Category Total Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Total Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Total Acc Refunds` mediumint(9) NOT NULL DEFAULT '0',
  `Invoice Category Total Acc Paid` mediumint(9) NOT NULL DEFAULT '0',
  `Invoice Category Total Acc To Pay` mediumint(9) NOT NULL DEFAULT '0',
  `Invoice Category 3 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 6 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Quarter Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 10 Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Year To Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Month To Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Week To Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Today Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Yesterday Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 3 Year Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Year Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 6 Month Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Quarter Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Month Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 10 Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Week Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Year To Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Month To Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Week To Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Today Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Month Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Week Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Yesterday Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 3 Year Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Year Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 6 Month Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Quarter Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Month Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 10 Day Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Week Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Day Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Year To Day Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Month To Day Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Week To Day Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Today Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Month Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Week Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Yesterday Acc Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 3 Year Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Year Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 6 Month Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Quarter Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Month Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 10 Day Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Week Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Day Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Year To Day Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Month To Day Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Week To Day Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Today Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Month Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Week Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Yesterday Acc To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Year Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 6 Month Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Quarter Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Month Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 10 Day Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Week Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Day Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Year To Day Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Month To Day Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Week To Day Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Today Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Month Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Week Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Yesterday Acc 1YB Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Year Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 6 Month Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Quarter Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Month Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 10 Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Week Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Year To Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Month To Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Week To Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Today Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Month Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Week Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Yesterday Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Year Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 6 Month Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Quarter Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Month Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 10 Day Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Week Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Day Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Year To Day Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Month To Day Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Week To Day Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Today Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Month Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Week Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Yesterday Acc 1YB Paid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Year Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 6 Month Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Quarter Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Month Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 10 Day Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Week Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 1 Day Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Year To Day Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Month To Day Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Week To Day Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Today Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Month Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Last Week Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category Yesterday Acc 1YB To Pay` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoice Category 3 Year Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Year Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Year To Day Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Month To Day Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Month To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Week To Day Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Week To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 6 Month Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Quarter Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Quarter Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Month Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 10 Day Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Week Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Year Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Year To Day Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Year To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Month To Day Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Month To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Week To Day Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Week To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 6 Month Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 6 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Quarter Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Quarter Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 10 Day Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 10 Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Week Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Yesterday Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Yesterday Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Week Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Month Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Today Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Today Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Today Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Month Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Month Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Week Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Yesterday Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 3 Year Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 3 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Total Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 3 Year Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Year Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Year To Day Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Month To Day Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Month To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Week To Day Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Week To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 6 Month Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Quarter Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Quarter Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Month Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 10 Day Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Week Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Year Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Year To Day Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Year To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Month To Day Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Month To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Week To Day Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Week To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 6 Month Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 6 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Quarter Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Quarter Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 10 Day Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 10 Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Week Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Yesterday Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Yesterday Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Week Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Month Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Today Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Today Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Today Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Month Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Month Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Week Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Yesterday Acc Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 3 Year Acc 1YB Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 3 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Category DC 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  KEY `Category Key` (`Invoice Category Key`,`Invoice Category Store Key`),
  KEY `Category Key_2` (`Invoice Category Key`),
  KEY `Store Key` (`Invoice Category Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Category History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Category History Bridge` (
  `Store Key` smallint(5) unsigned NOT NULL,
  `Category Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Type` enum('Changes','Assign') CHARACTER SET latin1 NOT NULL,
  UNIQUE KEY `Store Key` (`Store Key`,`Category Key`,`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Charged By Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Charged By Bridge` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `Staff Key` mediumint(8) unsigned NOT NULL,
  `Share` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`Invoice Key`,`Staff Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Delivery Note Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Delivery Note Bridge` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `Delivery Note Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Invoice Key`,`Delivery Note Key`),
  KEY `Delivery Note Key` (`Delivery Note Key`),
  KEY `Invoice Key` (`Invoice Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Dimension` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `Invoice Date` datetime DEFAULT NULL COMMENT 'Date when the order where first placed',
  `Invoice Paid Date` datetime DEFAULT NULL,
  `Invoice Type` enum('Invoice','Refund','CreditNote') NOT NULL DEFAULT 'Invoice',
  `Invoice Public ID` varchar(32) DEFAULT NULL,
  `Invoice File As` varchar(32) DEFAULT NULL,
  `Invoice Title` varchar(255) DEFAULT NULL,
  `Invoice XHTML Orders` text,
  `Invoice XHTML Delivery Notes` text,
  `Invoice XHTML Store` text,
  `Invoice Store Key` tinyint(8) unsigned DEFAULT NULL,
  `Invoice Store Code` varchar(32) DEFAULT NULL,
  `Invoice Customer Key` mediumint(8) unsigned DEFAULT NULL,
  `Invoice Customer Name` varchar(255) NOT NULL DEFAULT 'Unknown Customer',
  `Invoice Customer Contact Name` varchar(255) NOT NULL,
  `Invoice Customer Level Type` enum('Normal','VIP','Partner','Staff') NOT NULL DEFAULT 'Normal',
  `Invoice XHTML Sales Representative` text,
  `Invoice XHTML Processed By` varchar(255) DEFAULT NULL,
  `Invoice XHTML Charged By` varchar(255) DEFAULT NULL,
  `Invoice Main Source Type` enum('Internet','Call','Store','Unknown','Email','Fax') NOT NULL DEFAULT 'Unknown',
  `Invoice Items Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Items Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Items Net Adjust Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Items Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Net Amount Off` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Invoice Shipping Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Charges Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Insurance Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Credit Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Invoice Bonus Amount Value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Items Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Shipping Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Charges Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Unknown Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Items Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Shipping Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Charges Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Unknown Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Total Net Adjust Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Total Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Items Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Shipping Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Charges Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Insurance Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Credit Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Invoice Refund Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Total Tax Adjust Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Total Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Total Adjust Amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Invoice Total Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Outstanding Net Balance` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Invoice Outstanding Tax Balance` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Invoice Paid Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Outstanding Total Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Total Profit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Invoice Main Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown','Customer Account') NOT NULL DEFAULT 'Unknown',
  `Invoice Payment Account Key` mediumint(9) DEFAULT NULL,
  `Invoice Payment Account Code` varchar(64) DEFAULT NULL,
  `Invoice Payment Key` mediumint(9) DEFAULT NULL,
  `Invoice XHTML Address` text,
  `Invoice Billing To Key` mediumint(8) unsigned DEFAULT NULL,
  `Invoice Has Been Paid In Full` enum('Yes','No') DEFAULT 'No',
  `Invoice Metadata` varchar(8) DEFAULT NULL,
  `Invoice Billing Country 2 Alpha Code` varchar(2) NOT NULL DEFAULT 'XX',
  `Invoice Delivery Country 2 Alpha Code` varchar(2) DEFAULT 'XX',
  `Invoice For Partner` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Invoice For` enum('Staff','Customer') NOT NULL DEFAULT 'Customer',
  `Invoice Dispatching Lag` float DEFAULT NULL,
  `Invoice Taxable` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Invoice Tax Code` varchar(16) NOT NULL DEFAULT 'UNK',
  `Invoice Tax Shipping Code` varchar(16) DEFAULT NULL,
  `Invoice Tax Charges Code` varchar(16) DEFAULT NULL,
  `Invoice Paid` enum('Yes','No','Partially') NOT NULL DEFAULT 'No',
  `Invoice Currency` varchar(3) NOT NULL DEFAULT 'GBP',
  `Invoice Currency Exchange` float NOT NULL DEFAULT '1',
  `Invoice Delivery World Region Code` char(4) DEFAULT NULL,
  `Invoice Delivery Country Code` char(3) DEFAULT NULL,
  `Invoice Delivery Town` varchar(255) DEFAULT NULL,
  `Invoice Delivery Postal Code` varchar(64) DEFAULT NULL,
  `Invoice Billing Region` varchar(64) DEFAULT NULL,
  `Invoice Billing World Region Code` char(4) DEFAULT NULL,
  `Invoice Billing Country Code` char(3) DEFAULT NULL,
  `Invoice Billing Town` varchar(255) DEFAULT NULL,
  `Invoice Billing Postal Code` varchar(64) DEFAULT NULL,
  `Invoice Customer Sevices Note` text NOT NULL,
  `Invoice Tax Number` varchar(64) DEFAULT NULL,
  `Invoice Tax Number Valid` enum('Yes','No','Unknown') NOT NULL DEFAULT 'No',
  `Invoice Tax Number Validation Date` datetime DEFAULT NULL,
  `Invoice Tax Number Associated Name` varchar(255) DEFAULT NULL,
  `Invoice Tax Number Associated Address` text,
  `Invoice Category Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Invoice Key`),
  KEY `Invoice Has Been Paid In Full` (`Invoice Has Been Paid In Full`),
  KEY `Metadata` (`Invoice Metadata`),
  KEY `Invoice Title` (`Invoice Type`),
  KEY `Invoice Date` (`Invoice Date`),
  KEY `Invoice Store Key` (`Invoice Store Key`),
  KEY `Invoice Main Source Type` (`Invoice Main Source Type`),
  KEY `Invoice Paid` (`Invoice Paid`),
  KEY `Invoice Customer Key` (`Invoice Customer Key`),
  KEY `Invoice Customer Level Type` (`Invoice Customer Level Type`),
  KEY `Invoice Billing To Key` (`Invoice Billing To Key`),
  KEY `Invoice Billing Region` (`Invoice Billing Region`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice History Bridge` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') NOT NULL,
  PRIMARY KEY (`Invoice Key`,`History Key`),
  KEY `Invoice Key` (`Invoice Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Payment Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Payment Bridge` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `Payment Key` mediumint(8) unsigned NOT NULL,
  `Payment Account Key` mediumint(8) unsigned NOT NULL,
  `Payment Service Provider Key` mediumint(8) unsigned NOT NULL,
  `Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Invoice Key`,`Payment Key`),
  KEY `Payment Account Key` (`Payment Account Key`),
  KEY `Payment Service Provider Key` (`Payment Service Provider Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Processed By Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Processed By Bridge` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `Staff Key` mediumint(8) unsigned NOT NULL,
  `Share` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`Invoice Key`,`Staff Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Sales Representative Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Sales Representative Bridge` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `Staff Key` mediumint(8) unsigned NOT NULL,
  `Share` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`Invoice Key`,`Staff Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Tax Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Tax Bridge` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `Tax Code` varchar(16) NOT NULL,
  `Tax Amount` decimal(12,2) NOT NULL,
  `Tax Base` enum('Yes','No') NOT NULL,
  KEY `Invoice Key` (`Invoice Key`),
  KEY `Invoice Key_2` (`Invoice Key`),
  KEY `Tax Code` (`Tax Code`),
  KEY `Tax Code_2` (`Tax Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invoice Tax Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice Tax Dimension` (
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  `UNK` decimal(12,2) DEFAULT NULL,
  `EX` decimal(12,2) DEFAULT NULL,
  `S1` decimal(12,2) DEFAULT NULL,
  `S2` decimal(12,2) DEFAULT NULL,
  `S3` decimal(12,2) DEFAULT NULL,
  `OUT` decimal(12,2) DEFAULT NULL,
  KEY `Invoice Key` (`Invoice Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Language Country Bridge`
--

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
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Language Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Language Dimension` (
  `Language Key` smallint(5) unsigned NOT NULL,
  `Language Code` varchar(5) NOT NULL,
  `Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Language Name` varchar(60) NOT NULL,
  `Language Original Name` varchar(60) NOT NULL,
  PRIMARY KEY (`Language Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `List Customer Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `List Customer Bridge` (
  `List Key` smallint(5) unsigned NOT NULL,
  `Customer Key` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `Customer List Key` (`List Key`,`Customer Key`),
  KEY `Customer List Key_2` (`List Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `List Delivery Note Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `List Delivery Note Bridge` (
  ` List Key` smallint(5) NOT NULL,
  `Delivery Note Key` mediumint(8) NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `List Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `List Dimension` (
  `List Key` mediumint(8) unsigned NOT NULL,
  `List Scope` enum('Customer','Order','Invoice','Delivery Note','Product','Part') NOT NULL DEFAULT 'Customer',
  `List Use Type` enum('UserCreated','ImportedRecords','User Defined','CSV Import') NOT NULL DEFAULT 'UserCreated',
  `List Parent Key` smallint(5) unsigned NOT NULL,
  `List Name` varchar(255) NOT NULL,
  `List Type` enum('Dynamic','Static') NOT NULL DEFAULT 'Static',
  `List Metadata` mediumtext,
  `List Creation Date` datetime NOT NULL,
  `List Number Items` mediumint(9) NOT NULL DEFAULT '0',
  `List Number Items B` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`List Key`),
  UNIQUE KEY `Customer List Store Key` (`List Parent Key`,`List Name`(36),`List Scope`),
  KEY `Customer List Use Type` (`List Use Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `List Invoice Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `List Invoice Bridge` (
  `List Key` smallint(5) NOT NULL,
  `Invoice Key` mediumint(8) NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `List Order Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `List Order Bridge` (
  `List Key` smallint(5) NOT NULL,
  `Order Key` mediumint(8) NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `List Part Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `List Part Bridge` (
  `List Key` smallint(5) NOT NULL,
  `Part SKU` mediumint(8) NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `List Product Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `List Product Bridge` (
  `List Key` smallint(5) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`List Key`,`Product ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Locale Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Locale Dimension` (
  `Locale Code` varchar(5) NOT NULL,
  `Country 2 Alpha Code` varchar(2) NOT NULL,
  `Language Code` varchar(2) NOT NULL,
  `Currency Code` varchar(3) NOT NULL,
  PRIMARY KEY (`Locale Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Location Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Location Dimension` (
  `Location Key` mediumint(8) unsigned NOT NULL,
  `Location Warehouse Key` smallint(5) unsigned DEFAULT NULL,
  `Location Warehouse Area Key` mediumint(8) unsigned DEFAULT NULL,
  `Location Shelf Key` mediumint(9) DEFAULT NULL,
  `Location Code` varchar(16) NOT NULL,
  `Location File As` varchar(255) NOT NULL,
  `Location Mainly Used For` enum('Picking','Storing','Loading','Displaying','Other','Default') NOT NULL DEFAULT 'Picking',
  `Location Shape Type` enum('Box','Cylinder','Unknown') NOT NULL DEFAULT 'Unknown',
  `Location Radius` float DEFAULT NULL,
  `Location Deep` float DEFAULT NULL,
  `Location Height` float DEFAULT NULL,
  `Location Width` float DEFAULT NULL,
  `Location Max Weight` float DEFAULT NULL COMMENT 'In Kg',
  `Location Max Volume` float DEFAULT NULL COMMENT 'In Litres',
  `Location Max Slots` smallint(6) DEFAULT NULL,
  `Location Distinct Parts` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Location Has Stock` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  `Location Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Warehouse Flag` enum('Blue','Green','Orange','Pink','Purple','Red','Yellow') NOT NULL DEFAULT 'Blue',
  `Warehouse Flag Key` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`Location Key`),
  KEY `Location Warehouse Key` (`Location Warehouse Key`),
  KEY `Location Code` (`Location Code`),
  KEY `Location Mainly Used For` (`Location Mainly Used For`),
  KEY `Location Flag Key` (`Warehouse Flag Key`),
  KEY `Location File As` (`Location File As`(16))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Location History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Location History Bridge` (
  `Location Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Asset') NOT NULL,
  PRIMARY KEY (`Location Key`,`History Key`),
  KEY `Location Key` (`Location Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Manufacture Task Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Manufacture Task Dimension` (
  `Manufacture Task Key` mediumint(8) unsigned NOT NULL,
  `Manufacture Task Code` varchar(16) DEFAULT NULL,
  `Manufacture Task Name` varchar(255) NOT NULL,
  `Manufacture Task Materials Cost` float DEFAULT NULL,
  `Manufacture Task Energy Cost` float DEFAULT NULL,
  `Manufacture Task Other Cost` float DEFAULT NULL,
  `Manufacture Task Work Cost` float DEFAULT NULL,
  `Manufacture Task From` datetime NOT NULL,
  `Manufacture Task To` datetime DEFAULT NULL,
  `Manufacture Task Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Manufacture Task Lower Target` float DEFAULT NULL,
  `Manufacture Task Upper Target` float DEFAULT NULL,
  `Manufacture Task Operative Reward Terms` enum('Above Upper Limit','Above Lower Limit','Always','Never') NOT NULL DEFAULT 'Never',
  `Manufacture Task Operative Reward Allowance Type` enum('On top salary','Offset Salary') NOT NULL DEFAULT 'On top salary',
  `Manufacture Task Operative Reward Amount` double unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Manufacture Task Key`),
  UNIQUE KEY `Manufacture Task Name` (`Manufacture Task Name`),
  UNIQUE KEY `Manufacture Task Code` (`Manufacture Task Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Manufacturing Facility Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Manufacturing Facility Dimension` (
  `Manufacturing Facility Key` mediumint(8) unsigned NOT NULL,
  `Manufacturing Facility Code` varchar(16) NOT NULL,
  `Manufacturing Facility Contact Key` mediumint(9) NOT NULL,
  PRIMARY KEY (`Manufacturing Facility Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Marketing Post Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Marketing Post Dimension` (
  `Marketing Post Key` mediumint(8) NOT NULL,
  `Marketing Post Name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Store Key` mediumint(8) NOT NULL,
  PRIMARY KEY (`Marketing Post Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Marketing Post Sent Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Marketing Post Sent Fact` (
  `Marketing Post Sent Fact Key` int(8) NOT NULL,
  `Marketing Post Key` mediumint(8) NOT NULL DEFAULT '1',
  `Customer Key` mediumint(8) NOT NULL,
  `Store Key` mediumint(8) NOT NULL,
  `Requested Date` date NOT NULL,
  `Sent Date` date DEFAULT NULL,
  PRIMARY KEY (`Marketing Post Sent Fact Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MasterKey Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MasterKey Dimension` (
  `MasterKey Key` mediumint(8) unsigned NOT NULL,
  `Key` varchar(1024) NOT NULL,
  `User Key` mediumint(9) NOT NULL,
  `Valid Until` datetime NOT NULL,
  `IP` varchar(64) NOT NULL,
  `Used` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Date Used` datetime DEFAULT NULL,
  `Fails Already Used` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Fails Expired` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`MasterKey Key`),
  KEY `Key` (`Key`(8)),
  KEY `Used` (`Used`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MasterKey Internal Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MasterKey Internal Dimension` (
  `MasterKey Internal Key` mediumint(8) unsigned NOT NULL,
  `User Key` mediumint(8) unsigned DEFAULT NULL,
  `Key` varchar(1024) NOT NULL,
  `Valid Until` datetime NOT NULL,
  `IP` varchar(64) NOT NULL,
  PRIMARY KEY (`MasterKey Internal Key`),
  KEY `Key` (`Key`(8))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Material Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Material Dimension` (
  `Material Key` mediumint(8) unsigned NOT NULL,
  `Material Type` enum('Material','Ingredient') NOT NULL DEFAULT 'Material',
  `Material Name` varchar(255) NOT NULL,
  `Material Family` varchar(16) DEFAULT NULL,
  `Material Parts Number` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Material XHTML Description` longtext NOT NULL,
  PRIMARY KEY (`Material Key`),
  KEY `Material Name` (`Material Name`(64)),
  KEY `Material Type` (`Material Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Material History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Material History Bridge` (
  `Material Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Parts') NOT NULL,
  PRIMARY KEY (`Material Key`,`History Key`),
  KEY `Material Key` (`Material Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Media Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Media Dimension` (
  `Media Key` mediumint(8) unsigned NOT NULL,
  `Media Type` enum('Web site','Physical Display','Printed Catalogue') NOT NULL,
  `Madia Type Outigger Key` mediumint(9) NOT NULL,
  `Media Name` varchar(255) NOT NULL,
  `Product Key` mediumint(9) NOT NULL,
  `Media Begin Date` datetime NOT NULL,
  `Media End Date` datetime NOT NULL,
  PRIMARY KEY (`Media Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Message Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Message Dimension` (
  `Message Key` mediumint(8) unsigned NOT NULL,
  `Message Author` varchar(255) DEFAULT NULL,
  `Message Title` varchar(255) DEFAULT NULL,
  `Message Location` varchar(255) DEFAULT NULL COMMENT 'Location in  the application',
  `Message Creation Date` datetime NOT NULL,
  `Message` text NOT NULL,
  `Message Show` enum('Force Yes','Force No','Auto') NOT NULL DEFAULT 'Auto' COMMENT 'Auto uses From, Until dates',
  `Message Show From` datetime NOT NULL,
  `Message Show To` datetime NOT NULL,
  KEY `Message Key` (`Message Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Military Base Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Military Base Dimension` (
  `Military Base Key` mediumint(8) unsigned NOT NULL,
  `Military Base Country Key` mediumint(8) unsigned DEFAULT NULL,
  `Military Base Geographic Country Key` mediumint(8) unsigned DEFAULT NULL,
  `Military Base Name` varchar(255) DEFAULT NULL,
  `Military Base Location` varchar(255) DEFAULT NULL,
  `Military Base Type` varchar(255) DEFAULT NULL,
  `Military Base Post Code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Military Base Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Basket History Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Basket History Dimension` (
  `Order Basket History Key` int(10) unsigned NOT NULL,
  `Date` datetime DEFAULT NULL,
  `Order Transaction Key` int(10) unsigned DEFAULT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Quantity Delta` float NOT NULL DEFAULT '0',
  `Quantity` float NOT NULL DEFAULT '0',
  `Net Amount Delta` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Page Store Section Type` enum('System','Info','Department','Family','Product','FamilyCategory','ProductCategory') DEFAULT NULL,
  PRIMARY KEY (`Order Basket History Key`),
  KEY `Date` (`Date`),
  KEY `Order Key` (`Order Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Deal Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Deal Bridge` (
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Deal Campaign Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Key` mediumint(8) unsigned NOT NULL,
  `Deal Component Key` mediumint(8) unsigned NOT NULL,
  `Applied` enum('Yes','No') NOT NULL,
  `Used` enum('Yes','No') NOT NULL,
  PRIMARY KEY (`Order Key`,`Deal Component Key`),
  KEY `Order Key` (`Order Key`),
  KEY `Deal Key` (`Deal Key`),
  KEY `Elegible` (`Applied`),
  KEY `Used` (`Used`),
  KEY `Deal Component Key` (`Deal Component Key`),
  KEY `Deal Campaign Key` (`Deal Campaign Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Delivery Note Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Delivery Note Bridge` (
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Delivery Note Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Order Key`,`Delivery Note Key`),
  KEY `Order Key` (`Order Key`),
  KEY `Delivery Note Key` (`Delivery Note Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Dimension` (
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Order Date` datetime DEFAULT NULL COMMENT 'Date when the order where first placed',
  `Order Created Date` datetime DEFAULT NULL,
  `Order Checkout Submitted Payment Date` datetime DEFAULT NULL,
  `Order Checkout Completed Payment Date` datetime DEFAULT NULL,
  `Order Submitted by Customer Date` datetime DEFAULT NULL,
  `Order Send to Warehouse Date` datetime DEFAULT NULL,
  `Order Packed Done Date` datetime DEFAULT NULL,
  `Order Dispatched Date` datetime DEFAULT NULL,
  `Order Post Transactions Dispatched Date` datetime DEFAULT NULL,
  `Order Suspended Date` datetime DEFAULT NULL,
  `Order Cancelled Date` datetime DEFAULT NULL,
  `Order Last Updated Date` datetime DEFAULT NULL COMMENT 'Lastest Date when Adding/Modify Order Transaction or Data',
  `Order Public ID` varchar(255) DEFAULT NULL,
  `Order File As` varchar(255) DEFAULT NULL,
  `Order XHTML Invoices` text,
  `Order XHTML Delivery Notes` varchar(1024) DEFAULT NULL,
  `Order Customer Key` mediumint(8) unsigned DEFAULT NULL,
  `Order Customer Name` varchar(255) NOT NULL DEFAULT 'Unknown Customer',
  `Order Customer Contact Name` varchar(255) NOT NULL DEFAULT '',
  `Order Customer Fiscal Name` text,
  `Order Telephone` varchar(64) DEFAULT NULL,
  `Order Email` varchar(255) DEFAULT NULL,
  `Order Invoice Address Recipient` varchar(255) DEFAULT NULL,
  `Order Invoice Address Organization` varchar(255) DEFAULT NULL,
  `Order Invoice Address Line 1` varchar(255) DEFAULT NULL,
  `Order Invoice Address Line 2` varchar(255) DEFAULT NULL,
  `Order Invoice Address Sorting Code` varchar(255) DEFAULT NULL,
  `Order Invoice Address Postal Code` varchar(255) DEFAULT NULL,
  `Order Invoice Address Dependent Locality` varchar(255) DEFAULT NULL,
  `Order Invoice Address Locality` varchar(255) DEFAULT NULL,
  `Order Invoice Address Administrative Area` varchar(255) DEFAULT NULL,
  `Order Invoice Address Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Order Invoice Address Checksum` varchar(64) DEFAULT NULL,
  `Order Invoice Address Formatted` text,
  `Order Invoice Address Postal Label` text,
  `Order Delivery Address Recipient` varchar(255) DEFAULT NULL,
  `Order Delivery Address Organization` varchar(255) DEFAULT NULL,
  `Order Delivery Address Line 1` varchar(255) DEFAULT NULL,
  `Order Delivery Address Line 2` varchar(255) DEFAULT NULL,
  `Order Delivery Address Sorting Code` varchar(255) DEFAULT NULL,
  `Order Delivery Address Postal Code` varchar(255) DEFAULT NULL,
  `Order Delivery Address Dependent Locality` varchar(255) DEFAULT NULL,
  `Order Delivery Address Locality` varchar(255) DEFAULT NULL,
  `Order Delivery Address Administrative Area` varchar(255) DEFAULT NULL,
  `Order Delivery Address Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Order Delivery Address Checksum` varchar(64) DEFAULT NULL,
  `Order Delivery Address Formatted` text,
  `Order Delivery Address Postal Label` text,
  `Order Customer Order Number` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Order Site Key` mediumint(8) unsigned DEFAULT NULL,
  `Order Store Key` mediumint(8) unsigned DEFAULT NULL,
  `Order Store Code` varchar(32) DEFAULT NULL,
  `Order XHTML Store` text,
  `Order XHTML Sales Representative` text,
  `Order Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown') NOT NULL DEFAULT 'Unknown',
  `Order Payment Account Key` mediumint(9) DEFAULT NULL,
  `Order Payment Account Code` varchar(64) DEFAULT NULL,
  `Order Payment Key` mediumint(9) DEFAULT NULL,
  `Order Company Department Key` mediumint(8) unsigned DEFAULT NULL,
  `Order Company Department Code` varchar(255) DEFAULT NULL,
  `Order Main Source Type` enum('Internet','Call','Store','Other','Email','Fax') NOT NULL DEFAULT 'Other',
  `Order Current Dispatch State` enum('In Process by Customer','Waiting for Payment Confirmation','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Packing','Packed','Packed Done','Cancelled','Suspended','Cancelled by Customer') NOT NULL DEFAULT 'In Process',
  `Order Current XHTML Dispatch State` text NOT NULL,
  `Order Current Payment State` enum('Waiting Payment','Paid','Partially Paid','Unknown','No Applicable','Overpaid') NOT NULL DEFAULT 'Waiting Payment',
  `Order Current XHTML Payment State` text,
  `Order Current Post Dispatch State` enum('NA','In Process','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Packing','Packed','Packed Done','Cancelled','Suspended') NOT NULL DEFAULT 'NA',
  `Order Current XHTML Post Dispatch State` varchar(255) NOT NULL,
  `Order Customer Feedback` enum('Praise','None','Shortages','Breakings','Different Product','Multiple','Low Quality','Not Like','Slow Delivery','Other','Unknown') NOT NULL DEFAULT 'None',
  `Order Item Actions Taken` enum('None','Replacement','Send Missing','Replacement and Send Missing') NOT NULL DEFAULT 'None',
  `Order Money Actions Taken` enum('None','Refund','Credit') NOT NULL DEFAULT 'None',
  `Order Type` enum('Order','Sample','Donation','Other') NOT NULL DEFAULT 'Other',
  `Order Number Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Order Number Items` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Order Number Items Out of Stock` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Order Number Items Returned` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Order Number Items with Deals` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Order Items Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Items Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Items Adjust Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Items Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Items Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Shipping Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Shipping Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Charges Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Charges Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Insurance Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Insurance Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Deal Amount Off` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Net',
  `Order Total Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Total Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Total Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Balance Net Amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Order Balance Tax Amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Order Balance Total Amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Net+Tax',
  `Order Payments Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order To Pay Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Outstanding Balance Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Outstanding Balance Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Outstanding Balance Total Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Net Refund Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Tax Refund Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Net Credited Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Tax Credited Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Out of Stock Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Out of Stock Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order No Authorized Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order No Authorized Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Not Found Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Not Found Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Not Due Other Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Not Due Other Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Items Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Items Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Shipping Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Shipping Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Charges Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Charges Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Insurance Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Insurance Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Refund Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Refund Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Total Net Adjust Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Total Tax Adjust Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Balance Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Balance Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Balance Total Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Outstanding Balance Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Outstanding Balance Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Outstanding Balance Total Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Invoiced Profit Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Order Profit Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Order Margin` float DEFAULT NULL,
  `Order Invoiced Refund Notes` text,
  `Order For Collection` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Order Ship To Key To Deliver` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Order XHTML Ship Tos` text,
  `Order Ship To Keys` varchar(512) NOT NULL,
  `Order Ship To Country Code` varchar(3) NOT NULL DEFAULT 'UNK',
  `Order Customer Message` text,
  `Order Sticky Note` text,
  `Order Original Data Source` enum('Excel File','Other','Magento','Inikoo') NOT NULL DEFAULT 'Other',
  `Order Original Data MIME Type` varchar(255) DEFAULT NULL COMMENT 'Two-part identifier for file formats (RFC 2046). Ref: http://www.iana.org/assignments/media-types/',
  `Order Original Data File Key` mediumint(9) DEFAULT NULL COMMENT 'Original order link, E.G.: Email body message,cnversation audio file or a scaned document.',
  `Order Original Data Filename` varchar(255) DEFAULT NULL,
  `Order Original Metadata` varchar(8) DEFAULT NULL,
  `Order For` enum('Staff','Partner','Customer') NOT NULL DEFAULT 'Customer',
  `Order Currency` varchar(3) NOT NULL DEFAULT 'GBP',
  `Order Currency Exchange` float NOT NULL DEFAULT '1',
  `Order Cancel Note` text,
  `Order Suspend Note` text,
  `Order Category` varchar(16) NOT NULL DEFAULT 'Default',
  `Order Category Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Order Billing To Country 2 Alpha Code` varchar(2) NOT NULL DEFAULT 'XX',
  `Order Tax Rate` decimal(8,6) NOT NULL,
  `Order Tax Code` varchar(16) NOT NULL,
  `Order Tax Name` varchar(255) DEFAULT NULL,
  `Order Tax Operations` longtext,
  `Order Tax Selection Type` varchar(128) DEFAULT NULL,
  `Order Estimated Weight` float NOT NULL DEFAULT '0',
  `Order Dispatched Estimated Weight` float NOT NULL DEFAULT '0',
  `Order Weight` float DEFAULT NULL,
  `Order Shipping Method` enum('No Applicable','TBC','Calculated','Set') NOT NULL DEFAULT 'Calculated',
  `Order Ship To Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Order Ship To World Region Code` char(4) DEFAULT NULL,
  `Order Ship To Town` varchar(255) DEFAULT NULL,
  `Order Ship To Postal Code` varchar(64) DEFAULT NULL,
  `Order Customer Sevices Note` text NOT NULL,
  `Order Invoiced` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Order with Out of Stock` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Order Billing To Key To Bill` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Order XHTML Billing Tos` text,
  `Order Billing To Keys` varchar(255) DEFAULT NULL,
  `Order Billing To Country Code` varchar(3) DEFAULT NULL,
  `Order Billing To World Region Code` varchar(4) DEFAULT NULL,
  `Order Billing To Town` varchar(255) DEFAULT NULL,
  `Order Billing To Postal Code` varchar(64) DEFAULT NULL,
  `Order Tax Number` varchar(64) DEFAULT NULL,
  `Order Tax Number Valid` enum('Yes','No','Unknown') NOT NULL DEFAULT 'No',
  `Order Tax Number Validation Date` datetime DEFAULT NULL,
  `Order Tax Number Associated Name` varchar(255) DEFAULT NULL,
  `Order Tax Number Associated Address` text,
  `Order Apply Auto Customer Account Payment` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Order Show in Warehouse Orders` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Order Key`),
  KEY `Order Original Data Source` (`Order Original Data Source`),
  KEY `Order Public ID` (`Order Public ID`),
  KEY `Order Date` (`Order Date`),
  KEY `Order File As` (`Order File As`),
  KEY `Order Customer Key` (`Order Customer Key`),
  KEY `Order Type` (`Order Type`),
  KEY `Order Current Dispatch State` (`Order Current Dispatch State`),
  KEY `Order Current Payment State` (`Order Current Payment State`),
  KEY `Order Original Metadata` (`Order Original Metadata`),
  KEY `Order Customer Order Number` (`Order Customer Order Number`),
  KEY `Order Store Key` (`Order Store Key`),
  KEY `Order Current Post Dispatch State` (`Order Current Post Dispatch State`),
  KEY `Order with Out of Stock` (`Order with Out of Stock`),
  KEY `Order Payment Method` (`Order Payment Method`),
  KEY `Order Main Source Type` (`Order Main Source Type`),
  KEY `Order Site Key` (`Order Site Key`),
  KEY `Main Source Type Store Key` (`Order Main Source Type`,`Order Store Key`),
  KEY `Type Store Key` (`Order Type`,`Order Store Key`),
  KEY `Current Dispatch State Store Key` (`Order Current Dispatch State`,`Order Store Key`),
  KEY `Current Payment State Store Key` (`Order Current Payment State`,`Order Store Key`),
  KEY `Order Packed Done Date` (`Order Packed Done Date`),
  KEY `Order Submitted by Customer Date` (`Order Submitted by Customer Date`),
  KEY `Order Send to Warehouse Date` (`Order Send to Warehouse Date`),
  KEY `Order Dispatched Date` (`Order Dispatched Date`),
  KEY `Order Show in Warehouse Orders` (`Order Show in Warehouse Orders`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order History Bridge` (
  `Order Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Order Key`,`History Key`),
  KEY `Order Key` (`Order Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Import Metadata`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Import Metadata` (
  `Order Import Metadata Key` mediumint(8) unsigned NOT NULL,
  `Metadata` varchar(64) DEFAULT NULL,
  `Name` varchar(15) DEFAULT NULL,
  `Import Date` datetime DEFAULT NULL,
  `Start Picking Date` datetime DEFAULT NULL,
  `Finish Picking Date` datetime DEFAULT NULL,
  `Start Packing Date` datetime DEFAULT NULL,
  `Finish Packing Date` datetime DEFAULT NULL,
  `Approve Date` datetime DEFAULT NULL,
  `Picker Keys` varchar(255) DEFAULT NULL,
  `Packer Keys` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Order Import Metadata Key`),
  UNIQUE KEY `Metadata` (`Metadata`),
  KEY `Name` (`Name`),
  KEY `Import Date` (`Import Date`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Invoice Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Invoice Bridge` (
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Invoice Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Order Key`,`Invoice Key`),
  KEY `Order Key` (`Order Key`),
  KEY `Invoice Key` (`Invoice Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Meta Transaction Deal Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Meta Transaction Deal Dimension` (
  `Order Meta Transaction Deal Key` mediumint(8) unsigned NOT NULL,
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Deal Campaign Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Key` mediumint(9) NOT NULL,
  `Deal Component Key` mediumint(9) NOT NULL,
  `Deal Info` text NOT NULL,
  `Order Meta Transaction Deal Type` enum('Order Get Free','Percentage Off') NOT NULL DEFAULT 'Order Get Free',
  `Amount Discount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `Fraction Discount` float NOT NULL DEFAULT '0',
  `Bonus Quantity` decimal(10,0) NOT NULL DEFAULT '0',
  `Bonus Product Key` mediumint(8) unsigned DEFAULT NULL,
  `Bonus Product ID` mediumint(8) unsigned DEFAULT NULL,
  `Bonus Product Family Key` mediumint(8) unsigned DEFAULT NULL,
  `Bonus Order Transaction Fact Key` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Order Meta Transaction Deal Key`),
  UNIQUE KEY `Order Key_2` (`Order Key`,`Deal Component Key`),
  KEY `Order Key` (`Order Key`),
  KEY `Bonus Order Transaction Fact Key` (`Bonus Order Transaction Fact Key`),
  KEY `Order Meta Transaction Deal Type` (`Order Meta Transaction Deal Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order No Product Transaction Deal Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order No Product Transaction Deal Bridge` (
  `Order No Product Transaction Fact Key` int(10) unsigned NOT NULL,
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Deal Campaign Key` mediumint(9) NOT NULL,
  `Deal Key` mediumint(9) NOT NULL,
  `Deal Component Key` mediumint(9) NOT NULL,
  `Deal Info` varchar(255) NOT NULL,
  `Amount Discount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `Fraction Discount` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`Order No Product Transaction Fact Key`),
  KEY `Order Key` (`Order Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order No Product Transaction Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order No Product Transaction Fact` (
  `Order No Product Transaction Fact Key` int(10) unsigned NOT NULL,
  `Order Date` datetime DEFAULT NULL,
  `Delivery Note Date` datetime DEFAULT NULL,
  `Invoice Date` datetime DEFAULT NULL,
  `Paid Date` datetime DEFAULT NULL,
  `Refund Date` datetime DEFAULT NULL,
  `Order Key` mediumint(9) DEFAULT NULL,
  `Affected Order Key` mediumint(8) unsigned DEFAULT NULL,
  `Delivery Note Key` mediumint(8) unsigned DEFAULT NULL,
  `Invoice Key` mediumint(9) DEFAULT NULL,
  `Refund Key` mediumint(8) unsigned DEFAULT NULL,
  `Transaction Type` enum('Credit','Unknown','Refund','Shipping','Charges','Adjust','Other','Deal','Insurance','Discount') NOT NULL DEFAULT 'Unknown',
  `Transaction Type Key` mediumint(8) unsigned DEFAULT NULL,
  `Tax Category Code` varchar(16) DEFAULT NULL,
  `Transaction Description` varchar(1024) NOT NULL,
  `Transaction Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Transaction Total Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Transaction Net Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Transaction Tax Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Transaction Invoice Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Transaction Invoice Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Transaction Refund Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Transaction Refund Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Transaction Outstanding Net Amount Balance` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Transaction Outstanding Tax Amount Balance` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Transaction Outstanding Refund Net Amount Balance` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Transaction Outstanding Refund Tax Amount Balance` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Currency Exchange` float NOT NULL DEFAULT '1',
  `State` enum('Normal','Suspended','Cancelled','Cancelled by Customer') NOT NULL DEFAULT 'Normal',
  `Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Other','Unknown','NA') NOT NULL DEFAULT 'NA',
  `Paid Factor` float NOT NULL DEFAULT '0',
  `Current Payment State` enum('Waiting Payment','Paid','Unknown','Payment Refunded','Cancelled','No Applicable') NOT NULL DEFAULT 'Unknown',
  `Consolidated` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Metadata` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`Order No Product Transaction Fact Key`),
  KEY `Order Date` (`Order Date`),
  KEY `Invoice Date` (`Invoice Date`),
  KEY `Order Key` (`Order Key`),
  KEY `Invoice Key` (`Invoice Key`),
  KEY `Transaction Type` (`Transaction Type`),
  KEY `Metadata` (`Metadata`),
  KEY `Delivery Note Key` (`Delivery Note Key`),
  KEY `Transaction Type Key` (`Transaction Type Key`),
  KEY `Refund Key` (`Refund Key`),
  KEY `Affected Order Key` (`Affected Order Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Payment Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Payment Bridge` (
  `Order Key` mediumint(9) unsigned NOT NULL,
  `Payment Key` mediumint(9) unsigned NOT NULL,
  `Payment Account Key` mediumint(8) unsigned NOT NULL,
  `Payment Service Provider Key` mediumint(8) unsigned NOT NULL,
  `Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Is Account Payment` enum('Yes','No') NOT NULL DEFAULT 'No',
  UNIQUE KEY `Order Key` (`Order Key`,`Payment Key`),
  KEY `Order Key_2` (`Order Key`),
  KEY `Is Account Payment` (`Is Account Payment`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Post Transaction Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Post Transaction Dimension` (
  `Order Post Transaction Key` mediumint(8) unsigned NOT NULL,
  `Order Transaction Fact Key` int(8) unsigned NOT NULL,
  `Order Post Transaction Fact Key` int(8) unsigned DEFAULT NULL,
  `Order Key` mediumint(8) unsigned DEFAULT NULL,
  `Delivery Note Key` mediumint(8) unsigned DEFAULT NULL,
  `Refund Key` mediumint(8) unsigned DEFAULT NULL,
  `Quantity` float NOT NULL,
  `Operation` enum('Resend','Credit','Refund') NOT NULL,
  `Reason` enum('Other','Damaged','Missing','Do Not Like','Unknown') NOT NULL DEFAULT 'Other',
  `To Be Returned` enum('Yes','No') NOT NULL DEFAULT 'No',
  `State` enum('In Process','In Warehouse','Dispatched','Saved','Applied') NOT NULL DEFAULT 'In Process',
  `Order Post Transaction Metadata` varchar(64) DEFAULT NULL,
  `Customer Key` mediumint(9) DEFAULT NULL,
  `Credit` float DEFAULT NULL,
  `Credit Saved` float DEFAULT NULL,
  `Credit Used` float DEFAULT NULL,
  `Credit Paid` float DEFAULT NULL,
  PRIMARY KEY (`Order Post Transaction Key`),
  KEY `Order Transaction Fact Key` (`Order Transaction Fact Key`),
  KEY `Order Key` (`Order Key`),
  KEY `State` (`State`),
  KEY `Order Post Transaction Fact Key` (`Order Post Transaction Fact Key`),
  KEY `Order Post Transaction Metadata` (`Order Post Transaction Metadata`),
  KEY `Delivery Note Key` (`Delivery Note Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Post Transaction In Process Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Post Transaction In Process Dimension` (
  `Order Post Transaction In Process Key` mediumint(8) unsigned NOT NULL,
  `Order Transaction In Process Key` mediumint(8) unsigned NOT NULL,
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Quantity` float NOT NULL,
  `Operation` enum('Replacement','Credit','Refund') NOT NULL,
  `Reason` enum('Other','Damaged','Missing','Do Not Like') NOT NULL DEFAULT 'Other',
  `To Be Returned` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Order Post Transaction In Process Key`),
  KEY `Order Transaction In Process Key` (`Order Transaction In Process Key`,`Order Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Sales Representative Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Sales Representative Bridge` (
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Staff Key` mediumint(8) unsigned NOT NULL,
  `Share` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`Order Key`,`Staff Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Spanshot Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Spanshot Fact` (
  `Date` date NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Product Family Key` mediumint(8) unsigned NOT NULL,
  `Product Department Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Availability` decimal(3,2) NOT NULL DEFAULT '1.00',
  `Outers Out` float NOT NULL DEFAULT '0',
  `Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Sales DC` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `Date` (`Date`,`Product ID`),
  KEY `Date_2` (`Date`),
  KEY `Store Key` (`Store Key`),
  KEY `Product Family Key` (`Product Family Key`),
  KEY `Product Department Key` (`Product Department Key`),
  KEY `Product ID` (`Product ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Transaction Deal Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Transaction Deal Bridge` (
  `Order Transaction Fact Key` int(10) unsigned NOT NULL,
  `Order Key` mediumint(9) unsigned NOT NULL,
  `Product Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Product Family Key` mediumint(8) unsigned NOT NULL,
  `Deal Campaign Key` mediumint(8) unsigned DEFAULT NULL,
  `Deal Key` mediumint(8) unsigned NOT NULL,
  `Deal Component Key` mediumint(9) NOT NULL,
  `Deal Info` varchar(255) NOT NULL,
  `Amount Discount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `Fraction Discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Bunus Quantity` smallint(6) NOT NULL DEFAULT '0',
  KEY `Order Transaction Key` (`Order Key`,`Deal Component Key`),
  KEY `Order Transaction Fact Key` (`Order Transaction Fact Key`),
  KEY `Deal Key` (`Deal Key`),
  KEY `Deal Campaign Key` (`Deal Campaign Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Transaction Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Transaction Fact` (
  `Order Transaction Fact Key` int(10) unsigned NOT NULL,
  `Order Date` datetime DEFAULT NULL,
  `Paid Date` datetime DEFAULT NULL,
  `Order Last Updated Date` datetime DEFAULT NULL,
  `Backlog Date` datetime DEFAULT NULL,
  `Release to Manufacturing Date` datetime DEFAULT NULL,
  `Finished Inventory Placement Date` datetime DEFAULT NULL,
  `Start Picking Date` datetime DEFAULT NULL,
  `Picking Finished Date` datetime DEFAULT NULL,
  `Start Packing Date` datetime DEFAULT NULL,
  `Packing Finished Date` datetime DEFAULT NULL,
  `Requested Shipping Date` datetime DEFAULT NULL,
  `Scheduled Shipping Date` datetime DEFAULT NULL,
  `Actual Shipping Date` datetime DEFAULT NULL,
  `Arrival Date` datetime DEFAULT NULL,
  `Invoice Date` datetime DEFAULT NULL,
  `Order Transaction Type` enum('Order','Sample','Donation','Unknown','Other','Resend') NOT NULL DEFAULT 'Unknown',
  `Current Dispatching State` enum('In Process by Customer','Submitted by Customer','In Process','Ready to Pick','Picking','Ready to Pack','Ready to Ship','Dispatched','Unknown','Packing','Packed','Packed Done','Cancelled','No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Suspended','Cancelled by Customer','Out of Stock in Basket') NOT NULL DEFAULT 'Unknown',
  `Current Payment State` enum('Waiting Payment','Paid','Unknown','Payment Refunded','Cancelled','No Applicable','Cancelled by Customer') NOT NULL DEFAULT 'Unknown',
  `Paid Factor` float NOT NULL DEFAULT '0',
  `Picking Factor` float NOT NULL DEFAULT '0',
  `Packing Factor` float NOT NULL DEFAULT '0',
  `Picked Quantity` float NOT NULL DEFAULT '0',
  `Consolidated` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Source Type` enum('Internet','Call','Email','Fax','Post','Physical','Standing Order','Unknown','Replacement','Shortage') NOT NULL DEFAULT 'Unknown',
  `Customer Message` varchar(1024) DEFAULT NULL,
  `Order Source Type` enum('Internet','Call','Email','Fax','Post','Physical','Standing Order','Unknown') NOT NULL DEFAULT 'Unknown',
  `Order Key` mediumint(8) unsigned DEFAULT NULL,
  `Order Public ID` varchar(255) DEFAULT NULL,
  `Delivery Note Key` mediumint(8) unsigned DEFAULT NULL,
  `Delivery Note ID` varchar(255) DEFAULT NULL,
  `Invoice Key` mediumint(8) unsigned DEFAULT NULL,
  `Invoice Public ID` varchar(255) DEFAULT NULL,
  `Refund Key` mediumint(8) unsigned DEFAULT NULL,
  `Estimated Weight` float DEFAULT NULL COMMENT 'Estimated weight including packing In Kilograms',
  `Estimated Dispatched Weight` float DEFAULT NULL,
  `Weight` float DEFAULT NULL,
  `Estimated Volume` float DEFAULT NULL COMMENT 'Estimeded volume contribution in Liters',
  `Volume` float DEFAULT NULL,
  `Store Key` mediumint(8) unsigned DEFAULT NULL,
  `Company Departmet Key` smallint(5) unsigned DEFAULT NULL,
  `Product Key` mediumint(8) unsigned DEFAULT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Product Code` varchar(64) NOT NULL,
  `Product Family Key` mediumint(8) unsigned NOT NULL,
  `Product Department Key` mediumint(8) unsigned NOT NULL,
  `Customer Key` mediumint(8) unsigned DEFAULT NULL,
  `Sales Rep Key` mediumint(8) unsigned DEFAULT NULL,
  `Manufacturing Facility Key` mediumint(8) unsigned DEFAULT NULL,
  `Warehouse Key` mediumint(8) unsigned DEFAULT NULL,
  `Picker Key` smallint(6) DEFAULT NULL,
  `Packer Key` smallint(6) DEFAULT NULL,
  `Shipper Key` mediumint(8) unsigned DEFAULT NULL,
  `Ship to Key` mediumint(8) unsigned DEFAULT NULL,
  `Billing To Key` mediumint(8) unsigned DEFAULT NULL,
  `Destination Country 2 Alpha Code` varchar(2) NOT NULL DEFAULT 'XX',
  `Billing To 2 Alpha Country Code` varchar(2) DEFAULT NULL,
  `Order Quantity` float DEFAULT '0',
  `Order Bonus Quantity` float NOT NULL DEFAULT '0',
  `Current Manufacturing Quantity` float DEFAULT '0',
  `Current On Shelf Quantity` float DEFAULT '0',
  `Current On Box Quantity` float DEFAULT '0',
  `Current Autorized to Sell Quantity` float DEFAULT '0',
  `Delivery Note Quantity` float DEFAULT '0',
  `Shipped Quantity` float DEFAULT '0',
  `No Shipped Due Out of Stock` float DEFAULT '0',
  `No Shipped Due No Authorized` float DEFAULT '0',
  `No Shipped Due Not Found` float DEFAULT '0',
  `No Shipped Due Other` float DEFAULT '0',
  `Order Out of Stock Lost Amount` float NOT NULL DEFAULT '0',
  `Invoice Quantity` float DEFAULT '0',
  `Refund Quantity` float NOT NULL DEFAULT '0',
  `Customer Return Quantity` float DEFAULT '0',
  `Order Transaction Gross Amount` decimal(12,2) DEFAULT '0.00' COMMENT 'Order amount by customer (potential)',
  `Order Transaction Total Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Order Transaction Amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Transaction Tax Rate` float NOT NULL DEFAULT '0',
  `Transaction Tax Code` varchar(3) NOT NULL DEFAULT 'UNK',
  `Invoice Transaction Gross Amount` decimal(12,2) DEFAULT '0.00' COMMENT 'Paid/Aoustanding Bal depanding in payment flag',
  `Invoice Transaction Total Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Item Tax Amount` decimal(16,6) NOT NULL DEFAULT '0.000000',
  `Invoice Transaction Shipping Amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `Invoice Transaction Shipping Tax Amount` decimal(16,6) NOT NULL DEFAULT '0.000000',
  `Invoice Transaction Charges Amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `Invoice Transaction Charges Tax Amount` decimal(16,6) NOT NULL DEFAULT '0.000000',
  `Invoice Transaction Insurance Amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `Invoice Transaction Insurance Tax Amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `Invoice Transaction Outstanding Net Balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Outstanding Tax Balance` decimal(16,6) NOT NULL DEFAULT '0.000000',
  `Invoice Transaction Net Refund Items` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Net Refund Shipping` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Net Refund Charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Net Refund Insurance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Tax Refund Items` decimal(12,6) NOT NULL DEFAULT '0.000000',
  `Invoice Transaction Tax Refund Shipping` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Tax Refund Charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Tax Refund Insurance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Net Refund Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Tax Refund Amount` decimal(16,6) NOT NULL DEFAULT '0.000000',
  `Invoice Transaction Outstanding Refund Net Balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Outstanding Refund Tax Balance` decimal(16,6) NOT NULL DEFAULT '0.000000',
  `Invoice Transaction Net Adjust` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Invoice Transaction Tax Adjust` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Other','Unknown','NA') NOT NULL DEFAULT 'NA',
  `Refund Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Other','Unknown','NA') NOT NULL DEFAULT 'NA',
  `Cost Supplier` decimal(12,4) DEFAULT NULL,
  `Cost Storing` decimal(12,4) DEFAULT NULL,
  `Cost Handing` decimal(12,4) DEFAULT NULL,
  `Cost Shipping` decimal(12,4) DEFAULT NULL,
  `Order Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Invoice Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Invoice Currency Exchange Rate` float NOT NULL DEFAULT '1' COMMENT 'Exchange rate with respect to the default currency',
  `Backlog to Shipping Lag` float DEFAULT NULL,
  `Units Per Case` float DEFAULT NULL,
  `Metadata` varchar(16) DEFAULT NULL,
  `Refund Metadata` varchar(16) DEFAULT NULL,
  `Transaction Notes` varchar(255) NOT NULL DEFAULT '',
  `Supplier Metadata` text,
  `Multipart Partically No Picked` enum('Yes','No') NOT NULL DEFAULT 'No',
  KEY `product` (`Product Key`),
  KEY `cust` (`Customer Key`),
  KEY `order` (`Order Key`),
  KEY `order date` (`Order Date`),
  KEY `Picker Key` (`Picker Key`),
  KEY `Packer Key` (`Packer Key`),
  KEY `Current Dispatching State` (`Current Dispatching State`),
  KEY `Current Payment State` (`Current Payment State`),
  KEY `Source Type` (`Source Type`),
  KEY `Consolidated` (`Consolidated`),
  KEY `Store Key` (`Store Key`),
  KEY `Invoice Key` (`Invoice Key`),
  KEY `Delivery Note Key` (`Delivery Note Key`),
  KEY `Invoice Date` (`Invoice Date`),
  KEY `Refund Key` (`Refund Key`),
  KEY `Product ID` (`Product ID`),
  KEY `Product Family Key` (`Product Family Key`),
  KEY `Product Department Key` (`Product Department Key`),
  KEY `No Shipped Due Out of Stock` (`No Shipped Due Out of Stock`),
  KEY `Destination Country 2 Alpha Code` (`Destination Country 2 Alpha Code`),
  KEY `Order Transaction Fact Key` (`Order Transaction Fact Key`),
  KEY `Product Code` (`Product Code`(16)),
  KEY `Billing To Key` (`Billing To Key`),
  KEY `Metadata` (`Metadata`),
  KEY `Refund Metadata` (`Refund Metadata`),
  KEY `Product ID_2` (`Product ID`,`Customer Key`),
  KEY `Product Family Key_2` (`Product Family Key`,`Customer Key`)
)
/*!50100 PARTITION BY RANGE (to_days(`Invoice Date`))
(PARTITION p0 VALUES LESS THAN (732677),
 PARTITION p1 VALUES LESS THAN (733042),
 PARTITION p2 VALUES LESS THAN (733407),
 PARTITION p3 VALUES LESS THAN (733773),
 PARTITION p4 VALUES LESS THAN (734138),
 PARTITION p5 VALUES LESS THAN (734503),
 PARTITION p6 VALUES LESS THAN (734868),
 PARTITION p7 VALUES LESS THAN (735234),
 PARTITION p8 VALUES LESS THAN (735385),
 PARTITION p9 VALUES LESS THAN (735599),
 PARTITION p10 VALUES LESS THAN MAXVALUE) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Order Transaction Out of Stock in Basket Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order Transaction Out of Stock in Basket Bridge` (
  `Order Transaction Fact Key` int(10) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  `Store Key` smallint(5) unsigned NOT NULL,
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Product Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Quantity` float NOT NULL,
  `Amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT 'Net',
  UNIQUE KEY `Order Transaction Key` (`Order Transaction Fact Key`),
  KEY `Store Key` (`Store Key`),
  KEY `Order Key` (`Order Key`),
  KEY `Product ID` (`Product ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Organization Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Organization Dimension` (
  `Organization Key` mediumint(8) unsigned NOT NULL,
  `Organization Code` varchar(16) NOT NULL,
  `Organization Name` varchar(255) NOT NULL,
  `Organization Main Contact` mediumint(9) NOT NULL,
  `Organization Description` text NOT NULL,
  PRIMARY KEY (`Organization Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Dimension` (
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Page Type` enum('External','Store','Internal') NOT NULL,
  `Page Title` varchar(128) NOT NULL,
  `Page Short Title` varchar(64) NOT NULL,
  `Page Keywords` text NOT NULL,
  `Page URL` varchar(128) NOT NULL,
  `Page Snapshot Image Key` mediumint(8) unsigned DEFAULT NULL,
  `Page Snapshot Last Update` datetime DEFAULT NULL,
  `Page Valid URL` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Page Working URL` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  `Page Published` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Page Key`),
  KEY `Page URL` (`Page URL`(64))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Footer Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Footer Dimension` (
  `Page Footer Key` mediumint(8) unsigned NOT NULL,
  `Page Footer Name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Page Footer Preview Image Key` mediumint(9) DEFAULT NULL,
  `Page Footer Preview Snapshot Last Update` datetime DEFAULT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Number Pages` mediumint(9) NOT NULL DEFAULT '0',
  `Template` longtext CHARACTER SET latin1 NOT NULL,
  `CSS` longtext CHARACTER SET latin1 NOT NULL,
  `Javascript` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Page Footer Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Footer External File Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Footer External File Bridge` (
  `Page Store External File Key` mediumint(8) unsigned NOT NULL,
  `Page Footer Key` mediumint(8) unsigned NOT NULL,
  `External File Type` enum('Javascript','CSS') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Page Store External File Key`,`Page Footer Key`),
  KEY `Page Footer Key` (`Page Footer Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Header Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Header Dimension` (
  `Page Header Key` mediumint(8) unsigned NOT NULL,
  `Page Header Name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Page Header Preview Image Key` mediumint(9) DEFAULT NULL,
  `Page Header Preview Snapshot Last Update` datetime DEFAULT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Number Pages` mediumint(9) NOT NULL DEFAULT '0',
  `Template` longtext CHARACTER SET latin1 NOT NULL,
  `Default Site` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'Yes',
  `CSS` longtext CHARACTER SET latin1 NOT NULL,
  `Javascript` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Page Header Key`),
  KEY `Default Site` (`Default Site`),
  KEY `Site Key` (`Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Header External File Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Header External File Bridge` (
  `Page Store External File Key` mediumint(8) unsigned NOT NULL,
  `Page Header Key` mediumint(8) unsigned NOT NULL,
  `External File Type` enum('Javascript','CSS') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Page Store External File Key`,`Page Header Key`),
  KEY `Page Header Key` (`Page Header Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Internal Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Internal Dimension` (
  `Page Key` mediumint(9) NOT NULL,
  `Page Section` enum('Dashboard','Customers','Orders','Websites','Products','Marketing','Locations','Inventory','Suppliers','Reports','Account','Users') NOT NULL DEFAULT 'Reports',
  `Page Activated` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Page Parent Category` varchar(255) DEFAULT NULL,
  `Page Category` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Page Key`),
  KEY `Page Section` (`Page Section`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Product Button Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Product Button Dimension` (
  `Page Product Button Key` mediumint(8) unsigned NOT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Page Product Button Key`),
  UNIQUE KEY `Page Key_2` (`Page Key`,`Product ID`),
  KEY `Product ID` (`Product ID`),
  KEY `Page Key` (`Page Key`),
  KEY `Site Key` (`Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Product Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Product Dimension` (
  `Page Product Key` mediumint(8) unsigned NOT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Parent Key` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Family Key` mediumint(8) unsigned NOT NULL,
  `Parent Type` enum('Button','List') NOT NULL,
  `State` enum('Offline','Online') NOT NULL DEFAULT 'Online',
  PRIMARY KEY (`Page Product Key`),
  UNIQUE KEY `Product ID_2` (`Product ID`,`Page Key`),
  KEY `Parent Key` (`Parent Key`),
  KEY `Page Key` (`Page Key`),
  KEY `Product ID` (`Product ID`),
  KEY `Site Key` (`Site Key`),
  KEY `Family Key` (`Family Key`),
  KEY `State` (`State`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Product List Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Product List Dimension` (
  `Page Product List Key` mediumint(8) unsigned NOT NULL,
  `Page Product List Code` varchar(12) NOT NULL DEFAULT 'default',
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Page Product List Type` enum('FamilyList','CustomList') NOT NULL DEFAULT 'FamilyList',
  `Page Product List Parent Key` mediumint(9) DEFAULT NULL,
  `Page Product List Number Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Show RRP` enum('Yes','No') NOT NULL DEFAULT 'No',
  `List Order` enum('Code','Name','Special Characteristic','Price','RRP','Sales','Date') NOT NULL DEFAULT 'Code',
  `Range` varchar(246) DEFAULT NULL,
  `List Product Description` enum('Units Name','Units Special Characteristic','Units Name RRP','Units Special Characteristic RRP') NOT NULL DEFAULT 'Units Special Characteristic',
  `List Max Items` smallint(5) unsigned NOT NULL DEFAULT '500',
  PRIMARY KEY (`Page Product List Key`),
  KEY `Page Key` (`Page Key`),
  KEY `Site Key` (`Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Redirection Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Redirection Dimension` (
  `Page Redirection Key` mediumint(8) unsigned NOT NULL,
  `Source Host` varchar(1024) NOT NULL,
  `Source Path` varchar(1024) NOT NULL,
  `Source File` varchar(1024) NOT NULL,
  `Page Target URL` varchar(1024) NOT NULL,
  `Page Target Key` mediumint(8) unsigned NOT NULL,
  `Can Upload` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Redirect Uploaded` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Page Redirection Key`),
  KEY `Page Target Key` (`Page Target Key`),
  KEY `Source Host` (`Source Host`(32),`Source Path`(32),`Source File`(32))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Snapshot Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Snapshot Fact` (
  `Date` date NOT NULL,
  `Site Key` smallint(5) unsigned DEFAULT NULL,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Requests` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Visitors` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Requests Logged In` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Visitors Logged In` mediumint(8) unsigned NOT NULL DEFAULT '0',
  KEY `Site Key` (`Site Key`),
  KEY `Page Key` (`Page Key`),
  KEY `Date` (`Date`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page State Timeline`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page State Timeline` (
  `Page State Key` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  `State` enum('Online','Offline') NOT NULL,
  `Operation` enum('Created','Change','Deleted') NOT NULL,
  PRIMARY KEY (`Page State Key`),
  KEY `Operation` (`Operation`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store Data Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store Data Dimension` (
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Page Store Total Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Total Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Total Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Total Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 3 Year Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 3 Year Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 3 Year Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 3 Year Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Year Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Year Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Year Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Year Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 6 Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 6 Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 6 Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 6 Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Quarter Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Quarter Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Quarter Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Quarter Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 10 Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 10 Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 10 Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 10 Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Week Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Week Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Week Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Week Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Hour Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Hour Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Hour Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Hour Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Today Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Today Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Today Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Today Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Year To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Year To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Year To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Year To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Month To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Month To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Month To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Month To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Week To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Week To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Week To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Week To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Week Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Week Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Week Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Week Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Yesterday Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Yesterday Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Yesterday Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Yesterday Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Total Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Total Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 3 Year Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 3 Year Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Year Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Year Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 6 Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 6 Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Quarter Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Quarter Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 10 Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 10 Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Week Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Week Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Hour Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store 1 Hour Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Today Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Today Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Year To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Year To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Month To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Month To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Week To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Week To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Week Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Last Week Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Yesterday Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Page Store Yesterday Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Page Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store Deleted Dimension` (
  `Page Store Deleted Key` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned DEFAULT NULL,
  `Page Code` varchar(255) NOT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Page Title` varchar(255) NOT NULL,
  `Page Short Title` varchar(64) NOT NULL,
  `Page Description` text,
  `Page URL` text NOT NULL,
  `Page Store Section` varchar(255) DEFAULT NULL,
  `Page Parent Key` mediumint(8) unsigned DEFAULT NULL,
  `Page Parent Code` varchar(255) DEFAULT NULL,
  `Page Snapshot Image Key` mediumint(8) unsigned DEFAULT NULL,
  `Page Snapshot Last Update` datetime DEFAULT NULL,
  `Page Valid To` datetime DEFAULT NULL,
  PRIMARY KEY (`Page Store Deleted Key`),
  KEY `Site Key` (`Site Key`),
  KEY `Store Key` (`Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store Dimension` (
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Page Code` varchar(64) NOT NULL,
  `Page State` enum('Online','Offline') NOT NULL DEFAULT 'Offline',
  `Page Stealth Mode` enum('Yes','No') CHARACTER SET ucs2 NOT NULL DEFAULT 'No',
  `Page Site Key` mediumint(8) unsigned NOT NULL,
  `Page Store Key` smallint(5) unsigned NOT NULL,
  `Page Parent Key` mediumint(8) unsigned NOT NULL,
  `Page Parent Code` varchar(64) DEFAULT NULL,
  `Page Store Section Type` enum('System','Info','Department','Family','Product','FamilyCategory','ProductCategory') NOT NULL DEFAULT 'System',
  `Page Store Section Key` mediumint(8) unsigned NOT NULL,
  `Page Store Section` enum('Front Page Store','Search','Product Description','Information','Product Category Catalogue','Family Category Catalogue','Family Catalogue','Department Catalogue','Registration','Client Section','Checkout','Login','Welcome','Not Found','Reset','Basket','Login Help','Thanks','Payment Limbo','Family Description','Department Description') NOT NULL DEFAULT 'Information',
  `Page Locale` char(5) NOT NULL DEFAULT 'en_GB',
  `Page Store Title` varchar(255) NOT NULL,
  `Page Store Description` varchar(1024) NOT NULL,
  `Page Store See Also Type` enum('Auto','Manual') NOT NULL DEFAULT 'Auto',
  `Page Store Content Display Type` enum('Source','Template') NOT NULL DEFAULT 'Source',
  `Page Store Content Template Filename` varchar(128) DEFAULT NULL,
  `Page Use Site Head Include` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Page Head Include` text,
  `Page Use Site Body Include` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Page Body Include` text,
  `Page Store Source` longtext,
  `Page Header Key` mediumint(9) DEFAULT NULL,
  `Page Header Type` enum('Set','SiteDefault') NOT NULL DEFAULT 'SiteDefault',
  `Page Footer Key` mediumint(9) DEFAULT NULL,
  `Page Footer Type` enum('Set','SiteDefault','None') NOT NULL DEFAULT 'SiteDefault',
  `Page Store CSS` longtext,
  `Page Store Javascript` longtext,
  `Page Store Creation Date` datetime DEFAULT NULL,
  `Page Store Last Update Date` datetime DEFAULT NULL,
  `Page Store Last Structural Change Date` datetime DEFAULT NULL,
  `Number See Also Links` tinyint(3) unsigned NOT NULL,
  `Number Found In Links` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Number Lists` smallint(6) NOT NULL DEFAULT '0',
  `Number Products In Lists` smallint(6) NOT NULL DEFAULT '0',
  `Number Buttons` smallint(6) NOT NULL DEFAULT '0',
  `Number Products` smallint(6) NOT NULL DEFAULT '0',
  `Page Store Image URL` varchar(36) DEFAULT NULL,
  `Page Store Image Key` mediumint(8) unsigned DEFAULT NULL,
  `Page Preview Snapshot Image Key` mediumint(8) unsigned DEFAULT NULL,
  `Page Preview Snapshot Last Update` datetime DEFAULT NULL,
  `Page Header Height` mediumint(8) unsigned NOT NULL DEFAULT '150',
  `Page Content Height` mediumint(8) unsigned NOT NULL DEFAULT '518',
  `Page Footer Height` mediumint(8) unsigned NOT NULL DEFAULT '100',
  `Page Store Number Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Page Store Number List Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Page Store Number Button Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Page Store Number Out of Stock Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Page Store Number Sold Out Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Flag` enum('Blue','Green','Orange','Pink','Purple','Red','Yellow') NOT NULL DEFAULT 'Blue',
  `Site Flag Key` tinyint(3) unsigned DEFAULT NULL,
  `Page Related Products List` text,
  `Page See Also Last Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Page Key`),
  UNIQUE KEY `Page Site Key_2` (`Page Site Key`,`Page Code`),
  KEY `Page Store Function` (`Page Store Section`),
  KEY `Page Parent Key` (`Page Parent Key`),
  KEY `Page Site Key` (`Page Site Key`),
  KEY `Page Store See Also Type` (`Page Store See Also Type`),
  KEY `Page Header Type` (`Page Header Type`),
  KEY `Page Footer Type` (`Page Footer Type`),
  KEY `Page Code` (`Page Code`(5)),
  KEY `Page Store Section Type` (`Page Store Section Type`),
  KEY `Site Flag Key` (`Site Flag Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store External File Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store External File Bridge` (
  `Page Store External File Key` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `External File Type` enum('Javascript','CSS') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Page Store External File Key`,`Page Key`),
  KEY `Page Key` (`Page Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store External File Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store External File Dimension` (
  `Page Store External File Key` mediumint(8) unsigned NOT NULL,
  `Page Store External File Name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Page Store External File Type` enum('Javascript','CSS') CHARACTER SET latin1 NOT NULL,
  `Page Store External File Content` longtext NOT NULL,
  PRIMARY KEY (`Page Store External File Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store Found In Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store Found In Bridge` (
  `Page Store Key` mediumint(8) unsigned NOT NULL,
  `Page Store Found In Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Page Store Key`,`Page Store Found In Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store Search Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store Search Dimension` (
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Page Site Key` mediumint(8) unsigned NOT NULL,
  `Page URL` varchar(1024) NOT NULL,
  `Page Store Title` varchar(255) NOT NULL,
  `Page Store Resume` text NOT NULL,
  `Page Store Content` longtext NOT NULL,
  PRIMARY KEY (`Page Key`),
  KEY `Page Site Key` (`Page Site Key`),
  FULLTEXT KEY `Page Store Title` (`Page Store Title`,`Page Store Resume`,`Page Store Content`),
  FULLTEXT KEY `Page Store Title_2` (`Page Store Title`),
  FULLTEXT KEY `Page Store Resume` (`Page Store Resume`),
  FULLTEXT KEY `Page Store Content` (`Page Store Content`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store Search Query Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store Search Query Dimension` (
  `User Request Key` int(10) unsigned NOT NULL,
  `Site Key` smallint(5) unsigned NOT NULL,
  `User Key` mediumint(8) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  `Query` varchar(255) NOT NULL,
  `Number Results` mediumint(8) unsigned NOT NULL DEFAULT '0',
  KEY `User Request Key` (`User Request Key`),
  KEY `Site Key` (`Site Key`),
  KEY `User Key` (`User Key`),
  KEY `Query` (`Query`(64))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store Section Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store Section Dimension` (
  `Page Store Section Key` mediumint(8) unsigned NOT NULL,
  `Site Key` smallint(5) unsigned NOT NULL,
  `Page Store Section Code` varchar(64) NOT NULL,
  `Page Store Section Logo Data` text,
  `Page Store Section Header Data` text,
  `Page Store Section Content Data` text,
  `Page Store Section Footer Data` text,
  `Page Store Section Layout Data` text,
  PRIMARY KEY (`Page Store Section Key`),
  KEY `Site Key` (`Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page Store See Also Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page Store See Also Bridge` (
  `Page Store Key` mediumint(8) unsigned NOT NULL,
  `Page Store See Also Key` mediumint(8) unsigned NOT NULL,
  `Correlation Type` enum('Manual','Sales','Semantic','New') CHARACTER SET latin1 NOT NULL,
  `Correlation Value` float DEFAULT NULL,
  `Webpage See Also Order` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Page Store Key`,`Page Store See Also Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Page URL Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page URL Dimension` (
  `Page URL Key` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned DEFAULT NULL,
  `Page URL Host` varchar(255) NOT NULL,
  `Page URL Path` varchar(255) NOT NULL,
  `Page URL Arguments` text NOT NULL,
  `Page URL` text NOT NULL,
  PRIMARY KEY (`Page URL Key`),
  KEY `Page URL Host` (`Page URL Host`),
  KEY `Page URL Host_2` (`Page URL Host`,`Page URL Path`),
  KEY `Page URL` (`Page URL`(64))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Availability for Products Timeline`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Availability for Products Timeline` (
  `Part Availability for Products Key` mediumint(8) unsigned NOT NULL,
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Warehouse Key` smallint(5) unsigned NOT NULL,
  `User Key` mediumint(8) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  `Duration` mediumint(9) DEFAULT NULL COMMENT 'In seconds',
  `Availability for Products` enum('Yes','No') NOT NULL,
  `Source` enum('Manual','Automatic','Fix') NOT NULL DEFAULT 'Manual',
  PRIMARY KEY (`Part Availability for Products Key`),
  KEY `Date` (`Date`),
  KEY `Part SKU` (`Part SKU`),
  KEY `Warehouse Key` (`Warehouse Key`),
  KEY `User Key` (`User Key`),
  KEY `Source` (`Source`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Category Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Category Dimension` (
  `Part Category Key` mediumint(8) unsigned NOT NULL,
  `Part Category Warehouse Key` smallint(5) unsigned DEFAULT '1',
  `Part Category Valid From` datetime DEFAULT NULL,
  `Part Category Valid To` datetime DEFAULT NULL,
  `Part Category Status` enum('NotInUse','InUse') NOT NULL DEFAULT 'InUse',
  `Part Category Current Stock Value` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Required` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Given` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 3 Year Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 3 Year Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 3 Year Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Year Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Year Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Year Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Required` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Given` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 6 Month Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 6 Month Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 6 Month Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Quarter Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Quarter Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Quarter Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Quarter Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Month Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Month Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Month Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Required` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Given` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 10 Day Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 10 Day Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 10 Day Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Week Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Week Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Week Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Required` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Given` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Today Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Today Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category Today Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Today Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Today Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Today Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Today Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Total AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Total AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Total Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Total Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Total Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Total GMROI` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Required` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Given` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Year To Day Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Year To Day Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Year To Day Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Required` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Given` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Week To Day Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Week To Day Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Week To Day Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Required` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Given` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Month To Day Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Month To Day Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Month To Day Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Part Category Month To Day Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Required` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Given` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Yesterday Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Yesterday Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Yesterday Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Required` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Given` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Month Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Month Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Month Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Required` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Given` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Week Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Week Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Week Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Required` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Provided` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Lost` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Broken` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Sold` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Given` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Total Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Total Acc Margin` float NOT NULL DEFAULT '0',
  `Part Category Total Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Category Total Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Total Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Total Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Category Total Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Month Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Week Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Yesterday Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Week To Day Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Today Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Month To Day Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Year To Day Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 3 Year Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Year Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 6 Month Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Quarter Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Month Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 10 Day Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Week Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Month Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Week Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Yesterday Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Week To Day Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Today Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Month To Day Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Year To Day Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 3 Year Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Year Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 6 Month Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Quarter Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Month Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 10 Day Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 1 Week Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category Last Month Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Category Last Month Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category Last Week Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category Yesterday Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category Week To Day Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category Today Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category Month To Day Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category Year To Day Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category 3 Year Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Year Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category 6 Month Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Quarter Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Month Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category 10 Day Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category 1 Week Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Category Number Surplus Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Category Number Optimal Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Category Number Low Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Category Number Critical Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Category Number Out Of Stock Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Category Number Error Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Category 1 Year Ago Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 2 Year Ago Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 3 Year Ago Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Category 4 Year Ago Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Part Category Key`),
  KEY `Part Category Status` (`Part Category Status`),
  KEY `Part Category Warehouse Key` (`Part Category Warehouse Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Category History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Category History Bridge` (
  `Warehouse Key` smallint(5) unsigned NOT NULL,
  `Category Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Type` enum('Changes','Assign') NOT NULL,
  UNIQUE KEY `Warehouse Key` (`Warehouse Key`,`Category Key`,`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Custom Field Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Custom Field Dimension` (
  `Part SKU` mediumint(8) unsigned NOT NULL,
  KEY `Part SKU` (`Part SKU`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Data`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Data` (
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Part 3 Year Acc Required` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Provided` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Lost` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Broken` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Acquired` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Sold` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Given` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Acc Margin` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc GMROI` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Required` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Provided` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Lost` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Broken` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Sold` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Given` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Year Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Year Acc Margin` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Year Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc GMROI` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Required` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Provided` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Lost` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Broken` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Acquired` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Sold` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Given` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 6 Month Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 6 Month Acc Margin` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 6 Month Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc GMROI` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Required` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Provided` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Lost` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Broken` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Sold` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Given` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Quarter Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Quarter Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Quarter Acc Margin` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Quarter Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc GMROI` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Required` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Provided` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Lost` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Broken` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Sold` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Given` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Month Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Month Acc Margin` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Month Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc GMROI` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Required` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Provided` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Lost` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Broken` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Acquired` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Sold` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Given` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 10 Day Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 10 Day Acc Margin` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 10 Day Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc GMROI` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Required` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Provided` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Lost` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Broken` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Sold` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Given` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Week Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Week Acc Margin` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Week Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Today Acc Required` float NOT NULL DEFAULT '0',
  `Part Today Acc Provided` float NOT NULL DEFAULT '0',
  `Part Today Acc Lost` float NOT NULL DEFAULT '0',
  `Part Today Acc Broken` float NOT NULL DEFAULT '0',
  `Part Today Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Today Acc Sold` float NOT NULL DEFAULT '0',
  `Part Today Acc Given` float NOT NULL DEFAULT '0',
  `Part Today Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Today Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Today Acc Margin` float NOT NULL DEFAULT '0',
  `Part Today Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Today Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Today Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Today Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Today Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Today Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Total AVG Stock` float NOT NULL DEFAULT '0',
  `Part Total AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Total Keeping Days` float NOT NULL DEFAULT '0',
  `Part Total Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Total Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Total GMROI` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Required` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Provided` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Lost` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Broken` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Sold` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Given` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Year To Day Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Year To Day Acc Margin` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Year To Day Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Required` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Provided` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Lost` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Broken` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Sold` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Given` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Week To Day Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Week To Day Acc Margin` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Week To Day Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Required` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Provided` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Lost` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Broken` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Sold` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Given` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Month To Day Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Month To Day Acc Margin` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Month To Day Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Required` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Provided` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Lost` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Broken` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Sold` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Given` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Yesterday Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Yesterday Acc Margin` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Yesterday Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Required` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Provided` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Lost` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Broken` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Sold` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Given` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Month Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Month Acc Margin` float NOT NULL DEFAULT '0',
  `Part Last Month Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Last Month Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Month Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Last Month Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Last Month Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Required` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Provided` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Lost` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Broken` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Sold` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Given` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Week Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Week Acc Margin` float NOT NULL DEFAULT '0',
  `Part Last Week Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Last Week Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Week Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Last Week Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Last Week Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Total Acc Required` float NOT NULL DEFAULT '0',
  `Part Total Acc Provided` float NOT NULL DEFAULT '0',
  `Part Total Acc Lost` float NOT NULL DEFAULT '0',
  `Part Total Acc Broken` float NOT NULL DEFAULT '0',
  `Part Total Acc Acquired` float NOT NULL DEFAULT '0',
  `Part Total Acc Sold` float NOT NULL DEFAULT '0',
  `Part Total Acc Given` float NOT NULL DEFAULT '0',
  `Part Total Acc Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Total Acc Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Total Acc Margin` float NOT NULL DEFAULT '0',
  `Part Total Acc AVG Stock` float NOT NULL DEFAULT '0',
  `Part Total Acc AVG Stock Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Total Acc Keeping Days` float NOT NULL DEFAULT '0',
  `Part Total Acc Out of Stock Days` float NOT NULL DEFAULT '0',
  `Part Total Acc Unknown Stock Days` float NOT NULL DEFAULT '0',
  `Part Total Acc GMROI` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Month Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Week Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Yesterday Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Week To Day Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Today Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Month To Day Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Year To Day Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Year Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 6 Month Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Quarter Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Month Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 10 Day Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Week Acc 1YB Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Month Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YB Acquired` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Week Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Yesterday Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Week To Day Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Today Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Month To Day Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Year To Day Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Year Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 6 Month Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Quarter Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Month Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 10 Day Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Week Acc 1YB Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Last Month Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YB Sold` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YB Provided` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YB Required` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YB Given` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YB Broken` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YB Lost` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YB Margin` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Profit` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Profit After Storing` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Acquired` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Sold Amount` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Sold` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Provided` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Required` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Given` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Broken` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Lost` float NOT NULL DEFAULT '0',
  `Part Last Month Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Last Week Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Yesterday Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Week To Day Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Today Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Month To Day Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part Year To Day Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part 3 Year Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part 1 Year Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part 6 Month Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part 1 Quarter Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part 1 Month Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part 10 Day Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part 1 Week Acc 1YD Margin` float NOT NULL DEFAULT '0',
  `Part 1 Year Ago Provided` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 2 Year Ago Provided` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Ago Provided` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 4 Year Ago Provided` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 1 Year Ago Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 2 Year Ago Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 3 Year Ago Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part 4 Year Ago Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  UNIQUE KEY `Part SKU` (`Part SKU`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Deleted Dimension` (
  `Part Deleted Key` mediumint(9) NOT NULL,
  `Part Deleted Reference` varchar(255) DEFAULT NULL,
  `Part Deleted Date` datetime DEFAULT NULL,
  `Part Deleted Metadata` blob,
  PRIMARY KEY (`Part Deleted Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Dimension` (
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Part Reference` varchar(32) DEFAULT NULL,
  `Part Unit` enum('10','25','100','200','bag','ball','box','doz','dwt','ea','foot','gram','gross','hank','kilo','ib','m','oz','ozt','pair','pkg','set','skein','spool','strand','ten','tube','vial','yd') NOT NULL DEFAULT 'ea',
  `Part Unit Label` varchar(255) NOT NULL DEFAULT 'unit',
  `Part Status` enum('Not In Use','In Use','Discontinuing') NOT NULL DEFAULT 'In Use',
  `Part Available` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Part Available for Products` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Part Available for Products Configuration` enum('Yes','No','Automatic') NOT NULL DEFAULT 'No',
  `Part Main State` enum('Keeping','LastStock','Discontinued','NotKeeping') DEFAULT NULL,
  `Part Cost` decimal(12,4) DEFAULT '0.0000',
  `Part Unit Price` float unsigned DEFAULT NULL,
  `Part Unit RRP` float unsigned DEFAULT NULL,
  `Part XHTML Currently Used In` text,
  `Part Stock Status` enum('Surplus','Optimal','Low','Critical','Out_Of_Stock','Error') NOT NULL DEFAULT 'Optimal',
  `Part Stock State` enum('Excess','Normal','Low','VeryLow','OutofStock','Error') NOT NULL DEFAULT 'Normal',
  `Part Last Stock` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Part Currently Used In` varchar(255) DEFAULT NULL,
  `Part XHTML Currently Supplied By` text,
  `Part XHTML Description` varchar(255) DEFAULT NULL,
  `Part Package Description` varchar(255) DEFAULT NULL,
  `Part Unit Description` text,
  `Part Barcode Type` enum('none','ean8','ean13',' code11','code39','code128','codabar') NOT NULL DEFAULT 'code128',
  `Part Barcode Data Source` enum('SKU','Reference','Other') NOT NULL DEFAULT 'SKU',
  `Part Barcode Data` text,
  `Part Barcode Key` mediumint(8) unsigned DEFAULT NULL,
  `Part Barcode Number` bigint(20) unsigned DEFAULT NULL,
  `Part General Description` longtext,
  `Part Health And Safety` longtext,
  `Part UN Number` varchar(4) DEFAULT NULL,
  `Part UN Class` varchar(4) DEFAULT NULL,
  `Part Packing Group` enum('None','I','II','III') DEFAULT 'None',
  `Part Proper Shipping Name` varchar(255) DEFAULT NULL,
  `Part Hazard Indentification Number` varchar(64) DEFAULT NULL,
  `Part MSDS Attachment Bridge Key` mediumint(9) unsigned DEFAULT NULL,
  `Part MSDS Attachment XHTML Info` text,
  `Part Tariff Code` varchar(255) DEFAULT NULL,
  `Part Tariff Code Valid` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Part Duty Rate` varchar(255) DEFAULT NULL,
  `Part Origin Country Code` varchar(3) DEFAULT NULL,
  `Part Package Type` enum('Bottle','Bag','Box','None','Other') NOT NULL DEFAULT 'Box',
  `Part Package Weight` float DEFAULT NULL,
  `Part Package Weight Display` float DEFAULT NULL,
  `Part Package Weight Display Units` enum('Kg','g','oz','lb') DEFAULT 'Kg',
  `Part Package Volume` float unsigned DEFAULT NULL,
  `Part Units` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Part Unit Weight` float DEFAULT NULL,
  `Part Unit Weight Display` float DEFAULT NULL,
  `Part Unit Weight Display Units` enum('Kg','g','oz','lb') DEFAULT 'Kg',
  `Part Package Dimensions` varchar(255) DEFAULT NULL,
  `Part Unit Dimensions Type` enum('Rectangular','Cilinder','Sphere','String','Sheet') DEFAULT 'Rectangular',
  `Part Unit Dimensions Display Units` enum('mm','cm','m','in','yd','ft') DEFAULT 'cm',
  `Part Unit Dimensions Width` float DEFAULT NULL,
  `Part Unit Dimensions Depth` float DEFAULT NULL,
  `Part Unit Dimensions Length` float DEFAULT NULL,
  `Part Unit Dimensions Diameter` float DEFAULT NULL,
  `Part Unit Dimensions Width Display` float DEFAULT NULL,
  `Part Unit Dimensions Depth Display` float DEFAULT NULL,
  `Part Unit Dimensions Length Display` float DEFAULT NULL,
  `Part Unit Dimensions Diameter Display` float DEFAULT NULL,
  `Part Unit Dimensions Volume` float DEFAULT NULL,
  `Part Unit XHTML Dimensions` varchar(255) DEFAULT NULL,
  `Part Unit Dimensions` varchar(255) DEFAULT NULL,
  `Part Unit Volume` float DEFAULT NULL,
  `Part Unit Materials` text,
  `Part Materials` text,
  `Part Unit XHTML Materials` text,
  `Part Package Dimensions Type` enum('Rectangular','Cilinder','Sphere') DEFAULT 'Rectangular',
  `Part Package Dimensions Display Units` enum('mm','cm','m','in','yd','ft') DEFAULT 'cm',
  `Part Package Dimensions Width` float DEFAULT NULL,
  `Part Package Dimensions Depth` float DEFAULT NULL,
  `Part Package Dimensions Length` float DEFAULT NULL,
  `Part Package Dimensions Diameter` float DEFAULT NULL,
  `Part Package Dimensions Width Display` float DEFAULT NULL,
  `Part Package Dimensions Depth Display` float DEFAULT NULL,
  `Part Package Dimensions Length Display` float DEFAULT NULL,
  `Part Package Dimensions Diameter Display` float DEFAULT NULL,
  `Part Package Dimensions Volume` float DEFAULT NULL,
  `Part Package XHTML Dimensions` varchar(255) DEFAULT NULL,
  `Part Comercial Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Current Stock` decimal(24,6) DEFAULT NULL,
  `Part Current On Hand Stock` decimal(24,6) NOT NULL DEFAULT '0.000000',
  `Part Current Stock In Process` decimal(24,6) NOT NULL DEFAULT '0.000000',
  `Part Current Stock Picked` decimal(24,6) NOT NULL DEFAULT '0.000000',
  `Part Current Value` decimal(24,4) NOT NULL DEFAULT '0.0000',
  `Part Current Stock Negative Discrepancy` decimal(24,6) NOT NULL DEFAULT '0.000000',
  `Part Current Stock Negative Discrepancy Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Part Current Stock Cost Per Unit` decimal(12,2) DEFAULT NULL,
  `Part Current Storing Cost Per Unit` decimal(12,2) DEFAULT NULL,
  `Part XHTML Picking Location` varchar(255) NOT NULL,
  `Part Distinct Locations` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Part Days Available Forecast` float DEFAULT NULL,
  `Part XHTML Available For Forecast` text,
  `Part Average Future Cost Per Unit` float DEFAULT NULL,
  `Part Minimum Future Cost Per Unit` float DEFAULT NULL,
  `Part Next Shipment State` enum('None','Set','Overdue') NOT NULL DEFAULT 'None',
  `Part Next Supplier Shipment from PO` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Part Next Supplier Shipment` datetime DEFAULT NULL,
  `Part XHTML Next Supplier Shipment` text,
  `Part Valid From` datetime DEFAULT NULL,
  `Part Valid To` datetime DEFAULT NULL,
  `Part Sticky Note` text,
  `Part Main Image` varchar(1024) DEFAULT NULL,
  `Part Main Image Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Transactions` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Transactions In` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Transactions Out` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Transactions Audit` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Transactions OIP` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Transactions Move` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Part Delivery Days` smallint(5) unsigned NOT NULL DEFAULT '30',
  `Part Excess Availability Days Limit` smallint(5) unsigned NOT NULL DEFAULT '120',
  `Part Last Sale Date` datetime DEFAULT NULL,
  `Part Last Purchase Date` datetime DEFAULT NULL,
  `Part Last Booked In Date` datetime DEFAULT NULL,
  `Part Last Updated` datetime DEFAULT NULL,
  `Part Family Category Key` mediumint(8) unsigned DEFAULT NULL,
  `Part Units Per Package` smallint(5) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`Part SKU`),
  KEY `Part TYpe` (`Part Status`),
  KEY `Part Valid From` (`Part Valid From`),
  KEY `Part Valid To` (`Part Valid To`),
  KEY `Part Currently Used In` (`Part Currently Used In`),
  KEY `Part Available` (`Part Available`),
  KEY `Part Main State` (`Part Main State`),
  KEY `Part Tarrif Code Valid` (`Part Tariff Code Valid`),
  KEY `Part Stock State` (`Part Stock State`),
  KEY `Part Reference` (`Part Reference`),
  KEY `Part Available for Products Configuration` (`Part Available for Products Configuration`),
  KEY `Part Next Shipment State` (`Part Next Shipment State`),
  KEY `Part Units` (`Part Units`),
  KEY `Part Barcode Number` (`Part Barcode Number`),
  KEY `Part Barcode Key` (`Part Barcode Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part History Bridge` (
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments','Products') NOT NULL,
  PRIMARY KEY (`Part SKU`,`History Key`),
  KEY `Part SKU` (`Part SKU`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Location Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Location Dimension` (
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Location Key` mediumint(9) NOT NULL,
  `Part Location Warehouse Key` smallint(6) NOT NULL DEFAULT '1',
  `Quantity On Hand` float DEFAULT '0',
  `Quantity In Process` float NOT NULL DEFAULT '0',
  `Stock Value` decimal(14,3) DEFAULT '0.000',
  `Can Pick` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Minimum Quantity` mediumint(8) unsigned DEFAULT NULL,
  `Maximum Quantity` mediumint(8) unsigned DEFAULT NULL,
  `Moving Quantity` float DEFAULT NULL,
  `Last Updated` datetime NOT NULL,
  `Negative Discrepancy` float NOT NULL DEFAULT '0',
  `Negative Discrepancy Value` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Part SKU`,`Location Key`),
  KEY `Part SKU` (`Part SKU`),
  KEY `Location Key` (`Location Key`),
  KEY `Part Location Warehouse Key` (`Part Location Warehouse Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Material Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Material Bridge` (
  `Part Material Key` int(10) unsigned NOT NULL,
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Material Key` mediumint(8) unsigned NOT NULL,
  `Ratio` double DEFAULT NULL,
  `May Contain` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Part Material Key`),
  UNIQUE KEY `Part SKU` (`Part SKU`,`Material Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Picking Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Picking Fact` (
  `Delivery Note Key` mediumint(8) unsigned NOT NULL,
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Quantity To Pick` float NOT NULL,
  `Quantity Picked` float NOT NULL,
  `Location` varchar(64) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Notes` varchar(255) NOT NULL,
  `Date Created` datetime DEFAULT NULL,
  `Date Picked` datetime DEFAULT NULL,
  `Original Metadata` varchar(16) NOT NULL,
  KEY `Order Key` (`Delivery Note Key`,`Part SKU`),
  KEY `Part SKU` (`Part SKU`),
  KEY `Delivery Note Key` (`Delivery Note Key`),
  KEY `Order Original Metadata` (`Original Metadata`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Supplier Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Supplier Bridge` (
  `Part Key` mediumint(8) unsigned NOT NULL,
  `Supplier Key` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `Part Key` (`Part Key`,`Supplier Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Vendor Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Vendor Bridge` (
  `Part Key` mediumint(8) unsigned NOT NULL,
  `Vendor Key` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `Part Key` (`Part Key`,`Vendor Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Warehouse Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Warehouse Bridge` (
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Warehouse Key` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`Part SKU`,`Warehouse Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Part Week Forecasting Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Part Week Forecasting Dimension` (
  `Part Forecasting Part SKU` mediumint(8) unsigned NOT NULL,
  `Part Forecasting Date` date NOT NULL,
  `Part Forecasting Week Value` float NOT NULL,
  KEY `Part Forecasting Part SKU` (`Part Forecasting Part SKU`),
  KEY `Part Forecasting Date` (`Part Forecasting Date`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment Account Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment Account Dimension` (
  `Payment Account Key` mediumint(9) NOT NULL,
  `Payment Service Provider Key` mediumint(9) NOT NULL,
  `Payment Account Code` varchar(64) NOT NULL,
  `Payment Account Name` varchar(32) NOT NULL,
  `Payment Account ID` varchar(128) DEFAULT NULL,
  `Payment Account Cart ID` varchar(64) DEFAULT NULL,
  `Payment Type` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown','Account','Sofort') NOT NULL DEFAULT 'Unknown',
  `Payment Account Login` varchar(64) DEFAULT NULL,
  `Payment Account Password` varchar(64) DEFAULT NULL,
  `Payment Account Response` varchar(64) DEFAULT NULL,
  `Payment Account Online Refund` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Payment Account Refund Login` varchar(255) DEFAULT NULL,
  `Payment Account Refund Password` varchar(255) DEFAULT NULL,
  `Payment Account Refund Signature` varchar(255) DEFAULT NULL,
  `Payment Account Refund URL Link` varchar(255) DEFAULT NULL,
  `Payment Account Last Used Date` datetime DEFAULT NULL,
  `Payment Account Recipient Holder` varchar(64) DEFAULT NULL,
  `Payment Account Recipient Address` text,
  `Payment Account Recipient Bank Account Number` varchar(32) DEFAULT NULL,
  `Payment Account Recipient Bank Code` varchar(32) DEFAULT NULL,
  `Payment Account Recipient Bank Name` varchar(32) DEFAULT NULL,
  `Payment Account Recipient Bank Swift` varchar(32) DEFAULT NULL,
  `Payment Account Recipient Bank IBAN` varchar(32) DEFAULT NULL,
  `Payment Account Recipient Bank Country Code` varchar(3) DEFAULT NULL,
  `Payment Account Business Name` varchar(64) DEFAULT NULL,
  `Payment Account URL Link` varchar(255) DEFAULT NULL,
  `Payment Account Button Image URL` varchar(128) DEFAULT NULL,
  `Payment Account Button Image URL Selected` varchar(128) DEFAULT NULL,
  `Payment Account Settings` text,
  `Payment Account Currency` text NOT NULL,
  `Payment Account Transactions` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Payment Account Payments Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Payment Account Refunds Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Payment Account Balance Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Payment Account Key`),
  UNIQUE KEY `Payment Account Code` (`Payment Account Code`),
  KEY `Payment Service Provider Key` (`Payment Service Provider Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment Account History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment Account History Bridge` (
  `Payment Account Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes',
  PRIMARY KEY (`Payment Account Key`,`History Key`),
  KEY `Payment Account Key` (`Payment Account Key`),
  KEY `History Key` (`History Key`),
  KEY `Deletable` (`Deletable`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment Account Site Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment Account Site Bridge` (
  `Payment Account Key` mediumint(9) NOT NULL,
  `Site Key` mediumint(9) NOT NULL DEFAULT '0',
  `Store Key` mediumint(9) NOT NULL,
  `Valid From` datetime DEFAULT NULL,
  `Valid To` datetime DEFAULT NULL,
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `Show In Cart` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Show Cart Order` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Payment Account Key`,`Site Key`),
  KEY `Site Key` (`Site Key`,`Show In Cart`),
  KEY `Show Cart Order` (`Show Cart Order`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment Dimension` (
  `Payment Key` mediumint(9) NOT NULL,
  `Payment Account Key` mediumint(9) NOT NULL,
  `Payment Account Code` varchar(64) NOT NULL,
  `Payment Service Provider Key` mediumint(9) NOT NULL,
  `Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown','Account') NOT NULL DEFAULT 'Unknown',
  `Payment Invoice Key` mediumint(8) unsigned DEFAULT NULL,
  `Payment Order Key` mediumint(9) DEFAULT NULL,
  `Payment Store Key` mediumint(9) NOT NULL,
  `Payment Site Key` mediumint(9) DEFAULT NULL,
  `Payment Customer Key` mediumint(8) unsigned NOT NULL,
  `Payment Type` enum('Payment','Refund','Credit') NOT NULL DEFAULT 'Payment',
  `Payment Balance` decimal(12,2) NOT NULL,
  `Payment Amount` decimal(12,2) NOT NULL,
  `Payment Refund` decimal(12,2) NOT NULL,
  `Payment Amount Invoiced` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Payment Currency Code` varchar(3) NOT NULL,
  `Payment Transaction ID` varchar(128) DEFAULT NULL,
  `Payment Transaction Auxiliary ID` varchar(64) NOT NULL,
  `Payment Random String` varchar(64) NOT NULL,
  `Payment Sender` varchar(128) DEFAULT NULL,
  `Payment Sender Account Number` varchar(64) DEFAULT NULL,
  `Payment Sender Sort Code` varchar(32) DEFAULT NULL,
  `Payment Sender Bank Name` varchar(32) DEFAULT NULL,
  `Payment Sender BIC` varchar(64) DEFAULT NULL,
  `Payment Sender IBAN` varchar(64) DEFAULT NULL,
  `Payment Sender Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Payment Sender Language` varchar(2) DEFAULT NULL,
  `Payment Sender Email` varchar(128) DEFAULT NULL,
  `Payment Sender Card Type` varchar(16) DEFAULT NULL,
  `Payment Sender Payment Paypal Type` varchar(64) DEFAULT NULL,
  `Payment Sender Message` varchar(255) DEFAULT NULL,
  `Payment Transaction Address Status` varchar(16) DEFAULT NULL,
  `Payment Created Date` datetime DEFAULT NULL,
  `Payment Completed Date` datetime DEFAULT NULL,
  `Payment Cancelled Date` datetime DEFAULT NULL,
  `Payment Last Updated Date` datetime DEFAULT NULL,
  `Payment Fees` decimal(12,2) DEFAULT NULL,
  `Payment Transaction Status` enum('Pending','Completed','Cancelled','Error','Declined') NOT NULL DEFAULT 'Pending',
  `Payment Transaction Status Info` varchar(128) DEFAULT NULL,
  `Payment Related Payment Key` mediumint(8) unsigned DEFAULT NULL,
  `Payment Related Payment Transaction ID` varchar(255) DEFAULT NULL,
  `Payment Submit Type` enum('Manual','EPS','AutoCredit','OrphanRefund') NOT NULL DEFAULT 'EPS',
  `Payment User Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Payment Key`),
  KEY `Payment Customer Key` (`Payment Customer Key`),
  KEY `Payment Type` (`Payment Type`),
  KEY `Payment Related Payment Key` (`Payment Related Payment Key`),
  KEY `Payment Invoice Key` (`Payment Invoice Key`),
  KEY `Payment Method` (`Payment Method`),
  KEY `Payment Order Key` (`Payment Order Key`),
  KEY `Payment Transaction Status` (`Payment Transaction Status`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment History Bridge` (
  `Payment Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes',
  PRIMARY KEY (`Payment Key`,`History Key`),
  KEY `Payment Key` (`Payment Key`),
  KEY `History Key` (`History Key`),
  KEY `Deletable` (`Deletable`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment Service Provider Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment Service Provider Dimension` (
  `Payment Service Provider Key` mediumint(9) unsigned NOT NULL,
  `Payment Service Provider Code` varchar(64) NOT NULL,
  `Payment Service Provider Name` varchar(128) NOT NULL,
  `Payment Service Provider Type` enum('EPS','EBeP','Bank','Cash','Account','ConD') NOT NULL,
  `Payment Service Provider Last Used Date` datetime DEFAULT NULL,
  `Payment Service Provider Accounts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Payment Service Provider Transactions` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Payment Service Provider Currency` text NOT NULL,
  `Payment Service Provider Payments Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Payment Service Provider Refunds Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Payment Service Provider Balance Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Payment Service Provider Key`),
  KEY `Payment Service Provider Code` (`Payment Service Provider Code`(16))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment Service Provider History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment Service Provider History Bridge` (
  `Payment Service Provider Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes',
  PRIMARY KEY (`Payment Service Provider Key`,`History Key`),
  KEY `Payment Service Provider Key` (`Payment Service Provider Key`),
  KEY `History Key` (`History Key`),
  KEY `Deletable` (`Deletable`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment Service Provider Payment Method Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment Service Provider Payment Method Bridge` (
  `Payment Service Provider Key` smallint(5) unsigned NOT NULL,
  `Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Customer Account') NOT NULL,
  UNIQUE KEY `Payment Service Provider Key` (`Payment Service Provider Key`,`Payment Method`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Availability Timeline`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Availability Timeline` (
  `Product Availability Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Department Key` mediumint(8) unsigned NOT NULL,
  `Family Key` mediumint(8) unsigned NOT NULL,
  `User Key` mediumint(8) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  `Duration` mediumint(9) DEFAULT NULL COMMENT 'In seconds',
  `Availability` enum('Yes','No') NOT NULL,
  `Web State` enum('For Sale','Out of Stock','Discontinued','Offline') DEFAULT NULL,
  `Source` enum('Manual','Automatic','Fix') NOT NULL DEFAULT 'Manual',
  PRIMARY KEY (`Product Availability Key`),
  KEY `Date` (`Date`),
  KEY `Product ID` (`Product ID`),
  KEY `Family Key` (`Family Key`),
  KEY `Deparment Key` (`Department Key`),
  KEY `Store Key` (`Store Key`),
  KEY `User Key` (`User Key`),
  KEY `Source` (`Source`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Category Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Category Bridge` (
  `Product Key` mediumint(8) unsigned NOT NULL,
  `Category Key` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `Product Key` (`Product Key`,`Category Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Category Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Category Dimension` (
  `Product Category Key` mediumint(8) unsigned NOT NULL,
  `Product Category Store Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Product Category Description` mediumtext NOT NULL,
  `Product Category Valid From` datetime DEFAULT NULL,
  `Product Category Valid To` datetime DEFAULT NULL,
  `Product Category Part Family Category Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Category Departments` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Families` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category For Public Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category For Private Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category In Process Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Not For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Unknown Sales State Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Unknown Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Category Total Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category Total Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category Total Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Category Total Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Category Total Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Category Total Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Category Total Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Category Total Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category Total Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category Total Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category 1 Year Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Year Acc Quantity Ordered` float DEFAULT '0',
  `Product Category 1 Year Acc Quantity Invoiced` float DEFAULT '0',
  `Product Category 1 Year Acc Quantity Delivered` float DEFAULT '0',
  `Product Category 1 Year Acc Days On Sale` float DEFAULT '0',
  `Product Category 1 Year Acc Days Available` float DEFAULT '0',
  `Product Category 1 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category 1 Quarter Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Quarter Acc Quantity Ordered` float DEFAULT '0',
  `Product Category 1 Quarter Acc Quantity Invoiced` float DEFAULT '0',
  `Product Category 1 Quarter Acc Quantity Delivered` float DEFAULT '0',
  `Product Category 1 Quarter Acc Days On Sale` float DEFAULT '0',
  `Product Category 1 Quarter Acc Days Available` float DEFAULT '0',
  `Product Category 1 Quarter Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Quarter Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Quarter Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category 1 Month Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Month Acc Quantity Ordered` float DEFAULT '0',
  `Product Category 1 Month Acc Quantity Invoiced` float DEFAULT '0',
  `Product Category 1 Month Acc Quantity Delivered` float DEFAULT '0',
  `Product Category 1 Month Acc Days On Sale` float DEFAULT '0',
  `Product Category 1 Month Acc Days Available` float DEFAULT '0',
  `Product Category 1 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category 1 Week Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Category 1 Week Acc Quantity Ordered` float DEFAULT '0',
  `Product Category 1 Week Acc Quantity Invoiced` float DEFAULT '0',
  `Product Category 1 Week Acc Quantity Delivered` float DEFAULT '0',
  `Product Category 1 Week Acc Days On Sale` float DEFAULT '0',
  `Product Category 1 Week Acc Days Available` float DEFAULT '0',
  `Product Category 1 Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Week Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category 1 Week Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Category Stock Value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Product Category DC Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category DC Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category DC Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category DC Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category DC 1 Year Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category DC 1 Quarter Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category DC 1 Month Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Product Category DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Category DC 1 Week Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Category Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Product Category Public` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Product Category Key`,`Product Category Store Key`),
  KEY `Product Category Store Key` (`Product Category Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Category History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Category History Bridge` (
  `Store Key` smallint(5) unsigned NOT NULL,
  `Category Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Type` enum('Changes','Assign') NOT NULL,
  UNIQUE KEY `Store Key` (`Store Key`,`Category Key`,`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Code Default Currency`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Code Default Currency` (
  `Product Code` varchar(64) NOT NULL,
  `Product Code DC Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Code DC Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Code DC Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Code DC Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Code DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Code DC 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Code DC 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Code DC 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Code DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Code DC 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`Product Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Data Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Data Dimension` (
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Product Total Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Total Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Total Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Total Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Total Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Total Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Total Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Total Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Total Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Total Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Total Acc Estimated GMROI` float DEFAULT NULL,
  `Product 3 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product 3 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 3 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product 3 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product 3 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product 3 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product 3 Year Acc Days On Sale` float DEFAULT NULL,
  `Product 3 Year Acc Days Available` float DEFAULT NULL,
  `Product 3 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 3 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 3 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product 1 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product 1 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product 1 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product 1 Year Acc Days On Sale` float DEFAULT NULL,
  `Product 1 Year Acc Days Available` float DEFAULT NULL,
  `Product 1 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Year Acc Estimated GMROI` float DEFAULT NULL,
  `Product Year To Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Year To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Year To Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Year To Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Year To Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Year To Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Year To Day Acc Days On Sale` float DEFAULT NULL,
  `Product Year To Day Acc Days Available` float DEFAULT NULL,
  `Product Year To Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Year To Day Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Year To Day Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Last Month Acc Invoiced Gross Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Last Month Acc Invoiced Discount Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Last Month Acc Invoiced Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Last Month Acc Profit` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Last Month Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Last Month Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Last Month Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Last Month Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Last Month Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Last Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Last Month Acc Customers` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `Product Last Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Last Week Acc Invoiced Gross Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Last Week Acc Invoiced Discount Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Last Week Acc Invoiced Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Last Week Acc Profit` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Last Week Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Last Week Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Last Week Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Last Week Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Last Week Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Last Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Last Week Acc Customers` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `Product Last Week Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Yesterday Acc Invoiced Gross Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Yesterday Acc Invoiced Discount Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Yesterday Acc Invoiced Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Yesterday Acc Profit` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Yesterday Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Yesterday Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Yesterday Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Yesterday Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Yesterday Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Yesterday Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Yesterday Acc Customers` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `Product Yesterday Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Today Acc Invoiced Gross Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Today Acc Invoiced Discount Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Today Acc Invoiced Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Today Acc Profit` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Today Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Today Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Today Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Today Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Today Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Today Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Today Acc Customers` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `Product Today Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Month To Day Acc Invoiced Gross Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Month To Day Acc Invoiced Discount Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Month To Day Acc Invoiced Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Month To Day Acc Profit` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Month To Day Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Month To Day Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Month To Day Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Month To Day Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Month To Day Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Month To Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Month To Day Acc Customers` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `Product Month To Day Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Week To Day Acc Invoiced Gross Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Week To Day Acc Invoiced Discount Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Week To Day Acc Invoiced Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Week To Day Acc Profit` decimal(10,0) NOT NULL DEFAULT '0',
  `Product Week To Day Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Week To Day Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Week To Day Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Week To Day Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Week To Day Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Week To Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Week To Day Acc Customers` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `Product Week To Day Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 6 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product 6 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 6 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product 6 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product 6 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product 6 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product 6 Month Acc Days On Sale` float DEFAULT NULL,
  `Product 6 Month Acc Days Available` float DEFAULT NULL,
  `Product 6 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 6 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 6 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product 1 Quarter Acc Quantity Ordered` float DEFAULT NULL,
  `Product 1 Quarter Acc Quantity Invoiced` float DEFAULT NULL,
  `Product 1 Quarter Acc Quantity Delivered` float DEFAULT NULL,
  `Product 1 Quarter Acc Days On Sale` float DEFAULT NULL,
  `Product 1 Quarter Acc Days Available` float DEFAULT NULL,
  `Product 1 Quarter Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Quarter Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Quarter Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 3 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product 3 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product 3 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 3 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product 3 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product 3 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product 3 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product 3 Month Acc Days On Sale` float DEFAULT NULL,
  `Product 3 Month Acc Days Available` float DEFAULT NULL,
  `Product 3 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 3 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 3 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product 1 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product 1 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product 1 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product 1 Month Acc Days On Sale` float DEFAULT NULL,
  `Product 1 Month Acc Days Available` float DEFAULT NULL,
  `Product 1 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Month Acc Estimated GMROI` float DEFAULT NULL,
  `Product 10 Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product 10 Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 10 Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product 10 Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product 10 Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product 10 Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product 10 Day Acc Days On Sale` float DEFAULT NULL,
  `Product 10 Day Acc Days Available` float DEFAULT NULL,
  `Product 10 Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 10 Day Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 10 Day Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product 1 Week Acc Quantity Ordered` float DEFAULT NULL,
  `Product 1 Week Acc Quantity Invoiced` float DEFAULT NULL,
  `Product 1 Week Acc Quantity Delivered` float DEFAULT NULL,
  `Product 1 Week Acc Days On Sale` float DEFAULT NULL,
  `Product 1 Week Acc Days Available` float DEFAULT NULL,
  `Product 1 Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Week Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Week Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product 1 Week Acc Estimated GMROI` float DEFAULT NULL,
  `Product Total Acc Margin` float DEFAULT NULL,
  `Product 3 Year Acc Margin` float DEFAULT NULL,
  `Product 1 Year Acc Margin` float DEFAULT NULL,
  `Product Year To Day Acc Margin` float DEFAULT NULL,
  `Product Month To Day Acc Margin` float DEFAULT NULL,
  `Product Week To Day Acc Margin` float DEFAULT NULL,
  `Product Today Acc Margin` float NOT NULL DEFAULT '0',
  `Product Yesterday Acc Margin` float NOT NULL DEFAULT '0',
  `Product 6 Month Acc Margin` float DEFAULT NULL,
  `Product 1 Quarter Acc Margin` float DEFAULT NULL,
  `Product 3 Month Acc Margin` float DEFAULT NULL,
  `Product 1 Month Acc Margin` float DEFAULT NULL,
  `Product 10 Day Acc Margin` float DEFAULT NULL,
  `Product 1 Week Acc Margin` float DEFAULT NULL,
  `Product Last Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Last Week Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Yesterday Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Week To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Today Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Month To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Year To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product 3 Year Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product 1 Year Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product 6 Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product 1 Quarter Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product 1 Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product 10 Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product 1 Week Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Last Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Last Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Yesterday Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Week To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Today Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Month To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Year To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 3 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 6 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Quarter Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 10 Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Last Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Last Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Yesterday Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Week To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Today Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Month To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Year To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 3 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 6 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Quarter Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 10 Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product 1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Last Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Last Week Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Yesterday Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Week To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Today Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Month To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Year To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product 3 Year Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product 1 Year Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product 6 Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product 1 Quarter Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product 1 Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product 10 Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product 1 Week Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`Product ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Department Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Department Bridge` (
  `Product Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `Product Key` (`Product Key`,`Product Department Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Department Data Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Department Data Dimension` (
  `Product Department Key` mediumint(8) unsigned NOT NULL,
  `Product Department Total Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Department Total Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Department Total Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Department Total Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Product Department Total Acc Days Available` float NOT NULL DEFAULT '0',
  `Product Department Total Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Total Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Total Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 3 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 3 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 3 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department 3 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department 3 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department 3 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department 3 Year Acc Days On Sale` float DEFAULT NULL,
  `Product Department 3 Year Acc Days Available` float DEFAULT NULL,
  `Product Department 3 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 3 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 3 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Year To Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Year To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Year To Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department Year To Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department Year To Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department Year To Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department Year To Day Acc Days On Sale` float DEFAULT NULL,
  `Product Department Year To Day Acc Days Available` float DEFAULT NULL,
  `Product Department Year To Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Year To Day Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Year To Day Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Month To Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Month To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Month To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Month To Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department Month To Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department Month To Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department Month To Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department Month To Day Acc Days On Sale` float DEFAULT NULL,
  `Product Department Month To Day Acc Days Available` float DEFAULT NULL,
  `Product Department Month To Day Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Month To Day Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Month To Day Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Week To Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Week To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Week To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Week To Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department Week To Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department Week To Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department Week To Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department Week To Day Acc Days On Sale` float DEFAULT NULL,
  `Product Department Week To Day Acc Days Available` float DEFAULT NULL,
  `Product Department Week To Day Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Week To Day Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Week To Day Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Today Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Today Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Today Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department Today Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department Today Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department Today Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department Today Acc Days On Sale` float DEFAULT NULL,
  `Product Department Today Acc Days Available` float DEFAULT NULL,
  `Product Department Today Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Today Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Today Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Last Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Last Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department Last Week Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department Last Week Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department Last Week Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department Last Week Acc Days On Sale` float DEFAULT NULL,
  `Product Department Last Week Acc Days Available` float DEFAULT NULL,
  `Product Department Last Week Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Last Week Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Last Week Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Last Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Last Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department Last Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department Last Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department Last Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department Last Month Acc Days On Sale` float DEFAULT NULL,
  `Product Department Last Month Acc Days Available` float DEFAULT NULL,
  `Product Department Last Month Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Last Month Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Last Month Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Yesterday Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Yesterday Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Yesterday Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department Yesterday Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department Yesterday Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department Yesterday Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department Yesterday Acc Days On Sale` float DEFAULT NULL,
  `Product Department Yesterday Acc Days Available` float DEFAULT NULL,
  `Product Department Yesterday Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Yesterday Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Yesterday Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department 1 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department 1 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department 1 Year Acc Days On Sale` float DEFAULT NULL,
  `Product Department 1 Year Acc Days Available` float DEFAULT NULL,
  `Product Department 1 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 6 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 6 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 6 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department 6 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department 6 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department 6 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department 6 Month Acc Days On Sale` float DEFAULT NULL,
  `Product Department 6 Month Acc Days Available` float DEFAULT NULL,
  `Product Department 6 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 6 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 6 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Quarter Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department 1 Quarter Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department 1 Quarter Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department 1 Quarter Acc Days On Sale` float DEFAULT NULL,
  `Product Department 1 Quarter Acc Days Available` float DEFAULT NULL,
  `Product Department 1 Quarter Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Quarter Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Quarter Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 3 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 3 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 3 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 3 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department 3 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department 3 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department 3 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department 3 Month Acc Days On Sale` float DEFAULT NULL,
  `Product Department 3 Month Acc Days Available` float DEFAULT NULL,
  `Product Department 3 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 3 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 3 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department 1 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department 1 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department 1 Month Acc Days On Sale` float DEFAULT NULL,
  `Product Department 1 Month Acc Days Available` float DEFAULT NULL,
  `Product Department 1 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 10 Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 10 Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 10 Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department 10 Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department 10 Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department 10 Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department 10 Day Acc Days On Sale` float DEFAULT NULL,
  `Product Department 10 Day Acc Days Available` float DEFAULT NULL,
  `Product Department 10 Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 10 Day Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 10 Day Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department 1 Week Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department 1 Week Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department 1 Week Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department 1 Week Acc Days On Sale` float DEFAULT NULL,
  `Product Department 1 Week Acc Days Available` float DEFAULT NULL,
  `Product Department 1 Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Week Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department 1 Week Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Total Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Last Week Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Yesterday Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Week To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Today Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Month To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Year To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department 3 Year Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department 1 Year Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department 6 Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department 1 Quarter Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department 1 Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department 10 Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department 1 Week Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department Last Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Yesterday Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Week To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Today Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Month To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Year To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 3 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 6 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 10 Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Yesterday Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Week To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Today Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Month To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Year To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 3 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 6 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 10 Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Last Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department Last Week Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department Yesterday Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department Week To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department Today Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department Month To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department Year To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department 3 Year Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department 1 Year Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department 6 Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department 1 Quarter Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department 1 Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department 10 Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department 1 Week Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Department 10 Day Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 3 Year Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Year To Day Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Year To Day Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 6 Month Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 6 Month Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 10 Day Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 3 Year Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Product Department Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Department Default Currency`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Department Default Currency` (
  `Product Department Key` mediumint(8) unsigned NOT NULL,
  `Product Department DC Total Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Total Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department DC 3 Year Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 3 Year Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 6 Month Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 6 Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 10 Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 10 Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Year To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Year To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Month To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Month To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Month To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Week To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Week To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Week To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Today Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Today Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Yesterday Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Yesterday Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Last Week Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Last Week Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Last Month Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Last Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Product Department Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Department Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Department Dimension` (
  `Product Department Key` mediumint(8) unsigned NOT NULL,
  `Product Department Type` enum('Normal','Unknown','Historic') NOT NULL DEFAULT 'Normal',
  `Product Department Sales Type` enum('Public Sale','Private Sale','Not for Sale') NOT NULL DEFAULT 'Public Sale',
  `Product Department Store Key` smallint(5) unsigned NOT NULL,
  `Product Department Store Code` varchar(32) NOT NULL,
  `Product Department Code` varchar(255) DEFAULT NULL,
  `Product Department Name` varchar(255) DEFAULT NULL,
  `Product Department Description` text NOT NULL,
  `Product Department Slogan` varchar(255) DEFAULT NULL,
  `Product Department Marketing Resume` varchar(1024) DEFAULT NULL,
  `Product Department Marketing Presentation` text NOT NULL,
  `Product Department Main Image` varchar(255) NOT NULL DEFAULT 'art/nopic.png',
  `Product Department Main Image Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Valid From` datetime DEFAULT NULL,
  `Product Department Valid To` datetime DEFAULT NULL,
  `Product Department Number Days on Sale` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Number Days with Sales` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Number Days Available` float NOT NULL DEFAULT '0',
  `Product Department Avg Day Sales` float NOT NULL DEFAULT '0',
  `Product Department Avg with Sale Day Sales` float NOT NULL DEFAULT '0',
  `Product Department STD with Sale Day Sales` float NOT NULL DEFAULT '0',
  `Product Department Max Day Sales` float NOT NULL DEFAULT '0',
  `Product Department Sticky Note` text,
  `Product Department Most Recent` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Department Most Recent Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Department Page Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Department Families` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department For Public For Sale Families` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department For Public Discontinued Families` mediumint(9) NOT NULL DEFAULT '0',
  `Product Department For Public Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department For Private Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department In Process Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Not For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Historic Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Unknown Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Stock Value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Product Department Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Product Department Active Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Active Customers More 0.75 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Active Customers More 0.5 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Active Customers More 0.25 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Losing Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Losing Customers More 0.75 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Losing Customers More 0.5 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Losing Customers More 0.25 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Lost Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Lost Customers More 0.75 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Lost Customers More 0.5 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Lost Customers More 0.25 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Last Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Product Department Key`),
  UNIQUE KEY `Product Department Store Key_2` (`Product Department Store Key`,`Product Department Code`),
  KEY `code` (`Product Department Code`(16)),
  KEY `Product Department Most Recent` (`Product Department Most Recent`),
  KEY `Product Depxartment Discontinued Products` (`Product Department In Process Products`,`Product Department Historic Products`),
  KEY `Product Department Type` (`Product Department Type`),
  KEY `Product Department Name` (`Product Department Name`),
  KEY `Product Department Store Key` (`Product Department Store Key`),
  KEY `Product Department Sales Type` (`Product Department Sales Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Department History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Department History Bridge` (
  `Department Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Department Key`,`History Key`),
  KEY `Department Key` (`Department Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Dimension` (
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Product Current Key` mediumint(8) unsigned NOT NULL,
  `Product Status` enum('InProcess','Active','Suspended','Discontinuing','Discontinued') DEFAULT 'Active',
  `Product Public` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Type` enum('Normal','Fuzzy') NOT NULL DEFAULT 'Normal',
  `Product Record Type` enum('Normal','Historic') NOT NULL DEFAULT 'Normal',
  `Product Stage` enum('In Process','New','Normal') NOT NULL,
  `Product Sales Type` enum('Public Sale','Private Sale','Not for Sale') NOT NULL,
  `Product Availability Type` enum('Normal','Discontinued') NOT NULL,
  `Product Main Type` enum('Historic','Discontinued','Private','NoSale','Sale') NOT NULL,
  `Product Store Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Product Locale` enum('en_GB','de_DE','fr_FR','es_ES','pl_PL','pt_Pt','it_IT') NOT NULL DEFAULT 'en_GB',
  `Product Currency` varchar(3) NOT NULL DEFAULT 'GBP',
  `Product Web Configuration` enum('Online Force Out of Stock','Online Auto','Offline','Online Force For Sale') NOT NULL DEFAULT 'Online Auto',
  `Product Web State` enum('For Sale','Out of Stock','Discontinued','Offline') NOT NULL DEFAULT 'Offline',
  `Product Number Web Pages` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Product Code File As` varchar(255) NOT NULL,
  `Product Code` varchar(30) NOT NULL,
  `Product Barcode Type` enum('none','ean8','ean13',' code11','code39','code128','codabar') NOT NULL DEFAULT 'none',
  `Product Barcode Data Source` enum('ID','Code','Other') NOT NULL DEFAULT 'ID',
  `Product Barcode Data` varchar(255) DEFAULT NULL,
  `Product Price` decimal(9,2) DEFAULT NULL,
  `Product Cost` decimal(16,4) DEFAULT NULL,
  `Product RRP` decimal(9,2) DEFAULT NULL,
  `Product Name` varchar(255) DEFAULT NULL,
  `Product Short Description` varchar(255) DEFAULT NULL,
  `Product XHTML Short Description` varchar(255) DEFAULT NULL,
  `Product Health And Safety` longtext,
  `Product UN Number` varchar(4) DEFAULT NULL,
  `Product UN Class` varchar(4) DEFAULT NULL,
  `Product Packing Group` enum('None','I','II','III') DEFAULT 'None',
  `Product Proper Shipping Name` varchar(255) DEFAULT NULL,
  `Product Hazard Indentification Number` varchar(64) DEFAULT NULL,
  `Product Main Image` varchar(255) NOT NULL DEFAULT 'art/nopic.png',
  `Product Main Image Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Special Characteristic` varchar(255) DEFAULT NULL,
  `Product Special Characteristic Component A` varchar(255) NOT NULL,
  `Product Special Characteristic Component B` varchar(255) NOT NULL,
  `Product Description` text,
  `Product Slogan` varchar(255) DEFAULT NULL,
  `Product Origin Country Code` varchar(255) DEFAULT NULL,
  `Product Family Category Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family Code` varchar(32) DEFAULT NULL,
  `Product Family Name` varchar(255) DEFAULT NULL,
  `Product Main Department Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Main Department Code` varchar(255) DEFAULT NULL,
  `Product Main Department Name` varchar(255) DEFAULT NULL,
  `Product Department Degeneration` smallint(5) unsigned DEFAULT NULL,
  `Product Package Type` enum('Bottle','Bag','Box','None','Other') NOT NULL DEFAULT 'Box',
  `Product Package Weight` float DEFAULT NULL,
  `Product Package Weight Display` float DEFAULT NULL,
  `Product Package Weight Display Units` enum('Kg','g','oz','lb') NOT NULL DEFAULT 'Kg',
  `Product Unit Weight` float DEFAULT NULL,
  `Product Unit Weight Display` float DEFAULT NULL,
  `Product Unit Weight Display Units` enum('Kg','g','oz','lb') NOT NULL DEFAULT 'Kg',
  `Product Unit Dimensions Type` enum('Rectangular','Cilinder','Sphere','String','Sheet') NOT NULL DEFAULT 'Rectangular',
  `Product Unit Dimensions Display Units` enum('mm','cm','m','in','yd','ft') NOT NULL DEFAULT 'cm',
  `Product Unit Dimensions Width` float DEFAULT NULL,
  `Product Unit Dimensions Depth` float DEFAULT NULL,
  `Product Unit Dimensions Length` float DEFAULT NULL,
  `Product Unit Dimensions Diameter` float DEFAULT NULL,
  `Product Unit Dimensions Width Display` float DEFAULT NULL,
  `Product Unit Dimensions Depth Display` float DEFAULT NULL,
  `Product Unit Dimensions Length Display` float DEFAULT NULL,
  `Product Unit Dimensions Diameter Display` float DEFAULT NULL,
  `Product Unit Dimensions Volume` float DEFAULT NULL,
  `Product Unit XHTML Dimensions` varchar(255) DEFAULT NULL,
  `Product Unit Dimensions` varchar(255) DEFAULT NULL,
  `Product Unit Materials` text,
  `Product Unit XHTML Materials` text,
  `Product Package Dimensions Type` enum('Rectangular','Cilinder','Sphere') NOT NULL DEFAULT 'Rectangular',
  `Product Package Dimensions Display Units` enum('mm','cm','m','in','yd','ft') NOT NULL DEFAULT 'cm',
  `Product Package Dimensions Width` float DEFAULT NULL,
  `Product Package Dimensions Depth` float DEFAULT NULL,
  `Product Package Dimensions Length` float DEFAULT NULL,
  `Product Package Dimensions Diameter` float DEFAULT NULL,
  `Product Package Dimensions Width Display` float DEFAULT NULL,
  `Product Package Dimensions Depth Display` float DEFAULT NULL,
  `Product Package Dimensions Length Display` float DEFAULT NULL,
  `Product Package Dimensions Diameter Display` float DEFAULT NULL,
  `Product Package Dimensions Volume` float DEFAULT NULL,
  `Product Package XHTML Dimensions` varchar(255) DEFAULT NULL,
  `Product Parts Weight` float DEFAULT NULL,
  `Product XHTML Package Weight` varchar(16) DEFAULT NULL,
  `Product XHTML Unit Weight` varchar(16) DEFAULT NULL,
  `Product XHTML Package Dimensions` varchar(128) DEFAULT NULL,
  `Product XHTML Unit Dimensions` varchar(128) DEFAULT NULL,
  `Product Tariff Code` varchar(64) DEFAULT NULL,
  `Product Tariff Code Valid` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Product Duty Rate` varchar(255) DEFAULT NULL,
  `Product Units Per Case` smallint(5) unsigned DEFAULT NULL,
  `Product Unit Type` varchar(64) DEFAULT 'Piece',
  `Product Unit Container` enum('Unknown','Bottle','Box','None','Bag','Other') DEFAULT 'Unknown',
  `Product Info Sheet Attachment Key` mediumint(9) DEFAULT NULL,
  `Product MSDS Attachment Key` mediumint(9) DEFAULT NULL,
  `Product Availability State` enum('Excess','Normal','Low','VeryLow','OutofStock','Error') NOT NULL DEFAULT 'Normal',
  `Product Availability` float DEFAULT NULL,
  `Product Available Days Forecast` float DEFAULT NULL,
  `Product XHTML Available Forecast` varchar(1024) DEFAULT NULL,
  `Product Next Day Availability` float DEFAULT NULL,
  `Product Stock Value` decimal(12,2) DEFAULT NULL,
  `Product Next Supplier Shipment` datetime DEFAULT NULL,
  `Product XHTML Next Supplier Shipment` text NOT NULL,
  `Product Number of Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product XHTML Parts` varchar(1024) DEFAULT NULL,
  `Product XHTML Supplied By` varchar(1024) DEFAULT NULL,
  `Product Main Picking Location Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Main Picking Location` varchar(255) DEFAULT NULL,
  `Product Main Picking Location Stock` float DEFAULT NULL,
  `Product XHTML Picking` varchar(255) DEFAULT NULL,
  `Product Valid From` datetime DEFAULT NULL,
  `Product Valid To` datetime DEFAULT NULL,
  `Product Number Days on Sale` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Number Days with Sales` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Number Days Available` float NOT NULL DEFAULT '0',
  `Product Avg Day Sales` float NOT NULL DEFAULT '0',
  `Product Avg with Sale Day Sales` float NOT NULL DEFAULT '0',
  `Product STD with Sale Day Sales` float NOT NULL DEFAULT '0',
  `Product Max Day Sales` float NOT NULL DEFAULT '0',
  `Product Sticky Note` text,
  `Product Part Ratio` float NOT NULL DEFAULT '0',
  `Product Part Units Ratio` float DEFAULT NULL,
  `Product Use Part Tariff Data` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Use Part Properties` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Use Part Units Properties` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Use Part H and S` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Use Part Pictures` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Manufacure Metadata` text,
  `Product Manufacture Type Metadata` varchar(255) DEFAULT NULL,
  `Product Last Updated` datetime DEFAULT NULL,
  `Product For Sale Since Date` datetime DEFAULT NULL,
  `Product First Sold Date` datetime DEFAULT NULL,
  `Product Last Sold Date` datetime DEFAULT NULL,
  `Product Days On Sale` float DEFAULT NULL,
  `Product GMROI` float DEFAULT NULL,
  `Product Editing Price` decimal(9,2) DEFAULT NULL,
  `Product Editing RRP` decimal(9,2) DEFAULT NULL,
  `Product Editing Name` varchar(1024) DEFAULT NULL,
  `Product Editing Special Characteristic` varchar(255) DEFAULT NULL,
  `Product Editing Units Per Case` float DEFAULT NULL,
  `Product Editing Unit Type` enum('Piece','Grams','Liters','Meters','Other') DEFAULT NULL,
  `Product Part Metadata` varchar(255) DEFAULT NULL,
  `Product Unit Label` varchar(255) NOT NULL DEFAULT 'Unit',
  `Product Label in Family` varchar(255) DEFAULT NULL,
  `Product Materials` text,
  PRIMARY KEY (`Product ID`,`Product Store Key`),
  KEY `Product Alphanumeric Code` (`Product Code File As`(16)),
  KEY `date` (`Product Valid From`),
  KEY `date2` (`Product Valid To`),
  KEY `Product Department Key` (`Product Main Department Key`),
  KEY `family` (`Product Family Key`),
  KEY `Product Availability State` (`Product Availability State`),
  KEY `Product Price` (`Product Price`),
  KEY `Product Units Per Case` (`Product Units Per Case`),
  KEY `Product Unit Type` (`Product Unit Type`),
  KEY `Product Web State` (`Product Web Configuration`),
  KEY `code` (`Product Code`),
  KEY `Product Store Key` (`Product Store Key`),
  KEY `Product Type` (`Product Type`),
  KEY `Product Current Key` (`Product Current Key`),
  KEY `Product Availability Type` (`Product Availability Type`),
  KEY `Product Stage` (`Product Stage`),
  KEY `Product Web State_2` (`Product Web State`),
  KEY `Product Main Type` (`Product Main Type`),
  KEY `Product Number of Parts` (`Product Number of Parts`),
  KEY `Product Name` (`Product Name`(64)),
  KEY `Product Status` (`Product Status`),
  KEY `Product Public` (`Product Public`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family Category Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family Category Dimension` (
  `Product Family Category Key` mediumint(8) unsigned NOT NULL,
  `Product Family Category Store Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Product Family Category XHTML Description` text NOT NULL,
  `Product Family Category Valid From` datetime DEFAULT NULL,
  `Product Family Category Valid To` datetime DEFAULT NULL,
  `Product Family Category Departments` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Families` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category For Public Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category For Private Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category In Process Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Not For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Unknown Sales State Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Unknown Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category Total Invoiced Discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category Total Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Family Category Total Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Family Category Total Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Family Category Total Days On Sale` float NOT NULL DEFAULT '0',
  `Product Family Category Total Days Available` float NOT NULL DEFAULT '0',
  `Product Family Category Total Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Total Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Total Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Year Acc Invoiced Discount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category 1 Year Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Year Acc Quantity Ordered` float DEFAULT '0',
  `Product Family Category 1 Year Acc Quantity Invoiced` float DEFAULT '0',
  `Product Family Category 1 Year Acc Quantity Delivered` float DEFAULT '0',
  `Product Family Category 1 Year Acc Days On Sale` float DEFAULT '0',
  `Product Family Category 1 Year Acc Days Available` float DEFAULT '0',
  `Product Family Category 1 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Quarter Acc Invoiced Discount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category 1 Quarter Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Quarter Acc Quantity Ordered` float DEFAULT '0',
  `Product Family Category 1 Quarter Acc Quantity Invoiced` float DEFAULT '0',
  `Product Family Category 1 Quarter Acc Quantity Delivered` float DEFAULT '0',
  `Product Family Category 1 Quarter Acc Days On Sale` float DEFAULT '0',
  `Product Family Category 1 Quarter Acc Days Available` float DEFAULT '0',
  `Product Family Category 1 Quarter Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Quarter Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Quarter Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Month Acc Invoiced Discount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category 1 Month Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Month Acc Quantity Ordered` float DEFAULT '0',
  `Product Family Category 1 Month Acc Quantity Invoiced` float DEFAULT '0',
  `Product Family Category 1 Month Acc Quantity Delivered` float DEFAULT '0',
  `Product Family Category 1 Month Acc Days On Sale` float DEFAULT '0',
  `Product Family Category 1 Month Acc Days Available` float DEFAULT '0',
  `Product Family Category 1 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Week Acc Invoiced Discount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category 1 Week Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Family Category 1 Week Acc Quantity Ordered` float DEFAULT '0',
  `Product Family Category 1 Week Acc Quantity Invoiced` float DEFAULT '0',
  `Product Family Category 1 Week Acc Quantity Delivered` float DEFAULT '0',
  `Product Family Category 1 Week Acc Days On Sale` float DEFAULT '0',
  `Product Family Category 1 Week Acc Days Available` float DEFAULT '0',
  `Product Family Category 1 Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Week Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category 1 Week Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Category Stock Value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC Total Invoiced Discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Year Acc Invoiced Discount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC 1 Year Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Quarter Acc Invoiced Discount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC 1 Quarter Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Month Acc Invoiced Discount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC 1 Month Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Week Acc Invoiced Discount` decimal(12,2) DEFAULT '0.00',
  `Product Family Category DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Category DC 1 Week Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Product Family Category Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  PRIMARY KEY (`Product Family Category Key`,`Product Family Category Store Key`),
  KEY `Product Family Category Store Key` (`Product Family Category Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family Category History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family Category History Bridge` (
  `Store Key` smallint(5) unsigned NOT NULL,
  `Category Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Type` enum('Changes','Assign') NOT NULL,
  UNIQUE KEY `Store Key` (`Store Key`,`Category Key`,`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family Data Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family Data Dimension` (
  `Product Family Key` mediumint(8) unsigned NOT NULL,
  `Product Family Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Total Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Total Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Total Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Total Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Total Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Total Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Total Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Total Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Total Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Total Acc Days On Sale` float DEFAULT NULL,
  `Product Family Total Acc Days Available` float DEFAULT NULL,
  `Product Family 3 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 3 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family 3 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family 3 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family 3 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family 3 Year Acc Days On Sale` float DEFAULT NULL,
  `Product Family 3 Year Acc Days Available` float DEFAULT NULL,
  `Product Family 3 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 3 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 3 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family 1 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family 1 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family 1 Year Acc Days On Sale` float DEFAULT NULL,
  `Product Family 1 Year Acc Days Available` float DEFAULT NULL,
  `Product Family 1 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Year To Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Year To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Year To Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Year To Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Year To Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Year To Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Year To Day Acc Days On Sale` float DEFAULT NULL,
  `Product Family Year To Day Acc Days Available` float DEFAULT NULL,
  `Product Family Year To Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Year To Day Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Year To Day Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Month To Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Month To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Month To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Month To Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Month To Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Month To Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Month To Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Month To Day Acc Days On Sale` float DEFAULT NULL,
  `Product Family Month To Day Acc Days Available` float DEFAULT NULL,
  `Product Family Month To Day Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Month To Day Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Month To Day Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Week To Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Week To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Week To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Week To Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Week To Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Week To Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Week To Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Week To Day Acc Days On Sale` float DEFAULT NULL,
  `Product Family Week To Day Acc Days Available` float DEFAULT NULL,
  `Product Family Week To Day Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Week To Day Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Week To Day Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Today Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Today Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Today Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Today Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Today Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Today Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Today Acc Days On Sale` float DEFAULT NULL,
  `Product Family Today Acc Days Available` float DEFAULT NULL,
  `Product Family Today Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Today Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Today Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Last Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Last Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Last Week Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Last Week Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Last Week Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Last Week Acc Days On Sale` float DEFAULT NULL,
  `Product Family Last Week Acc Days Available` float DEFAULT NULL,
  `Product Family Last Week Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Last Week Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Last Week Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Last Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Last Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Last Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Last Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Last Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Last Month Acc Days On Sale` float DEFAULT NULL,
  `Product Family Last Month Acc Days Available` float DEFAULT NULL,
  `Product Family Last Month Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Last Month Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Last Month Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Yesterday Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Yesterday Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Yesterday Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Yesterday Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Yesterday Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Yesterday Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Yesterday Acc Days On Sale` float DEFAULT NULL,
  `Product Family Yesterday Acc Days Available` float DEFAULT NULL,
  `Product Family Yesterday Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Yesterday Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Yesterday Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family 6 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 6 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 6 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family 6 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family 6 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family 6 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family 6 Month Acc Days On Sale` float DEFAULT NULL,
  `Product Family 6 Month Acc Days Available` float DEFAULT NULL,
  `Product Family 6 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 6 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 6 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Quarter Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family 1 Quarter Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family 1 Quarter Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family 1 Quarter Acc Days On Sale` float DEFAULT NULL,
  `Product Family 1 Quarter Acc Days Available` float DEFAULT NULL,
  `Product Family 1 Quarter Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Quarter Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Quarter Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 3 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 3 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 3 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family 3 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family 3 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family 3 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family 3 Month Acc Days On Sale` float DEFAULT NULL,
  `Product Family 3 Month Acc Days Available` float DEFAULT NULL,
  `Product Family 3 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 3 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 3 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family 1 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family 1 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family 1 Month Acc Days On Sale` float DEFAULT NULL,
  `Product Family 1 Month Acc Days Available` float DEFAULT NULL,
  `Product Family 1 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 10 Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 10 Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 10 Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family 10 Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family 10 Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family 10 Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family 10 Day Acc Days On Sale` float DEFAULT NULL,
  `Product Family 10 Day Acc Days Available` float DEFAULT NULL,
  `Product Family 10 Day Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 10 Day Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 10 Day Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family 1 Week Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family 1 Week Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family 1 Week Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family 1 Week Acc Days On Sale` float DEFAULT NULL,
  `Product Family 1 Week Acc Days Available` float DEFAULT NULL,
  `Product Family 1 Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Week Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family 1 Week Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Total Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Year Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Quarter Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Month Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Week Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family Total Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Year Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Quarter Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Month Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Week Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Last Week Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Yesterday Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Week To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Today Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Month To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Year To Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family 3 Year Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family 1 Year Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family 6 Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family 1 Quarter Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family 1 Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family 10 Day Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family 1 Week Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Product Family Last Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Yesterday Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Week To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Today Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Month To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Year To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 6 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Quarter Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 10 Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Yesterday Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Week To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Today Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Month To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Year To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 6 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Quarter Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 10 Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Last Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family Last Week Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family Yesterday Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family Week To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family Today Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family Month To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family Year To Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family 3 Year Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family 1 Year Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family 6 Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family 1 Quarter Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family 1 Month Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family 10 Day Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  `Product Family 1 Week Acc 1YB Invoiced Delta` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`Product Family Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family Default Currency`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family Default Currency` (
  `Product Family Key` mediumint(8) unsigned NOT NULL,
  `Product Family DC Total Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Total Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family DC 3 Year Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 3 Year Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 6 Month Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 6 Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 10 Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 10 Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Year To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Year To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Month To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Month To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Month To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Week To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Week To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Week To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Today Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Today Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Yesterday Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Yesterday Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Last Week Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Last Week Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Last Month Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Last Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Product Family Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family Department Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family Department Bridge` (
  `Product Family Key` mediumint(8) unsigned NOT NULL,
  `Product Department Key` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `Product Family Key` (`Product Family Key`,`Product Department Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family Dimension` (
  `Product Family Key` mediumint(8) unsigned NOT NULL,
  `Product Family Stealth` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Product Family Code` varchar(255) DEFAULT NULL,
  `Product Family Name` varchar(255) DEFAULT NULL,
  `Product Family Description` text,
  `Product Family Slogan` varchar(255) DEFAULT NULL,
  `Product Family Marketing Description` varchar(1024) DEFAULT NULL,
  `Product Family Special Characteristic` varchar(255) NOT NULL,
  `Product Family Main Image` varchar(255) NOT NULL DEFAULT 'art/nopic.png',
  `Product Family Main Image Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Main Department Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family Main Department Code` varchar(255) DEFAULT NULL,
  `Product Family Main Department Name` varchar(255) DEFAULT NULL,
  `Product Family Record Type` enum('Normal','Discontinued','NoSale','Private') NOT NULL DEFAULT 'Normal',
  `Product Family Sales Type` enum('Public Sale','Private Sale Only','Not for Sale','Unknown','No Applicable') NOT NULL DEFAULT 'Public Sale',
  `Product Family Availability` enum('Normal','Some Out of Stock','All Out of Stock','No Applicable') NOT NULL DEFAULT 'No Applicable',
  `Product Family Web Products` enum('With Products For Sale','Empty') NOT NULL DEFAULT 'With Products For Sale',
  `Product Family From Price` float unsigned NOT NULL DEFAULT '0',
  `Product Family Product Price Multiplicity` smallint(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `Product Family Valid From` datetime DEFAULT NULL,
  `Product Family Valid To` datetime DEFAULT NULL,
  `Product Family Number Days on Sale` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Number Days with Sales` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Number Days Available` float NOT NULL DEFAULT '0',
  `Product Family Avg Day Sales` float NOT NULL DEFAULT '0',
  `Product Family Avg with Sale Day Sales` float NOT NULL DEFAULT '0',
  `Product Family STD with Sale Day Sales` float NOT NULL DEFAULT '0',
  `Product Family Max Day Sales` float NOT NULL DEFAULT '0',
  `Product Family Sticky Note` text,
  `Product Family Most Recent` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Family Most Recent Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family Page Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family For Public Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family For Private Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family In Process Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Not For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Historic Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Unknown Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Active Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Active Customers More 0.75 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Active Customers More 0.5 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Active Customers More 0.25 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Losing Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Losing Customers More 0.75 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Losing Customers More 0.5 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Losing Customers More 0.25 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Lost Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Lost Customers More 0.75 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Lost Customers More 0.5 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Lost Customers More 0.25 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Stock Value` decimal(14,2) DEFAULT NULL,
  `Product Family Store Key` smallint(5) unsigned NOT NULL,
  `Product Family Store Code` varchar(32) NOT NULL,
  `Product Family Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Product Family Last Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Product Family Key`),
  UNIQUE KEY `Product Family Code` (`Product Family Code`,`Product Family Store Key`),
  KEY `code` (`Product Family Code`(16)),
  KEY `Product Family Most Recent` (`Product Family Most Recent`),
  KEY `Product Family Store Key` (`Product Family Store Key`),
  KEY `Product Family Main Department Key` (`Product Family Main Department Key`),
  KEY `Product Family Sales Type` (`Product Family Sales Type`),
  KEY `Product Family Record Type` (`Product Family Record Type`),
  KEY `Product Family Product Price Multiplicity` (`Product Family Product Price Multiplicity`),
  KEY `Product Family Stealth` (`Product Family Stealth`),
  KEY `Product Family Name` (`Product Family Name`(128)),
  KEY `Product Family Special Characteristic` (`Product Family Special Characteristic`(128)),
  KEY `Product Family Web Products` (`Product Family Web Products`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family History Bridge` (
  `Family Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Family Key`,`History Key`),
  KEY `Family Key` (`Family Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family Sales Correlation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family Sales Correlation` (
  `Family A Key` mediumint(8) unsigned NOT NULL,
  `Family B Key` mediumint(8) unsigned NOT NULL,
  `Correlation` float NOT NULL,
  `Samples` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Family A Key`,`Family B Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Family Semantic Correlation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Family Semantic Correlation` (
  `Family A Key` mediumint(8) unsigned NOT NULL,
  `Family B Key` mediumint(8) unsigned NOT NULL,
  `Weight` float NOT NULL,
  PRIMARY KEY (`Family A Key`,`Family B Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product History Bridge` (
  `Product ID` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Product ID`,`History Key`),
  KEY `Product ID` (`Product ID`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product History Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product History Dimension` (
  `Product Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Product History Code` varchar(30) NOT NULL,
  `Product History Units Per Case` smallint(5) unsigned NOT NULL,
  `Product History Price` decimal(9,2) DEFAULT NULL,
  `Product History Name` varchar(1024) DEFAULT NULL,
  `Product History Valid From` datetime DEFAULT NULL,
  `Product History Valid To` datetime DEFAULT NULL,
  `Product History Short Description` varchar(255) DEFAULT NULL,
  `Product History XHTML Short Description` varchar(255) DEFAULT NULL,
  `Product History Special Characteristic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Product Key`),
  KEY `Product ID` (`Product ID`),
  KEY `Product History Valid From` (`Product History Valid From`),
  KEY `Product History Valid To` (`Product History Valid To`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product ID Default Currency`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product ID Default Currency` (
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Product ID DC Total Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Total Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Quarter Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 3 Year Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 3 Year Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 6 Month Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 6 Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 10 Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 10 Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Year To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Year To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Month To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Month To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Month To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Week To Day Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Week To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Week To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Today Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Today Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Yesterday Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Yesterday Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Last Week Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Last Week Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Last Month Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Last Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Product ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Image Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Image Bridge` (
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Image Key` mediumint(8) unsigned NOT NULL,
  `Is Principal` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Product ID`,`Image Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Import Metadata`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Import Metadata` (
  `Product Import Metadata Key` mediumint(8) unsigned NOT NULL,
  `Metadata` varchar(15) CHARACTER SET latin1 NOT NULL,
  `Import Date` datetime NOT NULL,
  PRIMARY KEY (`Product Import Metadata Key`),
  KEY `Metadata` (`Metadata`),
  KEY `Import Date` (`Import Date`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Material Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Material Bridge` (
  `Product Material Key` int(10) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Material Key` mediumint(8) unsigned NOT NULL,
  `Ratio` float DEFAULT NULL,
  `May Contain` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Product Material Key`),
  UNIQUE KEY `Product ID` (`Product ID`,`Material Key`),
  UNIQUE KEY `Product ID_2` (`Product ID`,`Material Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Page Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Page Bridge` (
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Type` enum('List','Button') NOT NULL,
  PRIMARY KEY (`Product ID`,`Page Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Part Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Part Bridge` (
  `Product Part Key` int(10) unsigned NOT NULL,
  `Product Part Product ID` mediumint(8) unsigned NOT NULL,
  `Product Part Part SKU` mediumint(8) unsigned NOT NULL,
  `Product Part Ratio` decimal(15,5) NOT NULL DEFAULT '1.00000',
  `Product Part Note` varchar(255) DEFAULT NULL,
  `Product Part Linked Fields` text NOT NULL,
  PRIMARY KEY (`Product Part Key`),
  UNIQUE KEY `Product Part Product ID_2` (`Product Part Product ID`,`Product Part Part SKU`),
  KEY `Product Part Product ID` (`Product Part Product ID`),
  KEY `Product Part Part SKU` (`Product Part Part SKU`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Part Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Part Dimension` (
  `Product Part Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Part Type` enum('Simple','Mix') NOT NULL DEFAULT 'Simple',
  `Product Part Metadata` varchar(1024) DEFAULT NULL,
  `Product Part Valid From` datetime DEFAULT NULL,
  `Product Part Valid To` datetime DEFAULT NULL,
  `Product Part Most Recent` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Product Part Key`),
  KEY `Product Part Valid From` (`Product Part Valid From`),
  KEY `Product Part Valid To` (`Product Part Valid To`),
  KEY `Product Part Most Recent` (`Product Part Most Recent`),
  KEY `Product ID` (`Product ID`),
  KEY `Product Part Type` (`Product Part Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Part List`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Part List` (
  `Product Part List Key` int(10) unsigned NOT NULL,
  `Product Part Key` mediumint(8) unsigned NOT NULL,
  `Part SKU` mediumint(8) unsigned DEFAULT NULL,
  `Parts Per Product` decimal(12,6) DEFAULT '1.000000',
  `Product Part List Note` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`Product Part List Key`),
  KEY `Part SKU` (`Part SKU`),
  KEY `Product Part Key` (`Product Part Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Sales Correlation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Sales Correlation` (
  `Product A ID` mediumint(8) unsigned NOT NULL,
  `Product B ID` mediumint(8) unsigned NOT NULL,
  `Correlation` float NOT NULL,
  `Samples` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `Product A ID` (`Product A ID`,`Product B ID`),
  KEY `Product A ID_2` (`Product A ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Product Same Code Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product Same Code Dimension` (
  `Product Code` varchar(16) NOT NULL,
  `Product Code File As` varchar(255) NOT NULL,
  `Product Same Code Valid From` datetime DEFAULT NULL,
  `Product Same Code Valid To` datetime DEFAULT NULL,
  `Product Same Code Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Same Code Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Same Code Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Same Code Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Same Code Total Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Same Code Total Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Same Code Total Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Same Code Total Days On Sale` float NOT NULL DEFAULT '0',
  `Product Same Code Total Days Available` float NOT NULL DEFAULT '0',
  `Product Same Code Total Estimated GMROI` float DEFAULT NULL,
  `Product Same Code 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Year Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Product Same Code 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product Same Code 1 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Same Code 1 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product Same Code 1 Year Acc Days On Sale` float DEFAULT NULL,
  `Product Same Code 1 Year Acc Days Available` float DEFAULT NULL,
  `Product Same Code 1 Year Acc Estimated GMROI` float DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Product Same Code 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Quantity Ordered` float DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Quantity Delivered` float DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Days On Sale` float DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Days Available` float DEFAULT NULL,
  `Product Same Code 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Month Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Product Same Code 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product Same Code 1 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Same Code 1 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product Same Code 1 Month Acc Days On Sale` float DEFAULT NULL,
  `Product Same Code 1 Month Acc Days Available` float DEFAULT NULL,
  `Product Same Code 1 Month Acc Estimated GMROI` float DEFAULT NULL,
  `Product Same Code 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Week Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Product Same Code 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Same Code 1 Week Acc Quantity Ordered` float DEFAULT NULL,
  `Product Same Code 1 Week Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Same Code 1 Week Acc Quantity Delivered` float DEFAULT NULL,
  `Product Same Code 1 Week Acc Days On Sale` float DEFAULT NULL,
  `Product Same Code 1 Week Acc Days Available` float DEFAULT NULL,
  `Product Same Code 1 Week Acc Estimated GMROI` float DEFAULT NULL,
  `Product Same Code For Sale Since Date` datetime DEFAULT NULL,
  `Product Same Code Last Sold Date` datetime DEFAULT NULL,
  `Product Same Code Days On Sale` float DEFAULT NULL,
  `Product Same Code GMROI` float DEFAULT NULL,
  `Product Same Code Total Margin` float DEFAULT NULL,
  `Product Same Code 1 Year Acc Margin` float DEFAULT NULL,
  `Product Same Code 1 Quarter Acc Margin` float DEFAULT NULL,
  `Product Same Code 1 Month Acc Margin` float DEFAULT NULL,
  `Product Same Code 1 Week Acc Margin` float DEFAULT NULL,
  PRIMARY KEY (`Product Code`),
  KEY `Product Alphanumeric Code` (`Product Code File As`(16)),
  KEY `date` (`Product Same Code Valid From`),
  KEY `date2` (`Product Same Code Valid To`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Promotion Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Promotion Dimension` (
  `Promotion Key` mediumint(8) unsigned NOT NULL,
  `Promotion Name` varchar(255) NOT NULL,
  `Promotion Media Type` varchar(255) NOT NULL,
  `Promotion Begin Date` datetime NOT NULL,
  `Promotion End Date` datetime NOT NULL,
  PRIMARY KEY (`Promotion Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Purchase Order Agent Coda`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Purchase Order Agent Coda` (
  `Purchase Order Key` mediumint(8) unsigned NOT NULL,
  `Purchase Order Agent Key` mediumint(9) DEFAULT NULL,
  `Purchase Order Agent State` enum('Created','SupplierOrdersCreated','SupplierOrdersReceived','Checked','Dispatched') NOT NULL DEFAULT 'Created',
  `Purchase Order Agent Supplier Orders Created Date` datetime DEFAULT NULL,
  `Purchase Order Agent Supplier Orders Received Date` datetime DEFAULT NULL,
  `Purchase Order Agent Checked Date` datetime DEFAULT NULL,
  `Purchase Order Agent Last Updated Date` datetime DEFAULT NULL,
  PRIMARY KEY (`Purchase Order Key`),
  KEY `Purchase Order Agent State` (`Purchase Order Agent State`),
  KEY `Purchase Order Agent Key` (`Purchase Order Agent Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Purchase Order Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Purchase Order Deleted Dimension` (
  `Purchase Order Deleted Key` mediumint(8) unsigned NOT NULL,
  `Purchase Order Deleted Public ID` varchar(255) NOT NULL,
  `Purchase Order Deleted Date` datetime NOT NULL,
  `Purchase Order Deleted Metadata` blob,
  PRIMARY KEY (`Purchase Order Deleted Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Purchase Order Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Purchase Order Dimension` (
  `Purchase Order Key` mediumint(8) unsigned NOT NULL,
  `Purchase Order Locked` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Purchase Order Parent` enum('Supplier','Agent') NOT NULL DEFAULT 'Supplier',
  `Purchase Order Parent Key` mediumint(8) unsigned DEFAULT NULL,
  `Purchase Order Parent Code` varchar(255) DEFAULT NULL,
  `Purchase Order Parent Name` varchar(255) DEFAULT NULL,
  `Purchase Order Parent Contact Name` varchar(255) DEFAULT NULL,
  `Purchase Order Parent Email` varchar(255) DEFAULT NULL,
  `Purchase Order Parent Telephone` varchar(255) DEFAULT NULL,
  `Purchase Order Parent Address` text,
  `Purchase Order Creation Date` datetime DEFAULT NULL COMMENT 'Date when the purchase order where first placed',
  `Purchase Order Confirmed Date` datetime DEFAULT NULL,
  `Purchase Order Submitted Date` datetime DEFAULT NULL,
  `Purchase Order Inputted Date` datetime DEFAULT NULL,
  `Purchase Order Estimated Receiving Date` datetime DEFAULT NULL,
  `Purchase Order Agreed Receiving Date` datetime DEFAULT NULL,
  `Purchase Order Received Date` datetime DEFAULT NULL,
  `Purchase Order Checked Date` datetime DEFAULT NULL,
  `Purchase Order Consolidated Date` datetime DEFAULT NULL,
  `Purchase Order Cancelled Date` datetime DEFAULT NULL,
  `Purchase Order Last Updated Date` datetime DEFAULT NULL COMMENT 'Lastest Date when Adding/Modify Purchase Order Transaction or Data',
  `Purchase Order Public ID` varchar(255) DEFAULT NULL,
  `Purchase Order File As` varchar(255) DEFAULT NULL,
  `Purchase Order XHTML PO Invoices` text,
  `Purchase Order Warehouse Key` smallint(5) unsigned NOT NULL,
  `Purchase Order Main Buyer Key` mediumint(8) unsigned DEFAULT NULL,
  `Purchase Order Main Buyer Name` varchar(255) DEFAULT NULL,
  `Purchase Order Main Source Type` enum('Post','Internet','Telephone','Fax','In Person','Unknown','Email','Other') NOT NULL DEFAULT 'Unknown',
  `Purchase Order State` enum('InProcess','Submitted','Inputted','Dispatched','Received','Checked','Placed','Cancelled') NOT NULL DEFAULT 'InProcess',
  `Purchase Order Max Supplier Delivery State` enum('NA','InProcess','Dispatched','Received','Checked','Placed') NOT NULL DEFAULT 'NA',
  `Purchase Order Our Feedback` enum('Praise','None','Shortages','Breakings','Different Product','Multiple','Low Quality','Not Like','Slow Delivery','Other') NOT NULL DEFAULT 'None',
  `Purchase Order Actions Taken` enum('Refund','Credit','Replacement','Send Missing','Other','No Applicable') NOT NULL DEFAULT 'No Applicable',
  `Purchase Order Number Suppliers` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Purchase Order Number Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Purchase Order Ordered Number Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Purchase Order Number Supplier Delivery Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Purchase Order Number Placed Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Purchase Order Items Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Items Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Shipping Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Charges Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Net Credited Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Total Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Shipping Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Charges Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Tax Credited Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Total Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Total Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Total To Pay Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Main XHTML Ship From` text,
  `Purchase Order Main XHTML Ship To` text,
  `Purchase Order Supplier Message` text,
  `Purchase Order Original Lines` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Number of distinct products originally purchase ordered.',
  `Purchase Order Current Lines` smallint(6) NOT NULL DEFAULT '0',
  `Purchase Order Data MIME Type` varchar(255) DEFAULT NULL COMMENT 'Two-part identifier for file formats (RFC 2046). Ref: http://www.iana.org/assignments/media-types/',
  `Purchase Order Data` blob COMMENT 'Original purchase order, E.G.: Email body message,cnversation audio file or a scaned document.',
  `Purchase Order Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Purchase Order Currency Exchange` float NOT NULL DEFAULT '1',
  `Purchase Order Cancel Note` text,
  `Purchase Order Warehouse Code` varchar(64) DEFAULT NULL,
  `Purchase Order Warehouse Name` varchar(255) DEFAULT NULL,
  `Purchase Order Warehouse Address` text,
  `Purchase Order Warehouse Company Name` varchar(255) DEFAULT NULL,
  `Purchase Order Warehouse Company Number` varchar(255) DEFAULT NULL,
  `Purchase Order Warehouse VAT Number` varchar(255) DEFAULT NULL,
  `Purchase Order Warehouse Telephone` varchar(255) DEFAULT NULL,
  `Purchase Order Warehouse Email` varchar(255) DEFAULT NULL,
  `Purchase Order Incoterm` varchar(3) DEFAULT NULL,
  `Purchase Order Port of Export` varchar(255) DEFAULT NULL,
  `Purchase Order Port of Import` varchar(255) DEFAULT NULL,
  `Purchase Order Terms and Conditions` text,
  `Purchase Order Weight` float DEFAULT NULL,
  `Purchase Order CBM` float DEFAULT NULL,
  `Purchase Order Missing Weights` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Purchase Order Missing CBMs` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Purchase Order Metadata` text,
  PRIMARY KEY (`Purchase Order Key`),
  KEY `Purchase Order Parent` (`Purchase Order Parent`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Purchase Order History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Purchase Order History Bridge` (
  `Purchase Order Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') CHARACTER SET latin1 NOT NULL,
  UNIQUE KEY `Purchase Order Key` (`Purchase Order Key`,`History Key`),
  KEY `Type` (`Type`),
  KEY `Deletable` (`Deletable`),
  KEY `Strikethrough` (`Strikethrough`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Purchase Order Transaction Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Purchase Order Transaction Fact` (
  `Purchase Order Transaction Fact Key` mediumint(8) unsigned NOT NULL,
  `Purchase Order Key` mediumint(8) unsigned DEFAULT NULL,
  `Purchase Order Item Index` smallint(5) unsigned DEFAULT NULL,
  `User Key` mediumint(8) unsigned DEFAULT NULL,
  `Creation Date` datetime DEFAULT NULL,
  `Purchase Order Last Updated Date` datetime DEFAULT NULL,
  `Supplier Delivery Last Updated Date` datetime DEFAULT NULL,
  `Supplier Invoice Last Updated Date` datetime DEFAULT NULL,
  `Supplier Part Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Part Historic Key` mediumint(8) unsigned DEFAULT NULL,
  `Note to Supplier` text,
  `Note to Supplier Locked` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Supplier Key` mediumint(9) NOT NULL,
  `Agent Key` mediumint(8) unsigned DEFAULT NULL,
  `Purchase Order Quantity` float NOT NULL,
  `Purchase Order Tax Code` varchar(16) DEFAULT NULL,
  `Purchase Order Net Amount` decimal(16,2) NOT NULL,
  `Purchase Order Tax Amount` decimal(16,3) NOT NULL DEFAULT '0.000',
  `Purchase Order Shipping Contribution Amount` double(15,2) NOT NULL DEFAULT '0.00',
  `Purchase Order Transaction State` enum('InProcess','Submitted','Inputted','Dispatched','Received','Checked','Placed','Cancelled') NOT NULL DEFAULT 'InProcess',
  `Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Supplier Invoice Current Payment State` enum('No Applicable','Paid') NOT NULL DEFAULT 'No Applicable',
  `Supplier Delivery Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Delivery Received Location Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Supplier Delivery Quantity` float NOT NULL DEFAULT '0',
  `Supplier Delivery Checked Quantity` float DEFAULT NULL,
  `Supplier Delivery Placed Quantity` float DEFAULT NULL,
  `Supplier Delivery Net Amount` float NOT NULL DEFAULT '0',
  `Supplier Delivery Tax Amount` float NOT NULL DEFAULT '0',
  `Supplier Delivery Transaction State` enum('InProcess','Dispatched','Received','Checked','Placed','Cancelled') DEFAULT NULL,
  `Supplier Delivery Transaction Placed` enum('Yes','No','NA') DEFAULT NULL,
  `Supplier Invoice Key` mediumint(8) unsigned DEFAULT NULL,
  `Purchase Order CBM` float DEFAULT NULL COMMENT 'In m3',
  `Purchase Order Weight` float DEFAULT NULL COMMENT 'In Kg',
  `Supplier Delivery CBM` float DEFAULT NULL COMMENT 'In m3',
  `Supplier Delivery Weight` float DEFAULT NULL COMMENT 'In Kg',
  `Metadata` text,
  PRIMARY KEY (`Purchase Order Transaction Fact Key`),
  KEY `Purchase Order Key` (`Purchase Order Key`),
  KEY `User Key` (`User Key`),
  KEY `Purchase Order Item Index` (`Purchase Order Item Index`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Right Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Right Dimension` (
  `Right Key` mediumint(8) unsigned NOT NULL,
  `Right Type` enum('View','Edit','Delete','Create') NOT NULL,
  `Right Name` varchar(32) NOT NULL,
  `Right Access` enum('All','Some','Except','None') NOT NULL DEFAULT 'None',
  `Right Access Keys` text NOT NULL,
  UNIQUE KEY `define_name_i_idx` (`Right Type`,`Right Name`),
  UNIQUE KEY `right_id_idx` (`Right Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Search Full Text Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Search Full Text Dimension` (
  `Store Key` smallint(5) unsigned NOT NULL,
  `Subject` enum('Family','Customer','Product','Part','Order','Page') NOT NULL,
  `Subject Key` mediumint(8) unsigned NOT NULL,
  `First Search Full Text` text NOT NULL,
  `Second Search Full Text` text NOT NULL,
  `Search Result Name` varchar(255) NOT NULL,
  `Search Result Description` text NOT NULL,
  `Search Result Image` varchar(255) NOT NULL,
  UNIQUE KEY `Store Key` (`Store Key`,`Subject`,`Subject Key`),
  KEY `Store Key_2` (`Store Key`),
  KEY `Subject` (`Subject`),
  KEY `Subject Key` (`Subject Key`),
  FULLTEXT KEY `Full Text` (`First Search Full Text`),
  FULLTEXT KEY `Second Search Full Text` (`Second Search Full Text`),
  FULLTEXT KEY `First Search Full Text` (`First Search Full Text`,`Second Search Full Text`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Session Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Session Dimension` (
  `Session ID` varchar(32) NOT NULL,
  `HTTP User Agent` varchar(32) NOT NULL,
  `Session Data` longblob NOT NULL,
  `Session Expire` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `Session ID` (`Session ID`),
  KEY `Session Expire` (`Session Expire`),
  KEY `HTTP User Agent` (`HTTP User Agent`(8))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Shelf Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Shelf Dimension` (
  `Shelf Key` mediumint(8) unsigned NOT NULL,
  `Shelf Warehouse Key` mediumint(8) unsigned NOT NULL,
  `Shelf Area Key` smallint(6) DEFAULT NULL,
  `Shelf Code` varchar(16) NOT NULL,
  `Shelf Type Key` smallint(5) unsigned NOT NULL,
  `Shelf Number Rows` smallint(5) unsigned NOT NULL,
  `Shelf Number Columns` smallint(5) unsigned NOT NULL,
  `Shelf Number Locations` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Shelf Distinct Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Shelf Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Shelf Location Type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Shelf Location Type` (
  `Shelf Location Type Key` mediumint(8) unsigned NOT NULL,
  `Shelf Type Key` smallint(5) unsigned NOT NULL,
  `Shelf Location Type Name` varchar(255) NOT NULL,
  `Shelf Location Type Max Weight` float NOT NULL,
  `Shelf Location Type Max Volume` float NOT NULL,
  PRIMARY KEY (`Shelf Location Type Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Shelf Type Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Shelf Type Dimension` (
  `Shelf Type Key` smallint(6) NOT NULL,
  `Shelf Type Name` varchar(255) NOT NULL,
  `Shelf Type Description` text NOT NULL,
  `Shelf Type Type` enum('Pallet','Shelf','Drawer','Other') NOT NULL DEFAULT 'Other',
  `Shelf Type Rows` smallint(6) NOT NULL DEFAULT '1',
  `Shelf Type Columns` smallint(6) NOT NULL DEFAULT '1',
  `Shelf Type Location Height` float DEFAULT NULL,
  `Shelf Type Location Length` float DEFAULT NULL,
  `Shelf Type Location Deep` float DEFAULT NULL,
  `Shelf Type Location Max Weight` float DEFAULT NULL,
  `Shelf Type Location Max Volume` float DEFAULT NULL,
  PRIMARY KEY (`Shelf Type Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ship To Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ship To Dimension` (
  `Ship To Key` mediumint(8) unsigned NOT NULL,
  `Ship To Contact Name` varchar(255) DEFAULT NULL,
  `Ship To Company Name` varchar(255) DEFAULT NULL,
  `Ship To Line 1` varchar(255) NOT NULL,
  `Ship To Line 2` varchar(255) NOT NULL,
  `Ship To Line 3` varchar(255) NOT NULL,
  `Ship To Town` varchar(255) NOT NULL,
  `Ship To Line 4` varchar(255) NOT NULL,
  `Ship To Postal Code` varchar(20) NOT NULL,
  `Ship To Country Name` varchar(80) NOT NULL,
  `Ship To XHTML Address` text,
  `Ship To Telephone` varchar(255) DEFAULT NULL,
  `Ship To Email` varchar(255) DEFAULT NULL,
  `Ship To Country Key` mediumint(8) unsigned DEFAULT NULL,
  `Ship To Country Code` varchar(3) NOT NULL DEFAULT 'UNK',
  `Ship To Country 2 Alpha Code` varchar(3) NOT NULL DEFAULT 'XX',
  PRIMARY KEY (`Ship To Key`),
  KEY `Ship To Country Key` (`Ship To Country Key`),
  KEY `Ship To Country Code` (`Ship To Country Code`),
  KEY `Ship To Country 2 Alpha Code` (`Ship To Country 2 Alpha Code`),
  KEY `Ship To Postal Code` (`Ship To Postal Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ship to Contact Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ship to Contact Dimension` (
  `Ship to Contact Key` mediumint(8) unsigned NOT NULL,
  `Ship to Contact Customer Key` mediumint(8) unsigned NOT NULL,
  `Ship to Contact Address Key` mediumint(8) unsigned NOT NULL,
  `Ship to Contact Name` varchar(255) NOT NULL,
  `Ship to Contact Company` varchar(255) NOT NULL,
  `Ship to Contact Telephone` varchar(255) NOT NULL,
  `Ship to Contact Email` varchar(360) NOT NULL,
  PRIMARY KEY (`Ship to Contact Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Shipment Invoice Transaction Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Shipment Invoice Transaction Fact` (
  `Shipment Invoice Line Item Transaction Key` mediumint(8) unsigned NOT NULL,
  `Invoice Date Key` datetime NOT NULL,
  `Requested Ship Date Key` datetime NOT NULL,
  `Actual Ship Date Key` datetime NOT NULL,
  `Part Key` mediumint(9) NOT NULL,
  `Customer Ship to Key` mediumint(9) NOT NULL,
  `Deal Key` mediumint(9) NOT NULL,
  `Ship from Key` mediumint(9) NOT NULL,
  `Shipper Key` mediumint(9) NOT NULL,
  `Customer Satisfaction Key` mediumint(9) NOT NULL,
  `Invoice Number` varchar(255) NOT NULL,
  `Order Number` varchar(255) NOT NULL,
  `Quantity Shipped` float NOT NULL COMMENT 'Number of cases of the particular line-item.',
  `Extended Gross Invoice Amount` decimal(9,2) NOT NULL COMMENT 'Extended list price, quantity shipped multipliead by the list unit price.',
  `Extended Allowance Amount` decimal(9,2) NOT NULL COMMENT 'Amount substracted from the invoive-lne gross amount for deal-related allowances.',
  `Extended  Discount Amount` decimal(9,2) NOT NULL COMMENT 'Amount substracted from the invoice gross amount for volume or payment-term discounts.',
  `Extended Net Invoice Amount` decimal(9,2) NOT NULL,
  `Extended Fixed Manufacuring Cost` decimal(9,2) NOT NULL,
  `Extedded Varable Manufacturing Cost` decimal(9,2) NOT NULL,
  `Extended Supplier Cost` decimal(9,2) NOT NULL,
  `Extended Storage Cost` decimal(9,2) NOT NULL,
  `Extended Distribution Cost` decimal(9,2) NOT NULL,
  `Contribution Amount` decimal(9,2) NOT NULL,
  `Shipment Line Item On-Time Count` tinyint(1) NOT NULL DEFAULT '0',
  `Shipment Line Item Complete Count` tinyint(1) NOT NULL DEFAULT '0',
  `Shipment Line Item Damage Free Count` tinyint(1) NOT NULL DEFAULT '0',
  `Units Factor` float NOT NULL,
  PRIMARY KEY (`Shipment Invoice Line Item Transaction Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Shipper Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Shipper Dimension` (
  `Shipper Key` mediumint(8) unsigned NOT NULL,
  `Shipper Code` varchar(16) NOT NULL,
  `Shipper Name` varchar(255) NOT NULL,
  `Shipper Telephone` varchar(64) DEFAULT NULL,
  `Shipper Website` varchar(128) DEFAULT NULL,
  `Shipper Tracking URL` varchar(255) DEFAULT NULL,
  `Shipper Fiscal Name` varchar(255) DEFAULT NULL,
  `Shipper Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Shipper Key`),
  KEY `Shipper Active` (`Shipper Active`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Shipping Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Shipping Dimension` (
  `Shipping Key` mediumint(8) unsigned NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Shipping Type` enum('Normal','Next Day','Saturday Delivery') DEFAULT NULL,
  `Shipping Destination Type` enum('Default','Country','Country Primary Division','World Region','Country Post Code') DEFAULT NULL,
  `Shipping Destination Code` varchar(255) DEFAULT NULL,
  `Shipping Destination Metadata` varchar(64) DEFAULT NULL,
  `Shipping Price Method` enum('Parent','Flat','Step Weight','Step Volume','Step Order Items Gross Amount','On Request','Step Order Items Net Amount') NOT NULL DEFAULT 'Flat',
  `Shipping Secondary Destination Check` enum('None','Post Code') NOT NULL DEFAULT 'None',
  `Shipping Metadata` varchar(4096) DEFAULT NULL,
  `Shipping Begin Date` datetime DEFAULT NULL,
  `Shipping Expiration Date` datetime DEFAULT NULL,
  PRIMARY KEY (`Shipping Key`),
  KEY `y` (`Shipping Destination Code`),
  KEY `z` (`Shipping Destination Type`),
  KEY `Shipping Secondary Destination Check` (`Shipping Secondary Destination Check`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Shipping Notices Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Shipping Notices Fact` (
  `Shipping Notices Key` mediumint(8) unsigned NOT NULL,
  `Shipping Notification Date` datetime NOT NULL,
  `Ship Date` datetime NOT NULL,
  `Requested Date` datetime NOT NULL,
  `Part Key` mediumint(9) NOT NULL,
  `Supplier Key` mediumint(9) NOT NULL,
  `Contract Terms Key` mediumint(9) NOT NULL,
  `Requested By Key` smallint(6) NOT NULL,
  `Purchase Agent Key` smallint(6) NOT NULL,
  `Contract Number` varchar(255) NOT NULL,
  `Purchase Requisition Number` varchar(255) NOT NULL,
  `Purchase Order Number` varchar(255) NOT NULL,
  `Shipping Notification Number` varchar(255) NOT NULL,
  `Shipping Quantity` float NOT NULL,
  `Shipping Quantity Type` enum('Piece','Case','Carton','Pallet','Kilograms','Liters','Meters','Metric Tons') NOT NULL,
  `Case Factor` float NOT NULL,
  `Unit Factor` float NOT NULL,
  PRIMARY KEY (`Shipping Notices Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Similar Families`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Similar Families` (
  `Family Key A` mediumint(9) NOT NULL,
  `Family Key B` mediumint(9) NOT NULL,
  PRIMARY KEY (`Family Key A`,`Family Key B`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Similar Products`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Similar Products` (
  `Product ID A` mediumint(8) unsigned NOT NULL,
  `Product ID B` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Product ID A`,`Product ID B`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Site Content Word Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Site Content Word Dimension` (
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Word` varchar(24) NOT NULL,
  `Word Soundex` varchar(64) NOT NULL,
  `Multiplicity` mediumint(8) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `Site Key` (`Site Key`,`Word`),
  KEY `Word` (`Word`),
  KEY `Word Soundex` (`Word Soundex`),
  KEY `Multiplicity` (`Multiplicity`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Site Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Site Deleted Dimension` (
  `Site Deleted Key` mediumint(8) unsigned NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `Site Code` varchar(64) NOT NULL,
  PRIMARY KEY (`Site Deleted Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Site Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Site Dimension` (
  `Site Key` smallint(5) unsigned NOT NULL,
  `Site Store Key` smallint(5) unsigned NOT NULL,
  `Site Code` varchar(8) NOT NULL,
  `Site Name` varchar(128) NOT NULL,
  `Site URL` varchar(255) NOT NULL,
  `Site Locale` enum('en_GB','de_DE','fr_FR','es_ES','pl_PL','it_IT','sk_SK','pt_PT') NOT NULL DEFAULT 'en_GB',
  `Site Contact Address` text NOT NULL,
  `Site Contact Telephone` text NOT NULL,
  `Site Contact Email` varchar(128) DEFAULT NULL,
  `Site Slogan` text NOT NULL,
  `Site Logo Image Key` mediumint(8) unsigned DEFAULT NULL,
  `Site Checkout Method` enum('Mals','Inikoo','AW') NOT NULL DEFAULT 'Inikoo',
  `Site Checkout Metadata` mediumtext NOT NULL,
  `Site Registration Method` enum('Simple','Wholesale','None') NOT NULL DEFAULT 'Simple',
  `Site Index Page Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Registration Type` enum('Steps','Simple','None') NOT NULL DEFAULT 'Simple',
  `Site Logo Data` text NOT NULL,
  `Site Header Data` text NOT NULL,
  `Site Content Data` text,
  `Site Footer Data` text NOT NULL,
  `Site Layout Data` text NOT NULL,
  `Site Head Include` text,
  `Site Body Include` text,
  `Site Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Site Secret Key` text,
  `Site Default Header Key` mediumint(9) DEFAULT NULL,
  `Site Default Footer Key` mediumint(9) DEFAULT NULL,
  `Site Default Number See Also Links` tinyint(4) unsigned NOT NULL,
  `Site Menu HTML` longtext NOT NULL,
  `Site Menu CSS` longtext NOT NULL,
  `Site Menu Javascript` longtext NOT NULL,
  `Site Search Method` enum('Inikoo','Custome') NOT NULL DEFAULT 'Inikoo',
  `Site Search HTML` longtext NOT NULL,
  `Site Search CSS` longtext NOT NULL,
  `Site Search Javascript` longtext NOT NULL,
  `Site FTP Server` text,
  `Site FTP User` text,
  `Site FTP Password` text,
  `Site FTP Directory` text NOT NULL,
  `Site FTP Passive` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site FTP Protocol` enum('SFTP','FTP','FTPS') NOT NULL DEFAULT 'FTPS',
  `Site FTP Port` mediumint(8) unsigned DEFAULT NULL,
  `Site Welcome Email Subject` text,
  `Site Welcome Email Plain Body` longtext,
  `Site Welcome Email HTML Body` longtext,
  `Site Forgot Password Email Subject` text,
  `Site Forgot Password Email Plain Body` longtext,
  `Site Forgot Password Email HTML Body` longtext,
  `Site Welcome Source` longtext,
  `Site Welcome Email Body` longtext,
  `Site Forgot Password Email Body` longtext,
  `Site Number Pages` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Pages with Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Pages with Out of Stock Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Out of Stock Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Products Out of Stock` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Back in Stock Reminder Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Back in Stock Reminder Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Back in Stock Reminder Waiting` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `Site Number Back in Stock Reminder Ready` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Back in Stock Reminder Sent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Number Back in Stock Reminder Cancelled` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Total Users` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Link Type` enum('Absolute','Relative') NOT NULL DEFAULT 'Absolute',
  `Show Site Badges` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Sitemap Last Update` datetime DEFAULT NULL,
  `Site Sitemap Last Ping Google` datetime DEFAULT NULL,
  `Site Sitemap Last Ping Bing` datetime DEFAULT NULL,
  `Site Sitemap Last Ping Ask` datetime DEFAULT NULL,
  `Site Sitemap Google Response` text,
  `Site Sitemap Bing Response` text,
  `Site Sitemap Ask Response` text,
  `Site Total Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site Total Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Total Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Total Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 3 Year Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 3 Year Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 3 Year Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 3 Year Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Year Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Year Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Year Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Year Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 6 Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 6 Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 6 Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 6 Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Quarter Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Quarter Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Quarter Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Quarter Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 10 Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 10 Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 10 Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 10 Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Week Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Week Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Week Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Week Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Hour Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Hour Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Hour Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Hour Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site Today Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site Today Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Today Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Today Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site Year To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site Year To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Year To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Year To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site Month To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site Month To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Month To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Month To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site Week To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site Week To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Week To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Week To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Week Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Week Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Week Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Week Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site Yesterday Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Site Yesterday Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Yesterday Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Yesterday Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Site Total Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Total Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 3 Year Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 3 Year Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Year Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Year Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 6 Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 6 Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Yesterday Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Yesterday Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Quarter Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Quarter Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 10 Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 10 Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Week Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Week Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Hour Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site 1 Hour Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Today Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Today Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Year To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Year To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Month To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Month To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Week To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Week To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Week Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Site Last Week Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Site From` datetime DEFAULT NULL,
  `Site To` datetime DEFAULT NULL,
  `Site Newsletter Custom Label` text,
  `Site Email Marketing Custom Label` text,
  `Site Postal Marketing Custom Label` text,
  `Site Facebook URL` text,
  `Site Twitter URL` text,
  `Site Skype URL` text,
  `Site Google URL` text,
  `Site LinkedIn URL` text,
  `Site Blog URL` text,
  `Site Digg URL` text,
  `Site Flickr URL` text,
  `Site RSS URL` text,
  `Site Youtube URL` text,
  `Site Show Facebook` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show LinkedIn` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show Skype` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show Youtube` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show Flickr` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show Blog` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show Digg` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show Google` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show RSS` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Show Twitter` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Site Registration Disclaimer` longtext,
  `Site Direct Subscribe Madmimi` varchar(255) NOT NULL,
  `Site Default Flag Color` enum('Blue','Green','Orange','Pink','Purple','Red','Yellow') NOT NULL DEFAULT 'Blue',
  `Site Welcome Email Code` text,
  `Site Forgot Password Email Code` text,
  `Site Order Confirmation Email Code` text,
  `Site Order Notification Email Code` text,
  `Site Order Notification Email Recipients` text,
  `Site SSL` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Site Key`),
  UNIQUE KEY `Site Code` (`Site Code`),
  UNIQUE KEY `Site Name` (`Site Name`,`Site Store Key`),
  UNIQUE KEY `Site URL` (`Site URL`),
  KEY `Store Key` (`Site Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Site External File Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Site External File Bridge` (
  `Page Store External File Key` mediumint(8) unsigned NOT NULL,
  `Site Key` mediumint(8) unsigned NOT NULL,
  `External File Type` enum('Javascript','CSS') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Page Store External File Key`,`Site Key`),
  KEY `Site Key` (`Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Site Flag Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Site Flag Dimension` (
  `Site Flag Key` mediumint(8) unsigned NOT NULL,
  `Site Key` smallint(5) unsigned NOT NULL,
  `Site Flag Color` enum('Blue','Green','Orange','Pink','Purple','Red','Yellow') NOT NULL,
  `Site Flag Label` varchar(16) NOT NULL,
  `Site Flag Number Pages` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Site Flag Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Site Flag Key`),
  UNIQUE KEY `Site Key` (`Site Key`,`Site Flag Color`),
  KEY `Site Key_2` (`Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Site Header Image Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Site Header Image Dimension` (
  `Site Header Image Key` mediumint(8) unsigned NOT NULL,
  `Site Header Image Name` varchar(255) NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Image Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Site Header Image Key`),
  KEY `Store Key` (`Store Key`),
  KEY `Image Key` (`Image Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Site History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Site History Bridge` (
  `Site Key` smallint(5) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','WebLog') NOT NULL DEFAULT 'Changes'
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Site Image Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Site Image Bridge` (
  `Image Key` mediumint(8) unsigned NOT NULL,
  `Store Key` smallint(5) unsigned NOT NULL,
  `Code` varchar(64) NOT NULL,
  UNIQUE KEY `Image Key` (`Image Key`,`Store Key`,`Code`),
  UNIQUE KEY `Image Key_3` (`Image Key`,`Store Key`,`Code`),
  KEY `Image Key_2` (`Image Key`),
  KEY `Store Key` (`Store Key`,`Code`),
  KEY `Image Key_4` (`Image Key`),
  KEY `Store Key_2` (`Store Key`,`Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Sitemap Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sitemap Dimension` (
  `Sitemap Key` mediumint(8) unsigned NOT NULL,
  `Sitemap Site Key` mediumint(8) unsigned NOT NULL,
  `Sitemap Date` datetime DEFAULT NULL,
  `Sitemap Name` varchar(64) DEFAULT NULL,
  `Sitemap Number` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Sitemap Content` longtext CHARACTER SET latin1,
  PRIMARY KEY (`Sitemap Key`),
  UNIQUE KEY `Sitemap Site Key` (`Sitemap Site Key`,`Sitemap Name`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Staff Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff Deleted Dimension` (
  `Staff Deleted Key` mediumint(8) unsigned NOT NULL,
  `Staff Deleted Type` enum('Employee','Contractor') NOT NULL DEFAULT 'Employee',
  `Staff Deleted ID` varchar(32) NOT NULL,
  `Staff Deleted Alias` varchar(64) NOT NULL,
  `Staff Deleted Name` varchar(255) NOT NULL,
  `Staff Deleted Date` datetime NOT NULL,
  `Staff Deleted Metadata` blob NOT NULL,
  PRIMARY KEY (`Staff Deleted Key`),
  KEY `Staff Deleted Name` (`Staff Deleted Name`),
  KEY `Staff Deleted Alias` (`Staff Deleted Alias`),
  KEY `Staff Deleted ID` (`Staff Deleted ID`),
  KEY `Staff Deleted Type` (`Staff Deleted Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Staff Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff Dimension` (
  `Staff Key` smallint(5) unsigned NOT NULL,
  `Staff ID` varchar(64) DEFAULT NULL,
  `Staff Alias` varchar(32) NOT NULL,
  `Staff Name` varchar(255) NOT NULL,
  `Staff Birthday` datetime DEFAULT NULL,
  `Staff Official ID` varchar(255) DEFAULT NULL,
  `Staff Email` varchar(255) DEFAULT NULL,
  `Staff Telephone` varchar(255) DEFAULT NULL,
  `Staff Telephone Formatted` varchar(255) DEFAULT NULL,
  `Staff Address` text,
  `Staff Next of Kind` varchar(1024) DEFAULT NULL,
  `Staff Area Key` smallint(6) NOT NULL DEFAULT '1',
  `Staff Department Key` smallint(6) NOT NULL DEFAULT '1',
  `Staff PIN` varchar(255) NOT NULL,
  `Staff Currently Working` enum('Yes','No') DEFAULT 'Yes',
  `Staff Type` enum('Employee','Volunteer','Contractor','TemporalWorker','WorkExperience') NOT NULL DEFAULT 'Employee',
  `Staff Job Title` varchar(255) DEFAULT NULL,
  `Staff Most Recent` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Staff Valid From` datetime DEFAULT NULL,
  `Staff Valid To` datetime DEFAULT NULL,
  `Staff Is Supervisor` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Staff Working Hours` text,
  `Staff Working Hours Per Week` float DEFAULT NULL,
  `Staff Working Hours Per Week Metadata` varchar(255) NOT NULL,
  `Staff Salary` text,
  PRIMARY KEY (`Staff Key`),
  UNIQUE KEY `Staff Alias` (`Staff Alias`),
  KEY `Staff ID` (`Staff ID`),
  KEY `Staff Area Key` (`Staff Area Key`),
  KEY `Staff Department Key` (`Staff Department Key`),
  KEY `Staff Currently Working` (`Staff Currently Working`),
  KEY `Staff Type` (`Staff Type`),
  KEY `Staff Most Recent` (`Staff Most Recent`),
  KEY `Staff Valid from` (`Staff Valid From`),
  KEY `Staff Valid To` (`Staff Valid To`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Staff Event Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff Event Dimension` (
  `Staff Event Key` mediumint(11) NOT NULL,
  `Subject` varchar(1000) DEFAULT NULL,
  `Location` varchar(200) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `End Time` datetime DEFAULT NULL,
  `Is All Day Event` smallint(6) NOT NULL,
  `Color` varchar(200) DEFAULT '6',
  `Recurring Rule` varchar(500) DEFAULT NULL,
  `Staff Date Key` mediumint(11) NOT NULL,
  `Staff Key` mediumint(11) NOT NULL,
  PRIMARY KEY (`Staff Event Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Staff History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff History Bridge` (
  `Staff Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Orders','Changes','Attachments','WebLog','Emails') NOT NULL DEFAULT 'Notes',
  PRIMARY KEY (`Staff Key`,`History Key`),
  KEY `Staff Key` (`Staff Key`),
  KEY `History Key` (`History Key`),
  KEY `Deletable` (`Deletable`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Staff Role Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff Role Bridge` (
  `Role Code` varchar(8) NOT NULL,
  `Staff Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Role Code`,`Staff Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Staff Supervisor Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff Supervisor Bridge` (
  `Staff Key` mediumint(8) unsigned NOT NULL,
  `Supervisor Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Staff Key`,`Supervisor Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Staff Timesheet Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff Timesheet Dimension` (
  `Staff Timesheet Key` mediumint(9) NOT NULL,
  `Staff Key` mediumint(8) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  PRIMARY KEY (`Staff Timesheet Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Staff Work Hours Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff Work Hours Dimension` (
  `Staff Key` mediumint(11) NOT NULL,
  `Date` date NOT NULL,
  `Start Time` datetime NOT NULL,
  `Finish Time` datetime NOT NULL,
  `Total Breaks Time` time NOT NULL,
  `Hours Worked` time NOT NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Store Data Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Store Data Dimension` (
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Store Total Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Acc Quantity Ordered` float NOT NULL DEFAULT '0',
  `Store Total Acc Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Store Total Acc Quantity Delivered` float NOT NULL DEFAULT '0',
  `Store Total Acc Days On Sale` float NOT NULL DEFAULT '0',
  `Store Total Acc Days Available` float NOT NULL DEFAULT '0',
  `Store Total Acc Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 3 Year Acc Invoiced Discount Amount` decimal(10,2) DEFAULT '0.00',
  `Store 3 Year Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 3 Year Acc Invoices` mediumint(8) DEFAULT '0',
  `Store 3 Year Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 3 Year Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 3 Year Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 3 Year Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store 3 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 3 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 3 Year Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Year Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Year Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store 1 Year Acc Invoices` mediumint(8) unsigned DEFAULT '0',
  `Store 1 Year Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Year Acc 1YB Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Year Acc 1YB Invoices` mediumint(9) unsigned DEFAULT '0',
  `Store 1 Year Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store Year To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store Year To Day Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store Year To Day Acc Invoices` mediumint(8) DEFAULT '0',
  `Store Year To Day Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store Year To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store Year To Day Acc 1YB Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store Year To Day Acc 1YB Invoices` mediumint(8) DEFAULT '0',
  `Store Year To Day Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store Month To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store Month To Day Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store Month To Day Acc Invoices` mediumint(8) DEFAULT '0',
  `Store Month To Day Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Month To Day Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Month To Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Month To Day Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store Month To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store Month To Day Acc 1YB Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store Month To Day Acc 1YB Invoices` mediumint(9) DEFAULT '0',
  `Store Month To Day Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Month To Day Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Month To Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Month To Day Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store Week To Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store Week To Day Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store Week To Day Acc Invoices` mediumint(8) DEFAULT '0',
  `Store Week To Day Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Week To Day Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Week To Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Week To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store Week To Day Acc 1YB Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store Week To Day Acc 1YB Invoices` mediumint(9) DEFAULT '0',
  `Store Week To Day Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Week To Day Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Week To Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Week To Day Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store Week To Day Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store 6 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 6 Month Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 6 Month Acc Invoices` decimal(12,2) DEFAULT '0.00',
  `Store 6 Month Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store 6 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 6 Month Acc 1YB Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 6 Month Acc 1YB Invoices` mediumint(9) DEFAULT '0',
  `Store 6 Month Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Quarter Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Quarter Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store 1 Quarter Acc Invoices` mediumint(8) unsigned DEFAULT '0',
  `Store 1 Quarter Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc 1YB Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Quarter Acc 1YB Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Quarter Acc 1YB Invoices` mediumint(9) unsigned DEFAULT '0',
  `Store 1 Quarter Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Month Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Month Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store 1 Month Acc Invoices` mediumint(8) unsigned DEFAULT '0',
  `Store 1 Month Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Month Acc 1YB Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Month Acc 1YB Invoices` mediumint(9) unsigned DEFAULT '0',
  `Store 1 Month Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store 10 Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 10 Day Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 10 Day Acc Invoices` mediumint(8) unsigned DEFAULT '0',
  `Store 10 Day Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 10 Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 10 Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 10 Day Acc 1YB Invoices` mediumint(9) unsigned DEFAULT '0',
  `Store 10 Day Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Week Acc Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Week Acc Profit` decimal(12,2) DEFAULT '0.00',
  `Store 1 Week Acc Invoices` mediumint(8) unsigned DEFAULT '0',
  `Store 1 Week Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Acc 1YB Invoiced Discount Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Week Acc 1YB Invoiced Amount` decimal(12,2) DEFAULT '0.00',
  `Store 1 Week Acc 1YB Invoices` mediumint(9) unsigned DEFAULT '0',
  `Store 1 Week Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Acc 1YB Profit` decimal(12,2) DEFAULT '0.00',
  `Store Today Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Today Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store Today Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Today Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Today Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Today Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Today Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Today Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store Today Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Today Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Today Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Yesterday Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Yesterday Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store Yesterday Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Yesterday Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Yesterday Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Yesterday Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Yesterday Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Yesterday Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store Yesterday Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Yesterday Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Yesterday Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Week Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Week Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store Last Week Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Week Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Week Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Week Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store Last Week Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Week Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Week Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Month Acc Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store Last Month Acc Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Month Acc Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Month Acc Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Last Month Acc 1YB Invoices` mediumint(9) NOT NULL DEFAULT '0',
  `Store Last Month Acc 1YB Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Month Acc 1YB Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Month Acc 1YB Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Users` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 3 Year New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Day New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day New Contacts With Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 3 Year Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Day Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day Lost Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 3 Year New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 6 Month New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Day New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Year To Day New Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Acc Average Dispatch Time` float DEFAULT NULL,
  `Store 3 Year Acc Average Dispatch Time` float DEFAULT NULL,
  `Store 1 Year Acc Average Dispatch Time` float DEFAULT NULL,
  `Store Year To Day Acc Average Dispatch Time` float DEFAULT NULL,
  `Store Month To Day Acc Average Dispatch Time` float DEFAULT NULL,
  `Store Week To Day Acc Average Dispatch Time` float DEFAULT NULL,
  `Store 6 Month Acc Average Dispatch Time` float DEFAULT NULL,
  `Store 1 Quarter Acc Average Dispatch Time` float DEFAULT NULL,
  `Store 1 Month Acc Average Dispatch Time` float DEFAULT NULL,
  `Store 10 Day Acc Average Dispatch Time` float DEFAULT NULL,
  `Store 1 Week Acc Average Dispatch Time` float DEFAULT NULL,
  `Store Today Acc Average Dispatch Time` float DEFAULT NULL,
  `Store Yesterday Acc Average Dispatch Time` float DEFAULT NULL,
  `Store Last Week Acc Average Dispatch Time` float DEFAULT NULL,
  `Store Last Month Acc Average Dispatch Time` float DEFAULT NULL,
  `Store 1 Week Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store 10 Day Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store 1 Day Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store 1 Month Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store 6 Month Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store 1 Quarter Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store 1 Year Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store 3 Year Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store Year To Day Lost Contacts With Orders` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Store Default Currency`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Store Default Currency` (
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Store DC Total Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Total Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 3 Year Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Year To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Year To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Month To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Month To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Week To Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Week To Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 6 Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Quarter Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 10 Day Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Year To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Year To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Month To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Month To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Week To Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Week To Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 6 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 6 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Quarter Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Quarter Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 10 Day Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 10 Day Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Yesterday Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Yesterday Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Week Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Week Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Today Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Today Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Today Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Today Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Month Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Month Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Month Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Week Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Yesterday Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Yesterday Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 3 Year Acc 1YB Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 3 Year Acc 1YB Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Store Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Store Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Store Dimension` (
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Store Code` varchar(16) DEFAULT NULL,
  `Store Name` varchar(255) DEFAULT NULL,
  `Store Contact Name` varchar(64) DEFAULT NULL,
  `Store URL` varchar(255) NOT NULL,
  `Store Email` varchar(255) NOT NULL,
  `Store Telephone` varchar(255) NOT NULL,
  `Store Fax` varchar(255) NOT NULL,
  `Store Slogan` varchar(64) NOT NULL DEFAULT '',
  `Store Address` varchar(255) DEFAULT NULL,
  `Short Marketing Description` varchar(255) DEFAULT NULL,
  `Store Telecom Format` varchar(255) NOT NULL DEFAULT 'GBR',
  `Store State` enum('Normal','Closed') NOT NULL DEFAULT 'Normal',
  `Store Valid From` datetime DEFAULT NULL,
  `Store Valid To` datetime DEFAULT NULL,
  `Store Number Days on Sale` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Number Days with Sales` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Number Days Available` float NOT NULL DEFAULT '0',
  `Store Avg Day Sales` float NOT NULL DEFAULT '0',
  `Store Avg with Sale Day Sales` float NOT NULL DEFAULT '0',
  `Store STD with Sale Day Sales` float NOT NULL DEFAULT '0',
  `Store Max Day Sales` float NOT NULL DEFAULT '0',
  `Store Locale` enum('en_GB','de_DE','fr_FR','es_ES','pl_PL','it_IT','sk_SK','pt_PT') DEFAULT 'en_GB',
  `Store Timezone` varchar(64) NOT NULL DEFAULT 'Europe/London',
  `Store Sticky Note` text,
  `Store Page Key` mediumint(8) unsigned DEFAULT NULL,
  `Store Departments` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Families` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store For Public Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store For Private Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store In Process Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store New Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Not For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Unknown Sales State Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Unknown Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store Orders In Process` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Dispatched Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Cancelled Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Suspended Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Unknown Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Paid Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Partially Paid Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Paid Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Partially Paid Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Ready to Pick Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Picking Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Packing Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Ready to Dispatch Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Dispatched Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Returned Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Delivery Notes For Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Delivery Notes For Replacements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Delivery Notes For Shortages` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Delivery Notes For Samples` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Delivery Notes For Donations` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Stock Value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Store Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Store Tax Category Code` varchar(64) DEFAULT NULL,
  `Store Tax Country Code` varchar(3) NOT NULL DEFAULT 'GBR',
  `Store Home Country Code 2 Alpha` varchar(2) NOT NULL,
  `Store Home Country Name` varchar(64) NOT NULL,
  `Store Order Public ID Format` varchar(65) NOT NULL DEFAULT '%05d',
  `Store Order Last Order ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Can Collect` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Store Collection XHTML Address` varchar(1024) DEFAULT NULL,
  `Store Collection Address Key` mediumint(8) unsigned DEFAULT NULL,
  `Store Total Users` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Losing Customer Interval` bigint(20) unsigned NOT NULL DEFAULT '5259487',
  `Store Lost Customer Interval` bigint(20) unsigned NOT NULL DEFAULT '7889231',
  `Store Contacts` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Contacts With Orders` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Contacts Who Visit Website` bigint(20) unsigned NOT NULL DEFAULT '0',
  `Store New Contacts` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Active Contacts` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Losing Contacts` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Lost Contacts` int(10) unsigned NOT NULL DEFAULT '0',
  `Store New Contacts With Orders` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Active Contacts With Orders` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Losing Contacts With Orders` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Lost Contacts With Orders` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Email Campaigns` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Newsletters` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Active Email Reminders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Active Deal Campaigns` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Active Deals` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Web Days Until Remove Discontinued Products` smallint(6) NOT NULL DEFAULT '0',
  `Store Websites` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Store VAT Number` varchar(255) DEFAULT NULL,
  `Store Company Number` varchar(255) DEFAULT NULL,
  `Store Company Name` varchar(255) DEFAULT NULL,
  `Store Invoice Message Header` text,
  `Store Invoice Message` text,
  `Store Delivery Note XHTML Message` mediumtext,
  `Store Invoice XHTML Message` mediumtext,
  `Store No Products Department Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store No Products Family Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Show in Warehouse Orders` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Store Customer Payment Account Key` smallint(5) unsigned DEFAULT NULL,
  `Store Dispatched Email Code` varchar(255) DEFAULT NULL,
  `Store Default Shipper Code` varchar(64) DEFAULT NULL,
  `Store Orphan Families Department` mediumint(8) unsigned DEFAULT NULL,
  `Store Orphan Products Family` mediumint(8) unsigned DEFAULT NULL,
  `Store Invoice Public ID Format` varchar(32) NOT NULL DEFAULT '%05d',
  `Store Invoice Last Invoice Public ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Next Invoice Public ID Method` enum('Order ID','Invoice Public ID','Account Wide Invoice Public ID') NOT NULL DEFAULT 'Order ID',
  `Store Invoice Last Refund Public ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Refund Public ID Format` varchar(32) NOT NULL DEFAULT '%05d',
  `Store Refund Public ID Method` enum('Same Invoice ID','Next Invoice ID','Account Wide Own Index','Store Own Index') NOT NULL DEFAULT 'Same Invoice ID',
  `Store Refund Suffix` varchar(16) NOT NULL DEFAULT 'ref',
  `Cancel Orders In Basket Older Than` bigint(20) unsigned NOT NULL DEFAULT '15778463' COMMENT 'Seconds (default 6 months)',
  `Store Payments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Family Category Key` mediumint(9) DEFAULT NULL,
  `Store Department Category Key` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`Store Key`),
  UNIQUE KEY `Store Name` (`Store Name`),
  KEY `code` (`Store Code`),
  KEY `Store State` (`Store State`),
  KEY `Store Show in Warehouse Orders` (`Store Show in Warehouse Orders`),
  KEY `Store Customer Payment Account Key` (`Store Customer Payment Account Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Store History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Store History Bridge` (
  `Store Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Store Key`,`History Key`),
  KEY `Store Key` (`Store Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Store Product Part Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Store Product Part Bridge` (
  `Store Product Key` mediumint(8) unsigned NOT NULL,
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Parts Per Product` float NOT NULL DEFAULT '1',
  `Note` varchar(255) DEFAULT NULL,
  `Linked Fields` text NOT NULL,
  PRIMARY KEY (`Store Product Key`,`Part SKU`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Category Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Category Dimension` (
  `Category Key` mediumint(8) unsigned NOT NULL,
  `Total Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Total Acc Part Sales` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `3 Year Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `3 Year Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Year Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Year Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Year To Day Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Year To Day Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Year To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Month To Day Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Month To Day Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Month To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Week To Day Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Week To Day Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Week To Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `6 Month Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `6 Month Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Quarter Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Quarter Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Quarter Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Month Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Month Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `10 Day Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `10 Day Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Week Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Week Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Year Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Year Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Year To Day Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Year To Day Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Year To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Month To Day Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Month To Day Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Month To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Week To Day Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Week To Day Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Week To Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `6 Month Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `6 Month Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `6 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Quarter Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Quarter Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Quarter Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `10 Day Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `10 Day Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `10 Day Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Week Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Week Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Yesterday Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Yesterday Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Yesterday Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Week Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Week Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Week Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Month Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Month Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Today Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Today Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Today Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Today Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Today Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Today Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Month Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Month Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Month Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Month Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Month Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Week Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Week Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Last Week Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Yesterday Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Yesterday Acc Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Yesterday Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `3 Year Acc 1YB Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `3 Year Acc 1YB Part Sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `3 Year Acc 1YB Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `1 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `2 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `3 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `4 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  KEY `Category Key` (`Category Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Category History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Category History Bridge` (
  `Category Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Type` enum('Changes','Assign') NOT NULL,
  UNIQUE KEY `Category Key` (`Category Key`,`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Data`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Data` (
  `Supplier Key` mediumint(8) unsigned NOT NULL,
  `Supplier 3 Year Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 3 Year Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 3 Year Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 3 Year Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 3 Year Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 3 Year Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 3 Year Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 3 Year Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 3 Year Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 3 Year Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 3 Year Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 3 Year Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 3 Year Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Year Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Year Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Year Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Year Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Year To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Year To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Year To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Year To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Month To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Month To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Month To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Month To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Week To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Week To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Week To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Week To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 10 Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 10 Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 10 Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 10 Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Week Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Week Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Week Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Week Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Today Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Today Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Today Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Today Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Today Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Yesterday Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Yesterday Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Yesterday Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Yesterday Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Week Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Week Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Week Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Week Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Total Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Total Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Total Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Total Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Total Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Year Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Year Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Year Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Year Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Year To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Year To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Year To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Year To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Year To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Month To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Month To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Month To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Month To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Month To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Week To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Week To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Week To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Week To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Week To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 6 Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 6 Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 6 Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 6 Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Quarter Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Quarter Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Quarter Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Quarter Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 1 Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 10 Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 10 Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 10 Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 10 Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 10 Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Week Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Week Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Week Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Week Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 1 Week Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Today Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Today Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Today Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Today Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Today Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Yesterday Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Yesterday Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Yesterday Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Yesterday Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Yesterday Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Week Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Week Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Week Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Week Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Last Week Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Last Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Last Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 6 Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 6 Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 6 Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 6 Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 6 Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Quarter Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Quarter Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Quarter Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 1 Quarter Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier 1 Quarter Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier 1 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 2 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 3 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier 4 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Supplier Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Deleted Dimension` (
  `Supplier Deleted Key` mediumint(9) NOT NULL,
  `Supplier Deleted Code` varchar(16) DEFAULT NULL,
  `Supplier Deleted Name` varchar(255) DEFAULT NULL,
  `Supplier Deleted Date` datetime DEFAULT NULL,
  `Supplier Deleted Metadata` blob,
  PRIMARY KEY (`Supplier Deleted Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Delivery Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Delivery Dimension` (
  `Supplier Delivery Key` mediumint(8) unsigned NOT NULL,
  `Supplier Delivery Purchase Order Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Delivery Parent` enum('Supplier','Agent') DEFAULT NULL,
  `Supplier Delivery Parent Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Delivery Parent Code` varchar(255) DEFAULT NULL,
  `Supplier Delivery Parent Name` varchar(255) DEFAULT NULL,
  `Supplier Delivery Parent Contact Name` varchar(255) DEFAULT NULL,
  `Supplier Delivery Parent Email` varchar(255) DEFAULT NULL,
  `Supplier Delivery Parent Telephone` varchar(255) DEFAULT NULL,
  `Supplier Delivery Parent Address` text,
  `Supplier Delivery Warehouse Key` smallint(5) unsigned DEFAULT NULL,
  `Supplier Delivery Warehouse Metadata` text,
  `Supplier Delivery State` enum('InProcess','Dispatched','Received','Checked','Placed','Cancelled') NOT NULL DEFAULT 'InProcess',
  `Supplier Delivery Receipt Confirmed Date` datetime DEFAULT NULL,
  `Supplier Delivery Date` date DEFAULT NULL,
  `Supplier Delivery Creation Date` datetime NOT NULL,
  `Supplier Delivery Dispatched Date` datetime DEFAULT NULL,
  `Supplier Delivery Estimated Receiving Date` datetime DEFAULT NULL,
  `Supplier Delivery Received Date` datetime DEFAULT NULL,
  `Supplier Delivery Checked Date` datetime DEFAULT NULL,
  `Supplier Delivery Damages Checked Date` datetime DEFAULT NULL,
  `Supplier Delivery Placed Date` datetime DEFAULT NULL,
  `Supplier Delivery Cancelled Date` datetime DEFAULT NULL,
  `Supplier Delivery Last Updated Date` datetime DEFAULT NULL COMMENT 'Lastest Date when Adding/Modify Supplier Delivery Transaction or Data',
  `Supplier Delivery Main Inputter Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Delivery Main Receiver Key` mediumint(9) DEFAULT NULL,
  `Supplier Delivery Main Checker Key` mediumint(9) DEFAULT NULL,
  `Supplier Delivery Main Damages Checker Key` mediumint(9) DEFAULT NULL,
  `Supplier Delivery Public ID` varchar(255) DEFAULT NULL,
  `Supplier Delivery File As` varchar(255) DEFAULT NULL,
  `Supplier Delivery XHTML Purchase Orders` varchar(4096) DEFAULT NULL,
  `Supplier Delivery Our Feedback` enum('Praise','None','Shortages','Breakings','Different Product','Multiple','Low Quality','Not Like','Slow Delivery','Other') NOT NULL DEFAULT 'None',
  `Supplier Delivery Actions Taken` enum('Refund','Credit','Replacement','Send Missing','Other','No Applicable') NOT NULL DEFAULT 'No Applicable',
  `Supplier Delivery Number Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Delivery Number Ordered Items` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Delivery Number Items Without PO` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Delivery Number Dispatched Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Delivery Number Checked Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Delivery Number Received and Checked Items` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Delivery Number Placed Items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Delivery Placed Items` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Supplier Delivery Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Supplier Delivery Currency Exchange` float NOT NULL DEFAULT '1',
  `Supplier Delivery Items Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Items Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Shipping Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Charges Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Other Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Total Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Shipping Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Charges Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Other Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Total Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Total Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Total To Pay Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Delivery Supplier Message` text,
  `Supplier Delivery Incoterm` varchar(3) DEFAULT NULL,
  `Supplier Delivery Port of Export` varchar(255) DEFAULT NULL,
  `Supplier Delivery Port of Import` varchar(255) DEFAULT NULL,
  `Supplier Delivery Weight` float DEFAULT NULL,
  `Supplier Delivery CBM` float DEFAULT NULL,
  `Supplier Delivery Metadata` text,
  PRIMARY KEY (`Supplier Delivery Key`),
  KEY `Supplier Delivery Purchase Order Key` (`Supplier Delivery Purchase Order Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Delivery History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Delivery History Bridge` (
  `Supplier Delivery Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes') CHARACTER SET latin1 NOT NULL,
  UNIQUE KEY `Supplier Delivery Key` (`Supplier Delivery Key`,`History Key`),
  KEY `Type` (`Type`),
  KEY `Deletable` (`Deletable`),
  KEY `Strikethrough` (`Strikethrough`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Dimension` (
  `Supplier Key` mediumint(8) unsigned NOT NULL,
  `Supplier Type` enum('Free','Agent','Archived') NOT NULL DEFAULT 'Free',
  `Supplier Has Agent` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Supplier Products Origin Country Code` varchar(3) DEFAULT NULL,
  `Supplier Code` varchar(16) NOT NULL,
  `Supplier Name` varchar(255) DEFAULT NULL,
  `Supplier File As` varchar(255) NOT NULL,
  `Supplier Fiscal Name` varchar(255) DEFAULT NULL,
  `Supplier Company Key` mediumint(8) unsigned NOT NULL,
  `Supplier Main Contact Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Accounts Payable Contact Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Sales Contact Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Main Address Key` mediumint(8) unsigned NOT NULL,
  `Supplier Main XHTML Address` varchar(1024) DEFAULT '',
  `Supplier Main Plain Address` varchar(1024) DEFAULT NULL,
  `Supplier Main Country Key` smallint(5) unsigned NOT NULL,
  `Supplier Main Country Code` varchar(3) NOT NULL,
  `Supplier Main Country` varchar(255) NOT NULL,
  `Supplier Main Location` varchar(255) DEFAULT NULL,
  `Supplier Main XHTML Email` varchar(1024) DEFAULT NULL,
  `Supplier Main Plain Email` varchar(255) NOT NULL,
  `Supplier Location` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Recipient` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Organization` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Line 1` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Line 2` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Sorting Code` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Postal Code` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Dependent Locality` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Locality` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Administrative Area` varchar(255) DEFAULT NULL,
  `Supplier Contact Address Country 2 Alpha Code` varchar(2) DEFAULT NULL,
  `Supplier Contact Address Checksum` varchar(64) DEFAULT NULL,
  `Supplier Contact Address Formatted` text,
  `Supplier Contact Address Postal Label` text,
  `Supplier Main Email Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Company Name` varchar(255) DEFAULT NULL,
  `Supplier Main Contact Name` varchar(255) DEFAULT NULL,
  `Supplier QQ` varchar(64) DEFAULT NULL,
  `Supplier Preferred Contact Number` enum('Mobile','Telephone') NOT NULL DEFAULT 'Mobile',
  `Supplier Preferred Contact Number Formatted Number` varchar(255) DEFAULT NULL,
  `Supplier Main Plain Mobile` varchar(255) DEFAULT NULL,
  `Supplier Main XHTML Mobile` varchar(255) DEFAULT NULL,
  `Supplier Main XHTML Telephone` varchar(255) DEFAULT NULL,
  `Supplier Main Telephone Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Main Plain Telephone` varchar(255) NOT NULL,
  `Supplier Main XHTML FAX` varchar(100) DEFAULT NULL,
  `Supplier Main FAX Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Main Plain FAX` varchar(100) NOT NULL DEFAULT '',
  `Supplier Website` varchar(255) NOT NULL,
  `Supplier Default Incoterm` varchar(3) DEFAULT NULL,
  `Supplier Default Port of Export` varchar(255) DEFAULT NULL,
  `Supplier Default Port of Import` varchar(255) DEFAULT NULL,
  `Supplier Default PO Terms and Conditions` text,
  `Supplier Show Warehouse TC in PO` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Supplier Discontinued Supplier Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Unknown Stock Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Not For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier To Be Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Average Delivery Days` float DEFAULT NULL,
  `Supplier Purchase Orders` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Open Purchase Orders` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Purchase Delivery Notes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Purchase Invoices` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Supplier Valid From` datetime DEFAULT NULL,
  `Supplier Valid To` datetime DEFAULT NULL,
  `Supplier Sticky Note` text,
  `Supplier Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Supplier Stock Value` decimal(14,2) DEFAULT NULL,
  `Supplier Default Currency Code` varchar(3) NOT NULL DEFAULT 'USD',
  `Supplier Number Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Number Active Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Number Surplus Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Number Optimal Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Number Low Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Number Critical Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Number Out Of Stock Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Supplier Key`),
  UNIQUE KEY `Supplier Code` (`Supplier Code`),
  KEY `Supplier Most Recent` (`Supplier Active`),
  KEY `Supplier Main Email` (`Supplier Main Plain Email`(80)),
  KEY `Supplier Main Telephone` (`Supplier Main Plain Telephone`(20)),
  KEY `Supplier Main Contact Key` (`Supplier Main Contact Key`),
  KEY `Supplier Company Key` (`Supplier Company Key`),
  KEY `Supplier Main Email Key` (`Supplier Main Email Key`),
  KEY `Supplier Main Address Key` (`Supplier Main Address Key`),
  KEY `Supplier Main Telephone Key` (`Supplier Main Telephone Key`),
  KEY `Supplier Main FAX Key` (`Supplier Main FAX Key`),
  KEY `Supplier Is Agent` (`Supplier Has Agent`),
  KEY `Supplier Type` (`Supplier Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier History Bridge` (
  `Supplier Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') CHARACTER SET latin1 NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Orders','Changes','Attachments','WebLog','Emails') CHARACTER SET latin1 NOT NULL,
  UNIQUE KEY `Supplier Key` (`Supplier Key`,`History Key`),
  KEY `Type` (`Type`),
  KEY `Deletable` (`Deletable`),
  KEY `Strikethrough` (`Strikethrough`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Other Email Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Other Email Dimension` (
  `Supplier Other Email Key` mediumint(8) unsigned NOT NULL,
  `Supplier Other Email Supplier Key` mediumint(8) unsigned NOT NULL,
  `Supplier Other Email Email` varchar(255) NOT NULL,
  `Supplier Other Email Label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Supplier Other Email Key`),
  KEY `Supplier Other Email Supplier Key` (`Supplier Other Email Supplier Key`,`Supplier Other Email Email`),
  KEY `Supplier Other Email Email` (`Supplier Other Email Email`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Other Telephone Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Other Telephone Dimension` (
  `Supplier Other Telephone Key` mediumint(8) unsigned NOT NULL,
  `Supplier Other Telephone Supplier Key` mediumint(8) unsigned NOT NULL,
  `Supplier Other Telephone Number` varchar(255) NOT NULL,
  `Supplier Other Telephone Formatted Number` varchar(255) NOT NULL,
  `Supplier Other Telephone Label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Supplier Other Telephone Key`),
  KEY `Supplier Other Telephone Supplier Key` (`Supplier Other Telephone Supplier Key`,`Supplier Other Telephone Number`),
  KEY `Supplier Other Telephone Number` (`Supplier Other Telephone Number`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Part Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Part Deleted Dimension` (
  `Supplier Part Deleted Key` mediumint(9) NOT NULL,
  `Supplier Part Deleted Reference` varchar(255) DEFAULT NULL,
  `Supplier Part Deleted Date` datetime DEFAULT NULL,
  `Supplier Part Deleted Metadata` blob,
  PRIMARY KEY (`Supplier Part Deleted Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Part Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Part Dimension` (
  `Supplier Part Key` mediumint(8) unsigned NOT NULL,
  `Supplier Part Historic Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Part Supplier Key` mediumint(8) unsigned NOT NULL,
  `Supplier Part Part SKU` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Part Reference` varchar(255) DEFAULT NULL,
  `Supplier Part Status` enum('Available','NoAvailable','Discontinued') NOT NULL DEFAULT 'Available',
  `Supplier Part From` datetime NOT NULL,
  `Supplier Part To` datetime DEFAULT NULL,
  `Supplier Part Unit Cost` float unsigned DEFAULT NULL,
  `Supplier Part Unit Extra Cost` float NOT NULL DEFAULT '0',
  `Supplier Part Currency Code` varchar(3) DEFAULT NULL,
  `Supplier Part Packages Per Carton` smallint(6) NOT NULL DEFAULT '1',
  `Supplier Part Carton CBM` float unsigned DEFAULT NULL COMMENT 'cubic meters',
  `Supplier Part Minimum Carton Order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Supplier Part Average Delivery Days` float unsigned DEFAULT NULL,
  `Supplier Part Note to Supplier` text,
  PRIMARY KEY (`Supplier Part Key`),
  KEY `Supplier Part Supplier Part Historic Key` (`Supplier Part Historic Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Part Historic Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Part Historic Dimension` (
  `Supplier Part Historic Key` mediumint(8) unsigned NOT NULL,
  `Supplier Part Historic Supplier Part Key` mediumint(8) unsigned NOT NULL,
  `Supplier Part Historic Reference` varchar(255) DEFAULT NULL,
  `Supplier Part Historic Unit Cost` decimal(16,6) unsigned DEFAULT NULL,
  `Supplier Part Historic Units Per Package` smallint(5) unsigned NOT NULL,
  `Supplier Part Historic Packages Per Carton` smallint(5) unsigned NOT NULL,
  `Supplier Part Historic Carton CBM` float(16,4) DEFAULT NULL,
  `Supplier Part Historic Currency Code` varchar(3) NOT NULL,
  PRIMARY KEY (`Supplier Part Historic Key`),
  KEY `Supplier Part Historic Supplier Part Key` (`Supplier Part Historic Supplier Part Key`),
  KEY `Supplier Part Historic Unit Cost` (`Supplier Part Historic Unit Cost`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Part History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Part History Bridge` (
  `Supplier Part Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Part') NOT NULL,
  PRIMARY KEY (`Supplier Part Key`,`History Key`),
  KEY `Supplier Part Key` (`Supplier Part Key`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Product Custom Field Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Product Custom Field Dimension` (
  `Supplier Product ID` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Supplier Product ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Product Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Product Dimension` (
  `Supplier Product ID` mediumint(8) unsigned NOT NULL,
  `Supplier Product Code` varchar(64) NOT NULL,
  `Supplier Product Status` enum('In Use','Not In Use') NOT NULL DEFAULT 'In Use',
  `Supplier Product Available` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Supplier Product State` enum('Available','NoAvailable','Discontinued') NOT NULL DEFAULT 'Available',
  `Supplier Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Supplier Code` varchar(60) DEFAULT NULL,
  `Supplier Name` varchar(255) DEFAULT NULL,
  `Supplier Product Name` varchar(255) DEFAULT NULL,
  `Supplier Product Description` text,
  `Supplier Product Store As` longtext,
  `Supplier Product XHTML Store As` longtext,
  `Supplier Product Barcode Type` enum('none','ean8','ean13',' code11','code39','code128','codabar') NOT NULL DEFAULT 'code128',
  `Supplier Product Barcode Data Source` enum('Code','Other') NOT NULL DEFAULT 'Code',
  `Supplier Product Barcode Data` varchar(1024) DEFAULT NULL,
  `Supplier Product Sold As` longtext,
  `Supplier Product XHTML Sold As` longtext,
  `Supplier Product URL` varchar(255) DEFAULT NULL,
  `Supplier Product Unit Type` enum('10','25','100','200','bag','ball','box','doz','dwt','item','foot','gram','gross','hank','kilo','ib','m','oz','ozt','pair','pkg','set','skein','spool','strand','ten','tube','vial','yd') NOT NULL DEFAULT 'item',
  `Supplier Product Units Per Case` smallint(6) NOT NULL DEFAULT '1',
  `Supplier Product Units Per Inner` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Supplier Product Tariff Code` varchar(255) DEFAULT NULL,
  `Supplier Product Tariff Code Valid` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Supplier Product Duty Rate` varchar(255) DEFAULT NULL,
  `Supplier Product Origin Country Code` varchar(3) DEFAULT NULL,
  `Supplier Product Package Type` enum('Bottle','Bag','Box','None','Other') NOT NULL DEFAULT 'Box',
  `Supplier Product Package Weight` float DEFAULT NULL,
  `Supplier Product Package Weight Display` float DEFAULT NULL,
  `Supplier Product Package Weight Display Units` enum('Kg','g','oz','lb') NOT NULL DEFAULT 'Kg',
  `Supplier Product Unit Weight` float DEFAULT NULL,
  `Supplier Product Unit Weight Display` float DEFAULT NULL,
  `Supplier Product Unit Weight Display Units` enum('Kg','g','oz','lb') NOT NULL DEFAULT 'Kg',
  `Supplier Product Unit Dimensions Type` enum('Rectangular','Cilinder','Sphere','String','Sheet') NOT NULL DEFAULT 'Rectangular',
  `Supplier Product Unit Dimensions Display Units` enum('mm','cm','m','in','yd','ft') NOT NULL DEFAULT 'cm',
  `Supplier Product Unit Dimensions Width` float DEFAULT NULL,
  `Supplier Product Unit Dimensions Depth` float NOT NULL,
  `Supplier Product Unit Dimensions Length` float NOT NULL,
  `Supplier Product Unit Dimensions Diameter` float NOT NULL,
  `Supplier Product Unit Dimensions Width Display` float NOT NULL,
  `Supplier Product Unit Dimensions Depth Display` float NOT NULL,
  `Supplier Product Unit Dimensions Length Display` float NOT NULL,
  `Supplier Product Unit Dimensions Diameter Display` float NOT NULL,
  `Supplier Product Unit Dimensions Volume` float DEFAULT NULL,
  `Supplier Product Unit XHTML Dimensions` varchar(255) DEFAULT NULL,
  `Supplier Product Unit Materials` text,
  `Supplier Product Unit XHTML Materials` text,
  `Supplier Product Package Dimensions Type` enum('Rectangular','Cilinder','Sphere') NOT NULL DEFAULT 'Rectangular',
  `Supplier Product Package Dimensions Display Units` enum('mm','cm','m','in','yd','ft') NOT NULL DEFAULT 'cm',
  `Supplier Product Package Dimensions Width` float NOT NULL,
  `Supplier Product Package Dimensions Depth` float NOT NULL,
  `Supplier Product Package Dimensions Length` float NOT NULL,
  `Supplier Product Package Dimensions Diameter` float NOT NULL,
  `Supplier Product Package Dimensions Width Display` float NOT NULL,
  `Supplier Product Package Dimensions Depth Display` float NOT NULL,
  `Supplier Product Package Dimensions Length Display` float DEFAULT NULL,
  `Supplier Product Package Dimensions Diameter Display` float DEFAULT NULL,
  `Supplier Product Package Dimensions Volume` float DEFAULT NULL,
  `Supplier Product Package XHTML Dimensions` varchar(255) DEFAULT NULL,
  `Supplier Product Carton Length` float DEFAULT NULL,
  `Supplier Product Carton Width` float DEFAULT NULL,
  `Supplier Product Carton Depth` float DEFAULT NULL,
  `Supplier Product Carton CBM` float DEFAULT NULL,
  `Supplier Product Health And Safety` longtext,
  `Supplier Product UN Number` varchar(4) DEFAULT NULL,
  `Supplier Product UN Class` varchar(4) DEFAULT NULL,
  `Supplier Product Packing Group` enum('None','I','II','III') NOT NULL DEFAULT 'None',
  `Supplier Product Proper Shipping Name` varchar(255) NOT NULL,
  `Supplier Product Hazard Indentification Number` varchar(64) NOT NULL,
  `Supplier Product MSDS Attachment Bridge Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Product MSDS Attachment XHTML Info` varchar(1024) DEFAULT NULL,
  `Supplier Product Part Convertion` enum('1:1','1:N') NOT NULL DEFAULT '1:1',
  `Supplier Product Currency` varchar(3) NOT NULL DEFAULT 'GBP',
  `Supplier Product Cost Per Case` decimal(16,2) DEFAULT NULL,
  `Supplier Product Valid From` datetime DEFAULT NULL,
  `Supplier Product Valid To` datetime DEFAULT NULL,
  `Supplier Product Current Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Product Sticky Note` text,
  `Supplier Product Note to Supplier` text,
  `Supplier Product Tax Code` varchar(16) NOT NULL DEFAULT 'EX',
  `Supplier Product Days Available` float DEFAULT NULL,
  `Supplier Product Last Purchase Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Product Last Purchase Date` datetime DEFAULT NULL,
  `Supplier Product Last Purchase Quantity` float NOT NULL DEFAULT '0',
  `Supplier Product Percentage Last Purchase` float DEFAULT NULL,
  `Supplier Product Stock` float DEFAULT NULL,
  `Supplier Product Buy State` enum('Discontinued','History','Ok','Deleted') NOT NULL DEFAULT 'Ok',
  `Supplier Product Main Image` varchar(1024) DEFAULT NULL,
  `Supplier Product Main Image Key` mediumint(8) unsigned NOT NULL,
  `Supplier Product Agent Supplier Name` varchar(255) DEFAULT NULL,
  `Supplier Product 3 Year Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 3 Year Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 3 Year Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 3 Year Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 3 Year Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 3 Year Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 3 Year Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 3 Year Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 3 Year Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 3 Year Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 3 Year Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 3 Year Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 3 Year Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Year Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Year Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Year Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Year Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Year To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Year To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Year To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Year To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Month To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Month To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Month To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Month To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Week To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Week To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Week To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Week To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 6 Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 6 Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 6 Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 6 Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 10 Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 10 Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 10 Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 10 Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Week Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Week Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Week Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Week Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Today Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Today Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Today Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Today Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Yesterday Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Yesterday Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Yesterday Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Yesterday Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Week Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Week Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Week Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Week Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Total Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Total Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Total Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Total Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Total Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Year Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Year Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Year Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Year Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Year Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Year To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Year To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Year To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Year To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Year To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Month To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Month To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Month To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Month To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Month To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Week To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Week To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Week To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Week To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Week To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 6 Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 6 Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 6 Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 6 Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 6 Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Quarter Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Quarter Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Quarter Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Quarter Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 10 Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 10 Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 10 Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 10 Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 10 Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Week Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Week Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Week Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Week Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Week Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Today Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Today Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Today Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Today Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Today Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Yesterday Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Yesterday Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Yesterday Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Yesterday Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Yesterday Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Week Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Week Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Week Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Week Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Last Week Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product Last Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product Last Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Quarter Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Quarter Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Quarter Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Quarter Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Quarter Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 1 Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `Supplier Product 1 Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `Supplier Product Delivery Days` smallint(5) unsigned NOT NULL DEFAULT '30',
  `Supplier Product Delivery Days Set Up` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Supplier Product 1 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 2 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 3 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Supplier Product 4 Year Ago Sales Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Supplier Product ID`),
  KEY `Supplier Key` (`Supplier Key`),
  KEY `Supplier Product Valid From` (`Supplier Product Valid From`),
  KEY `Supplier Product To` (`Supplier Product Valid To`),
  KEY `Supplier Product Current Key` (`Supplier Product Current Key`),
  KEY `Supplier Product Code` (`Supplier Product Code`(10)),
  KEY `Supplier Product Delivery Days Set Up` (`Supplier Product Delivery Days Set Up`),
  KEY `Supplier Product Available` (`Supplier Product Available`),
  KEY `Supplier Product State` (`Supplier Product State`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Product History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Product History Bridge` (
  `Supplier Product ID` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') NOT NULL,
  PRIMARY KEY (`Supplier Product ID`,`History Key`),
  KEY `Supplier Product ID` (`Supplier Product ID`),
  KEY `Deletable` (`Deletable`),
  KEY `History Key` (`History Key`),
  KEY `Type` (`Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Product History Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Product History Dimension` (
  `SPH Key` mediumint(8) unsigned NOT NULL,
  `Supplier Product ID` mediumint(8) unsigned DEFAULT NULL,
  `SPH Units Per Case` smallint(5) unsigned NOT NULL DEFAULT '1',
  `SPH Case Cost` decimal(16,2) DEFAULT NULL,
  `SPH Currency` varchar(3) NOT NULL DEFAULT 'GBP',
  `SPH Valid From` datetime DEFAULT NULL,
  `SPH Valid To` datetime DEFAULT NULL,
  `SPH Type` enum('Normal','Historic') NOT NULL DEFAULT 'Normal',
  `SPH Total Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Total Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Total Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Total Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Total Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Total Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH Total Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Total Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Total Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Total Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Total Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Total Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Total Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Today Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Today Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Today Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Today Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Today Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Week To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Week To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Week To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Week To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Month To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Month To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Month To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Month To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Year To Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Year To Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Year To Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Year To Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Yesterday Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Yesterday Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Yesterday Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Yesterday Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Week Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Week Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Week Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Week Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 3 Year Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 3 Year Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 3 Year Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 3 Year Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 3 Year Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Year Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Year Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Year Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Year Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 6 Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 6 Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 6 Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 6 Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Quarter Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Quarter Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Quarter Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Quarter Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Month Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Month Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Month Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Month Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 10 Day Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 10 Day Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 10 Day Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 10 Day Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Week Acc Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Week Acc Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Week Acc Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Week Acc Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts Required` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Today Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Today Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Today Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Today Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Today Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Week To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Week To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Week To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Week To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Week To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Month To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Month To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Month To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Month To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Month To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Year To Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Year To Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Year To Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Year To Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Year To Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Yesterday Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Yesterday Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Yesterday Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Yesterday Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Yesterday Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Week Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Week Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Week Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Week Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Last Week Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH Last Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH Last Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Year Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Year Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Year Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Year Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 1 Year Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 6 Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 6 Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 6 Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 6 Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 6 Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Quarter Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Quarter Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Quarter Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Quarter Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 1 Quarter Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Month Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Month Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Month Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Month Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 1 Month Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 10 Day Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 10 Day Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 10 Day Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 10 Day Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 10 Day Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Week Acc 1YB Parts Profit After Storing` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Week Acc 1YB Parts Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Week Acc 1YB Parts Sold Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPH 1 Week Acc 1YB Parts Bought` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts Required` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts No Dispatched` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts Sold` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts Lost` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts Broken` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts Returned` float NOT NULL DEFAULT '0',
  `SPH 1 Week Acc 1YB Parts Margin` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`SPH Key`),
  KEY `SPH Valid From` (`SPH Valid From`),
  KEY `SPH To` (`SPH Valid To`),
  KEY `SPH Type` (`SPH Type`),
  KEY `Supplier Product Key` (`Supplier Product ID`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Product Material Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Product Material Bridge` (
  `Supplier Product Material Key` mediumint(8) unsigned NOT NULL,
  `Supplier Product ID` mediumint(8) unsigned NOT NULL,
  `Material Key` mediumint(8) unsigned NOT NULL,
  `Ratio` float DEFAULT NULL,
  `May Contain` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Supplier Product Material Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Product Part Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Product Part Dimension` (
  `Supplier Product Part Key` mediumint(8) unsigned NOT NULL,
  `Supplier Product ID` mediumint(8) unsigned NOT NULL,
  `Supplier Product Historic Key` mediumint(8) unsigned NOT NULL,
  `Supplier Product Part Type` enum('Simple','Split') NOT NULL,
  `Supplier Product Part Metadata` text,
  `Supplier Product Part Valid From` datetime NOT NULL,
  `Supplier Product Part Valid To` datetime DEFAULT NULL,
  `Supplier Product Part Most Recent` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Supplier Product Part In Use` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Supplier Product Part Key`),
  KEY `Supplier Product Key` (`Supplier Product ID`),
  KEY `Supplier Product Part Most Recent` (`Supplier Product Part Most Recent`),
  KEY `Supplier Product Part In Use` (`Supplier Product Part In Use`),
  KEY `Supplier Product Historic Key` (`Supplier Product Historic Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Supplier Product Part List`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Supplier Product Part List` (
  `Supplier Product Part List Key` mediumint(8) unsigned NOT NULL,
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Supplier Product Part Key` mediumint(8) unsigned NOT NULL,
  `Supplier Product Units Per Part` float DEFAULT '1',
  PRIMARY KEY (`Supplier Product Part List Key`),
  KEY `Supplier Product Part ID` (`Supplier Product Part Key`),
  KEY `Part SKU` (`Part SKU`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Table Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Table Dimension` (
  `Table Key` mediumint(8) unsigned NOT NULL,
  `Table Name` varchar(64) NOT NULL,
  `Table Export Fields` text NOT NULL,
  PRIMARY KEY (`Table Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Table User Export Fields`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Table User Export Fields` (
  `Table User Export Fields Key` mediumint(8) unsigned NOT NULL,
  `Table Key` smallint(5) unsigned NOT NULL,
  `User Key` mediumint(8) unsigned NOT NULL,
  `Map Name` varchar(64) NOT NULL,
  `Map State` enum('Selected','Archive') NOT NULL DEFAULT 'Archive',
  `Fields` varchar(900) NOT NULL,
  PRIMARY KEY (`Table User Export Fields Key`),
  KEY `Table Key` (`Table Key`,`User Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Tax Category Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tax Category Dimension` (
  `Tax Category Key` smallint(5) unsigned NOT NULL,
  `Tax Category Type` enum('Standard','Zero','Unknown','Reduced','Exempt','Outside') NOT NULL DEFAULT 'Standard',
  `Tax Category Type Name` varchar(64) NOT NULL,
  `Tax Category Code` varchar(16) NOT NULL,
  `Tax Category Name` varchar(255) NOT NULL,
  `Tax Category Rate` decimal(8,6) NOT NULL,
  `Composite` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Composite Metadata` varchar(255) DEFAULT NULL,
  `Tax Category Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Tax Category Country Code` varchar(3) NOT NULL DEFAULT 'UNK',
  `Tax Category Default` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Tax Category Key`),
  UNIQUE KEY `Tax Category Code` (`Tax Category Code`),
  KEY `Tax Category Type` (`Tax Category Type Name`),
  KEY `Composite` (`Composite`),
  KEY `Tax Category Active` (`Tax Category Active`),
  KEY `Tax Category Country Code` (`Tax Category Country Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Telecom Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Telecom Bridge` (
  `Telecom Key` mediumint(8) unsigned NOT NULL,
  `Subject Key` mediumint(8) unsigned NOT NULL,
  `Subject Type` enum('Address','Customer','Contact','Staff','Company','Supplier') NOT NULL,
  `Is Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Is Main` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Telecom Description` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `unique` (`Telecom Key`,`Subject Key`,`Subject Type`),
  KEY `Telecom Key` (`Telecom Key`),
  KEY `FK` (`Subject Key`),
  KEY `Subject Type` (`Subject Type`),
  KEY `Is Active` (`Is Active`),
  KEY `Is Main` (`Is Main`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Telecom Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Telecom Dimension` (
  `Telecom Key` mediumint(8) unsigned NOT NULL,
  `Telecom Type` enum('Telephone','Fax','Mobile') NOT NULL DEFAULT 'Telephone',
  `Telecom Technology Type` enum('Landline','Mobile','Unknown','Non-geographic') NOT NULL DEFAULT 'Unknown',
  `Telecom Country Telephone Code` varchar(4) DEFAULT NULL,
  `Telecom National Access Code` varchar(10) DEFAULT NULL,
  `Telecom Area Code` varchar(6) DEFAULT NULL,
  `Telecom Number` varchar(15) NOT NULL,
  `Telecom Extension` varchar(8) DEFAULT NULL,
  `National Only Telecom` enum('Yes','No') DEFAULT 'No',
  `Telecom Plain Number` varchar(29) NOT NULL COMMENT 'no spaces of (nat access,local code,number,extension)',
  PRIMARY KEY (`Telecom Key`),
  KEY `Telecom Plain Number` (`Telecom Plain Number`),
  KEY `Telecom Area Code` (`Telecom Area Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Theme Background Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Theme Background Bridge` (
  `Theme Key` mediumint(9) NOT NULL,
  `Theme Background Key` mediumint(9) NOT NULL,
  PRIMARY KEY (`Theme Key`,`Theme Background Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Theme Background Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Theme Background Dimension` (
  `Theme Background Key` smallint(5) unsigned NOT NULL,
  `Theme Background Name` varchar(255) NOT NULL,
  `Header CSS` text NOT NULL,
  `Background CSS` text NOT NULL,
  `Footer CSS` text NOT NULL,
  PRIMARY KEY (`Theme Background Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Theme Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Theme Dimension` (
  `Theme Key` int(11) NOT NULL,
  `Theme Name` varchar(255) NOT NULL,
  `Theme CSS Buttons` text NOT NULL,
  `Theme CSS Header` text NOT NULL,
  `Theme CSS Tables` text NOT NULL,
  `Theme CSS Top Navigation` text NOT NULL,
  PRIMARY KEY (`Theme Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Time Series Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Time Series Dimension` (
  `Time Series Date` date NOT NULL,
  `Time Series Frequency` enum('Daily','Weekly','Monthly','Quarterly','Yearly') NOT NULL,
  `Time Series Name` varchar(255) NOT NULL,
  `Time Series Name Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Time Series Name Second Key` mediumint(9) NOT NULL DEFAULT '0',
  `Time Series Parent Key` mediumint(8) unsigned NOT NULL,
  `Time Series Label` varchar(16) NOT NULL,
  `Time Series Value` float NOT NULL,
  `Time Series Count` int(10) unsigned NOT NULL,
  `Open` float DEFAULT NULL,
  `High` float DEFAULT NULL,
  `Low` float DEFAULT NULL,
  `Close` float DEFAULT NULL,
  `Volume` float DEFAULT NULL,
  `Adj Close` float DEFAULT NULL,
  `Time Series Type` enum('Data','Forecast','First','Current','Target') NOT NULL,
  `Time Series Metadata` varchar(4) NOT NULL,
  `Time Series Tag` varchar(1) NOT NULL,
  UNIQUE KEY `Constraction` (`Time Series Date`,`Time Series Frequency`,`Time Series Name`(16),`Time Series Name Key`,`Time Series Name Second Key`,`Time Series Type`),
  KEY `Time Series Type` (`Time Series Type`),
  KEY `Time Series Parent Key` (`Time Series Parent Key`),
  KEY `Time Series Date` (`Time Series Date`),
  KEY `Time Series Frequency` (`Time Series Frequency`),
  KEY `Time Series Name` (`Time Series Name`(24)),
  KEY `Time Series Name Key` (`Time Series Name Key`),
  KEY `Time Series Label` (`Time Series Label`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Timeseries Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Timeseries Dimension` (
  `Timeseries Key` smallint(5) unsigned NOT NULL,
  `Timeseries Type` varchar(64) NOT NULL,
  `Timeseries Frequency` enum('Daily','Weekly','Monthly','Quarterly','Yearly') NOT NULL,
  `Timeseries Parent` varchar(32) DEFAULT NULL,
  `Timeseries Parent Key` mediumint(8) unsigned NOT NULL,
  `Timeseries Scope` varchar(32) NOT NULL,
  `Timeseries From` date DEFAULT NULL,
  `Timeseries To` date DEFAULT NULL,
  `Timeseries Number Records` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Timeseries Created` datetime DEFAULT NULL,
  `Timeseries Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Timeseries Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Timeseries Record Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Timeseries Record Dimension` (
  `Timeseries Record Key` mediumint(8) unsigned NOT NULL,
  `Timeseries Record Timeseries Key` smallint(6) NOT NULL,
  `Timeseries Record Date` date NOT NULL,
  `Timeseries Record Float A` float DEFAULT NULL,
  `Timeseries Record Float B` float DEFAULT NULL,
  `Timeseries Record Float C` float DEFAULT NULL,
  `Timeseries Record Float D` float DEFAULT NULL,
  `Timeseries Record Integer A` mediumint(9) DEFAULT NULL,
  `Timeseries Record Integer B` mediumint(9) DEFAULT NULL,
  `Timeseries Record Type` enum('Data','Forecast','First','Current','Target') DEFAULT NULL,
  PRIMARY KEY (`Timeseries Record Key`),
  KEY `Time Series Date` (`Timeseries Record Date`),
  KEY `Timeseries Key` (`Timeseries Record Timeseries Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Timesheet Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Timesheet Dimension` (
  `Timesheet Key` mediumint(8) unsigned NOT NULL,
  `Timesheet Date` date NOT NULL,
  `Timesheet Type` enum('WorkingDay','DayOff','NoFixedWorkingHours') DEFAULT 'NoFixedWorkingHours',
  `Timesheet Staff Key` mediumint(8) unsigned NOT NULL,
  `Timesheet Working Hours Records` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Timesheet Break Records` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Timesheet Overtime Records` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Timesheet Clocking Records` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Timesheet Ignored Clocking Records` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Timesheet Missing Clocking Records` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Timesheet Clocked Time` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Timesheet Working Time` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Timesheet Breaks Time` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Timesheet Unpaid Overtime` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Timesheet Paid Overtime` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Timesheet Monday Clocked Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Tuesday Clocked Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Wednesday Clocked Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Thursday Clocked Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Friday Clocked Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Saturday Clocked Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Sunday Clocked Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Monday Working Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Tuesday Working Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Wednesday Working Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Thursday Working Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Friday Working Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Saturday Working Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Sunday Working Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Monday Breaks Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Tuesday Breaks Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Wednesday Breaks Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Thursday Breaks Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Friday Breaks Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Saturday Breaks Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Sunday Breaks Time` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Monday Unpaid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Tuesday Unpaid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Wednesday Unpaid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Thursday Unpaid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Friday Unpaid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Saturday Unpaid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Sunday Unpaid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Monday Paid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Tuesday Paid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Wednesday Paid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Thursday Paid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Friday Paid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Saturday Paid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `Timesheet Sunday Paid Overtime` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Timesheet Key`),
  UNIQUE KEY `Timesheet Date` (`Timesheet Date`,`Timesheet Staff Key`),
  KEY `Timesheet Date_2` (`Timesheet Date`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Timesheet Record Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Timesheet Record Dimension` (
  `Timesheet Record Key` mediumint(9) NOT NULL,
  `Timesheet Record Timesheet Key` mediumint(8) unsigned DEFAULT NULL,
  `Timesheet Record Type` enum('WorkingHoursMark','OvertimeMark','ClockingRecord','BreakMark') NOT NULL DEFAULT 'ClockingRecord',
  `Timesheet Record Ignored` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Timesheet Record Ignored Due Missing End` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Timesheet Record Action Type` enum('MarkStart','Start','End','MarkEnd','Ignored','Unknown') NOT NULL DEFAULT 'Unknown',
  `Timesheet Record Staff Key` mediumint(8) unsigned NOT NULL,
  `Timesheet Record Date` datetime NOT NULL,
  `Timesheet Record Source` enum('ClockingMachine','Manual','API','System') DEFAULT 'Manual',
  `Timesheet Authoriser Key` mediumint(8) unsigned DEFAULT NULL,
  `Timesheet Remover Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Timesheet Record Key`),
  UNIQUE KEY `Staff Key` (`Timesheet Record Staff Key`,`Timesheet Record Date`,`Timesheet Record Type`),
  KEY `Timesheet Key` (`Timesheet Record Timesheet Key`),
  KEY `Timesheet Record Ignored Due Missing End` (`Timesheet Record Ignored Due Missing End`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Upload Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Upload Dimension` (
  `Upload Key` mediumint(8) unsigned NOT NULL,
  `Upload Type` enum('NewObjects','EditObjects') NOT NULL DEFAULT 'NewObjects',
  `Upload State` enum('Uploaded','InProcess','Finished','Cancelled') NOT NULL DEFAULT 'Uploaded',
  `Upload Parent` varchar(16) NOT NULL,
  `Upload Parent Key` mediumint(8) unsigned NOT NULL,
  `Upload Object` varchar(16) NOT NULL,
  `Upload User Key` mediumint(8) unsigned DEFAULT NULL,
  `Upload Created` datetime DEFAULT NULL,
  `Upload Records` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Upload OK` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Upload No Change` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Upload Warnings` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Upload Errors` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Upload Metadata` text,
  PRIMARY KEY (`Upload Key`),
  KEY `Upload Type` (`Upload Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Upload File Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Upload File Dimension` (
  `Upload File Key` mediumint(8) unsigned NOT NULL,
  `Upload File Upload Key` mediumint(8) unsigned DEFAULT NULL,
  `Upload File Checksum` varchar(64) DEFAULT NULL,
  `Upload File Name` varchar(255) NOT NULL,
  `Upload File Size` int(10) unsigned NOT NULL,
  `Upload File Metadata` text,
  PRIMARY KEY (`Upload File Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Upload Record Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Upload Record Dimension` (
  `Upload Record Key` int(10) unsigned NOT NULL,
  `Upload Record Upload Key` mediumint(8) unsigned NOT NULL,
  `Upload Record Upload File Key` smallint(5) unsigned DEFAULT NULL,
  `Upload Record Row Index` int(10) unsigned NOT NULL,
  `Upload Record Status` enum('InProcess','Done') NOT NULL DEFAULT 'InProcess',
  `Upload Record State` enum('InProcess','OK','Error','Warning','Cancelled','NoChange') NOT NULL DEFAULT 'InProcess',
  `Upload Record Date` datetime DEFAULT NULL,
  `Upload Record Message Code` varchar(255) DEFAULT NULL,
  `Upload Record Message Metadata` text,
  `Upload Record Data` longblob NOT NULL,
  `Upload Record Object Key` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`Upload Record Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Agent Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Agent Dimension` (
  `User Agent Key` mediumint(8) unsigned NOT NULL,
  `User Agent Name` varchar(255) DEFAULT NULL,
  `User Agent String` text NOT NULL,
  `User Agent Description` text,
  `User Agent Type` enum('Browser','Bot','Spam','Proxy','Other','Librarie','Cloud Platform','Feed Reader','Offline Browser','Console','LineChecker','Mobile Browser','Validator','E-Mail Collector') DEFAULT NULL,
  `User Agent Family` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`User Agent Key`),
  KEY `User Agent Type` (`User Agent Type`),
  KEY `User Agent String` (`User Agent String`(128))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Deleted Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Deleted Dimension` (
  `User Deleted Key` mediumint(8) unsigned NOT NULL,
  `User Deleted Handle` varchar(255) NOT NULL,
  `User Deleted Alias` varchar(120) NOT NULL,
  `User Deleted Type` enum('Customer','Staff','Supplier','Administrator','Warehouse','Contractor','Agent') NOT NULL,
  `User Deleted Date` datetime NOT NULL,
  `User Deleted Metadata` blob NOT NULL,
  PRIMARY KEY (`User Deleted Key`),
  KEY `User Deleted Handle` (`User Deleted Handle`),
  KEY `User Deleted Alias` (`User Deleted Alias`),
  KEY `User Deleted Type` (`User Deleted Type`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Dimension` (
  `User Key` mediumint(6) unsigned NOT NULL,
  `User Handle` varchar(255) NOT NULL,
  `User Password` varchar(128) DEFAULT NULL,
  `User Active` enum('Yes','No') NOT NULL DEFAULT 'No',
  `User Alias` varchar(120) DEFAULT NULL,
  `User Type` enum('Customer','Staff','Supplier','Administrator','Warehouse','Contractor','Agent') NOT NULL,
  `User Staff Type` enum('Working','NotWorking') DEFAULT NULL,
  `User Site Key` smallint(5) unsigned NOT NULL DEFAULT '0',
  `User Parent Key` mediumint(9) unsigned DEFAULT NULL,
  `User Preferred Locale` varchar(12) NOT NULL DEFAULT 'en_GB.UTF-8',
  `User Has Login` enum('Yes','No') NOT NULL DEFAULT 'No',
  `User Login Count` smallint(6) NOT NULL DEFAULT '0',
  `User Last Login` datetime DEFAULT NULL,
  `User Last Login IP` varchar(64) DEFAULT NULL,
  `User Failed Login Count` smallint(6) unsigned NOT NULL DEFAULT '0',
  `User Last Failed Login IP` varchar(64) DEFAULT NULL,
  `User Last Failed Login` datetime DEFAULT NULL,
  `User Sessions Count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `User Requests Count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `User Last Request` datetime DEFAULT NULL,
  `User Created` datetime NOT NULL,
  `User Verified` enum('Yes','No') NOT NULL DEFAULT 'No',
  `User Main Image Key` mediumint(8) DEFAULT NULL,
  `User Inactive Note` text NOT NULL,
  `User Password Recovery Email` varchar(255) DEFAULT NULL,
  `User Password Recovery Mobile` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`User Key`),
  UNIQUE KEY `User Handle` (`User Handle`,`User Type`,`User Site Key`),
  KEY `h` (`User Handle`),
  KEY `User Password` (`User Password`),
  KEY `User Type` (`User Type`),
  KEY `User Site Key` (`User Site Key`),
  KEY `User Staff Type` (`User Staff Type`),
  KEY `User Parent Key` (`User Parent Key`),
  KEY `User Login Count` (`User Login Count`),
  KEY `User Has Login` (`User Has Login`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Failed Log Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Failed Log Dimension` (
  `Handle` varchar(255) DEFAULT NULL,
  `Login Page` enum('staff','supplier','customer') NOT NULL,
  `User Key` mediumint(8) unsigned DEFAULT NULL,
  `Date` datetime NOT NULL,
  `IP` varchar(64) NOT NULL,
  `Fail Main Reason` enum('cookie_error','handle','password','logging_timeout','ip','ikey','masterkey_not_found','masterkey_used','masterkey_expired') NOT NULL,
  `Handle OK` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  `Password OK` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  `Logging On Time OK` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  `IP OK` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  `IKey OK` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  KEY `Fail Main Reason` (`Fail Main Reason`),
  KEY `Handle` (`Handle`(64)),
  KEY `User Key` (`User Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Group Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Group Dimension` (
  `User Group Key` smallint(6) unsigned NOT NULL,
  `User Group Name` varchar(255) NOT NULL,
  `User Group Description` text,
  PRIMARY KEY (`User Group Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Group Rights Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Group Rights Bridge` (
  `Group Key` mediumint(8) unsigned NOT NULL,
  `Right Key` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `id_i_idx` (`Group Key`,`Right Key`),
  KEY `Group Key` (`Group Key`),
  KEY `Right Key` (`Right Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Group User Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Group User Bridge` (
  `User Key` int(11) DEFAULT '0',
  `User Group Key` int(11) DEFAULT '0',
  UNIQUE KEY `id_i_idx` (`User Key`,`User Group Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User History Bridge` (
  `User Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') NOT NULL,
  PRIMARY KEY (`User Key`,`History Key`),
  KEY `User Key` (`User Key`),
  KEY `History Key` (`History Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Log Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Log Dimension` (
  `User Log Key` mediumint(8) unsigned NOT NULL,
  `Status` enum('Open','Close') NOT NULL DEFAULT 'Open',
  `User Key` mediumint(8) unsigned NOT NULL,
  `Site Key` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Session ID` varchar(40) NOT NULL,
  `IP` varchar(64) NOT NULL,
  `Start Date` datetime NOT NULL,
  `Last Visit Date` datetime DEFAULT NULL,
  `Logout Date` datetime DEFAULT NULL,
  `Remember Cookie` enum('Yes','No','Unknown') NOT NULL DEFAULT 'Unknown',
  PRIMARY KEY (`User Log Key`),
  KEY `User Key` (`User Key`),
  KEY `Remember Cookie` (`Remember Cookie`),
  KEY `Status` (`Status`),
  KEY `Site Key` (`Site Key`),
  KEY `Session ID` (`Session ID`(32))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Request Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Request Dimension` (
  `User Request Key` bigint(20) unsigned NOT NULL,
  `Is User` enum('Yes','No') NOT NULL DEFAULT 'No',
  `User Key` mediumint(8) NOT NULL,
  `User Log Key` mediumint(8) unsigned DEFAULT NULL,
  `Visitor Key` mediumint(9) DEFAULT NULL,
  `Visitor Session Key` mediumint(9) DEFAULT NULL,
  `Site Key` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Page Key` mediumint(10) NOT NULL,
  `Date` datetime NOT NULL,
  `User Request Referral Page URL Key` mediumint(8) unsigned DEFAULT NULL,
  `User Agent Key` bigint(20) DEFAULT NULL,
  `IP` varchar(64) NOT NULL,
  PRIMARY KEY (`User Request Key`,`Date`),
  KEY `User Agent Key` (`User Agent Key`),
  KEY `Date` (`Date`),
  KEY `Page Key` (`Page Key`),
  KEY `User Key` (`User Key`),
  KEY `User Log Key` (`User Log Key`),
  KEY `Site Key` (`Site Key`),
  KEY `Is User` (`Is User`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Right Scope Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Right Scope Bridge` (
  `User Key` mediumint(9) NOT NULL,
  `Scope` enum('Store','Warehouse','Supplier','Website') NOT NULL DEFAULT 'Store',
  `Scope Key` mediumint(9) NOT NULL,
  PRIMARY KEY (`User Key`,`Scope Key`,`Scope`),
  KEY `Scope` (`Scope`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Rights Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Rights Bridge` (
  `User Key` mediumint(8) unsigned NOT NULL,
  `Right Code` varchar(8) NOT NULL,
  UNIQUE KEY `id_i_idx` (`User Key`,`Right Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User Staff Settings Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User Staff Settings Dimension` (
  `User Key` mediumint(8) unsigned NOT NULL,
  `User PIN` varchar(255) NOT NULL DEFAULT '1234',
  `User Theme Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `User Theme Background Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `User Dashboard Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `User Hooked Store Key` mediumint(9) DEFAULT NULL,
  `User Hooked Site Key` mediumint(9) DEFAULT NULL,
  `User Hooked Warehouse Key` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`User Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User System View Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User System View Fact` (
  `User System View Key` int(10) unsigned NOT NULL,
  `User Key` mediumint(9) NOT NULL,
  `Date` datetime NOT NULL,
  `Module` varchar(255) DEFAULT NULL,
  `Section` varchar(255) DEFAULT NULL,
  `Tab` varchar(255) DEFAULT NULL,
  `Parent` varchar(255) DEFAULT NULL,
  `Parent Key` mediumint(9) DEFAULT NULL,
  `Object` varchar(255) DEFAULT NULL,
  `Object Key` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`User System View Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Visitor Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Visitor Dimension` (
  `Visitor Key` int(11) NOT NULL,
  `Visitor Site Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Visitor Key`),
  KEY `User Visitor Site Key` (`Visitor Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Visitor Session Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Visitor Session Dimension` (
  `Visitor Session Key` int(10) unsigned NOT NULL,
  `Visitor Session Site Key` mediumint(8) unsigned NOT NULL,
  `Visitor Session Visitor Key` mediumint(8) unsigned NOT NULL,
  `Visitor Session Start Date` datetime NOT NULL,
  `Visitor Session Last Request Date` datetime NOT NULL,
  PRIMARY KEY (`Visitor Session Key`),
  KEY `User Session Visitor Key` (`Visitor Session Visitor Key`),
  KEY `User Session Site Key` (`Visitor Session Site Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Voucher Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Voucher Dimension` (
  `Voucher Key` mediumint(8) unsigned NOT NULL,
  `Voucher Store Key` mediumint(8) unsigned NOT NULL,
  `Voucher Deal Key` mediumint(8) unsigned DEFAULT NULL,
  `Voucher Code` varchar(32) NOT NULL,
  `Voucher Status` enum('Active','Finish') NOT NULL DEFAULT 'Active',
  `Voucher Type` enum('Public','Private') NOT NULL DEFAULT 'Public',
  `Voucher Subject Type` enum('Order','Customer') NOT NULL DEFAULT 'Order',
  `Voucher Subject Key Metadata` mediumint(8) unsigned DEFAULT NULL,
  `Voucher Usage Limit per Customer` smallint(5) unsigned DEFAULT NULL,
  `Voucher Total Acc Used Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Voucher Total Acc Used Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Voucher Key`),
  UNIQUE KEY `Voucher Store Key` (`Voucher Store Key`,`Voucher Code`),
  KEY `Voucher Status` (`Voucher Status`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Voucher Order Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Voucher Order Bridge` (
  `Voucher Key` mediumint(8) unsigned NOT NULL,
  `Order Key` mediumint(8) unsigned NOT NULL,
  `Date` datetime DEFAULT NULL,
  `Deal Key` mediumint(8) unsigned NOT NULL,
  `Customer Key` mediumint(8) unsigned DEFAULT NULL,
  `State` enum('In Process','Cancelled','Consolidated') NOT NULL DEFAULT 'In Process',
  UNIQUE KEY `Voucher Key` (`Voucher Key`,`Order Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Warehouse Area Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Warehouse Area Dimension` (
  `Warehouse Area Key` smallint(5) unsigned NOT NULL,
  `Warehouse Area Code` varchar(16) NOT NULL,
  `Warehouse Area Name` varchar(64) NOT NULL,
  `Warehouse Area Description` text,
  `Warehouse Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Warehouse Area Number Shelfs` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Warehouse Area Number Locations` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Warehouse Area Distinct Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Warehouse Area Key`),
  UNIQUE KEY `Warehouse Area Code_2` (`Warehouse Area Code`,`Warehouse Key`),
  KEY `Warehouse Area Code` (`Warehouse Area Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Warehouse Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Warehouse Dimension` (
  `Warehouse Key` mediumint(8) unsigned NOT NULL,
  `Warehouse Code` varchar(16) NOT NULL,
  `Warehouse Name` varchar(255) NOT NULL,
  `Warehouse State` enum('Active','Closed') NOT NULL DEFAULT 'Active',
  `Warehouse Valid From` datetime DEFAULT NULL,
  `Warehouse Valid To` datetime DEFAULT NULL,
  `Warehouse Address` text NOT NULL,
  `Warehouse Company Name` varchar(255) NOT NULL,
  `Warehouse Company Number` varchar(255) NOT NULL,
  `Warehouse VAT Number` varchar(255) NOT NULL,
  `Warehouse Telephone` varchar(255) NOT NULL,
  `Warehouse Email` varchar(255) NOT NULL,
  `Warehouse Total Area` float DEFAULT NULL,
  `Warehouse SVG Map` longtext,
  `Warehouse Number Areas` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Warehouse Number Shelfs` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Warehouse Number Locations` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Warehouse Number Parts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Warehouse Number Stock Movements` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Warehouse Total Average Dispatch Time` float DEFAULT NULL,
  `Warehouse 3 Year Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse 1 Year Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse Year To Day Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse Month To Day Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse Week To Day Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse 6 Month Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse 1 Quarter Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse 1 Month Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse 10 Day Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse 1 Week Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse Today Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse Yesterday Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse Last Week Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse Last Month Acc Average Dispatch Time` float DEFAULT NULL,
  `Warehouse Picking Aid Type` enum('Inikoo','Static') NOT NULL DEFAULT 'Inikoo',
  `Warehouse Assign Operations Locked` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Warehouse Approve PP Locked` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Warehouse Unlock PIN` varchar(64) NOT NULL DEFAULT '1234',
  `Warehouse Family Category Key` mediumint(9) DEFAULT NULL,
  `Warehouse Excess Availability Days Limit` smallint(5) unsigned NOT NULL DEFAULT '120',
  `Warehouse Supplier Delivery Days` smallint(5) unsigned NOT NULL DEFAULT '30',
  `Warehouse Default Flag Color` enum('Blue','Green','Orange','Pink','Purple','Red','Yellow') NOT NULL DEFAULT 'Blue',
  `Warehouse Default PO Terms and Conditions` text,
  PRIMARY KEY (`Warehouse Key`),
  UNIQUE KEY `Warehouse Code` (`Warehouse Code`),
  UNIQUE KEY `Warehouse Name` (`Warehouse Name`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Warehouse Flag Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Warehouse Flag Dimension` (
  `Warehouse Flag Key` mediumint(8) unsigned NOT NULL,
  `Warehouse Key` smallint(5) unsigned NOT NULL,
  `Warehouse Flag Color` enum('Blue','Green','Orange','Pink','Purple','Red','Yellow') NOT NULL,
  `Warehouse Flag Label` varchar(16) NOT NULL,
  `Warehouse Flag Number Locations` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Warehouse Flag Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`Warehouse Flag Key`),
  UNIQUE KEY `Warehouse Key` (`Warehouse Key`,`Warehouse Flag Color`),
  KEY `Warehouse Key_2` (`Warehouse Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Warehouse History Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Warehouse History Bridge` (
  `Warehouse Key` mediumint(8) unsigned NOT NULL,
  `History Key` int(10) unsigned NOT NULL,
  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Type` enum('Notes','Changes','Attachments') NOT NULL,
  PRIMARY KEY (`Warehouse Key`,`History Key`),
  KEY `Warehouse Key` (`Warehouse Key`),
  KEY `History Key` (`History Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Warehouse Receipts Fact`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Warehouse Receipts Fact` (
  `Warehouse Receipts Key` mediumint(8) unsigned NOT NULL,
  `Warehouse Receipt Date` date NOT NULL,
  `Requested Date` date NOT NULL,
  `Part Key` mediumint(8) unsigned DEFAULT NULL,
  `Supplier Key` mediumint(8) unsigned NOT NULL,
  `Supplier Product Key` mediumint(9) DEFAULT NULL,
  `Received Condition Key` mediumint(9) NOT NULL,
  `Warehouse Clerk` mediumint(9) NOT NULL,
  `Purchase Requsition Number` varchar(255) NOT NULL,
  `Purchase Order Number` varchar(255) NOT NULL,
  `Shipping Notification Number` varchar(255) NOT NULL,
  `Received Quantity` float NOT NULL,
  PRIMARY KEY (`Warehouse Receipts Key`),
  KEY `Supplier Product Key` (`Supplier Product Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Webpage Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Webpage Dimension` (
  `Webpage Key` smallint(5) unsigned NOT NULL,
  `Webpage Website Key` smallint(5) unsigned NOT NULL,
  `Webpage Store Key` smallint(5) unsigned NOT NULL,
  `Webpage Website Node Key` smallint(5) unsigned NOT NULL,
  `Webpage Code` varchar(255) NOT NULL,
  `Webpage Name` varchar(128) NOT NULL,
  `Webpage Status` enum('Online','Offline') NOT NULL DEFAULT 'Online',
  `Webpage Class` varchar(255) DEFAULT NULL,
  `Webpage Object` varchar(64) DEFAULT NULL,
  `Webpage Object Key` mediumint(8) unsigned DEFAULT NULL,
  `Webpage Version Key` mediumint(8) unsigned DEFAULT NULL,
  `Webpage Number Displayable Versions` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Webpage Valid From` datetime DEFAULT NULL,
  `Webpage Locked` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Webpage Key`),
  UNIQUE KEY `Webpage Website Key` (`Webpage Website Node Key`,`Webpage Code`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Webpage Related Product Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Webpage Related Product Bridge` (
  `Webpage Related Product Page Key` mediumint(8) unsigned NOT NULL,
  `Webpage Related Product Product ID` mediumint(8) unsigned NOT NULL,
  `Webpage Related Product Product Page Key` mediumint(8) unsigned NOT NULL,
  `Webpage Related Product Order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`Webpage Related Product Page Key`,`Webpage Related Product Product ID`),
  KEY `Webpage Related Product Page Key` (`Webpage Related Product Page Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Webpage Version Block Bridge`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Webpage Version Block Bridge` (
  `Webpage Version Block Key` int(10) unsigned NOT NULL,
  `Webpage Version Block Webpage Version Key` mediumint(8) unsigned NOT NULL,
  `Webpage Version Block Position` smallint(5) unsigned NOT NULL DEFAULT '10',
  `Webpage Version Block Template` varchar(255) NOT NULL,
  `Webpage Version Block Objects` text,
  `Webpage Version Block Settings` text NOT NULL,
  PRIMARY KEY (`Webpage Version Block Key`),
  KEY `Webpage Block Website Key` (`Webpage Version Block Webpage Version Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Webpage Version Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Webpage Version Dimension` (
  `Webpage Version Key` mediumint(9) NOT NULL,
  `Webpage Version Webpage Key` mediumint(9) NOT NULL,
  `Webpage Version Code` varchar(64) DEFAULT NULL,
  `Webpage Version Display Probability` float NOT NULL DEFAULT '1',
  `Webpage Version Metadata` text,
  `Webpage Version Valid From` datetime NOT NULL,
  PRIMARY KEY (`Webpage Version Key`),
  KEY `Webpage Version Webpage Key` (`Webpage Version Webpage Key`),
  KEY `Webpage Version Code` (`Webpage Version Code`,`Webpage Version Webpage Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Website Data`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Website Data` (
  `Website Key` smallint(5) unsigned NOT NULL,
  `Website Total Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website Total Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Total Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Total Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 3 Year Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 3 Year Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 3 Year Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 3 Year Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Year Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Year Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Year Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Year Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 6 Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 6 Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 6 Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 6 Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Quarter Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Quarter Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Quarter Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Quarter Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 10 Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 10 Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 10 Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 10 Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Week Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Week Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Week Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Week Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Hour Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Hour Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Hour Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Hour Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website Today Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website Today Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Today Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Today Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website Year To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website Year To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Year To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Year To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website Month To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website Month To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Month To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Month To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website Week To Day Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website Week To Day Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Week To Day Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Week To Day Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Month Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Month Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Month Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Week Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Week Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Week Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Week Month Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website Yesterday Acc Visitors` mediumint(9) NOT NULL DEFAULT '0',
  `Website Yesterday Acc Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Yesterday Acc Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Yesterday Acc Users` mediumint(9) NOT NULL DEFAULT '0',
  `Website Total Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Total Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 3 Year Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 3 Year Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Year Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Year Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 6 Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 6 Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Yesterday Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Yesterday Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Quarter Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Quarter Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 10 Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 10 Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Week Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Week Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Hour Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website 1 Hour Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Today Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Today Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Year To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Year To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Month To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Month To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Week To Day Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Week To Day Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Month Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Month Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Week Acc Users Requests` mediumint(9) NOT NULL DEFAULT '0',
  `Website Last Week Acc Users Sessions` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Website Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Website Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Website Dimension` (
  `Website Key` smallint(5) unsigned NOT NULL,
  `Website Store Key` smallint(5) unsigned NOT NULL,
  `Website From` datetime DEFAULT NULL,
  `Website Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Website Code` varchar(8) NOT NULL,
  `Website Name` varchar(128) NOT NULL,
  `Website URL` varchar(255) NOT NULL,
  `Website Locale` varchar(5) NOT NULL DEFAULT 'en_GB',
  `Website Number Webpages` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Website Number Webpages with Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Website Number Webpages with Out of Stock Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Website Number Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Website Number Out of Stock Products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Website Number Users` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Website Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Website Node Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Website Node Dimension` (
  `Website Node Key` mediumint(8) unsigned NOT NULL,
  `Website Node Store Key` mediumint(8) unsigned NOT NULL,
  `Website Node Website Key` mediumint(8) unsigned NOT NULL,
  `Website Node Parent Key` mediumint(8) unsigned DEFAULT NULL,
  `Website Node Class` varchar(64) DEFAULT NULL,
  `Website Node Object` varchar(64) DEFAULT NULL,
  `Website Node Object Key` mediumint(8) unsigned DEFAULT NULL,
  `Website Node Type` enum('Root','Branch','Head') NOT NULL,
  `Website Node Webpage Key` mediumint(8) unsigned DEFAULT NULL,
  `Website Node Icon` varchar(255) DEFAULT NULL,
  `Website Node Locked` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Website Node Valid From` datetime DEFAULT NULL,
  PRIMARY KEY (`Website Node Key`),
  KEY `Website Node Website Key` (`Website Node Website Key`),
  KEY `Website Node Parent Key` (`Website Node Parent Key`),
  KEY `Website Node Webpage Key` (`Website Node Webpage Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Widget Dimension`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Widget Dimension` (
  `Widget Key` mediumint(8) NOT NULL,
  `Widget Name` varchar(255) NOT NULL,
  `Widget Block` varchar(255) NOT NULL,
  `Widget Dimension` varchar(255) DEFAULT NULL,
  `Widget URL` varchar(255) NOT NULL,
  `Widget Description` varchar(255) NOT NULL,
  `Widget Metadata` varchar(1024) NOT NULL,
  PRIMARY KEY (`Widget Key`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `debugtable`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `debugtable` (
  `id` int(11) NOT NULL,
  `text` longtext NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `todo_users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todo_users` (
  `id` mediumint(8) unsigned NOT NULL,
  `name` varchar(120) CHARACTER SET latin1 NOT NULL,
  `order_name` varchar(60) CHARACTER SET latin1 NOT NULL,
  `tipo` varchar(20) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-12  4:25:41
