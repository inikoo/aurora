<?php
// DB
// 12 oct 2009 Product Image Bridge =>  Image Bridge
// 13 oct 2009 kbase.Saludation Language Key -> Language Code
// ALTER TABLE `Salutation Dimension` CHANGE `Language Key` `Language Code` VARCHAR( 3 ) NOT NULL DEFAULT 'en'
// UPDATE `kbase`.`Salutation Dimension` SET `Language Code` = 'en' WHERE `Salutation Dimension`.`Language Code` =1 ;
//  UPDATE `kbase`.`Salutation Dimension` SET `Language Code` = 'es' WHERE `Salutation Dimension`.`Language Code` =2 ;

// Edited Country dimsion (regex postal codes)
/*


ALTER TABLE `Purchase Order Dimension` ADD `Purchase Order Cancel Note`  TEXT NULL DEFAULT ''


ALTER TABLE `Purchase Order Dimension` CHANGE `Purchase Order Estimated Receiving Date` `Purchase Order Estimated Receiving Date` DATE NULL DEFAULT NULL 


ALTER TABLE `Purchase Order Dimension` CHANGE `Purchase Order Current Dispatch State` `Purchase Order Current Dispatch State` ENUM( 'In Process', 'Submitted', 'Matched With DN', 'Received', 'Checking', 'Placing in the Warehouse', 'Done', 'Unknown', 'Cancelled' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'In Process'
ALTER TABLE `Purchase Order Dimension` CHANGE `Purchase Order Current Dispatch State` `Purchase Order Current Dispatch State` ENUM( 'In Process', 'Submitted', 'Partially Matched With DN', 'Matched With DN', 'Received', 'Checking', 'Placing in the Warehouse', 'Done', 'Unknown', 'Cancelled' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'In Process'
ALTER TABLE `Supplier Delivery Note Item Part Bridge` ADD `Done` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No',
ADD `Notes` TEXT NOT NULL ;
ALTER TABLE `Supplier Delivery Note Item Part Bridge` CHANGE `Done` `Done` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No',
CHANGE `Notes` `Notes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Contact Dimension` ADD `Contact Tax Number` VARCHAR( 64 ) NULL AFTER `Contact Title` ;

0.9.2
ALTER TABLE `Inventory Transaction Fact` ADD `Picked` FLOAT NOT NULL DEFAULT '0' AFTER `Required` ,ADD `Packed` FLOAT NOT NULL DEFAULT '0' AFTER `Picked` ;
ALTER TABLE `Customer Dimension` ADD `Customer Last Payment Method` ENUM( 'Credit Card', 'Cash', 'Paypal', 'Check', 'Bank Transfer', 'Other', 'Unknown' ) NOT NULL DEFAULT 'Unknown' AFTER `Customer Tax Category` , ADD `Customer Usual Payment Method` ENUM( 'Credit Card', 'Cash', 'Paypal', 'Check', 'Bank Transfer', 'Other', 'Unknown' ) NOT NULL DEFAULT 'Unknown' AFTER `Customer Last Payment Method` ;
ALTER TABLE `Inventory Transaction Fact` ADD `Map To Order Transaction Fact` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `Invoice Dimension` ADD `Invoice Tax Shipping Code` VARCHAR( 16 ) NULL DEFAULT NULL AFTER `Invoice Tax Code` ,ADD `Invoice Tax Charges Code` VARCHAR( 16 ) NULL DEFAULT NULL AFTER `Invoice Tax Shipping Code` ;
ALTER TABLE `Invoice Dimension` CHANGE `Invoice Tax Code` `Invoice Tax Code` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'UNK';
ALTER TABLE `Order Transaction Fact` CHANGE `Scheduled Shpping Date` `Scheduled Shipping Date` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `Order Transaction Fact` DROP `Order Line` ,DROP `Delivery Note Line` ,DROP `Invoice Line` ;
ALTER TABLE `Shipping Dimension` CHANGE `Shipping Price Method` `Shipping Price Method` ENUM( 'Parent', 'Flat', 'Step Weight', 'Step Volume', 'Step Order Items Gross Amount', 'On Request' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Flat';
ALTER TABLE `Shipping Dimension` CHANGE `Shipping Destination Code` `Shipping Destination Code` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Order Transaction Fact` ADD `Payment Method` ENUM( 'Credit Card', 'Cash', 'Paypal', 'Check', 'Bank Transfer', 'Other', 'Unknown', 'NA' ) NOT NULL DEFAULT 'NA' AFTER `Invoice Transaction Outstanding Refund Tax Balance` , ADD `Refund Method` ENUM( 'Credit Card', 'Cash', 'Paypal', 'Check', 'Bank Transfer', 'Other', 'Unknown', 'NA' ) NOT NULL DEFAULT 'NA' AFTER `Payment Method` ;
ALTER TABLE `Invoice Dimension` ADD `Invoice Outstanding Net Balance` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Invoice Total Amount` , ADD `Invoice Outstanding Tax Balance` DECIMAL( 16, 3 ) NOT NULL DEFAULT '0' AFTER `Invoice Outstanding Net Balance` ;
ALTER TABLE `Order Dimension` CHANGE `Order Current Dispatch State` `Order Current Dispatch State` ENUM( 'In Process by Customer', 'In Process', 'Submitted by Customer', 'Ready to Pick', 'Picking', 'Ready to Pack', 'Ready to Ship', 'Dispatched', 'Unknown', 'Packing', 'Cancelled' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Order Transaction Fact` CHANGE `Current Dispatching State` `Current Dispatching State` ENUM( 'In Process by Customer', 'Submitted by Customer', 'In Process', 'Ready to Pick', 'Picking', 'Ready to Pack', 'Ready to Ship', 'Dispatched', 'Unknown', 'Packing', 'Cancelled' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Order Dimension` CHANGE `Order Current Dispatch State` `Order Current Dispatch State` ENUM( 'In Process by Customer', 'In Process', 'Submitted by Customer', 'Ready to Pick', 'Picking & Packing', 'Ready to Ship', 'Dispatched', 'Unknown', 'Packing', 'Cancelled' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Tax Category Dimension` DROP PRIMARY KEY ,ADD UNIQUE (`Tax Category Code`);
ALTER TABLE `Tax Category Dimension` ADD `Tax Category Key` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;
ALTER TABLE `Store Dimension` ADD `Store Tax Category Code` VARCHAR( 64 ) NULL DEFAULT NULL AFTER `Store Currency Code` ;
ALTER TABLE `Customer Dimension` CHANGE `Customer Tax Category` `Customer Tax Category Code` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Default';
ALTER TABLE `Customer Dimension` CHANGE `Customer Tax Category Code` `Customer Tax Category Code` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `Order Transaction Fact` ADD `Order Transaction Type` ENUM( 'Order', 'Sample', 'Donation', 'Unknown', 'Replacement', 'Other', 'Missing' ) NOT NULL DEFAULT 'Unknown' AFTER `Invoice Date` ;

0.9.3
ALTER TABLE `Campaign Dimension` ADD `Campaign Code` VARCHAR( 64 ) NOT NULL AFTER `Campaign Key` ;

0.9.4
ALTER TABLE `Order Transaction Fact` CHANGE `Current Dispatching State` `Current Dispatching State` ENUM( 'In Process by Customer', 'Submitted by Customer', 'In Process', 'Ready to Pick', 'Picking', 'Ready to Pack', 'Ready to Ship', 'Dispatched', 'Unknown', 'Packing', 'Cancelled', 'No Picked Due Out of Stock', 'No Picked Due No Authorised','No Picked due Not Found', 'No Picked Due Other' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Order Transaction Fact` ADD `Order Bonus Quantity` FLOAT NOT NULL DEFAULT '0' AFTER `Order Quantity` ;
ALTER TABLE `Order Transaction Fact` ADD `Refund Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Invoice Public ID` ,ADD INDEX ( `Refund Key` ) ;
ALTER TABLE `Order Transaction Fact` ADD `Refund Metadata` VARCHAR( 64 ) NULL DEFAULT NULL AFTER `Metadata` ,ADD INDEX ( `Refund Metadata` );
ALTER TABLE `Order Transaction Fact` CHANGE `Metadata` `Metadata` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `Refund Metadata` `Refund Metadata` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Order No Product Transaction Fact` CHANGE `Transaction Type` `Transaction Type` ENUM( 'Credit', 'Unknown', 'Refund', 'Shipping', 'Charges', 'Adjust', 'Other' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Order No Product Transaction Fact` ADD `Transaction Invoice Net Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Transaction Tax Amount` ,
ADD `Transaction Invoice Tax Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Transaction Invoice Net Amount` ,
ADD `Transaction Outstandind Net Amount Balance` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Transaction Invoice Tax Amount` ,
ADD `Transaction Outstandind Tax Amount Balance` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Transaction Outstandind Net Amount Balance` ;
ALTER TABLE `Order No Product Transaction Fact` ADD `Delivery Note Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Order Key` ,ADD INDEX ( `Delivery Note Key` ) ;
ALTER TABLE `Order No Product Transaction Fact` ADD `Transaction Type Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Transaction Type` , ADD INDEX ( `Transaction Type Key` ) ;
ALTER TABLE `Order No Product Transaction Fact` ADD `Delivery Note Date` DATETIME NULL DEFAULT NULL AFTER `Order Date` ;
ALTER TABLE `Order No Product Transaction Fact` ADD `Refund Date` DATE NULL DEFAULT NULL AFTER `Invoice Date` ;
ALTER TABLE `Order No Product Transaction Fact` ADD `Refund Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Invoice Key` ,ADD INDEX ( `Refund Key` ); 
ALTER TABLE `Order No Product Transaction Fact` CHANGE `Refund Date` `Refund Date` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `Order Transaction Fact` DROP `Invoice Transaction Total Tax Amount` ;
ALTER TABLE `Order Transaction Fact` ADD `Inventory Transaction Item Tax Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Total Discount Amount` ;
ALTER TABLE `Order Transaction Fact` CHANGE `Inventory Transaction Item Tax Amount` `Invoice Transaction Item Tax Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

0.9.5
ALTER TABLE `Store Dimension` DROP `Store Total Invoices`,DROP `Store Total Pending Orders`;
ALTER TABLE `Store Dimension` ADD `Store Total Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Lost Customers` ,
ADD `Store Orders In Process` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Orders` ,
ADD `Store Dispatched Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Orders In Process` ,
ADD `Store Cancelled Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Dispatched Orders` ,
ADD `Store Unknown Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Cancelled Orders` ,
ADD `Store Total Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Unknown Orders` ,
ADD `Store Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Invoices` ,
ADD `Store Refunds` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Invoices` ;
ALTER TABLE `Store Dimension` ADD `Store Paid Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Refunds`, ADD `Store Partially Paid Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Paid Invoices`, ADD `Store Paid Refunds` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Partially Paid Invoices`,
ADD `Store Partially Paid Refunds` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Paid Refunds`, 
ADD `Store Total Delivery Notes` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Partially Paid Refunds`, 
ADD `Store Ready to Pick Delivery Notes` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Delivery Notes`, 
ADD `Store Picking Delivery Notes` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Ready to Pick Delivery Notes`, 
ADD `Store Packing Delivery Notes` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Picking Delivery Notes`, 
ADD `Store Ready to Dispatch Delivery Notes` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Packing Delivery Notes`,
ADD `Store Dispatched Delivery Notes` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Ready to Dispatch Delivery Notes`,
ADD `Store Cancelled Delivery Notes` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Dispatched Delivery Notes`;
ALTER TABLE `Store Dimension` CHANGE `Store Cancelled Delivery Notes` `Store Returned Delivery Notes` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';

0.9.6
ALTER TABLE `Invoice Dimension` ADD INDEX ( `Invoice Paid` ) ;
ALTER TABLE `Order Transaction Fact` ADD `Weight` FLOAT NULL DEFAULT NULL AFTER `Estimated Weight` ;
ALTER TABLE `Order Dimension` ADD `Order Dispatched Estimated Weight` FLOAT NOT NULL DEFAULT '0' AFTER `Order Estimated Weight` ;
ALTER TABLE `Order Transaction Fact` ADD `Volume` FLOAT NULL DEFAULT NULL AFTER `Estimated Volume` ;
ALTER TABLE `Order Dimension` ADD `Order Weight` FLOAT NULL DEFAULT NULL AFTER `Order Dispatched Estimated Weight`; 
ALTER TABLE `Order Transaction Fact` ADD `Estimated Dispatched Weight` FLOAT NULL DEFAULT NULL AFTER `Estimated Weight`;
ALTER TABLE `Inventory Transaction Fact` ADD `Inventory Transaction Weight` FLOAT NULL DEFAULT NULL AFTER `Inventory Transaction Amount`;
ALTER TABLE `Order No Product Transaction Fact` ADD `Tax Category Code` VARCHAR( 16 ) NULL DEFAULT NULL AFTER `Transaction Type Key` ;
ALTER TABLE `Invoice Dimension` ADD `Invoice Impot Notes` VARCHAR( 256 ) NOT NULL ;

0.9.7
ALTER TABLE `Store Dimension` ADD `Store Collection Address Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Store Dimension` ADD `Store Delivery Notes For Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Returned Delivery Notes` ,
ADD `Store Delivery Notes For Replacements` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Delivery Notes For Orders` ,
ADD `Store Delivery Notes For Shortages` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Delivery Notes For Replacements`,
ADD `Store Delivery Notes For Samples` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Delivery Notes For Shortages`,
ADD `Store Delivery Notes For Donations` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Delivery Notes For Samples`;
ALTER TABLE `Delivery Note Dimension` CHANGE `Delivery Note Type` `Delivery Note Type` ENUM( 'Replacement & Shortages', 'Order', 'Replacement', 'Shortages', 'Sample', 'Donation' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Order';
ALTER TABLE `Delivery Note Dimension` CHANGE `Delivery Note Dispatch Method` `Delivery Note Dispatch Method` ENUM( 'Dispatch', 'Collection', 'Unknown', 'NA' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `History Dimension` CHANGE `Direct Object` `Direct Object` ENUM( 'Delivery Note', 'Category', 'Warehouse', 'Warehouse Area', 'Shelf', 'Location', 'Company Department', 'Company Area', 'Position', 'Store', 'User', 'Product', 'Address', 'Customer', 'Note', 'Order', 'Telecom', 'Email', 'Company', 'Contact', 'FAX', 'Telephone', 'Mobile', 'Work Telephone', 'Office Fax', 'Supplier', 'Family', 'Department', 'Attachment' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

0.9.8
ALTER TABLE `History Dimension` CHANGE `Direct Object` `Direct Object` ENUM( 'After Sale', 'Delivery Note', 'Category', 'Warehouse', 'Warehouse Area', 'Shelf', 'Location', 'Company Department', 'Company Area', 'Position', 'Store', 'User', 'Product', 'Address', 'Customer', 'Note', 'Order', 'Telecom', 'Email', 'Company', 'Contact', 'FAX', 'Telephone', 'Mobile', 'Work Telephone', 'Office Fax', 'Supplier', 'Family', 'Department', 'Attachment' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Order Transaction Fact` CHANGE `Invoice Transaction Shipping Tax Amount` `Invoice Transaction Shipping Tax Amount` DECIMAL( 10, 6 ) NOT NULL DEFAULT '0',
CHANGE `Invoice Transaction Charges Tax Amount` `Invoice Transaction Charges Tax Amount` DECIMAL( 10, 6 ) NOT NULL DEFAULT '0',
CHANGE `Transaction Notes` `Transaction Notes` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `Inventory Transaction Fact` ADD INDEX ( `Map To Order Transaction Fact` ) ;
ALTER TABLE `Order Dimension` ADD `Order Suspended Date` DATETIME NULL DEFAULT NULL AFTER `Order Date` ;
ALTER TABLE `Order Dimension` CHANGE `Order Current Dispatch State` `Order Current Dispatch State` ENUM( 'In Process by Customer', 'In Process', 'Submitted by Customer', 'Ready to Pick', 'Picking & Packing', 'Ready to Ship', 'Dispatched', 'Unknown', 'Packing', 'Cancelled', 'Suspended' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Order Transaction Fact` CHANGE `Current Dispatching State` `Current Dispatching State` ENUM( 'In Process by Customer', 'Submitted by Customer', 'In Process', 'Ready to Pick', 'Picking', 'Ready to Pack', 'Ready to Ship', 'Dispatched', 'Unknown', 'Packing', 'Cancelled', 'No Picked Due Out of Stock', 'No Picked Due No Authorised', 'No Picked due Not Found', 'No Picked Due Other', 'Suspended' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Order No Product Transaction Fact` ADD `State` ENUM( 'Normal', 'Suspended', 'Cancelled' ) NOT NULL DEFAULT 'Normal' AFTER `Currency Exchange` ;
ALTER TABLE `Order Dimension` ADD `Order Suspend Note` VARCHAR( 1024 ) NULL DEFAULT '' AFTER `Order Cancel Note`;
ALTER TABLE `Order Dimension` CHANGE `Order Suspend Note` `Order Suspend Note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL  ;
ALTER TABLE `Store Dimension` ADD `Store Suspended Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Cancelled Orders` ;
ALTER TABLE `Inventory Transaction Fact` DROP PRIMARY KEY ,ADD PRIMARY KEY ( `Inventory Transaction Key` ) ;
ALTER TABLE `Inventory Transaction Fact` DROP INDEX `Picker Key` ,ADD INDEX `Picker Key` ( `Picker Key` ) ;
ALTER TABLE `Inventory Transaction Fact` ADD INDEX ( `Packer Key` ) ;

0.9.9
ALTER TABLE `Order Dimension` ADD `Order Customer Order Number` MEDIUMINT UNSIGNED NOT NULL DEFAULT '1' AFTER `Order Customer Contact Name` , ADD INDEX ( `Order Customer Order Number` ) ;
ALTER TABLE `Inventory Transaction Fact` CHANGE `Map To Order Transaction Fact` `Map To Order Transaction Fact Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Inventory Transaction Fact` DROP INDEX `Map To Order Transaction Fact` ;
ALTER TABLE `Inventory Transaction Fact` ADD INDEX ( `Map To Order Transaction Fact Key` ) ;
ALTER TABLE `Inventory Transaction Fact` ADD `Map To Order Transaction Fact Metadata` VARCHAR( 255 ) NULL DEFAULT NULL ;


0.9.10

ALTER TABLE `Page Store Dimension` CHANGE `Page Store Code` `Page Store Key` SMALLINT UNSIGNED NOT NULL ;
ALTER TABLE `Supplier Dimension` ADD `Supplier Default Currency` VARCHAR( 3 ) NOT NULL DEFAULT 'USD';
CREATE TABLE `User Log Dimension` (`User Key` MEDIUMINT UNSIGNED NOT NULL ,`IP` VARCHAR( 64 ) NOT NULL , `Start Date` DATETIME NOT NULL ,`Logout Date` DATETIME NULL , INDEX ( `User Key` , `Start Date` , `Logout Date` )) ENGINE = MYISAM ;
ALTER TABLE `Store Dimension` ADD `Store Total Users` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `User Log Dimension` ADD `Session ID` VARCHAR( 256 ) NOT NULL AFTER `User Key` ,ADD INDEX ( `Session ID` ) ;
ALTER TABLE `User Log Dimension` DROP INDEX `User Key` ;
ALTER TABLE `kaktus_empty`.`User Log Dimension` ADD INDEX ( `User Key` ) ;

0.9.11

ALTER TABLE `User Dimension` CHANGE `User Type` `User Type` ENUM( 'Customer', 'Staff', 'Supplier', 'Administrator' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `User Dimension` ADD `User Last Login IP` VARCHAR( 64 ) NOT NULL AFTER `User Last Login`;
ALTER TABLE `User Dimension` ADD `User Login Count` SMALLINT NOT NULL DEFAULT '0' AFTER `User Preferred Locale` ;
ALTER TABLE `User Dimension` ADD `User Failed Login Count` SMALLINT NOT NULL DEFAULT '0' AFTER `User Last Login IP` ;
ALTER TABLE `User Dimension` ADD `User Last Failed Login IP` VARCHAR( 64 ) NOT NULL AFTER `User Failed Login Count` ;
ALTER TABLE `User Dimension` ADD `User Last Failed Login Count` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `User Last Failed Login IP` ;
ALTER TABLE `User Dimension` CHANGE `User Failed Login Count` `User Failed Login Count` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0';
CREATE TABLE `User Failed Log Dimension` (
`Handle` VARCHAR( 256 ) NOT NULL ,
`Login Page` ENUM( 'staff', 'suppliers', 'customers' ) NOT NULL ,
`User Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,
`Date` DATETIME NOT NULL ,
`IP` VARCHAR( 64 ) NOT NULL ,
INDEX ( `Handle` )
) ENGINE = MYISAM ;
ALTER TABLE `User Failed Log Dimension` ADD `Fail Main Reason` ENUM( 'handle', 'password', 'logging_timeout', 'ip', 'ikey' ) NOT NULL ,ADD INDEX ( `Fail Main Reason` ) ;
ALTER TABLE `User Failed Log Dimension` ADD `Handle OK` ENUM( 'Yes', 'No', 'Unknown' ) NOT NULL DEFAULT 'Unknown';
ALTER TABLE `User Failed Log Dimension` ADD `Password OK` ENUM( 'Yes', 'No', 'Unknown' ) NOT NULL DEFAULT 'Unknown';
ALTER TABLE `User Failed Log Dimension` ADD `Logging On Time OK` ENUM( 'Yes', 'No', 'Unknown' ) NOT NULL DEFAULT 'Unknown';
ALTER TABLE `User Failed Log Dimension` ADD `IP OK` ENUM( 'Yes', 'No', 'Unknown' ) NOT NULL DEFAULT 'Unknown';

ALTER TABLE `User Failed Log Dimension` ADD `IKey OK` ENUM( 'Yes', 'No', 'Unknown' ) NOT NULL DEFAULT 'Unknown';
ALTER TABLE `User Dimension` CHANGE `User Last Failed Login Count` `User Last Failed Login` DATETIME NOT NULL ;
ALTER TABLE `User Dimension` CHANGE `User Last Failed Login` `User Last Failed Login` DATETIME NULL DEFAULT NULL ;
RENAME TABLE `session_data` TO  `Session Dimension` ;
ALTER TABLE `Session Dimension` CHANGE `session_id` `Session ID` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `http_user_agent` `HTTP User Agent` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `session_data` `Session Data` BLOB NOT NULL ,
CHANGE `session_expire` `Session Expire` INT( 11 ) NOT NULL DEFAULT '0';

0.9.12
 DROP TABLE `session`, `session_history`, `session_noauth`;
DROP TABLE `Language Country Bridge`, `Language Dimension`, `Locale Dimension`;

0.9.14
ALTER TABLE `User Dimension` CHANGE `User Last Login IP` `User Last Login IP` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `User Last Failed Login IP` `User Last Failed Login IP` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `User Dimension` ADD `User Site Key` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `User Type` , ADD INDEX ( `User Site Key` ) ;
ALTER TABLE `Supplier Product Dimension` ADD `Supplier Product Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;

0.9.15
ALTER TABLE `Supplier Product Dimension` ADD `Supplier Product Unit Net Weight` FLOAT NULL DEFAULT NULL AFTER `Supplier Product Units Per Case` ,
ADD `Supplier Product Unit Gross Weight` FLOAT NULL DEFAULT NULL AFTER `Supplier Product Unit Net Weight` ,
ADD `Supplier Product Unit Package Type` ENUM( 'Unknown', 'Bottle', 'Bag', 'Box', 'None', 'Other' ) NOT NULL DEFAULT 'Unknown' AFTER `Supplier Product Unit Gross Weight` ,
ADD `Supplier Product Unit Gross Volume` FLOAT NULL DEFAULT NULL AFTER `Supplier Product Unit Package Type` ,
ADD `Supplier Product Unit Minimun Orthogonal Gross Volume` FLOAT NULL DEFAULT NULL AFTER `Supplier Product Unit Gross Volume` ;

ALTER TABLE `Supplier Product Dimension` CHANGE `Supplier Product Unit Type` `Supplier Product Unit Type` ENUM( '10', '25', '100', '200', 'bag', 'ball', 'box', 'doz', 'dwt', 'item', 'foot', 'gram', 'gross', 'hank', 'kilo', 'ib', 'm', 'oz', 'ozt', 'pair', 'pkg', 'set', 'skein', 'spool', 'strand', 'ten', 'tube', 'vial', 'yd' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'item';
ALTER TABLE `Supplier Product Dimension` ADD `Supplier Product URL` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `Supplier Product Description` ;
ALTER TABLE `Supplier Product Dimension` ADD `Supplier Product Case Gross Weight` FLOAT NULL DEFAULT NULL AFTER `Supplier Product Unit Minimun Orthogonal Gross Volume` ;
ALTER TABLE `Supplier Product Dimension` ADD `Supplier Product Case Minimun Orthogonal Gross Volume` FLOAT NULL DEFAULT NULL AFTER `Supplier Product Case Gross Weight` ;
ALTER TABLE `History Dimension` CHANGE `Direct Object` `Direct Object` ENUM( 'After Sale', 'Delivery Note', 'Category', 'Warehouse', 'Warehouse Area', 'Shelf', 'Location', 'Company Department', 'Company Area', 'Position', 'Store', 'User', 'Product', 'Address', 'Customer', 'Note', 'Order', 'Telecom', 'Email', 'Company', 'Contact', 'FAX', 'Telephone', 'Mobile', 'Work Telephone', 'Office Fax', 'Supplier', 'Family', 'Department', 'Attachment', 'Supplier Product' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Supplier Product History Dimension` CHANGE `SPH Cost` `SPH Case Cost` DECIMAL( 16, 2 ) NULL DEFAULT NULL ;
ALTER TABLE `Supplier Product History Dimension` ADD `SPH Units Per Case` SMALLINT UNSIGNED NOT NULL DEFAULT '1' AFTER `Supplier Key` ;
ALTER TABLE `Supplier Product History Dimension` ADD `SPH Type` ENUM( 'Normal', 'Historic' ) NOT NULL DEFAULT 'Normal' AFTER `SPH Valid To` ,ADD INDEX ( `SPH Type` );
ALTER TABLE `Supplier Product Dimension` CHANGE `Supplier Product Cost` `Supplier Product Unit Cost` DECIMAL( 16, 4 ) NULL DEFAULT NULL ;

ALTER TABLE `Supplier Product History Dimension` ADD `Supplier Product Key` MEDIUMINT UNSIGNED NOT NULL AFTER `SPH Key` ,ADD INDEX ( `Supplier Product Key` ); 
ALTER TABLE `Supplier Product History Dimension` DROP `Supplier Product Code` ,DROP `Supplier Key` ;
ALTER TABLE `Supplier Product History Dimension` CHANGE `Supplier Product Key` `Supplier Product Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `Supplier Product Dimension` CHANGE `Supplier Product Unit Cost` `Supplier Product Cost Per Case` DECIMAL( 16, 2 ) NULL DEFAULT NULL ;

ALTER TABLE `Supplier Product Part List` DROP `Supplier Product Code` , DROP `Supplier Key` ;
ALTER TABLE `Supplier Product Part List` CHANGE `Supplier Product Part ID` `Supplier Product Part ID` MEDIUMINT UNSIGNED NOT NULL ;
ALTER TABLE `Supplier Product Part List` CHANGE `Supplier Product Part Key` `Supplier Product Part List Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT ;
ALTER TABLE `Supplier Product Part List` CHANGE `Supplier Product Part ID` `Supplier Product Part Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ;
ALTER TABLE `Supplier Product Part List` DROP `Supplier Product Part Valid From` ,DROP `Supplier Product Part Valid To` ,DROP `Supplier Product Part Most Recent` ,DROP `Supplier Product Part Most Recent Key` ,DROP `Supplier Product Part Status` ;
ALTER TABLE `Supplier Product Part List` DROP `Supplier Product Unit` ,DROP `Factor Supplier Product` ;


CREATE TABLE `Supplier Product Part Dimension` (
`Supplier Product Part Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Supplier Product Key` MEDIUMINT UNSIGNED NOT NULL ,
`Supplier Product Part Type` ENUM( 'Simple', 'Split' ) NOT NULL ,
`Supplier Product Part Metadata` TEXT NULL DEFAULT NULL ,
`Supplier Product Part Valid From` DATETIME NOT NULL ,
`Supplier Product Part Valid To` DATETIME NULL DEFAULT NULL ,
`Supplier Product Part Valid Most Recent` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes',
`Supplier Product Part In Use` ENUM( 'In Use', 'Not In Use' ) NOT NULL ,
INDEX ( `Supplier Product Key` , `Supplier Product Part Valid From` , `Supplier Product Part Valid To` , `Supplier Product Part In Use` )
) ENGINE = MYISAM ;
ALTER TABLE `Supplier Product Part Dimension` CHANGE `Supplier Product Part Valid Most Recent` `Supplier Product Part Most Recent` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Yes';
ALTER TABLE `Supplier Product Part Dimension` CHANGE `Supplier Product Part In Use` `Supplier Product Part In Use` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No';
ALTER TABLE `Supplier Product Part Dimension` CHANGE `Supplier Product Part Most Recent` `Supplier Product Part Most Recent` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No';
ALTER TABLE `Supplier Product Part Dimension` DROP INDEX `Supplier Product Key` ;
ALTER TABLE `Supplier Product Part Dimension` ADD INDEX ( `Supplier Product Key` ) ;
ALTER TABLE `Supplier Product Part Dimension` ADD INDEX ( `Supplier Product Part Most Recent` ) ;
ALTER TABLE `Supplier Product Part Dimension` ADD INDEX ( `Supplier Product Part In Use` ) ;

ALTER TABLE `Order Transaction Fact` CHANGE `Invoice Transaction Item Tax Amount` `Invoice Transaction Item Tax Amount` DECIMAL( 16, 6 ) NOT NULL DEFAULT '0.00',
CHANGE `Invoice Transaction Shipping Tax Amount` `Invoice Transaction Shipping Tax Amount` DECIMAL( 16, 6 ) NOT NULL DEFAULT '0.000000',
CHANGE `Invoice Transaction Charges Tax Amount` `Invoice Transaction Charges Tax Amount` DECIMAL( 16, 6 ) NOT NULL DEFAULT '0.000000',
CHANGE `Invoice Transaction Outstanding Tax Balance` `Invoice Transaction Outstanding Tax Balance` DECIMAL( 16, 6 ) NOT NULL DEFAULT '0.00',
CHANGE `Invoice Transaction Tax Refund Amount` `Invoice Transaction Tax Refund Amount` DECIMAL( 16, 6 ) NOT NULL DEFAULT '0.00',
CHANGE `Invoice Transaction Outstanding Refund Tax Balance` `Invoice Transaction Outstanding Refund Tax Balance` DECIMAL( 16, 6 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Order Dimension` ADD `Order Out of Stock Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Order Tax Credited Amount` ;
ALTER TABLE `Order Dimension` ADD `Order Invoiced Net Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0.00' AFTER `Order Out of Stock Amount` ,ADD `Order Invoiced Tax Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0.00' AFTER `Order Invoiced Net Amount` ;
ALTER TABLE `Order Dimension` ADD `Order Invoiced Items Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Order Out of Stock Amount` ,ADD `Order Invoiced Shipping Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Order Invoiced Items Amount` ,ADD `Order Invoiced Charges Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Order Invoiced Shipping Amount` ;

ALTER TABLE `Order No Product Transaction Fact` ADD `Affected Order Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Order Key` ;
ALTER TABLE `Order Dimension` ADD `Order Invoiced Refund Net Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Order Invoiced Charges Amount` ,ADD `Order Invoiced Refund Tax Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0' AFTER `Order Invoiced Refund Net Amount` ;
ALTER TABLE `Order Dimension` ADD `Order Invoiced Refund Notes` TEXT NULL DEFAULT NULL AFTER `Order Margin` ;

CREATE TABLE `Order Post Transaction In Process Dimension` (
`Order Post Transaction In Process Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Order Transaction In Process Key` MEDIUMINT UNSIGNED NOT NULL ,
`Order Key` MEDIUMINT UNSIGNED NOT NULL ,
`Quantity` FLOAT NOT NULL ,
`Operation` ENUM( 'Replacement', 'Credit', 'Refund' ) NOT NULL ,
`Reason` ENUM( 'Other', 'Damaged', 'Missing', 'Do Not Like' ) NOT NULL DEFAULT 'Other',
`To Be Returned` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No',
INDEX ( `Order Transaction In Process Key` , `Order Key` )
) ENGINE = MYISAM ;

ALTER TABLE `Order Post Transaction In Process Dimension` CHANGE `Order Transaction In Process Key` `Order Transaction Fact Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ;
ALTER TABLE `Order Post Transaction In Process Dimension` CHANGE `Operation` `Operation` ENUM( 'Resend', 'Credit', 'Refund' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
RENAME TABLE `Order Post Transaction In Process Dimension` TO `Order Post Transaction Dimension` ;
ALTER TABLE `Order Post Transaction Dimension` CHANGE `Order Post Transaction In Process Key` `Order Post Transaction Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT ;
ALTER TABLE `Order Post Transaction Dimension` DROP INDEX `Order Transaction In Process Key` ;
ALTER TABLE `Order Post Transaction Dimension` ADD INDEX ( `Order Transaction Fact Key` ) ;
ALTER TABLE `Order Post Transaction Dimension` ADD INDEX ( `Order Key` ) ;
ALTER TABLE `Customer Dimension` ADD `Customer Next Invoice Credit Amount` DECIMAL( 16, 2 ) NOT NULL DEFAULT '0.00' AFTER `Customer Outstanding Tax Balance` ;
ALTER TABLE `Order Post Transaction Dimension` ADD `State` ENUM( 'In Process', 'In Warehoouse', 'Dispatched' ) NOT NULL DEFAULT 'In Process',ADD INDEX ( `State` ); 
ALTER TABLE `Order Post Transaction Dimension` CHANGE `State` `State` ENUM( 'In Process by Customer', 'Submitted by Customer', 'In Process', 'Ready to Pick', 'Picking', 'Ready to Pack', 'Ready to Ship', 'Dispatched', 'Unknown', 'Packing', 'Cancelled', 'No Picked Due Out of Stock', 'No Picked Due No Authorised', 'No Picked due Not Found', 'No Picked Due Other', 'Suspended' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'In Process';


CREATE TABLE IF NOT EXISTS `User Click Dimension` (
  `User Click Key` mediumint(8) NOT NULL,
  `User Key` mediumint(8) NOT NULL,
  `URL` varchar(1024) NOT NULL,
  `Page Key` mediumint(10) NOT NULL,
  `Date` datetime NOT NULL,
  `Previous Page` varchar(1024) NOT NULL,
  `Session Key` mediumint(10) NOT NULL,
  `Previous Page Key` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`User Click Key`)
) ENGINE=MyISAM;

ALTER TABLE `User Click Dimension` CHANGE `User Click Key` `User Click Key` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `Session Dimension` DROP PRIMARY KEY ;
ALTER TABLE `Session Dimension` ADD `Session Dimension Key` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;
ALTER TABLE `Session Dimension` ADD UNIQUE (`Session ID`);
ALTER TABLE `Session Dimension` CHANGE `Session Dimension Key` `Session Key` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ;
ALTER TABLE `Order Post Transaction Dimension` CHANGE `State` `State` ENUM( 'In Process', 'In Warehoouse', 'Dispatched' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'In Process';
ALTER TABLE `Order Transaction Fact` CHANGE `Order Transaction Type` `Order Transaction Type` ENUM( 'Order', 'Sample', 'Donation', 'Unknown', 'Other', 'Resend' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Order Post Transaction Dimension` ADD `Order Post Transaction Fact Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Order Transaction Fact Key` ,ADD INDEX ( `Order Post Transaction Fact Key` ) ;
ALTER TABLE `Order Post Transaction Dimension` CHANGE `State` `State` ENUM( 'In Process', 'In Warehouse', 'Dispatched' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'In Process';
0.9.16
ALTER TABLE `Store Dimension` ADD `Store B2B Only` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes';
ALTER TABLE `Page Store Dimension` ADD `Page Options` TEXT NULL DEFAULT NULL AFTER `Product Manual Layout Data` ;
*/
?>