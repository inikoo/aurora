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
ALTER TABLE  `User Log Dimension` ADD INDEX ( `User Key` ) ;

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

ALTER TABLE `Order Post Transaction Dimension` ADD `Order Post Transaction Metadata` VARCHAR( 64 ) NULL DEFAULT NULL ,ADD INDEX ( `Order Post Transaction Metadata` ) ;
ALTER TABLE `Order Post Transaction Dimension` CHANGE `Order Key` `Order Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Order Post Transaction Dimension` CHANGE `Reason` `Reason` ENUM( 'Other', 'Damaged', 'Missing', 'Do Not Like', 'Unknown' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Other';
ALTER TABLE `Order Post Transaction Dimension` CHANGE `Order Key` `Order Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Order Transaction Fact` DROP `Shipped Damaged Quantity` ,DROP `Shipped Not Received Quantity` ;
ALTER TABLE `Order Transaction Fact` CHANGE `Cost Manufacure` `Cost Manufacture` DECIMAL( 12, 4 ) NULL DEFAULT NULL ;
ALTER TABLE `Order Transaction Fact` DROP `Cost Manufacture` ;
ALTER TABLE `Product Dimension` CHANGE `Product Cost` `Product Cost Supplier` DECIMAL( 16, 4 ) NULL DEFAULT NULL ;
ALTER TABLE `Product Dimension` ADD `Product Cost Storing` DECIMAL( 12, 4 ) NULL DEFAULT NULL AFTER `Product Cost Supplier` ;
ALTER TABLE `Product Dimension` DROP `Product Sales State DELETEME` ;
ALTER TABLE `Part Dimension` CHANGE `Part Current Stock Cost` `Part Current Stock Cost Per Unit` DECIMAL( 12, 2 ) NULL DEFAULT NULL , CHANGE `Part Current Storing Cost` `Part Current Storing Cost Per Unit` DECIMAL( 12, 2 ) NULL DEFAULT NULL ;
ALTER TABLE `Part Dimension` CHANGE `Part Average Future Cost` `Part Average Future Cost Per Unit` FLOAT NULL DEFAULT NULL ,CHANGE `Part Minimum Future Cost` `Part Minimum Future Cost Per Unit` FLOAT NULL DEFAULT NULL ;

0.9.17
ALTER TABLE `Inventory Transaction Fact` ADD `Not Found` FLOAT NOT NULL DEFAULT '0' AFTER `Out of Stock`;
ALTER TABLE `Inventory Transaction Fact` ADD `No Picked Other` FLOAT NOT NULL DEFAULT '0' AFTER `Not Found`;
CREATE TABLE `Customer History Bridge` (
  `Customer Key` mediumint(8) unsigned NOT NULL,
  `History Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Customer Key`,`History Key`),
  KEY `Customer Key` (`Customer Key`),
  KEY `History Key` (`History Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `Supplier Dimension` ADD `Supplier Unknown Stock Products` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Supplier Out Of Stock Products` ;

0.9.18
ALTER TABLE `Purchase Order Transaction Fact` ADD `Supplier Product Historic Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Supplier Product Key` ;
ALTER TABLE `Category Dimension` ADD `Category Parent Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Category Key` , ADD INDEX ( `Category Parent Key` ); 
ALTER TABLE `Image Dimension` CHANGE `Image Thumbnail URL` `Image Thumbnail URL` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `Image Small URL` `Image Small URL` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `Image Large URL` `Image Large URL` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
CREATE TABLE `Site Image Bridge` (
`Image Key` MEDIUMINT UNSIGNED NOT NULL ,
`Store Key` SMALLINT UNSIGNED NOT NULL ,
`Code` VARCHAR( 64 ) NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Site Image Bridge` ADD UNIQUE (`Image Key` ,`Store Key` ,`Code`);
ALTER TABLE `Site Image Bridge` ADD INDEX ( `Image Key` ) ;
ALTER TABLE `Site Image Bridge` ADD INDEX ( `Store Key` , `Code` ) ;


CREATE TABLE `Site Dimension` (
`Site Key` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Store Key` SMALLINT UNSIGNED NOT NULL ,
`Site Name` VARCHAR( 256 ) NOT NULL ,
`Logo Data` TEXT NOT NULL ,
`Header Data` TEXT NOT NULL ,
`Footer Data` TEXT NOT NULL ,
`Layout Data` TEXT NOT NULL ,
INDEX ( `Store Key` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `Site Dimension` CHANGE `Logo Data` `Site Logo Data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Header Data` `Site Header Data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Footer Data` `Site Footer Data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Layout Data` `Site Layout Data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Site Dimension` CHANGE `Store Key` `Site Store Key` SMALLINT( 5 ) UNSIGNED NOT NULL ;
ALTER TABLE `Site Dimension` ADD `Site Active` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes';
ALTER TABLE `Page Store Dimension` ADD `Page Site Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Page Code` ,ADD INDEX ( `Page Site Key` ); 
DROP TABLE `Staff Work Hours Dimension`;
CREATE TABLE IF NOT EXISTS `Staff Work Hours Dimension` (
  `Staff Key` mediumint(11) NOT NULL,
  `Day` varchar(20) NOT NULL,
  `Start Time` datetime NOT NULL,
  `Finish Time` datetime NOT NULL,
  `Total Breaks Time` time NOT NULL,
  `Hours Worked` time NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
drop table `Staff Event Dimension` ;
CREATE TABLE IF NOT EXISTS `Staff Event Dimension` (
  `Staff Event Key` mediumint(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `Location` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `Description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `End Time` datetime DEFAULT NULL,
  `Is All Day Event` smallint(6) NOT NULL,
  `Color` varchar(200) CHARACTER SET utf8 DEFAULT '6',
  `Recurring Rule` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `Staff Date Key` mediumint(11) NOT NULL,
  `Staff Key` mediumint(11) NOT NULL,
  PRIMARY KEY (`Staff Event Key`)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
drop table `Corporation Event Dimension`;
CREATE TABLE IF NOT EXISTS `Corporation Event Dimension` (
  `Corporation Event Key` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` enum('Others','National Holiday','Bank Holiday','Festive Holiday') DEFAULT 'Others',
  `Location` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `Description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `End Time` datetime DEFAULT NULL,
  `Is All Day Event` smallint(6) NOT NULL,
  `Color` varchar(200) CHARACTER SET utf8 DEFAULT '3',
  `Recurring Rule` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`Corporation Event Key`)
) ENGINE=InnoDB  DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

0.9.19
ALTER TABLE `Site Dimension` ADD `Site Index Page Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Site Name` ;
ALTER TABLE `Site Dimension` CHANGE `Site Index Page Key` `Site Index Page Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
CREATE TABLE `Page Store Section Dimension` (
`Page Store Section Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Page Store Section Code` VARCHAR( 64 ) NOT NULL ,
`Page Store Section Logo Data` TEXT NULL DEFAULT NULL ,
`Page Store Section Header Data` TEXT NULL DEFAULT NULL ,
`Page Store Section Footer Data` TEXT NULL DEFAULT NULL ,
`Page Store Section Layout Data` TEXT NULL DEFAULT NULL
) ENGINE = MYISAM ;
ALTER TABLE `Page Store Dimension` CHANGE `Page Store Section` `Page Store Section` ENUM( 'Front Page Store', 'Search', 'Product Descritiption', 'Information', 'Family Catalogue', 'Department Catalogue', 'Unknown', 'Store Catalogue', 'Registration', 'Client Section' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Page Store Dimension` CHANGE `Page Store Section` `Page Store Section` ENUM( 'Front Page Store', 'Search', 'Product Descritiption', 'Information', 'Family Catalogue', 'Department Catalogue', 'Unknown', 'Store Catalogue', 'Registration', 'Client Section', 'Check Out' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Page Store Dimension` ADD `Page Store Section Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Page Store Source Type` ;
ALTER TABLE `Page Store Dimension` CHANGE `Page Store Section` `Page Store Section` ENUM( 'Front Page Store', 'Search', 'Product Descritiption', 'Information', 'Category Catalogue', 'Family Catalogue', 'Department Catalogue', 'Unknown', 'Store Catalogue', 'Registration', 'Client Section', 'Check Out' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Page Store Dimension` ADD `Page Store Logo Data` TEXT NULL DEFAULT NULL AFTER `Page Options` ,ADD `Page Store Header Data` TEXT NULL DEFAULT NULL AFTER `Page Store Logo Data` ,ADD `Page Store Footer Data` TEXT NULL DEFAULT NULL AFTER `Page Store Header Data` ,ADD `Page Store Layout Data` TEXT NULL DEFAULT NULL AFTER `Page Store Footer Data` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Content Data` TEXT NULL DEFAULT NULL AFTER `Page Store Header Data` ;
ALTER TABLE `Page Store Section Dimension` ADD `Page Store Section Content Data` TEXT NULL DEFAULT NULL AFTER `Page Store Section Header Data` ;
ALTER TABLE `Site Dimension` ADD `Site Content Data` TEXT NULL DEFAULT NULL AFTER `Site Header Data` ;
ALTER TABLE `Image Dimension` CHANGE `Image File Format` `Image File Format` ENUM( 'jpeg', 'png', 'gif' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'jpeg';
ALTER TABLE `Page Store Section Dimension` ADD `Site Key` SMALLINT UNSIGNED NOT NULL AFTER `Page Store Section Key` ,ADD INDEX ( `Site Key` );

0.9.20
ALTER TABLE `Site Dimension` ADD `Registration Type` ENUM( 'Steps', 'Simple', 'None' ) NOT NULL DEFAULT 'Simple' AFTER `Site Index Page Key`;
ALTER TABLE `Page Store Dimension` CHANGE `Product Thumbnails Layout` `Product Thumbnails Layout` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes';
CREATE TABLE IF NOT EXISTS `Comment Dimension` (
  `Comment Key` mediumint(10) NOT NULL auto_increment,
  `Name` varchar(255) character set utf8 NOT NULL,
  `Email` varchar(200) character set utf8 NOT NULL,
  `Comment` text character set utf8 NOT NULL,
  `Date Added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`Comment Key`)
) ENGINE=MyISAM  CHARACTER SET utf8 COLLATE utf8_general_ci;



0.9.21
ALTER TABLE `History Dimension` ADD `Author Name` VARCHAR( 256 ) NULL AFTER `History Key`;
ALTER TABLE `History Dimension` CHANGE `Action` `Action` ENUM( 'sold_since', 'last_sold', 'first_sold', 'placed', 'wrote', 'deleted', 'edited', 'cancelled', 'charged', 'merged', 'created', 'associated', 'disassociate' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'edited';
ALTER TABLE `History Dimension` CHANGE `Preposition` `Preposition` ENUM( 'about', '', 'to' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `History Dimension` CHANGE `Direct Object Key` `Direct Object Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT '0';
ALTER TABLE `Time Series Dimension` ADD `Open` FLOAT NULL DEFAULT NULL AFTER `Time Series Count` ,
ADD `High` FLOAT NULL DEFAULT NULL AFTER `Open` ,
ADD `Low` FLOAT NULL DEFAULT NULL AFTER `High` ,
ADD `Close` FLOAT NULL DEFAULT NULL AFTER `Low` ,
ADD `Volume` FLOAT NULL DEFAULT NULL AFTER `Close` ,
ADD `Adj Close` FLOAT NULL DEFAULT NULL AFTER `Volume` ;
ALTER TABLE `Customer Dimension` ADD INDEX ( `Active Customer` ) ;
ALTER TABLE `Customer Dimension` ADD INDEX ( `Actual Customer` ) ;
ALTER TABLE `Customer Dimension` ADD INDEX ( `Customer First Order Date` ) ;
ALTER TABLE `Customer Dimension` ADD INDEX ( `Customer Lost Date` ) ;
ALTER TABLE `Store Dimension` ADD `Store 1 Year New Customers` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store New Customers` ,
ADD `Store 1 Quarter New Customers` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Year New Customers` ,
ADD `Store 1 Month New Customers` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Quarter New Customers` ,
ADD `Store 1 Week New Customers` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Month New Customers` ;
ALTER TABLE `Store Dimension` ADD `Store 1 Year Lost Customers` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Lost Customers` ,
ADD `Store 1 Quarter Lost Customers` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Year Lost Customers` ,
ADD `Store 1 Month Lost Customers` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Quarter Lost Customers` ,
ADD `Store 1 Week Lost Customers` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Month Lost Customers` ;

CREATE TABLE `Email Campaign Dimension` (
`Email Campaign Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Email Campaign Name` VARCHAR( 256 ) NOT NULL ,
`Email Campaign Objective` TEXT NOT NULL ,
`Email Campaign Maximum Emails` MEDIUMINT UNSIGNED NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Status` ENUM( 'Creating', 'Ready', 'Sending', 'Complete' ) NOT NULL ,ADD INDEX ( `Email Campaign Status` ) ;

CREATE TABLE `Email Campaign Mailing List` (
`Email Campaign Mailing List Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Email Campaign Key` MEDIUMINT UNSIGNED NOT NULL ,
`Email Key` MEDIUMINT UNSIGNED NOT NULL ,
`Email Send Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,
INDEX ( `Email Campaign Key` , `Email Key` , `Email Send Key` )
) ENGINE = MYISAM ;

CREATE TABLE `Email Campaign Scope Bridge` (
`Email Campaign Key` MEDIUMINT UNSIGNED NOT NULL ,
`Email Campaign Scope` ENUM( 'Product', 'Family', 'Department', 'Store', 'Campaign', 'Deal' ) NOT NULL ,
`Email Campaign Scope Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,
`Email Campaign Scope Linked` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No',
`Email Campaign Scope Visited` ENUM( 'Yes', 'No', 'NA' ) NOT NULL DEFAULT 'NA',
INDEX ( `Email Campaign Key` , `Email Campaign Scope Linked` , `Email Campaign Scope Visited` )
) ENGINE = MYISAM ;

0.9.22
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Engine` ENUM( 'Internal', 'External' ) NOT NULL DEFAULT 'Internal',ADD `Email Campaign Content` LONGTEXT NULL DEFAULT NULL ;
ALTER TABLE `Email Campaign Dimension` CHANGE `Email Campaign Maximum Emails` `Email Campaign Maximum Emails` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Email Campaign Dimension` ADD `Email Last Updated Date` DATETIME NOT NULL AFTER `Email Campaign Key` ,
ADD `Email Campaign Creation Date` DATETIME NOT NULL AFTER `Email Last Updated Date` ,
ADD `Email Campaign Date` DATETIME NULL DEFAULT NULL AFTER `Email Campaign Creation Date` ,
ADD INDEX ( `Email Last Updated Date` ) ;
ALTER TABLE `Email Campaign Dimension` CHANGE `Email Last Updated Date` `Email Campaign Last Updated Date` DATETIME NOT NULL ;
ALTER TABLE `Email Campaign Dimension` ADD INDEX ( `Email Campaign Store Key` ) ;
ALTER TABLE `Email Campaign Dimension` CHANGE `Email Campaign Status` `Email Campaign State` ENUM( 'Creating', 'Ready', 'Sending', 'Complete' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Creating';

ALTER TABLE `Store Dimension` ADD `Store 1 Year New Customers Contacts` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store New Customers` ,
ADD `Store 1 Quarter New Customers Contacts` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Year New Customers Contacts` ,
ADD `Store 1 Month New Customers Contacts` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Quarter New Customers Contacts` ,
ADD `Store 1 Week New Customers Contacts` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Month New Customers Contacts`,
ADD `Store 1 Day New Customers Contacts` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Week New Customers Contacts`;
ALTER TABLE `Customer Dimension` ADD INDEX ( `Customer First Contacted Date` ) ;


INSERT INTO `Right Dimension` (
`Right Key` ,
`Right Type` ,
`Right Name` ,
`Right Access` ,
`Rigth Access Keys`
)
VALUES (
NULL , 'View', 'supplier sales', 'All', ''
);

INSERT INTO `Right Dimension` (
`Right Key` ,
`Right Type` ,
`Right Name` ,
`Right Access` ,
`Rigth Access Keys`
)
VALUES (
NULL , 'View', 'supplier stock', 'All', ''
);

INSERT INTO `User Group Rights Bridge` (
`Group Key` ,
`Right Key`
)
VALUES (
'3', '59'
);

INSERT INTO `User Group Rights Bridge` (
`Group Key` ,
`Right Key`
)
VALUES (
'3', '58'
);

ALTER TABLE `Category Dimension` ADD `Category Store Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Category Key` ,ADD INDEX ( `Category Store Key` ) ;
ALTER TABLE `Category Dimension` ADD `Category Subject Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Category Subject` ,ADD INDEX ( `Category Subject Key` ) ;

0.9.23
ALTER TABLE `Category Dimension` CHANGE `Category Subject` `Category Subject` ENUM( 'Product', 'Supplier', 'Customer', 'Family', 'Invoice' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Product';

ALTER TABLE `Search Full Text Dimension` CHANGE `Subject` `Subject` ENUM( 'Family', 'Customer', 'Product', 'Part', 'Order', 'Page' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Inventory Spanshot Fact` ADD `Quantity Open` FLOAT NULL DEFAULT NULL AFTER `Quantity Lost` ,ADD `Quantity High` FLOAT NULL DEFAULT NULL AFTER `Quantity Open` ,ADD `Quantity Low` FLOAT NULL DEFAULT NULL AFTER `Quantity High` ;
ALTER TABLE `Category Dimension` CHANGE `Category Subject` `Category Subject` ENUM( 'Product', 'Supplier', 'Customer', 'Family', 'Invoice', 'Part' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Product';


0.9.24
ALTER TABLE `Category Dimension` ADD `Category Function` TEXT NULL DEFAULT NULL ;
ALTER TABLE `Category Dimension` CHANGE `Category Subject` `Category Subject` ENUM( 'Product', 'Supplier', 'Customer', 'Family', 'Invoice', 'Part' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Product';
ALTER TABLE `Invoice Dimension` CHANGE `Invoice For` `Invoice For` ENUM( 'Staff', 'Customer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Customer';
ALTER TABLE `Category Dimension` ADD `Category Function Order` MEDIUMINT UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Category Bridge` CHANGE `Subject` `Subject` ENUM( 'Product', 'Supplier', 'Customer', 'Family', 'Invoice', 'Part' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Invoice Dimension` DROP `Invoice Category`,DROP `Invoice Category Key`;

DROP TABLE IF EXISTS `Email Campaign Dimension`;
CREATE TABLE IF NOT EXISTS `Email Campaign Dimension` (
 `Email Campaign Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 `Email Campaign Name` varchar(256) NOT NULL,
 `Email Campaign Objective` text NOT NULL,
 `Email Campaign Maximum Emails` mediumint(8) unsigned DEFAULT NULL,
 `Email Campaign Status` enum('Creating','Ready','Sending','Complete') NOT NULL,
 `Email Campaign Engine` enum('Internal','External') NOT NULL DEFAULT 'Internal',
 `Email Campaign Content` longtext,
 `Flag` int(1) NOT NULL DEFAULT '0',
 `Folder ID` varchar(100) NOT NULL,
 PRIMARY KEY (`Email Campaign Key`),
 KEY `Email Campaign Status` (`Email Campaign Status`)
) ENGINE=MyISAM   CHARSET=utf8;

DROP TABLE IF EXISTS `Email Campaign Group Title`;
CREATE TABLE IF NOT EXISTS `Email Campaign Group Title` (
  `Email Campaign Group Key` varchar(50) NOT NULL,
  `Email List Key` mediumint(8) NOT NULL,
  `How Show Options` varchar(50) NOT NULL,
  `Group Title` varchar(100) NOT NULL,
  PRIMARY KEY (`Email Campaign Group Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Email Campaign Group Title Name Bridge`;
CREATE TABLE IF NOT EXISTS `Email Campaign Group Title Name Bridge` (
  `Email Campaign Group Name Key` int(11) NOT NULL AUTO_INCREMENT,
  `Email Campaign Group Key` varchar(50) NOT NULL,
  `Group Name` varchar(50) NOT NULL,
  PRIMARY KEY (`Email Campaign Group Name Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Email Campaign Mailing List`;
CREATE TABLE IF NOT EXISTS `Email Campaign Mailing List` (
  `Email Campaign Mailing List Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Email Campaign Key` mediumint(8) unsigned NOT NULL,
  `Email Key` mediumint(8) unsigned NOT NULL,
  `Email Send Key` mediumint(8) unsigned DEFAULT NULL,
  `List Name` varchar(100) NOT NULL,
  `Default From Name` varchar(100) NOT NULL,
  `Default Reply To Email` varchar(100) NOT NULL,
  `Default Subject` varchar(100) NOT NULL,
  `Permission Reminder List` varchar(100) NOT NULL,
  `Reminder Text` text NOT NULL,
  `People Subscribe` tinyint(1) NOT NULL,
  `People Unsubscribe` tinyint(1) NOT NULL,
  `Pick Email Format` tinyint(1) NOT NULL,
  `Activate Social Pro` tinyint(1) NOT NULL,
  PRIMARY KEY (`Email Campaign Mailing List Key`),
  KEY `Email Campaign Key` (`Email Campaign Key`,`Email Key`,`Email Send Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Email People Dimension` (
 `Email People Key` int(11) NOT NULL AUTO_INCREMENT,
 `People List Key` int(11) NOT NULL,
 `People Group Key` varchar(50) NOT NULL,
 `People Email` varchar(50) NOT NULL,
 `People First Name` varchar(50) NOT NULL,
 `People Last Name` varchar(50) NOT NULL,
 `People Email Type` varchar(50) NOT NULL,
 PRIMARY KEY (`Email People Key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `Campaign Mailing List` (
  `Campaign Mailing List Id` int(11) NOT NULL AUTO_INCREMENT,
  `Email Campaign Mailing List Key` int(20) NOT NULL,
  `Campaign Mailing List Name` varchar(255) NOT NULL,
  `Campaign Mailing List Default Name` varchar(255) NOT NULL,
  `Campaign Mailing List Email` varchar(255) NOT NULL,
  `Campaign Mailing List Reminder` varchar(255) NOT NULL,
  `Campaign Mailing List Recipients` int(100) NOT NULL,
  `Campaign Mailing List Contact Info` varchar(255) NOT NULL,
  `Campaign Mailing List Terms` int(5) NOT NULL,
  PRIMARY KEY (`Campaign Mailing List Id`)
) ENGINE=MyISAM;

ALTER TABLE `Email Campaign Mailing List` ADD `User Key` INT NOT NULL AFTER `Email Campaign Mailing List Key` ;

ALTER TABLE `Email Campaign Mailing List` DROP INDEX `Email Campaign Key`;

ALTER TABLE `Email Campaign Mailing List` CHANGE `Email Campaign Key` `Email Campaign Key` MEDIUMINT( 8 ) UNSIGNED NULL ,CHANGE `Email Key` `Email Key` MEDIUMINT( 8 ) UNSIGNED NULL ,CHANGE `Email Send Key` `Email Send Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL; 

ALTER TABLE `Email People Dimension` CHANGE `People Group Key` `People Group Key` VARCHAR( 50 ) NOT NULL; 



ALTER TABLE `Shelf Type Dimension` CHANGE `Shelf Type Type` `Shelf Type Type` ENUM( 'Pallet', 'Shelf', 'Drawer', 'Other' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Other';

ALTER TABLE `Right Dimension` CHANGE `Rigth Access Keys` `Right Access Keys` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

INSERT INTO `Right Dimension` (`Right Key` ,`Right Type` ,`Right Name` ,`Right Access` ,`Right Access Keys`)VALUES (NULL , 'View', 'marketing', 'All', '');
INSERT INTO `Right Dimension` (`Right Key` ,`Right Type` ,`Right Name` ,`Right Access` ,`Right Access Keys`)VALUES (NULL , 'Edit', 'marketing', 'All', '');
INSERT INTO `Right Dimension` (`Right Key` ,`Right Type` ,`Right Name` ,`Right Access` ,`Right Access Keys`)VALUES (NULL , 'Delete', 'marketing', 'All', '');
INSERT INTO `Right Dimension` (`Right Key` ,`Right Type` ,`Right Name` ,`Right Access` ,`Right Access Keys`)VALUES (NULL , 'Create', 'marketing', 'All', '');
INSERT INTO `User Group Dimension` (`User Group Key` ,`User Group Name` ,`User Group Description`)VALUES (NULL , 'Marketing', 'Marketing is the process of performing market research, selling products and/or services to customers and promoting them via advertising to further enhance sales.');
INSERT INTO `User Group Rights Bridge` (`Group Key` ,`Right Key`)VALUES ('9', '60');
INSERT INTO `User Group Rights Bridge` (`Group Key` ,`Right Key`)VALUES ('9', '61');
INSERT INTO `User Group Rights Bridge` (`Group Key` ,`Right Key`)VALUES ('9', '62');
INSERT INTO `User Group Rights Bridge` (`Group Key` ,`Right Key`)VALUES ('9', '63');
INSERT INTO `User Group User Bridge` (`User Key` ,`User Group Key`)VALUES ('1', '9');
ALTER TABLE `Customer Dimension` ADD `Customer XHTML Billing Address` VARCHAR( 1024 ) NULL DEFAULT NULL AFTER `Customer Main Country 2 Alpha Code` ;
ALTER TABLE `Customer Dimension` ADD `Customer Billing Address Country Code` VARCHAR( 3 ) NULL DEFAULT NULL AFTER `Customer XHTML Billing Address` ;
ALTER TABLE `Customer Dimension` ADD `Customer Billing Address Town` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `Customer Billing Address Country Code` ;
ALTER TABLE `Customer Dimension` ADD `Customer XHTML Main Delivery Address` VARCHAR( 1024 ) NULL DEFAULT NULL AFTER `Customer Delivery Address Link` ;
CREATE TABLE  `Customer List Dimension` (
`Customer List Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Customer List Name` VARCHAR( 256 ) NOT NULL ,
`Customer List Type` ENUM( 'Dynamic', 'Static' ) NOT NULL DEFAULT 'Static',
`Customer List Metadata` MEDIUMTEXT NULL DEFAULT NULL ,
`Customer List Creation Date` DATETIME NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `Customer List Dimension` ADD `Customer List Store Key` SMALLINT UNSIGNED NOT NULL AFTER `Customer List Key` ;
CREATE TABLE =`Customer List Customer Bridge` (
`Customer List Key` SMALLINT UNSIGNED NOT NULL ,
`Customer Key` MEDIUMINT UNSIGNED NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `Customer List Customer Bridge` ADD UNIQUE (
`Customer List Key` ,
`Customer Key`
);;
ALTER TABLE `Customer List Dimension` ADD UNIQUE (
`Customer List Store Key` ,
`Customer List Name`
);
ALTER TABLE  `Customer List Customer Bridge` ADD INDEX ( `Customer List Key` ) ;

0.9.25

ALTER TABLE `Customer Dimension` ADD `Customer Main Address Incomplete` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes' AFTER `Customer Main Country 2 Alpha Code` ,ADD INDEX ( `Customer Main Address Incomplete` ) ;
ALTER TABLE `Address Dimension` CHANGE `Address Fuzzy Type` `Address Fuzzy Type` ENUM( 'All', 'Country', 'World Region', 'Town', 'Street', 'Post Code' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `Order Dimension` CHANGE `Order Current Payment State` `Order Current Payment State` ENUM( 'Waiting Payment', 'Paid', 'Partially Paid', 'Unknown', 'Payment Refunded', 'Cancelled', 'No Applicable' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Customer History Bridge` ADD `Deletable` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `History Key` ,ADD INDEX ( `Deletable` ); 

ALTER TABLE `Category Dimension` ADD `Category Number Subjects` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Customer Dimension` ADD `Customer Account Operative` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes',ADD INDEX ( `Customer Account Operative` ) ;
ALTER TABLE `Customer Dimension` ADD `Customer Send Postal Marketing` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `Customer Send Email Marketing` ,ADD INDEX ( `Customer Send Postal Marketing` ) ;
ALTER TABLE `Customer Dimension` ADD `Customer Sticky Note` TEXT NOT NULL ;

ALTER TABLE `Category Dimension` ADD `Category Children` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Category Deep` ,ADD `Category Children Deep` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Category Children` ;

*/

?>
