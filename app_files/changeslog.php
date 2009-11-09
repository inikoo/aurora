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
ALTER TABLE `Supplier Dimension` ADD `Supplier Main FAX` VARCHAR( 100 ) NULL AFTER `Supplier Main Plain Telephone` ,
ADD `Supplier Main FAX Key` MEDIUMINT UNSIGNED NULL AFTER `Supplier Main FAX` ,
ADD `Supplier Main Plain FAX` VARCHAR( 100 ) NOT NULL DEFAULT '' AFTER `Supplier Main FAX Key` ;
 ALTER TABLE `Inventory Transaction Fact` ADD `Event Order` TINYINT UNSIGNED NOT NULL DEFAULT '0';

 */


?>