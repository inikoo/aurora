<?php
// DB
// 12 oct 2009 Product Image Bridge =>  Image Bridge
// 13 oct 2009 kbase.Saludation Language Key -> Language Code
// ALTER TABLE `Salutation Dimension` CHANGE `Language Key` `Language Code` VARCHAR( 3 ) NOT NULL DEFAULT 'en'
// UPDATE `kbase`.`Salutation Dimension` SET `Language Code` = 'en' WHERE `Salutation Dimension`.`Language Code` =1 ;
//  UPDATE `kbase`.`Salutation Dimension` SET `Language Code` = 'es' WHERE `Salutation Dimension`.`Language Code` =2 ;

// Edited Country dimsion (regex postal codes)
/*

ALTER TABLE `Product Department Dimension` ADD `Product Department Total Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department Total Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department Total Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department Total Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department Total Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department Total Customers` ;

ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Year Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Year Acc Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Year Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Year Acc Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Year Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Year Acc Customers` ;

ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Quarter Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Quarter Acc Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Quarter Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Quarter Acc Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Quarter Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Quarter Acc Customers` ;

ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Month Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Month Acc Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Month Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Month Acc Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Month Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Month Acc Customers` ;

ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Week Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Week Acc Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Week Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Week Acc Days Available` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Week Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department 1 Week Acc Customers` ;


ALTER TABLE `Product Family Dimension` ADD `Product Family Total Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family Total Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family Total Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family Total Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family Total Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family Total Customers` ;

ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Year Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Year Acc Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Year Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Year Acc Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Year Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Year Acc Customers` ;

ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Quarter Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Quarter Acc Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Quarter Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Quarter Acc Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Quarter Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Quarter Acc Customers` ;

ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Month Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Month Acc Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Month Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Month Acc Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Month Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Month Acc Customers` ;

ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Week Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Week Acc Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Week Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Week Acc Days Available` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Week Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family 1 Week Acc Customers` ;


ALTER TABLE `Store Dimension` ADD `Store Total Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store Total Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store Total Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Customers` ;

ALTER TABLE `Store Dimension` ADD `Store 1 Year Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Year Acc Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Year Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Year Acc Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Year Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Year Acc Customers` ;

ALTER TABLE `Store Dimension` ADD `Store 1 Quarter Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Quarter Acc Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Quarter Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Quarter Acc Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Quarter Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Quarter Acc Customers` ;

ALTER TABLE `Store Dimension` ADD `Store 1 Month Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Month Acc Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Month Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Month Acc Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Month Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Month Acc Customers` ;

ALTER TABLE `Store Dimension` ADD `Store 1 Week Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Week Acc Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Week Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Week Acc Days Available` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Week Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store 1 Week Acc Customers` ;

ALTER TABLE `Product Dimension` ADD `Product Total Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Total Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product Total Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Total Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product Total Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Total Customers` ;

ALTER TABLE `Product Dimension` ADD `Product 1 Year Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Year Acc Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product 1 Year Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Year Acc Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product 1 Year Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Year Acc Customers` ;

ALTER TABLE `Product Dimension` ADD `Product 1 Quarter Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Quarter Acc Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product 1 Quarter Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Quarter Acc Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product 1 Quarter Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Quarter Acc Customers` ;

ALTER TABLE `Product Dimension` ADD `Product 1 Month Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Month Acc Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product 1 Month Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Month Acc Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product 1 Month Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Month Acc Customers` ;

ALTER TABLE `Product Dimension` ADD `Product 1 Week Acc Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Week Acc Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product 1 Week Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Week Acc Days Available` ;
ALTER TABLE `Product Dimension` ADD `Product 1 Week Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product 1 Week Acc Customers` ;



ALTER TABLE `Order Dimension` ADD `Order Currency` VARCHAR( 3 ) NOT NULL DEFAULT 'GBP',ADD `Order Currency Exchange` FLOAT NOT NULL DEFAULT '1';
ALTER TABLE `Product Family Dimension` ADD `Product Family Currency Code` VARCHAR( 3 ) NOT NULL DEFAULT 'GBP';
ALTER TABLE `Product Department Dimension` ADD `Product Department Currency Code` VARCHAR( 3 ) NOT NULL DEFAULT 'GBP';

ALTER TABLE `History Dimension` CHANGE `Direct Object` `Direct Object` ENUM( 'User', 'Product', 'Address', 'Customer', 'Note', 'Order', 'Telecom', 'Email', 'Company', 'Contact', 'FAX', 'Telephone', 'Mobile', 'Work Telephone', 'Office Fax', 'Supplier' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
ALTER TABLE `Part Dimension` ADD `Part Distinct Locations` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Part Current Storing Cost` 


ALTER TABLE `Order Dimension` ADD `Order Shipping Tax Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0' AFTER `Order Shipping Net Amount` ;
ALTER TABLE `Order Dimension` ADD `Order Charges Tax Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0' AFTER `Order Charges Net Amount` ;

ALTER TABLE `Supplier Dimension` ADD `Supplier Main Plain WWW` VARCHAR( 256 ) NOT NULL AFTER `Supplier Main Plain Telephone` ;
ALTER TABLE `Supplier Dimension` ADD `Supplier Main XHTML FAX` VARCHAR( 100 ) NULL AFTER `Supplier Main Plain Telephone` ,
ADD `Supplier Main FAX Key` MEDIUMINT UNSIGNED NULL AFTER `Supplier Main XHTML FAX` ,
ADD `Supplier Main Plain FAX` VARCHAR( 100 ) NOT NULL DEFAULT '' AFTER `Supplier Main FAX Key` ;
 ALTER TABLE `Inventory Transaction Fact` ADD `Event Order` TINYINT UNSIGNED NOT NULL DEFAULT '0';
 ALTER TABLE `Part Dimension` ADD `Part XHTML Picking Location` VARCHAR( 256 ) NOT NULL AFTER `Part Current Storing Cost` 
ALTER TABLE `Product Dimension` ADD `Product Main Image` VARCHAR( 255 ) NOT NULL DEFAULT 'art/nopic.png' AFTER `Product XHTML Short Description` ;
ALTER TABLE `dw`.`Image Bridge` DROP INDEX `Subject Type` ,
ADD INDEX `Subject Type` ( `Subject Type` ) 

ALTER TABLE `Product Department Dimension` ADD `Product Department Total Avg Week Sales Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Year Acc Avg Week Sales Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Quarter Acc Avg Week Sales Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Month Acc Avg Week Sales Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Week Acc Avg Week Sales Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';

ALTER TABLE `Product Department Dimension` ADD `Product Department Total Avg Week Profit Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Year Acc Avg Week Profit Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Quarter Acc Avg Week Profit Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Month Acc Avg Week Profit Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Week Acc Avg Week Profit Per Family` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';


ALTER TABLE `Product Department Dimension` ADD `Product Department Total Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Year Acc Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Quarter Acc Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Month Acc Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Week Acc Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';

ALTER TABLE `Product Department Dimension` ADD `Product Department Total Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Year Acc Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Quarter Acc Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Month Acc Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Department Dimension` ADD `Product Department 1 Week Acc Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';


ALTER TABLE `Product Family Dimension` ADD `Product Family Total Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Year Acc Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Quarter Acc Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Month Acc Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Week Acc Avg Week Sales Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';

ALTER TABLE `Product Family Dimension` ADD `Product Family Total Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Year Acc Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Quarter Acc Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Month Acc Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family 1 Week Acc Avg Week Profit Per Product` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `Product Family Dimension` ADD `Product Family Sales State` ENUM( 'For sale', 'Out of Stock', 'Not for Sale', 'Discontinued', 'Unknown', 'No Applicable' ) NOT NULL DEFAULT 'For sale' AFTER `Product Family Main Department Name` 


ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Number Parcels` SMALLINT UNSIGNED  DEFAULT NULL,
ADD `Delivery Note Parcel Type` ENUM( 'Box', 'Pallet', 'Envelope' ) NOT NULL DEFAULT 'Box'

ALTER TABLE `Customer Dimension` CHANGE `Customer Delivery Address Link` `Customer Delivery Address Link` ENUM( 'Contact', 'Billing', 'None' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Billing';
ALTER TABLE `Order Dimension` ADD `Order Tax Rate` DECIMAL( 8, 6 ) NOT NULL ,ADD `Order Tax Code` VARCHAR( 16 ) NOT NULL ;
ALTER TABLE `Customer Dimension` CHANGE `Customer tax Category` `Customer Tax Category` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Default';
CREATE TABLE `costadw`.`Tax Category Dimension` (`Tax Category Code` VARCHAR( 16 ) NOT NULL ,`Tax Category Name` VARCHAR( 256 ) NOT NULL ,`Tax Category Rate` DECIMAL( 8, 6 ) NOT NULL ,PRIMARY KEY ( `Tax Category Code` )) ENGINE = MYISAM ;
ALTER TABLE `Tax Category Dimension` CHANGE `Tax Category Code` `Tax Category Code` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , CHANGE `Tax Category Name` `Tax Category Name` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;ALTER TABLE `Tax Category Dimension`  DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Order Dimension` CHANGE `Orders Items Tax Amount` `Order Items Tax Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00'

ALTER TABLE `Purchase Order Dimension` ADD `Purchase Order Cancelled Date` DATETIME NULL DEFAULT NULL AFTER `Purchase Order Consolidated Date` ;
ALTER TABLE `Purchase Order Transaction Fact` CHANGE `Purchase Order Current Dispatching State` `Purchase Order Current Dispatching State` ENUM( 'In Process', 'Submitted', 'Cancelled' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'In Process';
ALTER TABLE `Purchase Order Dimension` ADD `Purchase Order Cancelled Date` DATETIME NULL DEFAULT NULL ,ADD `Purchase Order Cancel Note` VARCHAR( 1024 ) NOT NULL DEFAULT ''
 



ALTER TABLE `Product Dimension` ADD `Product Slogan` VARCHAR( 256 ) NOT NULL AFTER `Product Description` ,
ADD `Product Marketing Description` VARCHAR( 1024 ) NOT NULL AFTER `Product Slogan` ;
ALTER TABLE `Product Dimension` CHANGE `Product Slogan` `Product Slogan` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Product Marketing Description` `Product Marketing Description` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Product Family Dimension` ADD `Product Family Main Image`  VARCHAR( 256 ) NOT NULL DEFAULT 'art/nopic.png' AFTER `Product Family Special Characteristic` 
ALTER TABLE `Store Dimension` ADD `Store Page Key` MEDIUMINT NULL DEFAULT NULL AFTER `Store Name` ;

ALTER TABLE `Store Dimension` ADD `Store Email` VARCHAR( 256 ) NOT NULL AFTER `Store Name` ,
ADD `Store Telephone` VARCHAR( 256 ) NOT NULL AFTER `Store Email` ,
ADD `Store Fax` VARCHAR( 256 ) NOT NULL AFTER `Store Telephone` 

ALTER TABLE `Part Dimension` ADD `Part Days Until Out of Stock` SMALLINT  UNSIGNED NULL DEFAULT NULL ,ADD `Part Interval Until Out of Stock` VARCHAR( 64 ) NULL DEFAULT NULL;

ALTER TABLE `Order Dimension` ADD `Order For Collection`  ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `Order Margin` ;
ALTER TABLE `Shipping Dimension` CHANGE `Shipping Destination Type`  `Shipping Destination Type` ENUM( 'Default', 'Country', 'Country Primary Division', 'World Region' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Order Dimension` CHANGE `Order Current Dispatch State` `Order Current Dispatch State` ENUM( 'In Process', 'Submited', 'Ready to Pick', 'Picking', 'Ready to Pack', 'Ready to Ship', 'Dispached', 'Unknown', 'Packing', 'Cancelled' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Shipping Dimension` CHANGE `Shipping Destination Key`  `Shipping Destination Code` VARCHAR( 8 ) NULL DEFAULT NULL 
ALTER TABLE `Shipping Dimension` CHANGE `Shipping Allowance Metadata`  `Shipping Metadata`  VARCHAR( 4096 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
ALTER TABLE `Order Dimension` ADD `Order Ship To Country Code`  VARCHAR( 3 ) NOT NULL DEFAULT 'UNK' AFTER `Order Ship To Keys` ;
ALTER TABLE `Store Dimension` ADD `Store Tax Country Code`  VARCHAR( 3 ) NOT NULL DEFAULT 'GBR' AFTER `Store Currency Code` ;
ALTER TABLE `Order Dimension` CHANGE `Order Shipping Method`  `Order Shipping Method` ENUM( 'No Applicable', 'On Demand', 'Calculated' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Calculated';
ALTER TABLE `Order Dimension` ADD `Order Estimated Weight`  FLOAT NOT NULL DEFAULT '0';
CREATE TABLE `dw`.`Company Area Department Bridge` (
`Company Area Key` SMALLINT UNSIGNED NOT NULL ,
`Company Department Key` SMALLINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Company Area Key` , `Company Department Key` )
) ENGINE = MYISAM ;

CREATE TABLE `dw`.`Company Department Position Bridge` (
`Company Department Key` SMALLINT UNSIGNED NOT NULL ,
`Company Position Key` SMALLINT UNSIGNED NOT NULL
) ENGINE = MYISAM ;


ALTER TABLE `Company Area Department Bridge` CHANGE `Company Area Key` `Area Key` SMALLINT( 5 ) UNSIGNED NOT NULL ,CHANGE `Company Department Key` `Department Key` SMALLINT( 5 ) UNSIGNED NOT NULL ;
ALTER TABLE `Company Position Dimension`
  DROP `Company Area Key`,
  DROP `Company Department Key`;
  
ALTER TABLE `Company Department Position Bridge` CHANGE `Company Department Key`  `Department Key`  SMALLINT( 5 ) UNSIGNED NOT NULL ,
CHANGE `Company Position Key` `Position Key` SMALLINT( 5 ) UNSIGNED NOT NULL   


  ALTER TABLE `Order Dimension` ADD `Order Payment Method`  ENUM( 'Credit/Debit Card', 'Pay Pal', 'Bank Transfer' ) NULL DEFAULT NULL ;

ALTER TABLE `Store Dimension` ADD `Store Total Customer Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Customers` ,
ADD `Store New Customer Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Customer Contacts` ,
ADD `Store Active Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store New Customer Contacts` ,
ADD `Store New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Active Customers` ,
ADD `Store Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store New Customers` ;






*/
?>