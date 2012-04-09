<?php
exit();

?>
// DB
// 12 oct 2009 Product Image Bridge =>  Image Bridge
// 13 oct 2009 kbase.Saludation Language Key -> Language Code
// ALTER TABLE `Salutation Dimension` CHANGE `Language Key` `Language Code` VARCHAR( 3 ) NOT NULL DEFAULT 'en'
// UPDATE `kbase`.`Salutation Dimension` SET `Language Code` = 'en' WHERE `Salutation Dimension`.`Language Code` =1 ;
//  UPDATE `kbase`.`Salutation Dimension` SET `Language Code` = 'es' WHERE `Salutation Dimension`.`Language Code` =2 ;

// Edited Country dimsion (regex postal codes)



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



DROP TABLE IF EXISTS `Email Campaign Dimension`;
CREATE TABLE IF NOT EXISTS `Email Campaign Dimension` (
  `Email Campaign Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Email Campaign Name` varchar(256) NOT NULL,
  `Email Campaign Objective` text NOT NULL,
  `Email Campaign Maximum Emails` mediumint(8) unsigned DEFAULT NULL,
  `Email Campaign Status` enum('Creating','Ready','Sending','Complete') NOT NULL,
  `Email Campaign Engine` enum('Internal','External') NOT NULL DEFAULT 'Internal',
  `Email Campaign Content` longtext,
  `Number of Emails` smallint(5) NOT NULL DEFAULT '0',
  `Number of Read Emails` smallint(5) NOT NULL DEFAULT '0',
  `Number of Rejected Emails` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Email Campaign Key`),
  KEY `Email Campaign Status` (`Email Campaign Status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Email Campaign Mailing List`;
CREATE TABLE IF NOT EXISTS `Email Campaign Mailing List` (
  `Email Campaign Mailing List Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Email Campaign Key` mediumint(8) unsigned NOT NULL,
  `Email Key` mediumint(8) unsigned NOT NULL,
  `Email Send Key` mediumint(8) unsigned DEFAULT NULL,
  `Customer Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Email Campaign Mailing List Key`),
  KEY `Email Campaign Key` (`Email Campaign Key`,`Email Key`,`Email Send Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


DROP TABLE IF EXISTS `Email Send Dimension`;
CREATE TABLE IF NOT EXISTS `Email Send Dimension` (
  `Email Send Key` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Email Send Type` enum('Marketing','Registration','Password Reminder','Newsletter') NOT NULL,
  `Email Send Type Key` mediumint(9) NOT NULL DEFAULT '0',
  `Email Send Recipient Type` enum('Customer','Supplier') NOT NULL DEFAULT 'Customer',
  `Email Send Recipient Key` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Email Key` int(10) unsigned NOT NULL,
  `Email Send Date` datetime DEFAULT NULL,
  `Email Send First Read Date` datetime DEFAULT NULL,
  `Email Send Last Read Date` datetime DEFAULT NULL,
  `Email Send Number Reads` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`Email Send Key`),
  KEY `Email Key` (`Email Key`),
  KEY `Email Send Type` (`Email Send Type`),
  KEY `Email Send Parent Key` (`Email Send Recipient Key`),
  KEY `Email Send Parent Type` (`Email Send Recipient Type`),
  KEY `Email Send Type Key` (`Email Send Type Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
------------------------------------------------------------


CREATE TABLE IF NOT EXISTS `Export Map` (
  `Map Key` int(11) NOT NULL AUTO_INCREMENT,
  `Map Name` varchar(255) NOT NULL,
  `Map Type` enum('Customer','Supplier') NOT NULL,
  `Map Data` longtext NOT NULL,
  `Customer Key` int(11) NOT NULL,
  `Export Map Default` enum('yes','no') NOT NULL,
  `Exported Date` datetime NOT NULL,
  PRIMARY KEY (`Map Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

0.9.26

ALTER TABLE `Customer Dimension` ADD `Customer Main XHTML Mobile` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `Customer Main FAX Key` ,ADD `Customer Main Mobile Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Customer Main XHTML Mobile` , ADD `Customer Main Plain Mobile` VARCHAR( 255 ) NOT NULL AFTER `Customer Main Mobile Key` ;

DROP TABLE IF EXISTS `Export Map`;
CREATE TABLE IF NOT EXISTS `Export Map` (
  `Map Key` int(11) NOT NULL AUTO_INCREMENT,
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

ALTER TABLE `Product Dimension` ADD `Product Store Code` VARCHAR( 64 ) NOT NULL AFTER `Product Store Key` ,ADD `Product Store Name` VARCHAR( 256 ) NOT NULL AFTER `Product Store Code` ;

ALTER TABLE `Order Transaction Fact` ADD `Product ID` MEDIUMINT UNSIGNED NOT NULL AFTER `Product Key` ,ADD `Product Code` VARCHAR( 64 ) NOT NULL AFTER `Product ID` ,ADD `Product Family Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Product Code` ,ADD `Product Department Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Product Family Key` ;
ALTER TABLE `Order Transaction Fact` ADD INDEX ( `Product ID` ) ;
ALTER TABLE `Order Transaction Fact` ADD INDEX ( `Product Family Key` ) ;
ALTER TABLE `Order Transaction Fact` ADD INDEX ( `Product Department Key` ) ;


CREATE TABLE IF NOT EXISTS `Customers Send Post` (
  `Customer Send Post Key` mediumint(11) NOT NULL,
  `Customer Key` mediumint(11) NOT NULL,
  `Send Post Status` enum('To Send','Send','Cancelled') NOT NULL,
  `Date Creation` datetime NOT NULL,
  `Date Send` datetime NOT NULL,
  `Post Type` enum('Catalogue','Advert','Letter') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `Search Full Text Dimension` ADD FULLTEXT (`First Search Full Text` ,`Second Search Full Text`);
ALTER TABLE `History Dimension` ORDER BY `History Date` DESC;

0.9.27

DROP TABLE IF EXISTS `Product Department Default Currency`;
CREATE TABLE `Product Department Default Currency` (
  `Product Department Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Product Department DC Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department DC Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
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
  PRIMARY KEY (`Product Department Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Product Family Default Currency`;
CREATE TABLE `Product Family Default Currency` (
  `Product Family Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Product Family DC Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family DC Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
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
  PRIMARY KEY (`Product Family Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Product ID Default Currency`;
CREATE TABLE `Product ID Default Currency` (
  `Product ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Product ID DC Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product ID DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product ID DC 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`Product ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Product Default Currency`;
CREATE TABLE `Product Default Currency` (
  `Product Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Product DC Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product DC Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product DC Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product DC Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product DC 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product DC 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product DC 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product DC 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`Product Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Product Code Default Currency`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE orders_data.`orders` ADD `customer_id` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,ADD INDEX ( `customer_id` );
ALTER TABLE de_orders_data.`orders` ADD `customer_id` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,ADD INDEX ( `customer_id` );
ALTER TABLE fr_orders_data.`orders` ADD `customer_id` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,ADD INDEX ( `customer_id` );
ALTER TABLE pl_orders_data.`orders` ADD `customer_id` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,ADD INDEX ( `customer_id` );
ALTER TABLE ci_orders_data.`orders` ADD `customer_id` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,ADD INDEX ( `customer_id` );

0.9.28 from here
ALTER TABLE `Order Dimension` ADD `Order Invoiced Total Net Adjust Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Order Invoiced Net Amount` ;
ALTER TABLE `Order Dimension` ADD `Order Invoiced Total Tax Adjust Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Order Invoiced Tax Amount` ;
ALTER TABLE `Order Dimension` ADD `Order Invoiced Total` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Order Invoiced Total Tax Adjust Amount`;
ALTER TABLE `Order Transaction Fact` ADD `Invoice Transaction Net Adjust` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Outstanding Refund Tax Balance` ,ADD `Invoice Transaction Tax Adjust` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Net Adjust` ;
CREATE TABLE IF NOT EXISTS `Theme Dimension` (
  `Theme Key` int(11) NOT NULL AUTO_INCREMENT,
  `Theme Name` varchar(255) NOT NULL,
  `Theme Common Css` varchar(255) NOT NULL,
  `Theme Table Css` varchar(255) NOT NULL,
  `Theme Index Css` varchar(255) NOT NULL,
  `Theme Dropdown Css` varchar(255) NOT NULL,
  `Theme Campaign Css` varchar(255) NOT NULL,
  PRIMARY KEY (`Theme Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `Theme Dimension`
--

INSERT INTO `Theme Dimension` (`Theme Key`, `Theme Name`, `Theme Common Css`, `Theme Table Css`, `Theme Index Css`, `Theme Dropdown Css`, `Theme Campaign Css`) VALUES
(1, 'brown', 'brown_common.css', 'brown_table.css', 'brown_index.css', 'brown_dropdown.css', 'brown_marketing_campaigns.css');


 ALTER TABLE `User Dimension` ADD `User Themes` INT( 4 ) NOT NULL;
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Store Key` SMALLINT UNSIGNED NOT NULL AFTER `Email Campaign Key` ,ADD INDEX ( `Email Campaign Store Key` ) ;
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Last Updated Date` DATETIME NOT NULL AFTER `Email Campaign Store Key` ;
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Creation Date` DATETIME NOT NULL AFTER `Email Campaign Store Key` ;
ALTER TABLE `Email Campaign Dimension` ADD UNIQUE (`Email Campaign Store Key` ,`Email Campaign Name`);
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Subject` VARCHAR( 64 ) NOT NULL AFTER `Email Campaign Engine` ;
CREATE TABLE `Email Content Dimension` (`Email Content Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,`Email Content Type` ENUM( 'text', 'html' ) NOT NULL ,`Email Content Layout Key` SMALLINT UNSIGNED NULL DEFAULT NULL ,`Email Content Subject` VARCHAR( 64 ) NOT NULL ,`Email Content` MEDIUMTEXT NOT NULL) ENGINE = MYISAM ;
ALTER TABLE `Email Campaign Mailing List` CHANGE `Email Key` `Email Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Email Campaign Mailing List` ADD `Email Address` VARCHAR( 256 ) NOT NULL AFTER `Email Key` ,ADD `Email Contact Name` VARCHAR( 256 ) NOT NULL AFTER `Email Address` ;
ALTER TABLE `Email Campaign Mailing List` CHANGE `Customer Key` `Customer Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Email Campaign Mailing List` ADD UNIQUE (`Email Address` ,`Email Campaign Key`);
CREATE TABLE `Email Campaign Dimension Content Bridge` (
`Email Campaign Key` MEDIUMINT UNSIGNED NOT NULL ,
`Email Content Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Email Campaign Key` , `Email Content Key` )
) ENGINE = MYISAM ;
ALTER TABLE `Email Content Dimension` CHANGE `Email Content Type` `Email Content Type` ENUM( 'Plain', 'HTML Template' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `Email Content Layout Key` `Email Content Template Key` SMALLINT( 5 ) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Number Contents` MEDIUMINT UNSIGNED NOT NULL DEFAULT '1' AFTER `Email Campaign Engine` ;
DROP TABLE `Email Campaign Group Titile`, `Email Campaign Group Titile Name Bridge`, `Email Campaign Group Title`, `Email Campaign Group Title Name Bridge`;
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Scope` VARCHAR( 1024 ) NOT NULL AFTER `Email Campaign Objective` ;
ALTER TABLE `Email Content Dimension` CHANGE `Email Content` `Email Text Content` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Content Dimension` CHANGE `Email Text Content` `Email Content Text` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Content Dimension` ADD `Email Content HTML` MEDIUMTEXT NOT NULL ,ADD `Email Content Metadata` MEDIUMTEXT NULL DEFAULT NULL ;
RENAME TABLE `Email Campaign Dimension Content Bridge` TO `Email Campaign Content Bridge` ;
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Content Type` ENUM( 'Plain', 'HTML Template', 'Multi Plain', 'Multi HTML Template', 'Multi Mixed', 'Unknown' ) NOT NULL DEFAULT 'Unknown' AFTER `Email Campaign Engine` ;
ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Subjects` TEXT NOT NULL AFTER `Email Campaign Number Contents` ;
ALTER TABLE `Email Campaign Dimension` CHANGE `Email Campaign Content` `Email Campaign Contents` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Order Dimension` CHANGE `Order Out of Stock Amount` `Order Out of Stock Net Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';
ALTER TABLE `Order Dimension` ADD `Order Out of Stock Tax Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Order Out of Stock Net Amount` ;
ALTER TABLE `Order Transaction Deal Bridge` ADD INDEX ( `Order Transaction Fact Key` ) ;


CREATE TABLE `Email Template Dimension` (
`Email Template Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Email Template Name` VARCHAR( 64 ) NOT NULL ,
`Email Template Type` ENUM( 'Basic', 'Newsletter Left', 'Newsletter Right', 'Postcard' ) NOT NULL ,
`Email Template Metadata` MEDIUMTEXT NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `Email Template Dimension` ADD `Email Template Source Code` MEDIUMTEXT NOT NULL ;

ALTER TABLE `Store Dimension` ADD `Store 3 Year Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store Delivery Notes For Donations` ,
ADD `Store 3 Year Acc Invoices` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 3 Year Acc Invoiced Amount` ,
ADD `Store 3 Year Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 3 Year Acc Invoices` ,
ADD `Store 3 Year Acc Days Available` FLOAT NOT NULL AFTER `Store 3 Year Acc Profit`;


ALTER TABLE `Store Dimension` ADD `Store YearToDay Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 1 Year Acc Pending Orders` ,
ADD `Store YearToDay Acc Invoices` DECIMAL( 12, 2 ) NOT NULL AFTER `Store YearToDay Acc Invoiced Amount` ,
ADD `Store YearToDay Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store YearToDay Acc Invoices` ,
ADD `Store YearToDay Acc Days Available` FLOAT NOT NULL AFTER `Store YearToDay Acc Profit`;


ALTER TABLE `Store Dimension` ADD `Store 6 Month Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store YearToDay Acc Days Available` ,
ADD `Store 6 Month Acc Invoices` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 6 Month Acc Invoiced Amount` ,
ADD `Store 6 Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 6 Month Acc Invoices` ,
ADD `Store 6 Month Acc Days Available` FLOAT NOT NULL AFTER `Store 6 Month Acc Profit`;


ALTER TABLE `Store Dimension` ADD `Store 3 Month Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 1 Quarter Acc Pending Orders` ,
ADD `Store 3 Month Acc Invoices` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 3 Month Acc Invoiced Amount` ,
ADD `Store 3 Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 3 Month Acc Invoices` ,
ADD `Store 3 Month Acc Days Available` FLOAT NOT NULL AFTER `Store 3 Month Acc Profit`;


ALTER TABLE `Store Dimension` ADD `Store 10 Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 1 Month Acc Pending Orders` ,
ADD `Store 10 Day Acc Invoices` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 10 Day Acc Invoiced Amount` ,
ADD `Store 10 Day Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store 10 Day Acc Invoices` ,
ADD `Store 10 Day Acc Days Available` FLOAT NOT NULL AFTER `Store 10 Day Acc Profit` ;

ALTER TABLE `Store Default Currency` ADD `Store DC YearToDay Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC 1 Year Acc Profit` ,
ADD `Store DC YearToDay Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC YearToDay Acc Invoiced Amount`;


ALTER TABLE `Store Default Currency` ADD `Store DC 3 Year Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC Total Profit` ,
ADD `Store DC 3 Year Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC 3 Year Acc Invoiced Amount`;


ALTER TABLE `Store Default Currency` ADD `Store DC 6 Month Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC YearToDay Acc Profit` ,
ADD `Store DC 6 Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC 6 Month Acc Invoiced Amount`;


ALTER TABLE `Store Default Currency` ADD `Store DC 3 Month Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC 1 Quarter Acc Profit` ,
ADD `Store DC 3 Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC 3 Month Acc Invoiced Amount`;


ALTER TABLE `Store Default Currency` ADD `Store DC 10 Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC 1 Month Acc Profit` ,
ADD `Store DC 10 Day Acc Profit` DECIMAL( 12, 2 ) NOT NULL AFTER `Store DC 10 Day Acc Invoiced Amount` ;

inikoo 29 from here

DROP TABLE IF EXISTS `Product Family Dimension`;
CREATE TABLE IF NOT EXISTS `Product Family Dimension` (
  `Product Family Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Product Family Code` varchar(255) DEFAULT NULL,
  `Product Family Name` varchar(255) DEFAULT NULL,
  `Product Family Description` text,
  `Product Family Slogan` varchar(256) DEFAULT NULL,
  `Product Family Marketing Description` varchar(1024) DEFAULT NULL,
  `Product Family Special Characteristic` varchar(256) NOT NULL,
  `Product Family Main Image` varchar(256) NOT NULL DEFAULT 'art/nopic.png',
  `Product Family Main Department Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family Main Department Code` varchar(255) DEFAULT NULL,
  `Product Family Main Department Name` varchar(255) DEFAULT NULL,
  `Product Family Record Type` enum('In Process','New','Normal','Discontinuing','Discontinued') NOT NULL DEFAULT 'Normal',
  `Product Family Sales Type` enum('Public Sale','Private Sale Only','Not for Sale','Unknown','No Applicable') NOT NULL DEFAULT 'Public Sale',
  `Product Family Availability` enum('Normal','Some Out of Stock','All Out of Stock','No Applicable') NOT NULL DEFAULT 'No Applicable',
  `Product Family Valid From` datetime DEFAULT NULL,
  `Product Family Valid To` datetime DEFAULT NULL,
  `Product Family Most Recent` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Product Family Most Recent Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family Page Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family For Public Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family For Private Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family In Process Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Not For Sale Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Discontinued Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Unknown Sales State Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Unknown Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Family Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family Total Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Family Total Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Family Total Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Family Total Days On Sale` float NOT NULL DEFAULT '0',
  `Product Family Total Days Available` float NOT NULL DEFAULT '0',
  `Product Family Total Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Total Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Total Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family Total Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Total Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family Total Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family Total Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family Total Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family Total Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family Total Acc Days On Sale` float DEFAULT NULL,
  `Product Family Total Acc Days Available` float DEFAULT NULL,
  `Product Family 3 Year Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Quantity Ordered` float NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Quantity Invoiced` float NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Quantity Delivered` float NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Days On Sale` float DEFAULT NOT NULL DEFAULT '0.00',
  `Product Family 3 Year Acc Days Available` float DEFAULT NOT NULL DEFAULT '0.00',
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
  `Product Family YearToDay Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Family YearToDay Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Family YearToDay Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Family YearToDay Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Family YearToDay Acc Quantity Ordered` float DEFAULT NULL,
  `Product Family YearToDay Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Family YearToDay Acc Quantity Delivered` float DEFAULT NULL,
  `Product Family YearToDay Acc Days On Sale` float DEFAULT NULL,
  `Product Family YearToDay Acc Days Available` float DEFAULT NULL,
  `Product Family YearToDay Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family YearToDay Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Family YearToDay Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
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
  `Product Family Stock Value` decimal(14,2) DEFAULT NULL,
  `Product Family Store Key` smallint(5) unsigned NOT NULL DEFAULT '1',
  `Product Family Store Code` varchar(32) NOT NULL,
  `Product Family Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Product Family Total Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Year Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Quarter Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Month Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Week Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family Total Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Year Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Quarter Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Month Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Family 1 Week Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Product Family Key`),
  KEY `code` (`Product Family Code`(16)),
  KEY `Product Family Most Recent` (`Product Family Most Recent`),
  KEY `Product Family Name` (`Product Family Name`),
  KEY `Product Family Store Key` (`Product Family Store Key`),
  KEY `Product Family Special Characteristic` (`Product Family Special Characteristic`),
  KEY `Product Family Main Department Key` (`Product Family Main Department Key`),
  KEY `Product Family Sales Type` (`Product Family Sales Type`),
  KEY `Product Family Record Type` (`Product Family Record Type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `Store Dimension`;
CREATE TABLE IF NOT EXISTS `Store Dimension` (
  `Store Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Store Code` varchar(16) DEFAULT NULL,
  `Store Name` varchar(255) DEFAULT NULL,
  `Store Contact Name` varchar(64) DEFAULT NULL,
  `Store URL` varchar(256) NOT NULL,
  `Store Email` varchar(256) NOT NULL,
  `Store Telephone` varchar(256) NOT NULL,
  `Store Fax` varchar(256) NOT NULL,
  `Store Slogan` varchar(64) NOT NULL DEFAULT '',
  `Store Valid From` datetime DEFAULT NULL,
  `Store Valid To` datetime DEFAULT NULL,
  `Store Locale` enum('en_GB','de_DE','fr_FR','es_ES','pl_PL') DEFAULT 'en_GB',
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
  `Store Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store Total Quantity Ordered` float NOT NULL DEFAULT '0',
  `Store Total Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Store Total Quantity Delivered` float NOT NULL DEFAULT '0',
  `Store Total Days On Sale` float NOT NULL DEFAULT '0',
  `Store Total Days Available` float NOT NULL DEFAULT '0',
  `Store Total Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Customer Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store New Customer Contacts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Active Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store New Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Lost Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Orders In Process` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Dispatched Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Cancelled Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Suspended Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Unknown Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Paid Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Partially Paid Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Paid Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Partially Paid Refunds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Total Delivery Notes` mediumint(8) unsigned NOT NULL DEFAULT '0',
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
  `Store 3 Year Acc Invoiced Gross Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Store 3 Year Acc Invoiced Discount Amount` decimal(10,0) NOT NULL DEFAULT '0',
  `Store 3 Year Acc Quantity Ordered` float NOT NULL DEFAULT '0.00',
  `Store 3 Year Acc Quantity Invoiced` float NOT NULL DEFAULT '0.00',
  `Store 3 Year Acc Quantity Delivered` float NOT NULL DEFAULT '0.00',
  `Store 3 Year Acc Customers` mediumint(9) NOT NULL DEFAULT '0',
  `Store 3 Year Acc Pending Orders` mediumint(9) NOT NULL DEFAULT '0',
  `Store 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 3 Year Acc Invoices` mediumint(8) NOT NULL DEFAULT '0',
  `Store 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 3 Year Acc Days On Sale` float DEFAULT NULL,
  `Store 3 Year Acc Days Available` float NOT NULL DEFAULT '0.00',
  `Store 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Store 1 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Store 1 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Store 1 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Store 1 Year Acc Days On Sale` float DEFAULT NULL,
  `Store 1 Year Acc Days Available` float DEFAULT NULL,
  `Store 1 Year Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Year Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store YearToDay Acc Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store YearToDay Acc Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store YearToDay Acc Quantity Ordered` float NOT NULL DEFAULT '0.00',
  `Store YearToDay Acc Quantity Invoiced` float NOT NULL DEFAULT '0.00',
  `Store YearToDay Acc Quantity Delivered` float NOT NULL DEFAULT '0.00',
  `Store YearToDay Acc Customers` mediumint(8) NOT NULL DEFAULT '0',
  `Store YearToDay Acc Pending Orders` mediumint(8) NOT NULL DEFAULT '0',
  `Store YearToDay Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store YearToDay Acc Invoices` decimal(12,2) NOT NULL DEFAULT '0',
  `Store YearToDay Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store YearToDay Acc Days On Sale` float DEFAULT NULL,
  `Store YearToDay Acc Days Available` float NOT NULL DEFAULT '0',
  `Store 6 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store 6 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store 6 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Store 6 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Store 6 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Store 6 Month Acc Customers` mediumint(8) NOT NULL DEFAULT '0',
  `Store 6 Month Acc Pending Orders` mediumint(8) NOT NULL DEFAULT '0',
  `Store 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 6 Month Acc Invoices` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 6 Month Acc Days On Sale` float DEFAULT NULL,
  `Store 6 Month Acc Days Available` float NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Store 1 Quarter Acc Quantity Ordered` float DEFAULT NULL,
  `Store 1 Quarter Acc Quantity Invoiced` float DEFAULT NULL,
  `Store 1 Quarter Acc Quantity Delivered` float DEFAULT NULL,
  `Store 1 Quarter Acc Days On Sale` float DEFAULT NULL,
  `Store 1 Quarter Acc Days Available` float DEFAULT NULL,
  `Store 1 Quarter Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Quarter Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 3 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store 3 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store 3 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Store 3 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Store 3 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Store 3 Month Acc Customers` mediumint(8) NOT NULL DEFAULT '0',
  `Store 3 Month Acc Pending Orders` mediumint(8) NOT NULL DEFAULT '0',
  `Store 3 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 3 Month Acc Invoices` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 3 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 3 Month Acc Days On Sale` float DEFAULT NULL,
  `Store 3 Month Acc Days Available` float NOT NULL DEFAULT '0',
  `Store 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Store 1 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Store 1 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Store 1 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Store 1 Month Acc Days On Sale` float DEFAULT NULL,
  `Store 1 Month Acc Days Available` float DEFAULT NULL,
  `Store 1 Month Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Month Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 10 Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store 10 Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store 10 Day Acc Quantity Ordered` float DEFAULT NULL,
  `Store 10 Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Store 10 Day Acc Quantity Delivered` float DEFAULT NULL,
  `Store 10 Day Acc Customers` mediumint(8) NOT NULL DEFAULT '0',
  `Store 10 Day Acc Pending Orders` mediumint(8) NOT NULL DEFAULT '0',
  `Store 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 10 Day Acc Invoices` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store 10 Day Acc Days On Sale` float DEFAULT NULL,
  `Store 10 Day Acc Days Available` float NOT NULL DEFAULT '0',
  `Store 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Store 1 Week Acc Quantity Ordered` float DEFAULT NULL,
  `Store 1 Week Acc Quantity Invoiced` float DEFAULT NULL,
  `Store 1 Week Acc Quantity Delivered` float DEFAULT NULL,
  `Store 1 Week Acc Days On Sale` float DEFAULT NULL,
  `Store 1 Week Acc Days Available` float DEFAULT NULL,
  `Store 1 Week Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store 1 Week Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Store Stock Value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Store Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Store Tax Category Code` varchar(64) DEFAULT NULL,
  `Store Tax Country Code` varchar(3) NOT NULL DEFAULT 'GBR',
  `Store Home Country Code 2 Alpha` varchar(2) NOT NULL,
  `Store Home Country Name` varchar(64) NOT NULL,
  `Store Home Country Short Name` varchar(12) NOT NULL,
  `Store Order Public ID Format` varchar(65) NOT NULL DEFAULT '%05d',
  `Store Order Last Order ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Store Collection Address Key` mediumint(8) unsigned DEFAULT NULL,
  `Store Total Users` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Store Key`),
  KEY `code` (`Store Code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `Product Department Dimension`;
CREATE TABLE IF NOT EXISTS `Product Department Dimension` (
  `Product Department Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Product Department Type` enum('Normal','Unknown','Historic') NOT NULL DEFAULT 'Normal',
  `Product Department Sales Type` enum('Public Sale','Private Sale','Not for Sale') NOT NULL DEFAULT 'Public Sale',
  `Product Department Store Key` smallint(5) unsigned NOT NULL,
  `Product Department Store Code` varchar(32) NOT NULL,
  `Product Department Code` varchar(255) DEFAULT NULL,
  `Product Department Name` varchar(255) DEFAULT NULL,
  `Product Department Description` text NOT NULL,
  `Product Department Slogan` varchar(256) DEFAULT NULL,
  `Product Department Marketing Resume` varchar(1024) DEFAULT NULL,
  `Product Department Marketing Presentation` text NOT NULL,
  `Product Department Main Image` varchar(256) NOT NULL DEFAULT 'art/nopic.png',
  `Product Department Valid From` datetime DEFAULT NULL,
  `Product Department Valid To` datetime DEFAULT NULL,
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
  `Product Department Unknown Sales State Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Surplus Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Optimal Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Low Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Critical Availability Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Out Of Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Unknown Stock Products` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Product Department Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Department Total Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Department Total Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Department Total Days On Sale` float NOT NULL DEFAULT '0',
  `Product Department Total Days Available` float NOT NULL DEFAULT '0',
  `Product Department Total Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Total Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Total Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
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
  `Product Department YearToDay Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product Department YearToDay Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product Department YearToDay Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Department YearToDay Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product Department YearToDay Acc Quantity Ordered` float DEFAULT NULL,
  `Product Department YearToDay Acc Quantity Invoiced` float DEFAULT NULL,
  `Product Department YearToDay Acc Quantity Delivered` float DEFAULT NULL,
  `Product Department YearToDay Acc Days On Sale` float DEFAULT NULL,
  `Product Department YearToDay Acc Days Available` float DEFAULT NULL,
  `Product Department YearToDay Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department YearToDay Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department YearToDay Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
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
  `Product Department Stock Value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `Product Department Currency Code` varchar(3) NOT NULL DEFAULT 'GBP',
  `Product Department Total Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Avg Week Sales Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Avg Week Profit Per Family` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Avg Week Sales Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Total Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Year Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Quarter Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Month Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department 1 Week Acc Avg Week Profit Per Product` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Product Department Active Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Department Active Customers More 0.5 Share` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Product Department Key`),
  KEY `code` (`Product Department Code`(16)),
  KEY `Product Department Most Recent` (`Product Department Most Recent`),
  KEY `Product Depxartment Discontinued Products` (`Product Department In Process Products`,`Product Department Unknown Sales State Products`),
  KEY `Product Department Type` (`Product Department Type`),
  KEY `Product Department Name` (`Product Department Name`),
  KEY `Product Department Store Key` (`Product Department Store Key`),
  KEY `Product Department Sales Type` (`Product Department Sales Type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `Product Dimension`;
CREATE TABLE IF NOT EXISTS `Product Dimension` (
  `Product ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Product Current Key` mediumint(8) unsigned NOT NULL,
  `Product Type` enum('Normal','Mix','Shortcut') NOT NULL DEFAULT 'Normal',
  `Product Record Type` enum('Normal','Discontinued','In Process','New','Discontinuing','Historic') NOT NULL DEFAULT 'Normal',
  `Product Sales Type` enum('Public Sale','Private Sale','Not for Sale') NOT NULL,
  `Product Store Key` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `Product Locale` enum('en_GB','de_DE','fr_FR','es_ES','pl_PL') NOT NULL DEFAULT 'en_GB',
  `Product Currency` varchar(3) NOT NULL DEFAULT 'GBP',
  `Product Sales State DELETEME` enum('For Sale','Out of Stock','Not for Sale','Discontinued','Unknown','No Applicable') NOT NULL DEFAULT 'Unknown',
  `Product To Be Discontinued` enum('Yes','No','No Applicable') NOT NULL DEFAULT 'No',
  `Product Web State` enum('Online Force Out of Stock','Online Auto','Offline','Unknown','Online Force For Sale') NOT NULL DEFAULT 'Unknown',
  `Product Code File As` varchar(255) NOT NULL,
  `Product Code` varchar(30) NOT NULL,
  `Product Price` decimal(9,2) DEFAULT NULL,
  `Product Cost` decimal(16,4) DEFAULT NULL,
  `Product RRP` decimal(9,2) DEFAULT NULL,
  `Product Name` varchar(1024) DEFAULT NULL,
  `Product Short Description` varchar(255) DEFAULT NULL,
  `Product XHTML Short Description` varchar(255) DEFAULT NULL,
  `Product Main Image` varchar(255) NOT NULL DEFAULT 'art/nopic.png',
  `Product Special Characteristic` varchar(255) DEFAULT NULL,
  `Product Family Special Characteristic` text,
  `Product Description` text,
  `Product Slogan` varchar(256) DEFAULT NULL,
  `Product Marketing Description` varchar(1024) DEFAULT NULL,
  `Product Tariff Code` varchar(16) DEFAULT NULL,
  `Product Brand Name` varchar(255) DEFAULT NULL,
  `Product Family Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Family Code` varchar(32) DEFAULT NULL,
  `Product Family Name` varchar(255) DEFAULT NULL,
  `Product Main Department Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Main Department Code` varchar(255) DEFAULT NULL,
  `Product Main Department Name` varchar(255) DEFAULT NULL,
  `Product Department Degeneration` smallint(5) unsigned DEFAULT NULL,
  `Product Package Type Description` enum('Unknown','Bottle','Bag','Box','None','Other') DEFAULT NULL,
  `Product Package Size Metadata` varchar(255) DEFAULT NULL,
  `Product Gross Volume` float DEFAULT NULL,
  `Product Minimun Orthogonal Gross Volume` float DEFAULT NULL,
  `Product Net Weight` float DEFAULT NULL,
  `Product Gross Weight` float DEFAULT NULL COMMENT 'Total weight of the product and its packaging',
  `Product Units Per Case` float DEFAULT NULL,
  `Product Unit Type` enum('Piece','Grams','Liters','Meters','Other') DEFAULT 'Piece',
  `Product Unit Container` enum('Unknown','Bottle','Box','None','Bag','Other') DEFAULT 'Unknown',
  `Product Unit XHTML Description` varchar(4096) DEFAULT NULL,
  `Product Availability State` enum('Optimal','Low','Critical','Surplus','Out of Stock','Unknown','No applicable') NOT NULL DEFAULT 'Unknown',
  `Product Availability` float DEFAULT NULL,
  `Product Available Days Forecast` float DEFAULT NULL,
  `Product XHTML Available Forecast` varchar(1024) DEFAULT NULL,
  `Product Next Day Availability` float DEFAULT NULL,
  `Product Stock Value` decimal(12,2) DEFAULT NULL,
  `Product Next Supplier Shipment` varchar(1024) NOT NULL,
  `Product XHTML Parts` varchar(1024) DEFAULT NULL,
  `Product XHTML Supplied By` varchar(1024) DEFAULT NULL,
  `Product Main Picking Location Key` mediumint(8) unsigned DEFAULT NULL,
  `Product Main Picking Location` varchar(255) DEFAULT NULL,
  `Product Main Picking Location Stock` float DEFAULT NULL,
  `Product XHTML Picking` varchar(255) DEFAULT NULL,
  `Product Valid From` datetime DEFAULT NULL,
  `Product Valid To` datetime DEFAULT NULL,
  `Product Manufacure Metadata` varchar(4096) DEFAULT NULL,
  `Product Manufacture Type Metadata` varchar(255) DEFAULT NULL,
  `Product Last Updated` datetime DEFAULT NULL,
  `Product Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product Total Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product Total Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product Total Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product Total Days On Sale` float NOT NULL DEFAULT '0',
  `Product Total Days Available` float NOT NULL DEFAULT '0',
  `Product Total Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Total Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Total Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product Total Estimated GMROI` float DEFAULT NULL,
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
  `Product YearToDay Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product YearToDay Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product YearToDay Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product YearToDay Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product YearToDay Acc Quantity Ordered` float DEFAULT NULL,
  `Product YearToDay Acc Quantity Invoiced` float DEFAULT NULL,
  `Product YearToDay Acc Quantity Delivered` float DEFAULT NULL,
  `Product YearToDay Acc Days On Sale` float DEFAULT NULL,
  `Product YearToDay Acc Days Available` float DEFAULT NULL,
  `Product YearToDay Acc Invoices` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product YearToDay Acc Customers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Product YearToDay Acc Pending Orders` mediumint(8) unsigned NOT NULL DEFAULT '0',
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
  `Product For Sale Since Date` datetime DEFAULT NULL,
  `Product First Sold Date` datetime DEFAULT NULL,
  `Product Last Sold Date` datetime DEFAULT NULL,
  `Product Days On Sale` float DEFAULT NULL,
  `Product GMROI` float DEFAULT NULL,
  `Product Total Margin` float DEFAULT NULL,
  `Product 3 Year Acc Margin` float DEFAULT NULL,
  `Product 1 Year Acc Margin` float DEFAULT NULL,
  `Product YearToDay Acc Margin` float DEFAULT NULL,
  `Product 6 Month Acc Margin` float DEFAULT NULL,
  `Product 1 Quarter Acc Margin` float DEFAULT NULL,
  `Product 3 Month Acc Margin` float DEFAULT NULL,
  `Product 1 Month Acc Margin` float DEFAULT NULL,
  `Product 10 Day Acc Margin` float DEFAULT NULL,
  `Product 1 Week Acc Margin` float DEFAULT NULL,
  `Product Editing Price` decimal(9,2) DEFAULT NULL,
  `Product Editing RRP` decimal(9,2) DEFAULT NULL,
  `Product Editing Name` varchar(1024) DEFAULT NULL,
  `Product Editing Special Characteristic` varchar(255) DEFAULT NULL,
  `Product Editing Family Special Characteristic` varchar(255) DEFAULT NULL,
  `Product Editing Units Per Case` float DEFAULT NULL,
  `Product Editing Unit Type` enum('Piece','Grams','Liters','Meters','Other') DEFAULT NULL,
  PRIMARY KEY (`Product ID`,`Product Store Key`),
  KEY `Product Alphanumeric Code` (`Product Code File As`(16)),
  KEY `date` (`Product Valid From`),
  KEY `date2` (`Product Valid To`),
  KEY `Product Department Key` (`Product Main Department Key`),
  KEY `family` (`Product Family Key`),
  KEY `Product State` (`Product Sales State DELETEME`),
  KEY `Product Availability State` (`Product Availability State`),
  KEY `Product Name` (`Product Name`(333)),
  KEY `Product Price` (`Product Price`),
  KEY `Product Units Per Case` (`Product Units Per Case`),
  KEY `Product Unit Type` (`Product Unit Type`),
  KEY `Product Web State` (`Product Web State`),
  KEY `code` (`Product Code`),
  KEY `Product Tariff Code` (`Product Tariff Code`),
  KEY `Product Store Key` (`Product Store Key`),
  KEY `Product Type` (`Product Type`),
  KEY `Product Current Key` (`Product Current Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


DROP TABLE IF EXISTS `Product History Dimension`;
CREATE TABLE IF NOT EXISTS `Product History Dimension` (
  `Product Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Product ID` mediumint(8) unsigned NOT NULL,
  `Product History Price` decimal(9,2) DEFAULT NULL,
  `Product History Name` varchar(1024) DEFAULT NULL,
  `Product History Short Description` varchar(255) DEFAULT NULL,
  `Product History XHTML Short Description` varchar(255) DEFAULT NULL,
  `Product History Special Characteristic` varchar(255) DEFAULT NULL,
  `Product History Valid From` datetime DEFAULT NULL,
  `Product History Valid To` datetime DEFAULT NULL,
  `Product History Last Updated` datetime DEFAULT NULL,
  `Product History Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History Total Quantity Ordered` float NOT NULL DEFAULT '0',
  `Product History Total Quantity Invoiced` float NOT NULL DEFAULT '0',
  `Product History Total Quantity Delivered` float NOT NULL DEFAULT '0',
  `Product History Total Days On Sale` float NOT NULL DEFAULT '0',
  `Product History Total Days Available` float NOT NULL DEFAULT '0',
  `Product History Total Estimated GMROI` float DEFAULT NULL,
  `Product History 3 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History 3 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History 3 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History 3 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product History 3 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History 3 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product History 3 Year Acc Days On Sale` float DEFAULT NULL,
  `Product History 3 Year Acc Days Available` float DEFAULT NULL,
  `Product History 3 Year Acc Estimated GMROI` float DEFAULT NULL,
  `Product History 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History 1 Year Acc Quantity Ordered` float DEFAULT NULL,
  `Product History 1 Year Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History 1 Year Acc Quantity Delivered` float DEFAULT NULL,
  `Product History 1 Year Acc Days On Sale` float DEFAULT NULL,
  `Product History 1 Year Acc Days Available` float DEFAULT NULL,
  `Product History 1 Year Acc Estimated GMROI` float DEFAULT NULL,
  `Product History YearToDay Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History YearToDay Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History YearToDay Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History YearToDay Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History YearToDay Acc Quantity Ordered` float DEFAULT NULL,
  `Product History YearToDay Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History YearToDay Acc Quantity Delivered` float DEFAULT NULL,
  `Product History YearToDay Acc Days On Sale` float DEFAULT NULL,
  `Product History YearToDay Acc Days Available` float DEFAULT NULL,
  `Product History YearToDay Acc Estimated GMROI` float DEFAULT NULL,
  `Product History 6 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History 6 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History 6 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History 6 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product History 6 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History 6 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product History 6 Month Acc Days On Sale` float DEFAULT NULL,
  `Product History 6 Month Acc Days Available` float DEFAULT NULL,
  `Product History 6 Month Acc Estimated GMROI` float DEFAULT NULL,
  `Product History 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History 1 Quarter Acc Quantity Ordered` float DEFAULT NULL,
  `Product History 1 Quarter Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History 1 Quarter Acc Quantity Delivered` float DEFAULT NULL,
  `Product History 1 Quarter Acc Days On Sale` float DEFAULT NULL,
  `Product History 1 Quarter Acc Days Available` float DEFAULT NULL,
  `Product History 3 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History 3 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History 3 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History 3 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History 3 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product History 3 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History 3 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product History 3 Month Acc Days On Sale` float DEFAULT NULL,
  `Product History 3 Month Acc Days Available` float DEFAULT NULL,
  `Product History 3 Month Acc Estimated GMROI` float DEFAULT NULL,
  `Product History 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History 1 Month Acc Quantity Ordered` float DEFAULT NULL,
  `Product History 1 Month Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History 1 Month Acc Quantity Delivered` float DEFAULT NULL,
  `Product History 1 Month Acc Days On Sale` float DEFAULT NULL,
  `Product History 1 Month Acc Days Available` float DEFAULT NULL,
  `Product History 1 Month Acc Estimated GMROI` float DEFAULT NULL,
  `Product History 10 Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History 10 Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History 10 Day Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History 10 Day Acc Quantity Ordered` float DEFAULT NULL,
  `Product History 10 Day Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History 10 Day Acc Quantity Delivered` float DEFAULT NULL,
  `Product History 10 Day Acc Days On Sale` float DEFAULT NULL,
  `Product History 10 Day Acc Days Available` float DEFAULT NULL,
  `Product History 10 Day Acc Estimated GMROI` float DEFAULT NULL,
  `Product History 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Product History 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Product History 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Product History 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  `Product History 1 Week Acc Quantity Ordered` float DEFAULT NULL,
  `Product History 1 Week Acc Quantity Invoiced` float DEFAULT NULL,
  `Product History 1 Week Acc Quantity Delivered` float DEFAULT NULL,
  `Product History 1 Week Acc Days On Sale` float DEFAULT NULL,
  `Product History 1 Week Acc Days Available` float DEFAULT NULL,
  `Product History 1 Week Acc Estimated GMROI` float DEFAULT NULL,
  `Product History For Sale Since Date` datetime DEFAULT NULL,
  `Product History Last Sold Date` datetime DEFAULT NULL,
  `Product History Days On Sale` float DEFAULT NULL,
  `Product History GMROI` float DEFAULT NULL,
  `Product History Total Margin` float DEFAULT NULL,
  `Product History 3 Year Acc Margin` float DEFAULT NULL,
  `Product History 1 Year Acc Margin` float DEFAULT NULL,
  `Product History YearToDay Acc Margin` float DEFAULT NULL,
  `Product History 6 Month Acc Margin` float DEFAULT NULL,
  `Product History 1 Quarter Acc Margin` float DEFAULT NULL,
  `Product History 3 Month Acc Margin` float DEFAULT NULL,
  `Product History 1 Month Acc Margin` float DEFAULT NULL,
  `Product History 10 Day Acc Margin` float DEFAULT NULL,
  `Product History 1 Week Acc Margin` float DEFAULT NULL,
  `Product History Editing Price` decimal(9,2) DEFAULT NULL,
  `Product History Editing RRP` decimal(9,2) DEFAULT NULL,
  `Product History Editing Name` varchar(1024) DEFAULT NULL,
  `Product History Editing Special Characteristic` varchar(255) DEFAULT NULL,
  `Product History Editing Family Special Characteristic` varchar(255) DEFAULT NULL,
  `Product History Editing Units Per Case` float DEFAULT NULL,
  `Product History Editing Unit Type` enum('Piece','Grams','Liters','Meters','Other') DEFAULT NULL,
  PRIMARY KEY (`Product Key`),
  KEY `Product ID` (`Product ID`),
  KEY `Product History Valid From` (`Product History Valid From`),
  KEY `Product History Valid To` (`Product History Valid To`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Store Default Currency`;
CREATE TABLE IF NOT EXISTS `Store Default Currency` (
  `Store Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Store DC Total Invoiced Gross Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Total Invoiced Discount Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Total Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC Total Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 3 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 3 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 3 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 3 Year Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 1 Year Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 1 Year Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 1 Year Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Year Acc Profit` decimal(12,2) DEFAULT NULL,
  `Store DC YearToDay Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC YearToDay Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC YearToDay Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC YearToDay Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 6 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 6 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 6 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 6 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 1 Quarter Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 1 Quarter Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 1 Quarter Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Quarter Acc Profit` decimal(12,2) DEFAULT NULL,
  `Store DC 3 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 3 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 3 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 3 Month Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 1 Month Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 1 Month Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 1 Month Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Month Acc Profit` decimal(12,2) DEFAULT NULL,
  `Store DC 10 Day Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 10 Day Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 10 Day Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 10 Day Acc Profit` decimal(12,2) NOT NULL DEFAULT '0',
  `Store DC 1 Week Acc Invoiced Gross Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 1 Week Acc Invoiced Discount Amount` decimal(12,2) DEFAULT NULL,
  `Store DC 1 Week Acc Invoiced Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Store DC 1 Week Acc Profit` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`Store Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


aqui empieza inikoo 30


CREATE TABLE `Customer Correlation` (
`Customer A` MEDIUMINT UNSIGNED NOT NULL ,
`Customer B` MEDIUMINT UNSIGNED NOT NULL ,
`Correlation` FLOAT NOT NULL ,
PRIMARY KEY ( `Customer A` , `Customer B` )
) ENGINE = MYISAM ;

ALTER TABLE `Customer Correlation` ADD `Store Key` MEDIUMINT UNSIGNED NOT NULL ,ADD INDEX ( `Store Key` );
ALTER TABLE `Category Dimension` ADD `Category Label` VARCHAR( 256 ) NOT NULL AFTER `Category Name` ;
ALTER TABLE `Category Dimension` ADD INDEX ( `Category Name` ) ;

ALTER TABLE `Address Dimension` ADD `Address Street Number Position` ENUM( 'Left', 'Right' ) NOT NULL DEFAULT 'Left' AFTER `Address Street Number` ;
ALTER TABLE `Customer History Bridge` ADD `Type` ENUM( 'Note', 'Order', 'Changes' ) NOT NULL ,ADD INDEX ( `Type` ) ;
ALTER TABLE `Customer History Bridge` CHANGE `Type` `Type` ENUM( 'Notes', 'Orders', 'Changes' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Notes';

ALTER TABLE `Session Dimension` CHANGE `Session Data` `Session Data` LONGBLOB NOT NULL ;

ALTER TABLE `Store Dimension` ADD `Store 3 Year New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Year New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 6 Month New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Quarter New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Month New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 10 Day New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Week New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Day New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `Store Dimension` ADD `Store YearToDay New Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `Store Dimension` ADD `Store 3 Year Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Year Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 6 Month Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Quarter Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Month Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 10 Day Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Week Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Day Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `Store Dimension` ADD `Store YearToDay Lost Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `Store Dimension` ADD `Store 3 Year New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Year New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 6 Month New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Quarter New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Month New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 10 Day New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Week New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store 1 Day New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store YearToDay New Customers Contacts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Customer Dimension` ADD `Customer Main Plain Postal Code` VARCHAR( 64 ) NULL DEFAULT NULL AFTER `Customer Main Postal Code` ,ADD INDEX ( `Customer Main Plain Postal Code` );

ALTER TABLE `User Dimension` ADD `User Theme Background Status` SMALLINT UNSIGNED NULL DEFAULT NULL ;
truncate `Theme Dimension`;
INSERT INTO `Theme Dimension` (`Theme Key`, `Theme Name`, `Theme Common Css`, `Theme Table Css`, `Theme Index Css`, `Theme Dropdown Css`, `Theme Campaign Css`) 
VALUES
(1, 'brown', 'brown_common.css.php', 'brown_table.css', 'brown_index.css', 'brown_dropdown.css', 'brown_marketing_campaigns.css'),
(2, 'green', 'green_common.css.php', 'green_table.css', 'green_index.css', 'green_marketing_campaigns.css', 'green_dropdown.css');
INSERT INTO `Theme Dimension` (`Theme Key`, `Theme Name`, `Theme Common Css`, `Theme Table Css`, `Theme Index Css`, `Theme Dropdown Css`, `Theme Campaign Css`) VALUES
(3, 'magento', 'magento_common.css.php', 'magento_table.css', 'magento_index.css', 'magento_marketing_campaigns.css', 'magento_dropdown.css');
ALTER TABLE `Customer Dimension` ADD INDEX ( `Customer Send Newsletter` ) ;



ALTER TABLE `Store Dimension` 
CHANGE `Store YearToDay Acc Invoiced Gross Amount` `Store Year To Day Acc Invoiced Gross Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
CHANGE `Store YearToDay Acc Invoiced Discount Amount` `Store Year To Day Acc Invoiced Discount Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Store YearToDay Acc Quantity Ordered` `Store Year To Day Acc Quantity Ordered` FLOAT NOT NULL DEFAULT '0', 
CHANGE `Store YearToDay Acc Quantity Invoiced` `Store Year To Day Acc Quantity Invoiced` FLOAT NOT NULL DEFAULT '0', 
CHANGE `Store YearToDay Acc Quantity Delivered` `Store Year To Day Acc Quantity Delivered` FLOAT NOT NULL DEFAULT '0', 
CHANGE `Store YearToDay Acc Customers` `Store Year To Day Acc Customers` MEDIUMINT(8) NOT NULL DEFAULT '0', 
CHANGE `Store YearToDay Acc Pending Orders` `Store Year To Day Acc Pending Orders` MEDIUMINT(8) NOT NULL DEFAULT '0', 
CHANGE `Store YearToDay Acc Invoiced Amount` `Store Year To Day Acc Invoiced Amount` DECIMAL(12,2) NOT NULL DEFAULT
CHANGE `Store YearToDay Acc Invoices` `Store Year To Day Acc Invoices` DECIMAL(12,2) NOT NULL DEFAULT
CHANGE `Store YearToDay Acc Profit` `Store Year To Day Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT
CHANGE `Store YearToDay Acc Days On Sale` `Store Year To Day Acc Days On Sale` FLOAT NOT NULL DEFAULT '0', 
CHANGE `Store YearToDay Acc Days Available` `Store Year To Day Acc Days Available` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Store Dimension`
  DROP `Store 3 Year Acc Customers`,
  DROP `Store 3 Year Acc Pending Orders`,
  DROP `Store 3 Year Acc Days On Sale`,
  DROP `Store 3 Year Acc Days Available`,
  DROP `Store 1 Year Acc Days On Sale`,
  DROP `Store 1 Year Acc Days Available`,
  DROP `Store 1 Year Acc Customers`,
  DROP `Store 1 Year Acc Pending Orders`,
  DROP `Store Year To Day Acc Customers`,
  DROP `Store Year To Day Acc Pending Orders`;
  
  
  ALTER TABLE `Store Dimension`
  DROP `Store 3 Year Acc Invoiced Gross Amount`,
  DROP `Store 1 Year Acc Invoiced Gross Amount`,
  DROP `Store Year To Day Acc Invoiced Gross Amount`,
  DROP `Store 6 Month Acc Invoiced Gross Amount`,
  DROP `Store 6 Month Acc Customers`,
  DROP `Store 6 Month Acc Pending Orders`,
  DROP `Store 6 Month Acc Days On Sale`,
  DROP `Store 6 Month Acc Days Available`,
  DROP `Store 1 Quarter Acc Invoiced Gross Amount`,
  DROP `Store 1 Quarter Acc Days On Sale`,
  DROP `Store 1 Quarter Acc Days Available`,
  DROP `Store 1 Quarter Acc Customers`,
  DROP `Store 1 Quarter Acc Pending Orders`,
  DROP `Store 3 Month Acc Invoiced Gross Amount`,
  DROP `Store 3 Month Acc Customers`,
  DROP `Store 3 Month Acc Pending Orders`,
  DROP `Store 3 Month Acc Days On Sale`,
  DROP `Store 3 Month Acc Days Available`,
  DROP `Store 1 Month Acc Invoiced Gross Amount`,
  DROP `Store 1 Month Acc Days On Sale`,
  DROP `Store 1 Month Acc Days Available`,
  DROP `Store 1 Month Acc Customers`,
  DROP `Store 1 Month Acc Pending Orders`,
  DROP `Store 10 Day Acc Invoiced Gross Amount`,
  DROP `Store 10 Day Acc Customers`,
  DROP `Store 10 Day Acc Pending Orders`,
  DROP `Store 10 Day Acc Days On Sale`,
  DROP `Store 10 Day Acc Days Available`,
  DROP `Store 1 Week Acc Invoiced Gross Amount`,
  DROP `Store 1 Week Acc Customers`,
  DROP `Store 1 Week Acc Pending Orders`;

ALTER TABLE `Store Dimension` CHANGE `Store Locale` `Store Locale` ENUM( 'en_GB', 'de_DE', 'fr_FR', 'es_ES', 'pl_PL', 'it_IT' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'en_GB';

ALTER TABLE `Store Dimension`
  DROP `Store Year To Day Acc Days On Sale`,
  DROP `Store Year To Day Acc Days Available`,
  DROP `Store 1 Week Acc Days On Sale`,
  DROP `Store 1 Week Acc Days Available`;

ALTER TABLE `Store Dimension` 
CHANGE `Store 3 Year New Customers` `Store 3 Year New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', 
CHANGE `Store 1 Year New Customers` `Store 1 Year New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', 
CHANGE `Store 6 Month New Customers` `Store 6 Month New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', 
CHANGE `Store 1 Quarter New Customers` `Store 1 Quarter New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', 
CHANGE `Store 1 Month New Customers` `Store 1 Month New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', 
CHANGE `Store 10 Day New Customers` `Store 10 Day New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', 
CHANGE `Store 1 Day New Customers` `Store 1 Day New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', 
CHANGE `Store YearToDay New Customers` `Store Year To Day New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT,
CHANGE `Store 1 Week New Customers` `Store 1 Week New Contacts With Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT,
ALTER TABLE `Store Dimension` CHANGE `Store 3 Year Lost Customers` `Store 3 Year Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Year Lost Customers` `Store 1 Year Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 6 Month Lost Customers` `Store 6 Month Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Quarter Lost Customers` `Store 1 Quarter Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `Store Dimension` CHANGE `Store 1 Month Lost Customers` `Store 1 Month Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 10 Day Lost Customers` `Store 10 Day Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Week Lost Customers` `Store 1 Week Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Day Lost Customers` `Store 1 Day Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store YearToDay Lost Customers` `Store Year To Day Lost Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` CHANGE `Store 3 Year New Customers Contacts` `Store 3 Year New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Year New Customers Contacts` `Store 1 Year New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 6 Month New Customers Contacts` `Store 6 Month New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Quarter New Customers Contacts` `Store 1 Quarter New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Month New Customers Contacts` `Store 1 Month New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` CHANGE `Store 10 Day New Customers Contacts` `Store 10 Day New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Week New Customers Contacts` `Store 1 Week New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store 1 Day New Customers Contacts` `Store 1 Day New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Store YearToDay New Customers Contacts` `Store Year To Day New Contacts` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `Store Dimension` ADD `Store Month To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Year To Day Acc Profit` ,
ADD `Store Month To Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Month To Day Acc Invoiced Discount Amount` ,
ADD `Store Month To Day Acc Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Month To Day Acc Invoiced Amount` ,
ADD `Store Month To Day Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Month To Day Acc Invoices` ;

ALTER TABLE `Store Dimension` ADD `Store Week To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Month To Day Acc Profit` ,
ADD `Store Week To Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Week To Day Acc Invoiced Discount Amount` ,
ADD `Store Week To Day Acc Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Week To Day Acc Invoiced Amount` ,
ADD `Store Week To Day Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Week To Day Acc Invoices` ;


ALTER TABLE `Store Dimension` 
ADD `Store 1 Year Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Year Acc Invoices` ,
ADD `Store 1 Year Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Year Acc 1YB Invoiced Discount Amount` ,
ADD `Store 1 Year Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Year Acc 1YB Invoiced Amount` ,
ADD `Store 1 Year Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Year Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store Year To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Year To Day Acc Profit` ,
ADD `Store Year To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Year To Day Acc 1YB Invoiced Discount Amount` ,
ADD `Store Year To Day Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Year To Day Acc 1YB Invoiced Amount` ,
ADD `Store Year To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Year To Day Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store Month To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Month To Day Acc Profit` ,
ADD `Store Month To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Month To Day Acc 1YB Invoiced Discount Amount` ,
ADD `Store Month To Day Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Month To Day Acc 1YB Invoiced Amount` ,
ADD `Store Month To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Month To Day Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store Week To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Week To Day Acc Invoices` ,
ADD `Store Week To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Week To Day Acc 1YB Invoiced Discount Amount` ,
ADD `Store Week To Day Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Week To Day Acc 1YB Invoiced Amount` ,
ADD `Store Week To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Week To Day Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store 6 Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 6 Month Acc Profit` ,
ADD `Store 6 Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 6 Month Acc 1YB Invoiced Discount Amount` ,
ADD `Store 6 Month Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 6 Month Acc 1YB Invoiced Amount` ,
ADD `Store 6 Month Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 6 Month Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store 1 Quarter Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Quarter Acc Invoices` ,
ADD `Store 1 Quarter Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Quarter Acc 1YB Invoiced Discount Amount` ,
ADD `Store 1 Quarter Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Quarter Acc 1YB Invoiced Amount` ,
ADD `Store 1 Quarter Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Quarter Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store 1 Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Month Acc Invoices` ,
ADD `Store 1 Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Month Acc 1YB Invoiced Discount Amount` ,
ADD `Store 1 Month Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Month Acc 1YB Invoiced Amount` ,
ADD `Store 1 Month Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Month Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store 10 Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 10 Day Acc Profit` ,
ADD `Store 10 Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 10 Day Acc 1YB Invoiced Discount Amount` ,
ADD `Store 10 Day Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 10 Day Acc 1YB Invoiced Amount` ,
ADD `Store 10 Day Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 10 Day Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store 1 Week Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Week Acc Invoices` ,
ADD `Store 1 Week Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Week Acc 1YB Invoiced Discount Amount` ,
ADD `Store 1 Week Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 1 Week Acc 1YB Invoiced Amount` ,
ADD `Store 1 Week Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Week Acc 1YB Invoices` ;



ALTER TABLE `Store Dimension`
  DROP `Store 3 Year Acc Quantity Ordered`,
  DROP `Store 3 Year Acc Quantity Invoiced`,
  DROP `Store 3 Year Acc Quantity Delivered`,
  DROP `Store 1 Year Acc Quantity Ordered`,
  DROP `Store 1 Year Acc Quantity Invoiced`,
  DROP `Store 1 Year Acc Quantity Delivered`,
  DROP `Store Year To Day Acc Quantity Ordered`,
  DROP `Store Year To Day Acc Quantity Invoiced`,
  DROP `Store Year To Day Acc Quantity Delivered`,
  DROP `Store 6 Month Acc Quantity Ordered`,
  DROP `Store 6 Month Acc Quantity Invoiced`,
  DROP `Store 6 Month Acc Quantity Delivered`,
  DROP `Store 1 Quarter Acc Quantity Ordered`,
  DROP `Store 1 Quarter Acc Quantity Invoiced`,
  DROP `Store 1 Quarter Acc Quantity Delivered`,
  DROP `Store 3 Month Acc Quantity Ordered`,
  DROP `Store 3 Month Acc Quantity Invoiced`,
  DROP `Store 3 Month Acc Quantity Delivered`,
  DROP `Store 1 Month Acc Quantity Ordered`,
  DROP `Store 1 Month Acc Quantity Invoiced`,
  DROP `Store 1 Month Acc Quantity Delivered`,
  DROP `Store 10 Day Acc Quantity Ordered`,
  DROP `Store 10 Day Acc Quantity Invoiced`,
  DROP `Store 10 Day Acc Quantity Delivered`,
  DROP `Store 1 Week Acc Quantity Ordered`,
  DROP `Store 1 Week Acc Quantity Invoiced`,
  DROP `Store 1 Week Acc Quantity Delivered`;
  
  ALTER TABLE `Store Dimension` CHANGE `Store 1 Year Acc Invoiced Discount Amount` `Store 1 Year Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Year Acc Profit` `Store 1 Year Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 6 Month Acc Invoiced Discount Amount` `Store 6 Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Quarter Acc Invoiced Discount Amount` `Store 1 Quarter Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Dimension` CHANGE `Store 1 Quarter Acc Profit` `Store 1 Quarter Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 3 Month Acc Invoiced Discount Amount` `Store 3 Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Month Acc Invoiced Discount Amount` `Store 1 Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Month Acc Profit` `Store 1 Month Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 10 Day Acc Invoiced Discount Amount` `Store 10 Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Dimension` CHANGE `Store 1 Week Acc Invoiced Discount Amount` `Store 1 Week Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Week Acc Profit` `Store 1 Week Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00'

ALTER TABLE `Store Dimension`
  DROP `Store 3 Month Acc Invoiced Discount Amount`,
  DROP `Store 3 Month Acc Invoiced Amount`,
  DROP `Store 3 Month Acc Invoices`,
  DROP `Store 3 Month Acc Profit`;
  
  
 ALTER TABLE `Store Dimension` CHANGE `Store Year To Day Acc Invoiced Discount Amount` `Store Year To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Year To Day Acc Invoiced Amount` `Store Year To Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Year To Day Acc Invoices` `Store Year To Day Acc Invoices` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Year To Day Acc Profit` `Store Year To Day Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Dimension` CHANGE `Store Year To Day Acc 1YB Invoiced Discount Amount` `Store Year To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Year To Day Acc 1YB Invoiced Amount` `Store Year To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Year To Day Acc 1YB Invoices` `Store Year To Day Acc 1YB Invoices` MEDIUMINT( 9 ) NULL DEFAULT '0',
CHANGE `Store Year To Day Acc 1YB Profit` `Store Year To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';
ALTER TABLE `Store Dimension` CHANGE `Store Month To Day Acc Invoiced Discount Amount` `Store Month To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Month To Day Acc Invoiced Amount` `Store Month To Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Month To Day Acc Invoices` `Store Month To Day Acc Invoices` MEDIUMINT( 9 ) NULL DEFAULT '0',
CHANGE `Store Month To Day Acc Profit` `Store Month To Day Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';


ALTER TABLE `Store Dimension` CHANGE `Store Month To Day Acc 1YB Invoiced Discount Amount` `Store Month To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Month To Day Acc 1YB Invoiced Amount` `Store Month To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Month To Day Acc 1YB Invoices` `Store Month To Day Acc 1YB Invoices` MEDIUMINT( 9 ) NULL DEFAULT '0',
CHANGE `Store Month To Day Acc 1YB Profit` `Store Month To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Week To Day Acc Invoiced Discount Amount` `Store Week To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Week To Day Acc Invoiced Amount` `Store Week To Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Dimension` CHANGE `Store Week To Day Acc Invoices` `Store Week To Day Acc Invoices` MEDIUMINT( 9 ) NULL DEFAULT '0',
CHANGE `Store Week To Day Acc Profit` `Store Week To Day Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Week To Day Acc 1YB Invoiced Discount Amount` `Store Week To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Week To Day Acc 1YB Invoiced Amount` `Store Week To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store Week To Day Acc 1YB Invoices` `Store Week To Day Acc 1YB Invoices` MEDIUMINT( 9 ) NULL DEFAULT '0',
CHANGE `Store Week To Day Acc 1YB Profit` `Store Week To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Dimension` CHANGE `Store 6 Month Acc Invoiced Amount` `Store 6 Month Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 6 Month Acc Invoices` `Store 6 Month Acc Invoices` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 6 Month Acc Profit` `Store 6 Month Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 6 Month Acc 1YB Invoiced Discount Amount` `Store 6 Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 6 Month Acc 1YB Invoiced Amount` `Store 6 Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 6 Month Acc 1YB Invoices` `Store 6 Month Acc 1YB Invoices` MEDIUMINT( 9 ) NULL DEFAULT '0';

ALTER TABLE `Store Dimension` CHANGE `Store 3 Year Acc Invoiced Discount Amount` `Store 3 Year Acc Invoiced Discount Amount` DECIMAL( 10, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 3 Year Acc Invoiced Amount` `Store 3 Year Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 3 Year Acc Invoices` `Store 3 Year Acc Invoices` MEDIUMINT( 8 ) NULL DEFAULT '0',
CHANGE `Store 3 Year Acc Profit` `Store 3 Year Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Year Acc Invoiced Amount` `Store 1 Year Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Dimension` CHANGE `Store 1 Year Acc Invoices` `Store 1 Year Acc Invoices` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT '0',
CHANGE `Store 1 Year Acc 1YB Invoiced Discount Amount` `Store 1 Year Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Year Acc 1YB Invoiced Amount` `Store 1 Year Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Year Acc 1YB Invoices` `Store 1 Year Acc 1YB Invoices` MEDIUMINT( 9 ) UNSIGNED NULL DEFAULT '0',
CHANGE `Store 1 Year Acc 1YB Profit` `Store 1 Year Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Dimension` CHANGE `Store 3 Year Acc Invoices` `Store 3 Year Acc Invoices` MEDIUMINT( 8 ) NULL DEFAULT '0',
CHANGE `Store Year To Day Acc Invoices` `Store Year To Day Acc Invoices` MEDIUMINT( 8 ) NULL DEFAULT '0',
CHANGE `Store Year To Day Acc 1YB Invoices` `Store Year To Day Acc 1YB Invoices` MEDIUMINT( 8 ) NULL DEFAULT '0',
CHANGE `Store Month To Day Acc Invoices` `Store Month To Day Acc Invoices` MEDIUMINT( 8 ) NULL DEFAULT '0',
CHANGE `Store Week To Day Acc Invoices` `Store Week To Day Acc Invoices` MEDIUMINT( 8 ) NULL DEFAULT '0';

ALTER TABLE `Store Dimension` CHANGE `Store 1 Month Acc 1YB Invoices` `Store 1 Month Acc 1YB Invoices` MEDIUMINT( 9 ) UNSIGNED NULL DEFAULT '0',
CHANGE `Store 10 Day Acc Invoices` `Store 10 Day Acc Invoices` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT '0',
CHANGE `Store 10 Day Acc 1YB Invoices` `Store 10 Day Acc 1YB Invoices` MEDIUMINT( 9 ) UNSIGNED NULL DEFAULT '0',
CHANGE `Store 1 Week Acc Invoices` `Store 1 Week Acc Invoices` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT '0',
CHANGE `Store 1 Week Acc 1YB Invoices` `Store 1 Week Acc 1YB Invoices` MEDIUMINT( 9 ) UNSIGNED NULL DEFAULT '0'

ALTER TABLE `Store Dimension` CHANGE `Store 6 Month Acc 1YB Profit` `Store 6 Month Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Quarter Acc Invoiced Amount` `Store 1 Quarter Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Quarter Acc Invoices` `Store 1 Quarter Acc Invoices` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT '0',
CHANGE `Store 1 Quarter Acc 1YB Invoiced Discount Amount` `Store 1 Quarter Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Quarter Acc 1YB Invoiced Amount` `Store 1 Quarter Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Quarter Acc 1YB Invoices` `Store 1 Quarter Acc 1YB Invoices` MEDIUMINT( 9 ) UNSIGNED NULL DEFAULT '0';

ALTER TABLE `Store Dimension` CHANGE `Store 1 Quarter Acc 1YB Profit` `Store 1 Quarter Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Month Acc Invoiced Amount` `Store 1 Month Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Month Acc Invoices` `Store 1 Month Acc Invoices` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT '0',
CHANGE `Store 1 Month Acc 1YB Invoiced Discount Amount` `Store 1 Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Month Acc 1YB Invoiced Amount` `Store 1 Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Month Acc 1YB Profit` `Store 1 Month Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 10 Day Acc Invoiced Amount` `Store 10 Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';
ALTER TABLE `Store Dimension` CHANGE `Store 10 Day Acc 1YB Profit` `Store 10 Day Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Week Acc Invoiced Amount` `Store 1 Week Acc Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Week Acc 1YB Invoiced Discount Amount` `Store 1 Week Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Week Acc 1YB Invoiced Amount` `Store 1 Week Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store 1 Week Acc 1YB Profit` `Store 1 Week Acc 1YB Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` CHANGE `Store DC YearToDay Acc Invoiced Gross Amount` `Store DC Year To Day Acc Invoiced Gross Amount` DECIMAL( 12, 2 ) NULL DEFAULT NULL ,
CHANGE `Store DC YearToDay Acc Invoiced Discount Amount` `Store DC Year To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT NULL ,
CHANGE `Store DC YearToDay Acc Invoiced Amount` `Store DC Year To Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';
ALTER TABLE `Store Default Currency` CHANGE `Store DC YearToDay Acc Profit` `Store DC Year To Day Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency`
  DROP `Store DC 3 Month Acc Invoiced Gross Amount`,
  DROP `Store DC 3 Month Acc Invoiced Discount Amount`,
  DROP `Store DC 3 Month Acc Invoiced Amount`,
  DROP `Store DC 3 Month Acc Profit`;
  
  
  ALTER TABLE `Store Default Currency`
  DROP `Store DC Total Invoiced Gross Amount`,
  DROP `Store DC 3 Year Acc Invoiced Gross Amount`,
  DROP `Store DC 1 Year Acc Invoiced Gross Amount`,
  DROP `Store DC Year To Day Acc Invoiced Gross Amount`,
  DROP `Store DC 6 Month Acc Invoiced Gross Amount`,
  DROP `Store DC 1 Quarter Acc Invoiced Gross Amount`,
  DROP `Store DC 1 Month Acc Invoiced Gross Amount`,
  DROP `Store DC 10 Day Acc Invoiced Gross Amount`,
  DROP `Store DC 1 Week Acc Invoiced Gross Amount`;
  
  ALTER TABLE `Store Default Currency` CHANGE `Store DC 3 Year Acc Invoiced Discount Amount` `Store DC 3 Year Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC 1 Year Acc Invoiced Discount Amount` `Store DC 1 Year Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC 1 Year Acc Profit` `Store DC 1 Year Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC Year To Day Acc Invoiced Discount Amount` `Store DC Year To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC 6 Month Acc Invoiced Discount Amount` `Store DC 6 Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` CHANGE `Store DC 1 Quarter Acc Invoiced Discount Amount` `Store DC 1 Quarter Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC 1 Month Acc Invoiced Discount Amount` `Store DC 1 Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC 1 Month Acc Profit` `Store DC 1 Month Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC 10 Day Acc Invoiced Discount Amount` `Store DC 10 Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC 1 Week Acc Invoiced Discount Amount` `Store DC 1 Week Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NULL DEFAULT '0.00',
CHANGE `Store DC 1 Week Acc Profit` `Store DC 1 Week Acc Profit` DECIMAL( 12, 2 ) NULL DEFAULT '0.00';
ALTER TABLE `Store Default Currency` CHANGE `Store DC 1 Quarter Acc Profit` `Store DC 1 Quarter Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';
ALTER TABLE `Store Default Currency` CHANGE `Store DC 3 Year Acc Invoiced Discount Amount` `Store DC 3 Year Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 1 Year Acc Invoiced Discount Amount` `Store DC 1 Year Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 1 Year Acc Profit` `Store DC 1 Year Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC Year To Day Acc Invoiced Discount Amount` `Store DC Year To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 6 Month Acc Invoiced Discount Amount` `Store DC 6 Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 1 Quarter Acc Invoiced Discount Amount` `Store DC 1 Quarter Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 1 Month Acc Invoiced Discount Amount` `Store DC 1 Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` CHANGE `Store DC 1 Month Acc Profit` `Store DC 1 Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 10 Day Acc Invoiced Discount Amount` `Store DC 10 Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 1 Week Acc Invoiced Discount Amount` `Store DC 1 Week Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 1 Week Acc Profit` `Store DC 1 Week Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC Month To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store DC Year To Day Acc Profit` ,
ADD `Store DC Month To Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store DC Month To Day Acc Invoiced Discount Amount` ,
ADD `Store DC Month To Day Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store DC Month To Day Acc Invoiced Amount` ,
ADD `Store DC Week To Day Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store DC Month To Day Acc Profit` ,
ADD `Store DC Week To Day Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store DC Week To Day Acc Invoiced Discount Amount` ,
ADD `Store DC Week To Day Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store DC Week To Day Acc Invoiced Amount` ;

ALTER TABLE `Store Default Currency` ADD `Store DC 1 Year Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 1 Year Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 1 Year Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC Year To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Year To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Year To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC Month To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Month To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Month To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC Week To Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Week To Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Week To Day Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC 6 Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 6 Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 6 Month Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC 6 Quarter Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 6 Quarter Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 6 Quarter Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC 6 Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 6 Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 6 Month Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC 10 Day Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 10 Day Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 10 Day Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC 1 Week Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 1 Week Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 1 Week Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';


ALTER TABLE `Store Default Currency` ADD `Store DC Yesterday Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Yesterday Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Yesterday Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC Last Week Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Last Week Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Last Week Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC Last Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Last Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Last Month Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Dimension` 
ADD `Store Yesterday Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Week Acc 1YB Profit` ,
ADD `Store Yesterday Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Yesterday Acc Invoiced Discount Amount` ,
ADD `Store Yesterday Acc Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Yesterday Acc Invoiced Amount` ,
ADD `Store Yesterday Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Yesterday Acc Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store Yesterday Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Yesterday Acc Profit` ,
ADD `Store Yesterday Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Yesterday Acc 1YB Invoiced Discount Amount` ,
ADD `Store Yesterday Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Yesterday Acc 1YB Invoiced Amount` ,
ADD `Store Yesterday Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Yesterday Acc 1YB Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store Last Week Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Yesterday Acc 1YB Profit` ,
ADD `Store Last Week Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Week Acc Invoiced Discount Amount` ,
ADD `Store Last Week Acc Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Last Week Acc Invoiced Amount` ,
ADD `Store Last Week Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Week Acc Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store Last Week Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Week Acc Profit` ,
ADD `Store Last Week Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Week Acc 1YB Invoiced Discount Amount` ,
ADD `Store Last Week Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Last Week Acc 1YB Invoiced Amount` ,
ADD `Store Last Week Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Week Acc 1YB Invoices` ;


ALTER TABLE `Store Dimension` 
ADD `Store Last Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Week Acc 1YB Profit` ,
ADD `Store Last Month Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Month Acc Invoiced Discount Amount` ,
ADD `Store Last Month Acc Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Last Month Acc Invoiced Amount` ,
ADD `Store Last Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Month Acc Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store Last Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Month Acc Profit` ,
ADD `Store Last Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Month Acc 1YB Invoiced Discount Amount` ,
ADD `Store Last Month Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Last Month Acc 1YB Invoiced Amount` ,
ADD `Store Last Month Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Last Month Acc 1YB Invoices` ;


ALTER TABLE `Store Dimension` 
ADD `Store Today Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 1 Week Acc 1YB Profit` ,
ADD `Store Today Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Today Acc Invoiced Discount Amount` ,
ADD `Store Today Acc Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Today Acc Invoiced Amount` ,
ADD `Store Today Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Today Acc Invoices` ;

ALTER TABLE `Store Dimension` 
ADD `Store Today Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Today Acc Profit` ,
ADD `Store Today Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Today Acc 1YB Invoiced Discount Amount` ,
ADD `Store Today Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store Today Acc 1YB Invoiced Amount` ,
ADD `Store Today Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store Today Acc 1YB Invoices` ;

ALTER TABLE `Store Default Currency` 
ADD `Store DC Today Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Today Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Today Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';


ALTER TABLE `Store Default Currency` ADD `Store DC Today Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Today Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Today Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` ADD `Store DC 1 Month Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 1 Month Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 1 Month Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';
ALTER TABLE `Store Default Currency` CHANGE `Store DC 6 Quarter Acc 1YB Invoiced Discount Amount` `Store DC 1 Quarter Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 6 Quarter Acc 1YB Invoiced Amount` `Store DC 1 Quarter Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Store DC 6 Quarter Acc 1YB Profit` `Store DC 1 Quarter Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` 
ADD `Store DC Last Month Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Last Month Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Last Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` 
ADD `Store DC Last Week Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Last Week Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Last Week Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Store Default Currency` 
ADD `Store DC Yesterday Acc Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Yesterday Acc Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC Yesterday Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';


ALTER TABLE `Store Dimension` 
ADD `Store 3 Year Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 3 Year Acc Profit` ,
ADD `Store 3 Year Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 3 Year Acc 1YB Invoiced Discount Amount` ,
ADD `Store 3 Year Acc 1YB Invoices` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Store 3 Year Acc 1YB Invoiced Amount` ,
ADD `Store 3 Year Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Store 3 Year Acc 1YB Invoices` ;

ALTER TABLE `Store Default Currency` 
ADD `Store DC 3 Year Acc 1YB Invoiced Discount Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 3 Year Acc 1YB Invoiced Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
ADD `Store DC 3 Year Acc 1YB Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Customer Dimension`
  DROP `New Customer`,
  DROP `New Served Customer`,
  DROP `Active Customer`,
  DROP `Actual Customer`;
    ALTER TABLE `Customer Dimension` CHANGE `Customer Type by Activity` `Customer Type by Activity` ENUM('Active', 'Losing', 'Lost' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active';
ALTER TABLE `Customer Dimension`
  DROP `Customer Category`,
  DROP `Customer Category Data`;
   ALTER TABLE `Customer Dimension` ADD `Customer Active` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes',ADD INDEX ( `Customer Active` );
  ALTER TABLE `Customer Dimension` ADD `Customer With Orders` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No',ADD INDEX ( `Customer Active` );
  
  ALTER TABLE `Store Dimension` ADD `Store Losing Customer Interval` BIGINT UNSIGNED NOT NULL DEFAULT '5259487',
ADD `Store Lost Customer Interval` BIGINT UNSIGNED NOT NULL DEFAULT '7889231';

ALTER TABLE `Customer Dimension` CHANGE `Customer Order Interval` `Customer Order Interval` BIGINT NULL DEFAULT NULL COMMENT 'Average order interval messired in seconds',
CHANGE `Customer Order Interval STD` `Customer Order Interval STD` BIGINT NULL DEFAULT NULL COMMENT 'standard deviation';

ALTER TABLE `Store Dimension`
  DROP `Store Total Customers`,
  DROP `Store Total Customer Contacts`,
  DROP `Store New Customer Contacts`,
  DROP `Store Active Customers`,
  DROP `Store New Customers`,
  DROP `Store Lost Customers`;
  
  
  ALTER TABLE `Store Dimension` ADD `Store Contacts` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store Contacts With Orders` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store New Contacts` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store Active Contacts` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store Losing Contacts` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store Lost Contacts` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store New Contacts With Orders` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store Active Contacts With Orders` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store Losing Contacts With Orders` INT UNSIGNED NOT NULL DEFAULT '0',
ADD `Store Lost Contacts With Orders` INT UNSIGNED NOT NULL DEFAULT '0';

Inikoo-31
ALTER TABLE `Category Dimension` ADD `Category Number Subjects Not Assigned` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Category Dimension` CHANGE `Category Number Subjects Not Assigned` `Category Children Subjects Not Assigned` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Category Dimension` ADD `Category Children Subjects Assigned` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `Category Number Subjects` ;

ALTER TABLE `Customer Correlation` ADD INDEX ( `Customer A` ) ;
ALTER TABLE `Customer Correlation` ADD INDEX ( `Customer B` ) ;

ALTER TABLE `Customer Correlation` CHANGE `Customer A` `Customer Key A` MEDIUMINT( 8 ) UNSIGNED NOT NULL ,CHANGE `Customer B` `Customer Key B` MEDIUMINT( 8 ) UNSIGNED NOT NULL ;

ALTER TABLE `Customer Correlation` ADD `Customer Name A` VARCHAR( 256 ) NOT NULL AFTER `Customer Key A` ;
ALTER TABLE `Customer Correlation` ADD `Customer Name B` VARCHAR( 256 ) NOT NULL AFTER `Customer Key B` ;
ALTER TABLE `Customer Correlation` CHANGE `Customer Name A` `Customer Name A` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Customer Correlation` CHANGE `Customer Name B` `Customer Name B` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 

ALTER TABLE `Customer Correlation` ADD INDEX ( `Customer Name A` ( 8 ) ) ;
ALTER TABLE `Customer Correlation` ADD INDEX ( `Customer Name B` ( 8 ) ) ;

ALTER TABLE `Customer Correlation` CHANGE `Customer Key A` `Customer A Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ,
CHANGE `Customer Name A` `Customer A Name` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Customer Key B` `Customer B Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ,
CHANGE `Customer Name B` `Customer B Name` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `Contact Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Company', 'Supplier', 'Customer', 'Staff' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Company';
ALTER TABLE `Company Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Supplier', 'Customer', 'Contact', 'HQ' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Customer';
ALTER TABLE `Corporation Dimension` CHANGE `Corporation Name` `HQ Name` VARCHAR( 245 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Corporation Currency` `HQ Currency` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'GBP',
CHANGE `Corporation Company Key` `HQ Company Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ;
RENAME TABLE `Corporation Dimension` TO `HQ Dimension` ;
ALTER TABLE `Corporation Event Dimension` CHANGE `Corporation Event Key` `HQ Event Key` INT( 11 ) NOT NULL AUTO_INCREMENT ;
RENAME TABLE `Corporation Event Dimension` TO `HQ Event Dimension` ;

CREATE TABLE `Customer Deleted Dimension` (
`Customer Key` MEDIUMINT UNSIGNED NOT NULL ,
`Customer Store Key` SMALLINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Customer Key` )
) ENGINE = MYISAM ;
ALTER TABLE `Customer Deleted Dimension` ADD `Customer Card` TEXT NOT NULL ;
ALTER TABLE `Customer Deleted Dimension` ADD `Customer Deleted Date` DATETIME NOT NULL ,ADD `Customer Deleted Note` TEXT NOT NULL ;
ALTER TABLE `History Dimension` CHANGE `Preposition` `Preposition` ENUM( 'about', '', 'to', 'on' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

update  `History Dimension` set `Preposition`='on' , `Direct Object`='Note',`Direct Object Key`=0    where `Subject`='Staff' and `Action`='created' and `Indirect Object`='Customer' and `Direct Object`='Customer' ;
CREATE TABLE `Customer Merge Bridge` (
`Merged Customer Key` MEDIUMINT UNSIGNED NOT NULL ,
`Customer Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Merged Customer Key` , `Customer Key` )
) ENGINE = MYISAM ;

ALTER TABLE `Customer Merge Bridge` ADD `Date Merged` DATETIME NULL DEFAULT NULL ,ADD INDEX ( `Date Merged` ); 

truncate `Theme Dimension`;
CREATE TABLE IF NOT EXISTS `Theme Dimension` (
  `Theme Key` int(11) NOT NULL AUTO_INCREMENT,
  `Theme Name` varchar(255) NOT NULL,
  `Theme Css` varchar(255) NOT NULL,
  PRIMARY KEY (`Theme Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `Theme Dimension`
--

INSERT INTO `Theme Dimension` (`Theme Key`, `Theme Name`, `Theme Css`) VALUES
(1, 'brown', 'brown_theme.css.php'),
(2, 'green', 'green_theme.css.php'),
(3, 'magento', 'magento_theme.css.php'),
(4, 'Black', 'black_theme.css.php'),
(5, 'chrismas', 'chrismas_theme.css.php'),
(6, 'puple', 'puple_theme.css.php'),
(7, 'radish', 'radish_theme.css.php');

ALTER TABLE `Part Dimension`
  DROP `Part Total Required`,
  DROP `Part Total Provided`,
  DROP `Part Total Lost`,
  DROP `Part Total Broken`,
  DROP `Part Total Adquired`,
  DROP `Part Total Sold`,
  DROP `Part Total Given`,
  DROP `Part Total Sold Amount`,
  DROP `Part Total Absolute Profit`,
  DROP `Part Total Profit When Sold`,
  DROP `Part Total Absolute Profit After Storing`,
  DROP `Part Total Profit When Sold After Storing`,
  DROP `Part Total Margin`,
  DROP `Part Total AVG Stock`,
  DROP `Part Total AVG Stock Value`,
  DROP `Part Total Keeping Days`,
  DROP `Part Total Out of Stock Days`,
  DROP `Part Total Unknown Stock Days`,
  DROP `Part Total GMROI`,
  DROP `Part 1 Year Acc Required`,
  DROP `Part 1 Year Acc Provided`,
  DROP `Part 1 Year Acc Lost`,
  DROP `Part 1 Year Acc Broken`,
  DROP `Part 1 Year Acc Adquired`,
  DROP `Part 1 Year Acc Sold`,
  DROP `Part 1 Year Acc Given`,
  DROP `Part 1 Year Acc Sold Amount`,
  DROP `Part 1 Year Acc Absolute Profit`,
  DROP `Part 1 Year Acc Profit When Sold`,
  DROP `Part 1 Year Acc Absolute Profit After Storing`,
  DROP `Part 1 Year Acc Profit When Sold After Storing`,
  DROP `Part 1 Year Acc Margin`,
  DROP `Part 1 Year Acc AVG Stock`,
  DROP `Part 1 Year Acc AVG Stock Value`,
  DROP `Part 1 Year Acc Keeping Days`,
  DROP `Part 1 Year Acc Out of Stock Days`,
  DROP `Part 1 Year Acc Unknown Stock Days`,
  DROP `Part 1 Year Acc GMROI`,
  DROP `Part 1 Quarter Acc Required`,
  DROP `Part 1 Quarter Acc Provided`,
  DROP `Part 1 Quarter Acc Lost`,
  DROP `Part 1 Quarter Acc Broken`,
  DROP `Part 1 Quarter Acc Adquired`,
  DROP `Part 1 Quarter Acc Sold`,
  DROP `Part 1 Quarter Acc Given`,
  DROP `Part 1 Quarter Acc Sold Amount`,
  DROP `Part 1 Quarter Acc Absolute Profit`,
  DROP `Part 1 Quarter Acc Profit When Sold`,
  DROP `Part 1 Quarter Acc Absolute Profit After Storing`,
  DROP `Part 1 Quarter Acc Profit When Sold After Storing`,
  DROP `Part 1 Quarter Acc Margin`,
  DROP `Part 1 Quarter Acc AVG Stock`,
  DROP `Part 1 Quarter Acc AVG Stock Value`,
  DROP `Part 1 Quarter Acc Keeping Days`,
  DROP `Part 1 Quarter Acc Out of Stock Days`,
  DROP `Part 1 Quarter Acc Unknown Stock Days`,
  DROP `Part 1 Quarter Acc GMROI`,
  DROP `Part 1 Month Acc Required`,
  DROP `Part 1 Month Acc Provided`,
  DROP `Part 1 Month Acc Lost`,
  DROP `Part 1 Month Acc Broken`,
  DROP `Part 1 Month Acc Adquired`,
  DROP `Part 1 Month Acc Sold`,
  DROP `Part 1 Month Acc Given`,
  DROP `Part 1 Month Acc Sold Amount`,
  DROP `Part 1 Month Acc Absolute Profit`,
  DROP `Part 1 Month Acc Profit When Sold`,
  DROP `Part 1 Month Acc Absolute Profit After Storing`,
  DROP `Part 1 Month Acc Profit When Sold After Storing`,
  DROP `Part 1 Month Acc Margin`,
  DROP `Part 1 Month Acc AVG Stock`,
  DROP `Part 1 Month Acc AVG Stock Value`,
  DROP `Part 1 Month Acc Keeping Days`,
  DROP `Part 1 Month Acc Out of Stock Days`,
  DROP `Part 1 Month Acc Unknown Stock Days`,
  DROP `Part 1 Month Acc GMROI`,
  DROP `Part 1 Week Acc Required`,
  DROP `Part 1 Week Acc Provided`,
  DROP `Part 1 Week Acc Lost`,
  DROP `Part 1 Week Acc Broken`,
  DROP `Part 1 Week Acc Adquired`,
  DROP `Part 1 Week Acc Sold`,
  DROP `Part 1 Week Acc Given`,
  DROP `Part 1 Week Acc Sold Amount`,
  DROP `Part 1 Week Acc Absolute Profit`,
  DROP `Part 1 Week Acc Profit When Sold`,
  DROP `Part 1 Week Acc Absolute Profit After Storing`,
  DROP `Part 1 Week Acc Profit When Sold After Storing`,
  DROP `Part 1 Week Acc Margin`,
  DROP `Part 1 Week Acc AVG Stock`,
  DROP `Part 1 Week Acc AVG Stock Value`,
  DROP `Part 1 Week Acc Keeping Days`,
  DROP `Part 1 Week Acc Out of Stock Days`,
  DROP `Part 1 Week Acc Unknown Stock Days`,
  DROP `Part 1 Week Acc GMROI`;
  
   ALTER TABLE `Part Dimension` 
  ADD `Part 3 Year Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 3 Year Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 3 Year Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 3 Year Acc GMROI` FLOAT NOT NULL DEFAULT '0';
 
  ALTER TABLE `Part Dimension` 
  ADD `Part 1 Year Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Year Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Year Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Year Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
   ALTER TABLE `Part Dimension` 
  ADD `Part 6 Month Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 6 Month Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 6 Month Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 6 Month Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
  
   ALTER TABLE `Part Dimension` 
  ADD `Part 1 Quarter Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Quarter Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Quarter Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Quarter Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
   ALTER TABLE `Part Dimension` 
  ADD `Part 1 Month Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Month Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Month Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Month Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
   ALTER TABLE `Part Dimension` 
  ADD `Part 10 day Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 10 day Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 10 day Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 10 day Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
   ALTER TABLE `Part Dimension` 
  ADD `Part 1 Week Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Week Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Week Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Week Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
  
   ALTER TABLE `Part Dimension` 
  ADD `Part 1 Day Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Day Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part 1 Day Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part 1 Day Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
  ALTER TABLE `Part Dimension` 
  ADD `Part Total Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Total Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Total Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Total Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Total Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Total Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Total Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total GMROI` FLOAT NOT NULL DEFAULT '0';
  
  
    ALTER TABLE `Part Dimension` 
  ADD `Part Year To Day Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Year To Day Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Year To Day Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Year To Day Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
  DROP TABLE IF EXISTS `Part Warehouse Bridge`;

CREATE TABLE `Part Warehouse Bridge` (
  `Part SKU` mediumint(8) unsigned NOT NULL,
  `Warehouse Key` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`Part SKU`,`Warehouse Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `Customer History Bridge` CHANGE `Type` `Type` ENUM( 'Notes', 'Orders', 'Changes', 'Emails' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Notes';

CREATE TABLE `Customer History Email Checksum` (
`History Key` INT UNSIGNED NOT NULL ,
`Checksum` VARCHAR( 64 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `Customer History Email Checksum` ADD INDEX ( `Checksum` ( 64 ) ) ;
ALTER TABLE `Customer History Email Checksum` ADD INDEX ( `History Key` ) ;
ALTER TABLE `Customer Dimension` ADD `Recargo Equivalencia` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No';

ALTER TABLE `Address Dimension` ADD `Address Contact` VARCHAR( 256 ) NULL DEFAULT NULL ;



kaktus =====================


ALTER TABLE `Customer Dimension` ADD `Customer Registration Number` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `Customer Tax Number` ;
ALTER TABLE `Contact Dimension` ADD `Contact Identification Number` VARCHAR( 256 ) NOT NULL DEFAULT '' AFTER `Contact Tax Number` ;

-----------------

ALTER TABLE `Customer List Dimension` ADD `Customer List Use Type` ENUM( 'User Defined', 'CSV Import' ) NOT NULL DEFAULT 'User Defined' AFTER `Customer List Key` , ADD INDEX ( `Customer List Use Type` );

CREATE TABLE `Imported Records Dimension` (
  `Imported Records Key` mediumint(9) NOT NULL AUTO_INCREMENT,
  `Imported Records Checksum File` varchar(64) DEFAULT NULL,
  `Imported Records Creation Date` datetime NOT NULL,
  `Imported Records Start Date` datetime DEFAULT NULL,
  `Imported Records Finish Date` datetime DEFAULT NULL,
  `Imported Records Scope` varchar(64) CHARACTER SET utf8 NOT NULL,
  `Imported Records Scope Key` mediumint(8) unsigned DEFAULT NULL,
  `Original Records` int(11) NOT NULL DEFAULT '0',
  `Ignored Records` int(11) NOT NULL DEFAULT '0',
  `Imported Records` int(11) NOT NULL DEFAULT '0',
  `Error Records` int(11) NOT NULL DEFAULT '0',
  `Not Imported Log` longtext CHARACTER SET utf8,
  `Scope List Key` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`Imported Records Key`)
) ENGINE=MyISAM ;



ALTER TABLE `Customer List Dimension` CHANGE `Customer List Key` `List Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `Customer List Use Type` `List Use Type` ENUM( 'User Defined', 'CSV Import' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'User Defined',
CHANGE `Customer List Store Key` `List Store Key` SMALLINT( 5 ) UNSIGNED NOT NULL ,
CHANGE `Customer List Name` `List Name` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Customer List Type` `List Type` ENUM( 'Dynamic', 'Static' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Static',
CHANGE `Customer List Metadata` `List Metadata` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Customer List Creation Date` `List Creation Date` DATETIME NOT NULL ;
RENAME TABLE `Customer List Dimension` TO `List Dimension` ;
ALTER TABLE `List Dimension` ADD `List Scope` ENUM( 'Customer', 'Order', 'Invoice', 'Delivery Note', 'Product', 'Part' ) NOT NULL DEFAULT 'Customer' AFTER `List Key` ;
ALTER TABLE `Customer List Customer Bridge` CHANGE `Customer List Key` `List Key` SMALLINT( 5 ) UNSIGNED NOT NULL ;
RENAME TABLE `Customer List Customer Bridge` TO `List Customer Bridge` ;

CREATE TABLE `List Product Bridge` (
`List Key` SMALLINT UNSIGNED NOT NULL ,
`Product ID` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `List Key` , `Product ID` )
) ENGINE = MYISAM ;

ALTER TABLE `Telecom Bridge` ADD `Telecom Description` VARCHAR( 256 ) NOT NULL DEFAULT '';
ALTER TABLE `Customer Dimension` ADD `Customer Preferred Contact Number` ENUM( 'Telephone', 'Mobile' ) NOT NULL DEFAULT 'Telephone';





CREATE TABLE IF NOT EXISTS `Custom Field Dimension` (
  `Custom Field Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Custom Field Table` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Custom Field Name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Custom Field Type` enum('Mediumint','Varchar') CHARACTER SET utf8 NOT NULL,
  `Custom Field Store Key` mediumint(8) unsigned DEFAULT NULL,
  `Custom Field In New Subject` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Custom Field In Showcase` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `Default Value` varchar(30) DEFAULT '',
  PRIMARY KEY (`Custom Field Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `Customer Custom Field Dimension` (
`Customer Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ,
PRIMARY KEY ( `Customer Key` )
) ENGINE = MYISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `Store Dimension` ADD `Store Telecom Format` VARCHAR( 256 ) NOT NULL DEFAULT 'GBR' AFTER `Store Slogan` ;

ALTER TABLE `Customer History Bridge` CHANGE `Type` `Type` ENUM( 'Notes', 'Orders', 'Changes', 'Attachments' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Notes';


alter table `Part Dimension` add column `Part General Description` varchar(255) after `Part Unit Description`;
alter table `Part Dimension` add column `Part Health And Safety` varchar(255) after `Part General Description`;
ALTER TABLE `Part Dimension` CHANGE `Part General Description` `Part General Description` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `Part Health And Safety` `Part Health And Safety` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
CREATE TABLE `Part Custom Field Dimension` (`Part SKU` MEDIUMINT UNSIGNED NOT NULL) ENGINE = MYISAM ;
ALTER TABLE `Part Custom Field Dimension` ADD INDEX ( `Part SKU` ) ;


ALTER TABLE `Email Content Dimension` ADD `Email Content Header Image Source` VARCHAR( 256 ) NULL DEFAULT 'templates/emails/img/logo_header.jpg';
ALTER TABLE `Customer History Bridge` ADD `Strikethrough` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `Deletable` ;
alter table `Store Dimension` add column `Store Address` varchar(255) after `Store Slogan`;
alter table `Store Dimension` add column `Short Marketing Description` varchar(255) after `Store Address`;
ALTER TABLE `Email Content Dimension` CHANGE `Email Content Metadata` `Email Content Metadata` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

CREATE TABLE `Email Content Paragraph Dimension` (
`Email Paragraph Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Email Content Key` MEDIUMINT UNSIGNED NOT NULL ,
`Paragraph Order` SMALLINT NOT NULL ,
`Paragraph Type` ENUM( 'Main', 'Side' ) NOT NULL DEFAULT 'Main',
`Paragraph Title` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`Paragraph Subtitle` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`Paragraph Content` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
INDEX ( `Email Content Key` )
) ENGINE = MYISAM ;
ALTER TABLE `Email Campaign Dimension` DROP `Email Campaign Subjects` ,DROP `Email Campaign Contents` ;
ALTER TABLE `Email Content Dimension` CHANGE `Email Content Type` `Email Content Type` ENUM( 'Plain', 'HTML Template', 'HTML' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;


CREATE TABLE `Import CSV Map` (
`Map Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Store Key` MEDIUMINT( 8 ) NOT NULL ,
`Map Name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`Meta Data` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = MYISAM;
ALTER TABLE `Import CSV Map` ADD `Scope` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `Store Key` ,ADD INDEX ( `Scope` ) ;


CREATE TABLE `External Records` (
`External Record Key` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
`Read Status` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No',
`Date Time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`Record` VARCHAR( 500 ) NOT NULL ,
PRIMARY KEY ( `External Record Key` )
) ENGINE = MYISAM;

alter table `External Records` add column `Store Key` Mediumint(8) after `External Record Key`;
alter table `External Records` add column `Scope` varchar(255) after `Store Key`;
alter table `External Records` add column `IP` varchar(20) after `Read Status`;

ALTER TABLE `Store Dimension` ADD `Store Email Campaigns` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Store Dimension` ADD `Store Newsletters` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';


CREATE TABLE `Email Credentials Dimension` (
`Email Credentials Key` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
`Store Key` MEDIUMINT( 8 ) NOT NULL ,
`Scope` VARCHAR( 255 ) NOT NULL ,
`Email Address` VARCHAR( 255 ) NOT NULL ,
`Password` VARCHAR( 255 ) NOT NULL ,
`Incoming Mail Server` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `Email Credentials Key` )
) ENGINE = MYISAM;
alter table `Email Credentials Dimension` add column `Outgoing Mail Sever` varchar (255);
ALTER TABLE `Email Credentials Dimension` CHANGE `Store Key` `Store Key` MEDIUMINT( 8 ) NULL DEFAULT NULL ;
ALTER TABLE `Email Credentials Dimension` ADD `User Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Store Key` ;

ALTER TABLE `Email Credentials Dimension` DROP `Scope` ;
ALTER TABLE `Email Credentials Dimension` ADD `Customer Communication` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No', ADD INDEX ( `Customer Communication` ) ;
ALTER TABLE `Email Credentials Dimension` ADD `Login` VARCHAR( 256 ) NOT NULL AFTER `Email Address` ;
ALTER TABLE `Email Credentials Dimension`  DROP `Store Key`,  DROP `User Key`;
CREATE TABLE `Email Credentials Store Bridge` (
`Email Credentials Key` MEDIUMINT UNSIGNED NOT NULL ,
`Store Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Email Credentials Key` , `Store Key` )
) ENGINE = MYISAM ;
CREATE TABLE `Email Credentials User Bridge` (
`Email Credentials Key` MEDIUMINT UNSIGNED NOT NULL ,
`User Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Email Credentials Key` , `User Key` )
) ENGINE = MYISAM ;
CREATE TABLE `Email Credentials Scope Bridge` (
`Email Credentials Key` MEDIUMINT UNSIGNED NOT NULL ,
`Scope` ENUM( 'Customer Communications' ) NOT NULL ,
PRIMARY KEY ( `Email Credentials Key` , `Scope` )
) ENGINE = MYISAM ;


CREATE TABLE `Email Read Dimension` (
`Email Read Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
`Email Credentials Key` MEDIUMINT UNSIGNED NOT NULL ,
`Email Uid` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`Customer Communications` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No',
PRIMARY KEY ( `Email Read Key` )
) ENGINE = MYISAM ;
ALTER TABLE `Email Read Dimension` ADD INDEX ( `Email Credentials Key` ) ;
ALTER TABLE `Email Read Dimension` ADD INDEX ( `Email Uid` ) ;

ALTER TABLE `Customer Dimension` CHANGE `Customer Has More  Invoices Than` `Customer Has More Invoices Than` MEDIUMINT( 9 ) NULL DEFAULT NULL ;

ALTER TABLE `Customer History Bridge` CHANGE `Type` `Type` ENUM( 'Notes', 'Orders', 'Changes', 'Attachments', 'Email' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Notes';
ALTER TABLE `Email Read Dimension` ADD `Scope Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Customer History Bridge` CHANGE `Type` `Type` ENUM( 'Notes', 'Orders', 'Changes', 'Attachments', 'Emails' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Notes';


CREATE TABLE `List Order Bridge` (`List Key` SMALLINT( 5 ) NOT NULL ,`Order Key` MEDIUMINT( 8 ) NOT NULL) ENGINE = MYISAM;
ALTER TABLE `Telecom Bridge` ADD `Telecom Description` VARCHAR( 256 ) NOT NULL ;

ALTER TABLE `Tax Category Dimension` ADD `Tax Category Type` VARCHAR( 64 ) NOT NULL AFTER `Tax Category Key` ,ADD INDEX ( `Tax Category Type` ) ;
ALTER TABLE `Tax Category Dimension` ADD `Composite` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No',ADD INDEX ( `Composite` ) ;
ALTER TABLE `Tax Category Dimension` ADD `Composite Metadata` VARCHAR( 256 ) NULL DEFAULT NULL;
ALTER TABLE `Tax Category Dimension` CHANGE `Tax Category Type` `Tax Category Type Name` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

CREATE TABLE `Invoice Tax Dimension` (
`Invoice Key` MEDIUMINT UNSIGNED NOT NULL ,
`UNK` FLOAT NULL DEFAULT NULL ,
INDEX ( `Invoice Key` )
) ENGINE = MYISAM ;
ALTER TABLE `HQ Dimension` ADD `HQ Country Code` VARCHAR( 3 ) NOT NULL AFTER `HQ Name` ;
ALTER TABLE `HQ Dimension` ADD `HQ Country 2 Alpha Code` VARCHAR( 2 ) NOT NULL AFTER `HQ Country Code` ;

CREATE TABLE `List Order Bridge` (
`List Key` SMALLINT( 5 ) NOT NULL ,
`Order Key` MEDIUMINT( 8 ) NOT NULL
) ENGINE = MYISAM;


CREATE TABLE `List Invoice Bridge` (
`List Key` SMALLINT( 5 ) NOT NULL ,
`Invoice Key` MEDIUMINT( 8 ) NOT NULL
) ENGINE = MYISAM;

ALTER TABLE `Invoice Tax Dimension` CHANGE `UNK` `UNK` DECIMAL( 12, 2 ) NULL DEFAULT NULL ;

/// from here


alter table `Order Dimension` add `Order Main World Region Code` char(4) NULL DEFAULT NULL ,
add `Order Main Country Code` char(4) NULL DEFAULT NULL ,
add `Order Main City Code` char(4) NULL DEFAULT NULL ,
add `Order Main Postal Code` char(4) NULL DEFAULT NULL, 
add `Order Ship To World Region Code` char(4) NULL DEFAULT NULL ,
add `Order Ship To City Code` char(4) NULL DEFAULT NULL ,
add `Order Ship To Postal Code` char(4) NULL DEFAULT NULL;

alter table `Delivery Note Dimension` add `Delivery Note World Region Code` char(4) NULL DEFAULT NULL ,
add `Delivery Note Country Code` char(4) NULL DEFAULT NULL ,
add `Delivery Note City Code` char(4) NULL DEFAULT NULL ,
add `Delivery Note Postal Code` char(4) NULL DEFAULT NULL;


alter table `Invoice Dimension` add `Invoice Delivery World Region Code` char(4) NULL DEFAULT NULL ,
add `Invoice Delivery Country Code` char(4) NULL DEFAULT NULL ,
add `Invoice Delivery City Code` char(4) NULL DEFAULT NULL ,
add `Invoice Delivery Postal Code` char(4) NULL DEFAULT NULL;

alter table `Invoice Dimension` add `Invoice Billing World Region Code` char(4) NULL DEFAULT NULL ,
add `Invoice Billing Country Code` char(4) NULL DEFAULT NULL ,
add `Invoice Billing City Code` char(4) NULL DEFAULT NULL ,
add `Invoice Billing Postal Code` char(4) NULL DEFAULT NULL;

CREATE TABLE `List Delivery Note Bridge` (
` List Key` SMALLINT( 5 ) NOT NULL ,
`Delivery Note Key` MEDIUMINT( 8 ) NOT NULL
) ENGINE = MYISAM;

CREATE TABLE `Attachment Bridge` (
`Attachment Key` MEDIUMINT UNSIGNED ZEROFILL NOT NULL ,
`Subject` ENUM( 'Customer Communications', 'Customer History Attachment' ) NOT NULL ,
`Subject Key` MEDIUMINT UNSIGNED ZEROFILL NOT NULL ,
PRIMARY KEY ( `Attachment Key` , `Subject` , `Subject Key` )
) ENGINE = MYISAM ;


ALTER TABLE `Attachment Bridge` ADD INDEX ( `Attachment Key` ) ;
ALTER TABLE `Attachment Bridge` ADD INDEX ( `Subject` , `Subject Key` ); 
ALTER TABLE `Attachment Dimension` CHANGE `Attachment Compressed Data` `Attachment Data` LONGBLOB NOT NULL ;
ALTER TABLE `Attachment Dimension` DROP `Attachment Filename` ;

CREATE TABLE `Email Credentials Scope Mailbox` (
`Email Credentials Key` MEDIUMINT UNSIGNED NOT NULL ,
`Scope` ENUM( 'Customer Communications' ) NOT NULL ,
`Mailbox` VARCHAR( 256 ) NOT NULL ,
PRIMARY KEY ( `Email Credentials Key` , `Scope` )
) ENGINE = MYISAM ;
ALTER TABLE `Email Credentials Scope Mailbox` DROP PRIMARY KEY ;


ALTER TABLE `Invoice Dimension` 
CHANGE `Invoice Delivery World Region Code` `Invoice Delivery World Region Code` CHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
CHANGE `Invoice Delivery Country Code` `Invoice Delivery Country Code` CHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
CHANGE `Invoice Delivery City Code` `Invoice Delivery Town` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
 CHANGE `Invoice Delivery Postal Code` `Invoice Delivery Postal Code` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
 CHANGE `Invoice Billing World Region Code` `Invoice Billing World Region Code` CHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
 CHANGE `Invoice Billing Country Code` `Invoice Billing Country Code` CHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
CHANGE `Invoice Billing City Code` `Invoice Billing Town` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
 CHANGE `Invoice Billing Postal Code` `Invoice Billing Postal Code` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
 
 ALTER TABLE `Delivery Note Dimension` CHANGE `Delivery Note Country Code` `Delivery Note Country Code` CHAR( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Delivery Note City Code` `Delivery Note Town` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Delivery Note Postal Code` `Delivery Note Postal Code` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Order Dimension` CHANGE `Order Main Country Code` `Order Main Country Code` CHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Order Main City Code` `Order Main Town` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Order Main Postal Code` `Order Main Postal Code` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Order Dimension` CHANGE `Order Ship To City Code` `Order Ship To Town` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Order Ship To Postal Code` `Order Ship To Postal Code` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;



ALTER TABLE `Email Campaign Mailing List` ADD `Email Content Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Email Campaign Key` ,ADD INDEX ( `Email Content Key` ) ;


// from here to costadw

CREATE TABLE `Email Queue Dimension` (
`Email Queue Key` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
`To` VARCHAR( 255 ) NOT NULL ,
`Subject` VARCHAR( 255 ) NOT NULL ,
`Body` LONGTEXT NULL ,
`Email Credentials Key` MEDIUMINT( 8 ) NOT NULL ,
`BCC` VARCHAR( 1024 ) NULL ,
PRIMARY KEY ( `Email Queue Key` )
) ENGINE = MYISAM;

ALTER TABLE `Email Queue Dimension` ADD `Status` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `BCC` ;
ALTER TABLE `Email Queue Dimension` CHANGE `Body` `Plain` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Email Queue Dimension` ADD `HTML` LONGTEXT NULL AFTER `Plain`;

ALTER TABLE `Email Queue Dimension` ADD `Type` ENUM( 'Plain', 'HTML' ) NOT NULL DEFAULT 'Plain';
CREATE TABLE `Email Queue Attachement Dimension` (
`Attachement Key` INT NOT NULL AUTO_INCREMENT ,
`Email Queue Key` INT NOT NULL ,
`Data` VARCHAR( 255 ) NULL ,
`FileName` VARCHAR( 255 ) NULL ,
`Name` VARCHAR( 255 ) NULL ,
`Content-Type` VARCHAR( 255 ) NOT NULL ,
`Disposition` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `Attachement Key` )

) ENGINE = MYISAM;


ALTER TABLE `MasterKey Dimension` CHANGE `Handle` `User Key` MEDIUMINT NOT NULL ;
ALTER TABLE `Image Dimension` CHANGE `Image URL` `Image Data` LONGBLOB NOT NULL ,
CHANGE `Image Thumbnail URL` `Image Thumbnail Data` LONGBLOB NULL DEFAULT NULL ,
CHANGE `Image Small URL` `Image Small Data` LONGBLOB NULL DEFAULT NULL ,
CHANGE `Image Large URL` `Image Large Data` LONGBLOB NULL DEFAULT NULL ;

ALTER TABLE `Part Dimension` CHANGE `Part 1 Day Acc Required` `Part Today Acc Required` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Day Acc Provided` `Part Today Acc Provided` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Day Acc Lost` `Part Today Acc Lost` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Day Acc Broken` `Part Today Acc Broken` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Day Acc Adquired` `Part Today Acc Adquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Day Acc Sold` `Part Today Acc Sold` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Day Acc Given` `Part Today Acc Given` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Day Acc Sold Amount` `Part Today Acc Sold Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 1 Day Acc Absolute Profit` `Part Today Acc Absolute Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';
ALTER TABLE `Part Dimension` CHANGE `Part 1 Day Acc Profit When Sold` `Part Today Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part 1 Day Acc Absolute Profit After Storing` `Part Today Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part 1 Day Acc Profit When Sold After Storing` `Part Today Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part 1 Day Acc Margin` `Part Today Acc Margin` FLOAT NOT NULL DEFAULT '0', 
CHANGE `Part 1 Day Acc AVG Stock` `Part Today Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
CHANGE `Part 1 Day Acc AVG Stock Value` `Part Today Acc AVG Stock Value` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part 1 Day Acc Keeping Days` `Part Today Acc Keeping Days` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Day Acc Out of Stock Days` `Part Today Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
CHANGE `Part 1 Day Acc Unknown Stock Days` `Part Today Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
CHANGE `Part 1 Day Acc GMROI` `Part Today Acc GMROI` FLOAT NOT NULL DEFAULT '0';

   ALTER TABLE `Part Dimension` 
  ADD `Part Week To Day Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Week To Day Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Week To Day Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Week To Day Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
  
     ALTER TABLE `Part Dimension` 
  ADD `Part Month To Day Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Month To Day Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Month To Day Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Month To Day Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
     ALTER TABLE `Part Dimension` 
  ADD `Part Yesterday Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Yesterday Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Yesterday Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Yesterday Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
     ALTER TABLE `Part Dimension` 
  ADD `Part Last Month Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Last Month Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Last Month Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Month Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
     ALTER TABLE `Part Dimension` 
  ADD `Part Last Week Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Last Week Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Last Week Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Last Week Acc GMROI` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Part Dimension` DROP `Part Total Required`, DROP `Part Total Provided`, DROP `Part Total Lost`, DROP `Part Total Broken`, DROP `Part Total Adquired`, DROP `Part Total Sold`, DROP `Part Total Given`, DROP `Part Total Sold Amount`, DROP `Part Total Absolute Profit`, DROP `Part Total Profit When Sold`, DROP `Part Total Absolute Profit After Storing`, DROP `Part Total Profit When Sold After Storing`, DROP `Part Total Margin`;


  ALTER TABLE `Part Dimension` 
  ADD `Part Total Acc Required` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Provided` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Lost` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Broken` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Adquired` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Sold` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Given` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Sold Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Total Acc Absolute Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  ADD `Part Total Acc Profit When Sold` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Absolute Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Profit When Sold After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Margin` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc AVG Stock` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc AVG Stock Value`  DECIMAL(12,2) NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Keeping Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0', 
  ADD `Part Total Acc GMROI` FLOAT NOT NULL DEFAULT '0';
  
  ALTER TABLE `Order Transaction Fact` ADD `Invoice Transaction Net Refund Items` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Outstanding Tax Balance` ,
ADD `Invoice Transaction Net Refund Shipping` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Net Refund Items` ,
ADD `Invoice Transaction Net Refund Charges` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Net Refund Shipping` ,
ADD `Invoice Transaction Tax Refund Items` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Net Refund Charges` ,
ADD `Invoice Transaction Tax Refund Shipping` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Tax Refund Items` ,
ADD `Invoice Transaction Tax Refund Charges` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `Invoice Transaction Tax Refund Shipping`;


ALTER TABLE `History Dimension` CHANGE `Direct Object` `Direct Object` ENUM( 'After Sale', 'Delivery Note', 'Category', 'Warehouse', 'Warehouse Area', 'Shelf', 'Location', 'Company Department', 'Company Area', 'Position', 'Store', 'User', 'Product', 'Address', 'Customer', 'Note', 'Order', 'Telecom', 'Email', 'Company', 'Contact', 'FAX', 'Telephone', 'Mobile', 'Work Telephone', 'Office Fax', 'Supplier', 'Family', 'Department', 'Attachment', 'Supplier Product', 'Part' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `MasterKey Dimension` ADD `IP` VARCHAR( 64 ) NOT NULL ;

ALTER TABLE `Part Dimension` CHANGE `Part 10 day Acc Required` `Part 10 Day Acc Required` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc Provided` `Part 10 Day Acc Provided` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc Lost` `Part 10 Day Acc Lost` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc Broken` `Part 10 Day Acc Broken` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc Adquired` `Part 10 Day Acc Adquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc Sold` `Part 10 Day Acc Sold` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc Given` `Part 10 Day Acc Given` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Part Dimension` CHANGE `Part 10 day Acc Sold Amount` `Part 10 Day Acc Sold Amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 10 day Acc Absolute Profit` `Part 10 Day Acc Absolute Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 10 day Acc Profit When Sold` `Part 10 Day Acc Profit When Sold` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 10 day Acc Absolute Profit After Storing` `Part 10 Day Acc Absolute Profit After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 10 day Acc Profit When Sold After Storing` `Part 10 Day Acc Profit When Sold After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Part Dimension` CHANGE `Part 10 day Acc Margin` `Part 10 Day Acc Margin` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc AVG Stock` `Part 10 Day Acc AVG Stock` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc AVG Stock Value` `Part 10 Day Acc AVG Stock Value` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 10 day Acc Keeping Days` `Part 10 Day Acc Keeping Days` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc Out of Stock Days` `Part 10 Day Acc Out of Stock Days` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc Unknown Stock Days` `Part 10 Day Acc Unknown Stock Days` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 day Acc GMROI` `Part 10 Day Acc GMROI` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Image Dimension` ADD `Image Public` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No', ADD INDEX ( `Image Public` ) ;


ALTER TABLE `Purchase Order Transaction Fact` DROP `Purchase Order Line`,DROP `Supplier Delivery Note Line`,DROP `Supplier Invoice Line`;
ALTER TABLE `Purchase Order Transaction Fact` ADD `Purchase Order Transaction Fact Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;

ALTER TABLE `Supplier Product Dimension` DROP INDEX `Supplier Product Code` ;
ALTER TABLE `Supplier Product Dimension` ADD INDEX ( `Supplier Product Current Key` ) ;

ALTER TABLE `Supplier Product Dimension` ADD `Supplier Product Store As` VARCHAR( 1024 ) NOT NULL AFTER `Supplier Product Description` ,ADD `Supplier Product XHTML Store As` VARCHAR( 1024 ) NOT NULL AFTER `Supplier Product Store As` ,ADD `Supplier Product Sold As` VARCHAR( 1024 ) NOT NULL AFTER `Supplier Product XHTML Store As` ,ADD `Supplier Product XHTML Sold As` VARCHAR( 1024 ) NOT NULL AFTER `Supplier Product Sold As` ;
ALTER TABLE `Supplier Product Dimension` CHANGE `Supplier Product Store As` `Supplier Product Store As` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,CHANGE `Supplier Product XHTML Store As` `Supplier Product XHTML Store As` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,CHANGE `Supplier Product Sold As` `Supplier Product Sold As` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,CHANGE `Supplier Product XHTML Sold As` `Supplier Product XHTML Sold As` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `Supplier Delivery Note Item Part Bridge` CHANGE `Supplier Delivery Note Line` `Purchase Order Transaction Fact Key` MEDIUMINT( 5 ) UNSIGNED NOT NULL ;

ALTER TABLE `Site Dimension` ADD `Site Secret Key` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `Site Dimension` ADD `Site URL` VARCHAR( 255 ) NOT NULL AFTER `Site Name`;
ALTER TABLE `Product Dimension` DROP `Product Sales State DELETEME` ;
ALTER TABLE `Product Dimension` CHANGE `Product Web State` `Product Web Configuration` ENUM( 'Online Force Out of Stock', 'Online Auto', 'Offline', 'Unknown', 'Online Force For Sale' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Product Dimension` CHANGE `Product Web Configuration` `Product Web Configuration` ENUM( 'Online Force Out of Stock', 'Online Auto', 'Offline', 'Online Force For Sale' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Online Auto';
ALTER TABLE `Store Dimension` ADD `Store Web Days Until Remove Discontinued Products` SMALLINT( 90 ) NULL ;
ALTER TABLE `Product Dimension` DROP `Product To Be Discontinued` ;
ALTER TABLE `Email Credentials Dimension` DROP `Customer Communication` ;
ALTER TABLE `Email Credentials Scope Bridge` CHANGE `Scope` `Scope` ENUM( 'Customer Communications', 'Newsletters', 'Marketing Email', 'Site Registration', 'Inikoo' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `Order Transaction Fact` CHANGE `Invoice Transaction Tax Refund Items` `Invoice Transaction Tax Refund Items` DECIMAL( 12, 6 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Product Dimension` CHANGE `Product Record Type` `Product Record Type` ENUM( 'Normal', 'Historic' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Normal';
ALTER TABLE `Product Dimension` CHANGE `Product Type` `Product Type` ENUM( 'Normal', 'Fuzzy' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Normal';
ALTER TABLE `Product Dimension` ADD `Product Availability Type` ENUM( 'Normal', 'Discontinued' ) NOT NULL AFTER `Product Sales Type` ,ADD INDEX ( `Product Availability Type` ) ;
ALTER TABLE `Product Dimension` ADD `Product Stage` ENUM( 'In Process', 'New', 'Normal' ) NOT NULL AFTER `Product Record Type` ,ADD INDEX ( `Product Stage` ) ;
ALTER TABLE `Product Dimension` CHANGE `Product Sales Type` `Product Sales Type` ENUM( 'Public Sale', 'Private Sale', 'Not for Sale', 'Discontinued Public Sale' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Product Dimension` ADD `Product Web State` ENUM( 'For Sale', 'Out of Stock', 'Discontinued', 'Offline' ) NOT NULL AFTER `Product Web Configuration` , ADD INDEX ( `Product Web State` ) ;
ALTER TABLE `Product Dimension` CHANGE `Product Web State` `Product Web State` ENUM( 'For Sale', 'Out of Stock', 'Discontinued', 'Offline' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Offline';
ALTER TABLE `Part Dimension` ADD `Part Available` ENUM( 'Yes', 'No' ) NOT NULL AFTER `Part Status` , ADD INDEX ( `Part Available` ) ;
ALTER TABLE `Product Dimension` CHANGE `Product Sales Type` `Product Sales Type` ENUM( 'Public Sale', 'Private Sale', 'Not for Sale' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `Product Dimension` ADD `Product Main Type` ENUM( 'Historic', 'Discontinued', 'Private', 'NoSale', 'Sale' ) NOT NULL AFTER `Product Availability Type` ,ADD INDEX ( `Product Main Type` ); 
ALTER TABLE `Product Family Dimension` CHANGE `Product Family Record Type` `Product Family Record Type` ENUM( 'In Process', 'Normal', 'Discontinuing', 'Discontinued' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Normal';
ALTER TABLE `Product Family Dimension` CHANGE `Product Family Record Type` `Product Family Record Type` ENUM( 'InProcess', 'Normal', 'Discontinuing', 'Discontinued' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Normal';
ALTER TABLE `Site Dimension` CHANGE `Site Secret Key` `Site Secret Key` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Type` ENUM( 'External Content and HTML HEAD' ) NOT NULL DEFAULT 'External Content and HTML HEAD' AFTER `Page Parent Key` ,ADD INDEX ( `Page Store Type` ); 
ALTER TABLE `Page Store Dimension` ADD UNIQUE (`Page Site Key` ,`Page Code`);
ALTER TABLE `Part Dimension` ADD `Part Current On Hand Stock` FLOAT NOT NULL DEFAULT '0' AFTER `Part Current Stock`;
ALTER TABLE `User Dimension` CHANGE `User Type` `User Type` ENUM( 'Customer', 'Staff', 'Supplier', 'Administrator', 'Warehouse' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Credentials Scope Bridge` CHANGE `Scope` `Scope` ENUM( 'Customer Communications', 'Newsletters', 'Marketing Email', 'Site Registration', 'Bugs', 'Requests' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Product Family Dimension` CHANGE `Product Family Record Type` `Product Family Record Type` ENUM( 'InProcess', 'Normal', 'Discontinuing', 'Discontinued', 'Private', 'NoSale' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Normal';
ALTER TABLE `Product Family Dimension` CHANGE `Product Family Record Type` `Product Family Record Type` ENUM( 'InProcess', 'Normal', 'Discontinuing', 'Discontinued', 'NoPublic' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Normal';
ALTER TABLE `User Log Dimension` ADD `User Log Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;
ALTER TABLE `User Click Dimension` ADD `User Log Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `User Key` ;
ALTER TABLE `Part Dimension` ADD `Part Main State` ENUM( 'Keeping', 'LastStock', 'Discontinued', 'NotKeeping' ) NULL DEFAULT NULL AFTER `Part Available` ,ADD INDEX ( `Part Main State` ) ;

CREATE TABLE `Product Family Sales Correlation` (
`Family A Key` MEDIUMINT UNSIGNED NOT NULL ,
`Family B Key` MEDIUMINT UNSIGNED NOT NULL ,
`Correlation` FLOAT NOT NULL ,
`Samples` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Family A Key` , `Family B Key` )
) ENGINE = MYISAM ;

CREATE TABLE `Page Store Found In Bridge` (
`Page Store Key` MEDIUMINT UNSIGNED NOT NULL ,
`Page Store Found In Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Page Store Key` , `Page Store Found In Key` )
) ENGINE = MYISAM ;

CREATE TABLE `Product Family Semantic Correlation` (
`Family A Key` MEDIUMINT UNSIGNED NOT NULL ,
`Family B Key` MEDIUMINT UNSIGNED NOT NULL ,
`Weight` FLOAT NOT NULL ,
PRIMARY KEY ( `Family A Key` , `Family B Key` )
) ENGINE = MYISAM ;

CREATE TABLE `Product Page Bridge` (
`Product ID` MEDIUMINT UNSIGNED NOT NULL ,
`Page Key` MEDIUMINT UNSIGNED NOT NULL ,
`Type` ENUM( 'List', 'Button' ) NOT NULL ,
PRIMARY KEY ( `Product ID` , `Page Key` )
) ENGINE = MYISAM ;

ALTER TABLE `Supplier Dimension`
  DROP `Supplier Total Parts Profit`,
  DROP `Supplier Total Parts Profit After Storing`,
  DROP `Supplier Total Cost`,
  DROP `Supplier Total Parts Sold Amount`,
  DROP `Supplier 1 Year Acc Parts Profit`,
  DROP `Supplier 1 Year Acc Parts Profit After Storing`,
  DROP `Supplier 1 Year Acc Cost`,
  DROP `Supplier 1 Year Acc Parts Sold Amount`,
  DROP `Supplier 1 Quarter Acc Parts Profit`,
  DROP `Supplier 1 Quarter Acc Parts Profit After Storing`,
  DROP `Supplier 1 Quarter Acc Cost`,
  DROP `Supplier 1 Quarter Acc Parts Sold Amount`,
  DROP `Supplier 1 Month Acc Parts Profit`,
  DROP `Supplier 1 Month Acc Parts Profit After Storing`,
  DROP `Supplier 1 Month Acc Cost`,
  DROP `Supplier 1 Month Acc Parts Sold Amount`,
  DROP `Supplier 1 Month Acc Parts Broken`,
  DROP `Supplier 1 Week Acc Parts Profit`,
  DROP `Supplier 1 Week Acc Parts Profit After Storing`,
  DROP `Supplier 1 Week Acc Cost`,
  DROP `Supplier 1 Week Acc Parts Sold Amount`;
  


ALTER TABLE `Supplier Product Dimension`
  DROP `Supplier Product Total Parts Profit`,
  DROP `Supplier Product Total Parts Profit After Storing`,
  DROP `Supplier Product Total Cost`,
  DROP `Supplier Product Total Parts Used`,
  DROP `Supplier Product Total Sold Amount`,
  DROP `Supplier Product Total Parts Required`,
  DROP `Supplier Product Total Parts Provided`,
  DROP `Supplier Product Total Parts No Dispatched`,
  DROP `Supplier Product Total Parts Lost`,
  DROP `Supplier Product Total Parts Broken`,
  DROP `Supplier Product Total Margin`,
  DROP `Supplier Product 1 Year Acc Parts Profit`,
  DROP `Supplier Product 1 Year Acc Parts Profit After Storing`,
  DROP `Supplier Product 1 Year Acc Cost`,
  DROP `Supplier Product 1 Year Acc Parts Used`,
  DROP `Supplier Product 1 Year Acc Sold Amount`,
  DROP `Supplier Product 1 Year Acc Parts Required`,
  DROP `Supplier Product 1 Year Acc Parts Provided`,
  DROP `Supplier Product 1 Year Acc Parts No Dispatched`,
  DROP `Supplier Product 1 Year Acc Parts Lost`,
  DROP `Supplier Product 1 Year Acc Parts Broken`,
  DROP `Supplier Product 1 Year Acc Margin`,
  DROP `Supplier Product 1 Quarter Acc Parts Profit`,
  DROP `Supplier Product 1 Quarter Acc Parts Profit After Storing`,
  DROP `Supplier Product 1 Quarter Acc Cost`,
  DROP `Supplier Product 1 Quarter Acc Parts Used`;
  
  ALTER TABLE `Supplier Product Dimension`
  DROP `Supplier Product 1 Quarter Acc Sold Amount`,
  DROP `Supplier Product 1 Quarter Acc Parts Required`,
  DROP `Supplier Product 1 Quarter Acc Parts Provided`,
  DROP `Supplier Product 1 Quarter Acc Parts No Dispatched`,
  DROP `Supplier Product 1 Quarter Acc Parts Lost`,
  DROP `Supplier Product 1 Quarter Acc Parts Broken`,
  DROP `Supplier Product 1 Quarter Acc Margin`,
  DROP `Supplier Product 1 Month Acc Parts Profit`,
  DROP `Supplier Product 1 Month Acc Parts Profit After Storing`,
  DROP `Supplier Product 1 Month Acc Cost`,
  DROP `Supplier Product 1 Month Acc Parts Used`,
  DROP `Supplier Product 1 Month Acc Sold Amount`,
  DROP `Supplier Product 1 Month Acc Parts Required`,
  DROP `Supplier Product 1 Month Acc Parts Provided`,
  DROP `Supplier Product 1 Month Acc Parts No Dispatched`,
  DROP `Supplier Product 1 Month Acc Parts Lost`,
  DROP `Supplier Product 1 Month Acc Parts Broken`,
  DROP `Supplier Product 1 Month Acc Margin`,
  DROP `Supplier Product 1 Week Acc Parts Profit`,
  DROP `Supplier Product 1 Week Acc Parts Profit After Storing`,
  DROP `Supplier Product 1 Week Acc Cost`,
  DROP `Supplier Product 1 Week Acc Parts Used`,
  DROP `Supplier Product 1 Week Acc Sold Amount`,
  DROP `Supplier Product 1 Week Acc Parts Required`,
  DROP `Supplier Product 1 Week Acc Parts Provided`,
  DROP `Supplier Product 1 Week Acc Parts No Dispatched`,
  DROP `Supplier Product 1 Week Acc Parts Lost`,
  DROP `Supplier Product 1 Week Acc Parts Broken`,
  DROP `Supplier Product 1 Week Acc Margin`;
  
  
  
ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 3 Year Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 3 Year Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 3 Year Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 3 Year Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 3 Year Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 3 Year Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 3 Year Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 3 Year Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 3 Year Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 3 Year Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';


ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Year Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Year Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Year Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Year Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Year Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Year Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Year Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Year Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Year To Day Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Year To Day Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Year To Day Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Year To Day Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Year To Day Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Year To Day Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Year To Day Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Year To Day Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Month To Day Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Month To Day Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Month To Day Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Month To Day Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Month To Day Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Month To Day Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Month To Day Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Month To Day Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Week To Day Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Week To Day Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Week To Day Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Week To Day Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Week To Day Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Week To Day Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Week To Day Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Week To Day Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 6 Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 6 Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Quarter Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Quarter Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 10 Day Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 10 Day Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 10 Day Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 10 Day Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 10 Day Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 10 Day Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 10 Day Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 10 Day Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Week Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Week Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Week Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Week Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Week Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Week Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Week Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Week Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Today Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Today Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Today Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Today Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Today Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Today Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Today Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Today Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Yesterday Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Yesterday Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Yesterday Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Yesterday Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Yesterday Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Yesterday Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Yesterday Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Yesterday Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Last Week Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Last Week Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Week Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Week Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Week Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Last Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Last Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Total Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Total Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Total Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Total Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Total Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Total Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Total Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Total Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Total Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Total Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';


ALTER TABLE `Supplier Product Dimension` DROP `Supplier Product XHTML Used In`;

ALTER TABLE `Supplier Product Dimension` CHANGE `Supplier Product Store As` `Supplier Product Store As` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `Supplier Product XHTML Store As` `Supplier Product XHTML Store As` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `Supplier Product Sold As` `Supplier Product Sold As` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `Supplier Product XHTML Sold As` `Supplier Product XHTML Sold As` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Part Dimension`
  DROP `Part 3 Year Acc Absolute Profit`,
  DROP `Part 3 Year Acc Absolute Profit After Storing`,
  DROP `Part 1 Year Acc Absolute Profit`,
  DROP `Part 1 Year Acc Absolute Profit After Storing`,
  DROP `Part 6 Month Acc Absolute Profit`,
  DROP `Part 6 Month Acc Absolute Profit After Storing`,
  DROP `Part 1 Quarter Acc Absolute Profit`,
  DROP `Part 1 Quarter Acc Absolute Profit After Storing`,
  DROP `Part 1 Month Acc Absolute Profit`,
  DROP `Part 1 Month Acc Absolute Profit After Storing`,
  DROP `Part 10 Day Acc Absolute Profit`,
  DROP `Part 10 Day Acc Absolute Profit After Storing`,
  DROP `Part 1 Week Acc Absolute Profit`,
  DROP `Part 1 Week Acc Absolute Profit After Storing`,
  DROP `Part Today Acc Absolute Profit`,
  DROP `Part Today Acc Absolute Profit After Storing`,
  DROP `Part Year To Day Acc Absolute Profit`,
  DROP `Part Year To Day Acc Absolute Profit After Storing`,
  DROP `Part Week To Day Acc Absolute Profit`,
  DROP `Part Week To Day Acc Absolute Profit After Storing`,
  DROP `Part Month To Day Acc Absolute Profit`,
  DROP `Part Month To Day Acc Absolute Profit After Storing`,
  DROP `Part Yesterday Acc Absolute Profit`,
  DROP `Part Yesterday Acc Absolute Profit After Storing`,
  DROP `Part Last Month Acc Absolute Profit`,
  DROP `Part Last Month Acc Absolute Profit After Storing`,
  DROP `Part Last Week Acc Absolute Profit`,
  DROP `Part Last Week Acc Absolute Profit After Storing`,
  DROP `Part Total Acc Absolute Profit`,
  DROP `Part Total Acc Absolute Profit After Storing`;
  
  
  ALTER TABLE `Part Dimension` 
  CHANGE `Part 3 Year Acc Profit When Sold` `Part 3 Year Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  CHANGE `Part 3 Year Acc Profit When Sold After Storing` `Part 3 Year Acc Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  CHANGE `Part 1 Year Acc Profit When Sold` `Part 1 Year Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  CHANGE `Part 1 Year Acc Profit When Sold After Storing` `Part 1 Year Acc Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  CHANGE `Part 6 Month Acc Profit When Sold` `Part 6 Month Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  CHANGE `Part 6 Month Acc Profit When Sold After Storing` `Part 6 Month Acc Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  CHANGE `Part 1 Quarter Acc Profit When Sold` `Part 1 Quarter Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
  CHANGE `Part 1 Quarter Acc Profit When Sold After Storing` `Part 1 Quarter Acc Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00';
  
  ALTER TABLE `Part Dimension` CHANGE `Part 1 Month Acc Profit When Sold` `Part 1 Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 1 Month Acc Profit When Sold After Storing` `Part 1 Month Acc Profit After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 10 Day Acc Profit When Sold` `Part 10 Day Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 10 Day Acc Profit When Sold After Storing` `Part 10 Day Acc Profit After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 1 Week Acc Profit When Sold` `Part 1 Week Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part 1 Week Acc Profit When Sold After Storing` `Part 1 Week Acc Profit After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Part Dimension` CHANGE `Part Today Acc Profit When Sold` `Part Today Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part Today Acc Profit When Sold After Storing` `Part Today Acc Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part Year To Day Acc Profit When Sold` `Part Year To Day Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part Year To Day Acc Profit When Sold After Storing` `Part Year To Day Acc Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part Week To Day Acc Profit When Sold` `Part Week To Day Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part Week To Day Acc Profit When Sold After Storing` `Part Week To Day Acc Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part Month To Day Acc Profit When Sold` `Part Month To Day Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', 
CHANGE `Part Month To Day Acc Profit When Sold After Storing` `Part Month To Day Acc Profit After Storing` DECIMAL(12,2) NOT NULL DEFAULT '0.00';

  
ALTER TABLE `Part Dimension` CHANGE `Part Yesterday Acc Profit When Sold` `Part Yesterday Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part Yesterday Acc Profit When Sold After Storing` `Part Yesterday Acc Profit After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part Last Month Acc Profit When Sold` `Part Last Month Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part Last Month Acc Profit When Sold After Storing` `Part Last Month Acc Profit After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part Last Week Acc Profit When Sold` `Part Last Week Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part Last Week Acc Profit When Sold After Storing` `Part Last Week Acc Profit After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Part Dimension` CHANGE `Part Total Acc Profit When Sold` `Part Total Acc Profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
CHANGE `Part Total Acc Profit When Sold After Storing` `Part Total Acc Profit After Storing` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00';

ALTER TABLE `Part Dimension` CHANGE `Part 3 Year Acc Adquired` `Part 3 Year Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Year Acc Adquired` `Part 1 Year Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 6 Month Acc Adquired` `Part 6 Month Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Quarter Acc Adquired` `Part 1 Quarter Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Month Acc Adquired` `Part 1 Month Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 10 Day Acc Adquired` `Part 10 Day Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part 1 Week Acc Adquired` `Part 1 Week Acc Acquired` FLOAT NOT NULL DEFAULT '0';


ALTER TABLE `Part Dimension` CHANGE `Part Today Acc Adquired` `Part Today Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part Year To Day Acc Adquired` `Part Year To Day Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part Week To Day Acc Adquired` `Part Week To Day Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part Month To Day Acc Adquired` `Part Month To Day Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part Yesterday Acc Adquired` `Part Yesterday Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part Last Month Acc Adquired` `Part Last Month Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part Last Week Acc Adquired` `Part Last Week Acc Acquired` FLOAT NOT NULL DEFAULT '0',
CHANGE `Part Total Acc Adquired` `Part Total Acc Acquired` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Part Dimension` ADD `Part Main Image` VARCHAR( 1024 ) NULL DEFAULT NULL AFTER `Part Interval Until Out of Stock` ;
ALTER TABLE `Supplier Product Dimension` ADD `Supplier Product Main Image` VARCHAR( 1024 ) NULL DEFAULT NULL AFTER `Supplier Product Buy State` ;


ALTER TABLE `User Dimension` DROP INDEX `User Type_2` ;


ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Type` ENUM( 'Newsletter', 'Marketing' ) NOT NULL DEFAULT 'Marketing' AFTER `Email Campaign Key` ,ADD INDEX ( `Email Campaign Type` );
ALTER TABLE `Email Send Dimension` CHANGE `Email Send Recipient Type` `Email Send Recipient Type` ENUM( 'Customer', 'Supplier', 'User' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Customer';
ALTER TABLE `Email Send Dimension` CHANGE `Email Key` `Email Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL;

CREATE TABLE `Configuration Dimension` (
`Public Path` VARCHAR( 1024 ) NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `Email Template Header Image Dimension` (
`Email Template Header Image Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Email Template Header Image Name` VARCHAR( 256 ) NOT NULL ,
`Image Key` MEDIUMINT UNSIGNED NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `Email Template Header Image Dimension` ADD `Store Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Email Template Header Image Name` ,ADD INDEX ( `Store Key` );
ALTER TABLE `Part Dimension` ADD `Part Export Code` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `Part Health And Safety` ;
ALTER TABLE `Part Dimension` CHANGE `Part Export Code` `Part Tariff Code` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Product Dimension` DROP `Product Tariff Code` ;
ALTER TABLE `Product Dimension` ADD `Product Tariff Code` VARCHAR( 1024 ) NULL DEFAULT NULL AFTER `Product Gross Weight` ;
ALTER TABLE `Inventory Transaction Fact` ADD `Picking Note` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `Note` ;
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Warehouse Key` SMALLINT UNSIGNED NOT NULL AFTER `Delivery Note Key` ,ADD INDEX ( `Delivery Note Warehouse Key` ); 
update `Delivery Note Dimension`  set `Delivery Note Warehouse Key`=1 ;
ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Start Overdue Date` DATETIME NULL DEFAULT NULL AFTER `Email Campaign Last Updated Date` ,
ADD `Email Campaign Start Send Date` DATETIME NULL DEFAULT NULL AFTER `Email Campaign Start Overdue Date` ,
ADD `Email Campaign End Send Date` DATETIME NULL DEFAULT NULL AFTER `Email Campaign Start Send Date`;

CREATE TABLE `Supplier Category Dimension` (
  `Category Key` mediumint(8) unsigned NOT NULL,
 
  `Total Acc Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Total Acc Profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Total Acc Part Sales` decimal(12,2)  unsigned NOT NULL DEFAULT '0',
 
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

  KEY `Category Key` (`Category Key`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8;


  



ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Year Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Year Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Year Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Year Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Year Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Year Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Year Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Year Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Year Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Year Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Year To Day Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Year To Day Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Year To Day Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Year To Day Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Year To Day Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Year To Day Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Year To Day Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Year To Day Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Year To Day Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Year To Day Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Month To Day Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Month To Day Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Month To Day Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Month To Day Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Month To Day Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Month To Day Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Month To Day Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Month To Day Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Month To Day Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Month To Day Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Week To Day Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Week To Day Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Week To Day Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Week To Day Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Week To Day Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Week To Day Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Week To Day Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Week To Day Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Week To Day Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Week To Day Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 6 Month Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 6 Month Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Quarter Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Quarter Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Month Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Month Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Month Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Month Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Month Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Month Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 10 Day Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 10 Day Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 10 Day Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 10 Day Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 10 Day Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 10 Day Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 10 Day Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 10 Day Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 10 Day Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 10 Day Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Week Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Week Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Week Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Week Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Week Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Week Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Week Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Week Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Week Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Week Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Today Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Today Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Today Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Today Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Today Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Today Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Today Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Today Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Today Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Today Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Yesterday Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Yesterday Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Yesterday Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Yesterday Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Yesterday Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Yesterday Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Yesterday Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Yesterday Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Yesterday Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Yesterday Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Last Week Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Last Week Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Week Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Week Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Week Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Week Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Last Month Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Month Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Month Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Month Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Month Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier Last Month Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Month Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Month Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Last Month Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Last Month Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product Last Week Acc 1YB Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc 1YB Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc 1YB Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product Last Week Acc 1YB Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product Last Week Acc 1YB Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Page Store Dimension` CHANGE `Page Store Section` `Page Store Section` ENUM( 'Front Page Store', 'Search', 'Product Description', 'Information', 'Category Catalogue', 'Family Catalogue', 'Department Catalogue', 'Unknown', 'Store Catalogue', 'Registration', 'Client Section', 'Check Out' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Page Store Dimension` ADD `Page Parent Code` VARCHAR( 64 ) NULL DEFAULT NULL AFTER `Page Parent Key` ;

CREATE TABLE `Page Snapshot Fact` (
`Date` DATE NOT NULL ,
`Site Key` SMALLINT UNSIGNED NULL DEFAULT NULL ,
`Page Key` MEDIUMINT UNSIGNED NOT NULL ,
`Requests` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
`Visitors` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
`Requests Logged In` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
`Visitors Logged In` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0'
) ENGINE = MYISAM ;

ALTER TABLE `Page Snapshot Fact` ADD INDEX ( `Site Key` ) ;
ALTER TABLE `Page Snapshot Fact` ADD INDEX ( `Page Key` ) ;
ALTER TABLE `Page Snapshot Fact` ADD INDEX ( `Date` ) ;


CREATE TABLE `Page Store See Also Bridge` (
`Page Store Key` MEDIUMINT UNSIGNED NOT NULL ,
`Page Store See Also Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Page Store Key` , `Page Store See Also Key` )
) ENGINE = MYISAM ;

ALTER TABLE `Page Store Dimension` ADD `Page Store See Also Type` ENUM( 'Auto', 'Manual' ) NOT NULL DEFAULT 'Auto' AFTER `Page Store Resume` ,ADD INDEX ( `Page Store See Also Type` ) ;
ALTER TABLE `Page Store See Also Bridge` ADD `Correlation Type` ENUM( 'Manual', 'Sales', 'Semantic' ) NOT NULL ,ADD `Correlation Value` FLOAT NULL DEFAULT NULL ;


CREATE TABLE `List Part Bridge` (
`List Key` SMALLINT( 5 ) NOT NULL ,
`Part SKU` MEDIUMINT( 8 ) NOT NULL
) ENGINE = MYISAM;


CREATE TABLE `Marketing Post Dimension` (
`Marketing Post Key` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
`Marketing Post Name` VARCHAR( 255 ) NOT NULL ,
`Store Key` MEDIUMINT( 8 ) NOT NULL ,
PRIMARY KEY ( `Marketing Post Key` )
) ENGINE = MYISAM;




CREATE TABLE `Marketing Post Sent Fact` (
`Marketing Post Key` INT( 8 ) NOT NULL AUTO_INCREMENT ,
`Customer Key` MEDIUMINT( 8 ) NOT NULL ,
`Store Key` MEDIUMINT( 8 ) NOT NULL ,
`Requested Date` DATE NOT NULL ,
`Sent Date` DATE NULL ,
PRIMARY KEY ( `Marketing Post Key` )
) ENGINE = MYISAM;

ALTER TABLE `Marketing Post Sent Fact` CHANGE `Marketing Post Key` `Marketing Post Sent Fact Key` INT( 8 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Marketing Post Sent Fact` ADD `Marketing Post Key` MEDIUMINT( 8 ) NOT NULL DEFAULT '1' AFTER `Marketing Post Sent Fact Key`;

CREATE TABLE `Email Link Dimension` (
`Email Link Dimension Key` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
`Email Link URL` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `Email Link Dimension Key` )
) ENGINE = MYISAM;



ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 6 Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 6 Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 6 Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 6 Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 6 Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 6 Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Quarter Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Quarter Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Quarter Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Supplier Dimension` 
ADD `Supplier 1 Quarter Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier 1 Quarter Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier 1 Quarter Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Order Transaction Fact` ADD `Supplier Metadata` TEXT NULL DEFAULT NULL ;




ALTER TABLE `Supplier Product Dimension` 
ADD `Supplier Product 1 Month Acc Parts Profit` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc Parts Profit After Storing` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc Parts Cost` DECIMAL (12,2) NOT NULL DEFAULT '0.00',
ADD `Supplier Product 1 Month Acc Parts Sold Amount` DECIMAL (12,2) NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Bought` FLOAT  NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Required` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts No Dispatched` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Sold` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Lost` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Broken` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Returned` FLOAT NOT NULL DEFAULT '0',
ADD `Supplier Product 1 Month Acc Parts Margin` FLOAT NOT NULL DEFAULT '0';


ALTER TABLE `Email Send Dimension` ADD `Email Send Creation Date` DATETIME NOT NULL AFTER `Email Key` ;
ALTER TABLE `Email Send Dimension` CHANGE `Email Send Recipient Type` `Email Send Recipient Type` ENUM( 'Customer', 'Supplier', 'User', 'Other', 'Staff' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Other';
ALTER TABLE `Email Send Dimension` CHANGE `Email Send Recipient Key` `Email Send Recipient Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;

ALTER TABLE `Email Send Read Fact` ADD `Agent Type` ENUM( 'Browser', 'Bot', 'Email Reader', 'Other' ) NOT NULL ,ADD `OS` VARCHAR( 64 ) NOT NULL ,ADD `Browser` VARCHAR( 64 ) NOT NULL ;
ALTER TABLE `Email Send Read Fact` ADD `IP` VARCHAR( 16 ) NOT NULL AFTER `Email Send Read Date` ;
ALTER TABLE `Email Send Read Fact` CHANGE `Agent Type` `Agent Type` ENUM( 'Browser', 'Bot', 'Email Reader', 'Other', 'Unknown' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Send Read Fact` DROP `Agent Type` ;
ALTER TABLE `Email Send Dimension` ADD `Email Send Type Parent Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Email Send Type Key` ,ADD INDEX ( `Email Send Type Parent Key` ) ;

ALTER TABLE `Order Dimension` DROP `Order Original Lines` , DROP `Order Current Lines` ;
ALTER TABLE `Order Dimension` ADD `Order Number Items` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Order Type` ;
ALTER TABLE `Order Transaction Deal Bridge` CHANGE `Product Key` `Product Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Order Transaction Fact` CHANGE `Current Dispatching State` `Current Dispatching State` ENUM( 'In Process by Customer', 'Submitted by Customer', 'In Process', 'Ready to Pick', 'Picking', 'Ready to Pack', 'Ready to Ship', 'Dispatched', 'Unknown', 'Packing', 'Cancelled', 'No Picked Due Out of Stock', 'No Picked Due No Authorised', 'No Picked Due Not Found', 'No Picked Due Other', 'Suspended' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Store Dimension` ADD `Store Active Email Reminders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Newsletters` ;
ALTER TABLE `Email Campaign Dimension` CHANGE `Email Campaign Type` `Email Campaign Type` ENUM( 'Newsletter', 'Marketing', 'Reminder' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Marketing';

ALTER TABLE `Email Campaign Scope Bridge` CHANGE `Email Campaign Scope` `Email Campaign Scope` ENUM( 'Product', 'Family', 'Department', 'Store', 'Campaign', 'Deal', 'Store Page', 'External Link' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;


ALTER TABLE `Email Campaign Scope Bridge` CHANGE `Email Campaign Scope Linked` `Email Campaign Scope Link Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,CHANGE `Email Campaign Scope Visited` `Email Campaign Scope Link Clicks` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';

CREATE TABLE `Email Link Click Fact` (
  `Email Link Key` int(10) unsigned NOT NULL,
  `Email Link Click Date` datetime NOT NULL,
  `IP` varchar(16) NOT NULL,
  `OS` varchar(64) NOT NULL,
  `Browser` varchar(64) NOT NULL,
  KEY `Email Link Key` (`Email Link Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `Email Campaign Scope Bridge` ADD `Email Campaign Scope Name` VARCHAR( 256 ) NOT NULL AFTER `Email Campaign Scope Key` ;
ALTER TABLE `Email Campaign Scope Bridge` ADD `Email Campaign Scope Type` ENUM( 'Context', 'Link' ) NOT NULL DEFAULT 'Context' AFTER `Email Campaign Key` ,ADD INDEX ( `Email Campaign Scope Type` ) ;
ALTER TABLE `Invoice Dimension` ADD INDEX ( `Invoice Customer Key` ) ;

ALTER TABLE `Email Campaign Scope Bridge` DROP INDEX `Email Campaign Key` ,ADD INDEX `Email Campaign Key` ( `Email Campaign Key` ) ;
RENAME TABLE `Email Campaign Scope Bridge` TO `Email Campaign Objetive Dimension` ;

ALTER TABLE `Email Campaign Objetive Dimension` ADD `Email Campaign Objetive Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST 

ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Scope Type` `Email Campaign Objetive Type` ENUM( 'Context', 'Link' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Context';
ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Scope Key` `Email Campaign Objetive Parent Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Scope` `Email Campaign Objetive Parent` ENUM( 'Product', 'Family', 'Department', 'Store', 'Campaign', 'Deal', 'Store Page', 'External Link' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Scope Name` `Email Campaign Objetive Name` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Scope Link Key` `Email Campaign Objetive Link Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Scope Link Clicks` `Email Campaign Objetive Link Clicks` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Objetive Link Key` `Email Campaign Objetive Links` MEDIUMINT UNSIGNED NULL DEFAULT '0';

CREATE TABLE `Email Campaign Objetive Link Bridge` (
`Email Campaign Objetive Key` MEDIUMINT UNSIGNED NOT NULL ,
`Email Link Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Email Campaign Objetive Key` , `Email Link Key` )
) ENGINE = MYISAM ;

ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Objetive Link Clicks` `Email Campaign Objetive Links Clicks` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Email Campaign Objetive Dimension` ADD `Email Campaign Objetive Term` ENUM( 'Order', 'Buy', 'Visit' ) NOT NULL , ADD `Email Campaign Objetive Term Metadata` VARCHAR( 1028 ) NOT NULL ;
ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Objetive Term` `Email Campaign Objetive Term` ENUM( 'Order', 'Buy', 'Visit', 'Use' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Content Dimension` CHANGE `Email Content Template Key` `Email Content Template Type` ENUM( 'Basic', 'Left Column', 'Right Column', 'Postcard' ) NOT NULL DEFAULT 'Basic';



ALTER TABLE `Email Content Dimension` ADD `Email Content Color Scheme Key` MEDIUMINT NOT NULL DEFAULT '1' AFTER `Email Content Template Type` ;
ALTER TABLE `Email Content Dimension` CHANGE `Email Content Header Image Source` `Email Content Header Image Source` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Email Content Paragraph Dimension` ADD `Paragraph Original Type` ENUM( 'Main', 'Side' ) NOT NULL DEFAULT 'Main' AFTER `Paragraph Type` ;

ALTER TABLE `Email Content Dimension` ADD `Email Content Color Scheme Historic Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Email Content Color Scheme Key` ;
ALTER TABLE `Email Template Header Image Dimension` CHANGE `Email Template Header Image Name` `Email Template Header Image Name` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Content Dimension` CHANGE `Email Content Header Image Source` `Email Content Header Image Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Email Template Header' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Email Template Header', 'Email Template Postcard', 'Email Image' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Email Template Header', 'Store Email Postcard', 'Email Image' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Store Email Template Header', 'Store Email Postcard', 'Email Image' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Email Template Header Image Dimension` ADD INDEX ( `Image Key` ) ;

CREATE TABLE `Email Template Postcard Dimension` (
`Email Template Postcard Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Email Template Postcard Name` VARCHAR( 256 ) NOT NULL ,
`Store Key` MEDIUMINT UNSIGNED NOT NULL ,
`Image Key` MEDIUMINT UNSIGNED NOT NULL
) ENGINE = MYISAM ;


ALTER TABLE `Email Content Dimension` ADD `Email Content Template Postcard Key` MEDIUMINT UNSIGNED NOT NULL ;
ALTER TABLE `Email Campaign Dimension` DROP `Email Campaign Objective` ;
ALTER TABLE `Email Campaign Dimension` CHANGE `Email Campaign Content Type` `Email Campaign Content Type` ENUM( 'HTML', 'Multi HTML', 'Plain', 'HTML Template', 'Multi Plain', 'Multi HTML Template', 'Multi Mixed', 'Unknown' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Attachment Bridge` CHANGE `Attachment Key` `Attachment Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ;
ALTER TABLE `Attachment Bridge` CHANGE `Subject Key` `Subject Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ;
ALTER TABLE `Customer History Bridge` CHANGE `Type` `Type` ENUM( 'Notes', 'Orders', 'Changes', 'Attachments', 'Emails' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Notes';
ALTER TABLE `User Dimension` CHANGE `User Themes` `User Theme Key` SMALLINT UNSIGNED NULL DEFAULT NULL ,CHANGE `User Theme Background Status` `User Theme Background Key` SMALLINT UNSIGNED NULL DEFAULT NULL ;



RENAME TABLE `Deal Dimension` TO `Deal Metadata Dimension` ;
ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Key` `Deal Metadata Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT ;

ALTER TABLE `Deal Metadata Dimension` 
CHANGE `Deal Metadata Key` `Deal Metadata Key` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT, 
CHANGE `Deal Status` `Deal Metadata Status` ENUM('Suspended','Active','Finish','Waiting') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active', 
CHANGE `Deal Name` `Deal Metadata Name` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
CHANGE `Store Key` `Store Key` MEDIUMINT(8) UNSIGNED NOT NULL, CHANGE `Deal Trigger` `Deal Metadata Trigger` ENUM('Family','Product','Order') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
CHANGE `Deal Trigger Key` `Deal Metadata Trigger Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL, 
CHANGE `Campaign Deal Schema Key` `Campaign Deal Schema Key` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Terms Type` `Deal Metadata Terms Type` ENUM( 'Order Total Net Amount AND Order Number', 'Order Items Net Amount AND Shipping Country', 'Order Interval', 'Product Quantity Ordered', 'Family Quantity Ordered', 'Total Amount', 'Order Number', 'Total Amount AND Shipping Country', 'Total Amount AND Order Number' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Terms Description` `Deal Metadata Terms Description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Deal Terms Lock` `Deal Metadata Terms Lock` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No',
CHANGE `Deal Terms Metadata` `Deal Metadata Terms` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Deal Allowance Type` `Deal Metadata Allowance Type` ENUM( 'Percentage Off', 'Get Free', 'Get Same Free' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Deal Allowance Target` `Deal Metadata Allowance Target` ENUM( 'Product', 'Order', 'Shipping', 'Charge', 'Family', 'Department' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Allowance Target Key` `Deal Metadata Allowance Target Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ,
CHANGE `Deal Allowance Description` `Deal Metadata Allowance Description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Deal Allowance Metadata` `Deal Metadata Allowance` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Deal Allowance Lock` `Deal Metadata Allowance Lock` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No',
CHANGE `Deal Replace` `Deal Metadata Replace Type` ENUM( 'same target', 'same tigger', 'same target and tigger', 'deal', 'none' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'none';

ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Replace Metadata` `Deal Metadata Replace` VARCHAR( 4096 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Deal Begin Date` `Deal Metadata Begin Date` DATETIME NULL DEFAULT NULL ,
CHANGE `Deal Expiration Date` `Deal Metadata Expiration Date` DATETIME NULL DEFAULT NULL ;
RENAME TABLE `Campaign Dimension` TO `Deal Dimension` ;

ALTER TABLE `Deal Dimension` 
CHANGE `Campaign Key` `Deal Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `Campaign Code` `Deal Code` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Campaign Name` `Deal Name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `Campaign Description` `Deal Description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;


ALTER TABLE `Deal Dimension` CHANGE `Campaign Deal Terms Type` `Deal Terms Type` ENUM( 'Order Total Net Amount AND Order Number', 'Order Items Net Amount AND Shipping Country', 'Order Interval', 'Product Quantity Ordered', 'Family Quantity Ordered', 'Total Amount', 'Order Number', 'Total Amount AND Shipping Country', 'Total Amount AND Order Number' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `Deal Dimension` 
CHANGE `Campaign Deal Terms Description` `Deal Terms Description` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Campaign Deal Terms Lock` `Deal Terms Lock` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No',
CHANGE `Campaign Deal Terms Metadata` `Deal Terms Metadata` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Campaign Begin Date` `Deal Begin Date` DATETIME NULL DEFAULT NULL ,
CHANGE `Campaign Expiration Date` `Deal Expiration Date` DATETIME NULL DEFAULT NULL ;
update `User Dimension` set `User Theme Key`=1,`User Theme Background Key`=1;
ALTER TABLE `Deal Metadata Dimension` ADD `Deal Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Deal Metadata Key` ,ADD INDEX ( `Deal Key` ) ;
ALTER TABLE `Deal Dimension` ADD `Deal Terms Object` ENUM( 'Order', 'Department', 'Family', 'Product' ) NOT NULL AFTER `Deal Description` ,ADD INDEX ( `Deal Terms Object` ) ;

// RUN fix_deals.php

ALTER TABLE `Deal Dimension` ADD `Deal Number Elegible Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',ADD `Deal Number Used Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Deal Dimension` ADD `Deal Number Elegible Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',ADD `Deal Number Used Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Deal Dimension` CHANGE `Deal Number Elegible Orders` `Deal Total Acc Elegible Orders` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Deal Number Used Orders` `Deal Total Acc Used Orders` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Deal Number Elegible Customers` `Deal Total Acc Elegible Customers` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Deal Number Used Customers` `Deal Total Acc Used Customers` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Deal Metadata Dimension` ADD `Deal Metadata Total Acc Used` MEDIUMINT NOT NULL DEFAULT '0';
ALTER TABLE `Deal Dimension` CHANGE `Deal Total Acc Used Customers` `Deal Total Acc Customers` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Metadata Total Acc Used` `Deal Metadata Total Acc Used Orders` MEDIUMINT( 9 ) NOT NULL DEFAULT '0';
ALTER TABLE `Deal Metadata Dimension` ADD `Deal Metadata Total Acc Used Customers` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Metadata Total Acc Used Orders` `Deal Metadata Total Acc Used Orders` MEDIUMINT( 9 ) UNSIGNED NOT NULL DEFAULT '0';

CREATE TABLE `Order Deal Bridge` (
`Order Key` MEDIUMINT UNSIGNED NOT NULL ,
`Deal Key` MEDIUMINT UNSIGNED NOT NULL ,
`Elegible` ENUM( 'Yes', 'No' ) NOT NULL ,
`Used` ENUM( 'Yes', 'No' ) NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `Order Deal Bridge` ADD INDEX ( `Order Key` ) ;
ALTER TABLE `Order Deal Bridge` ADD INDEX ( `Deal Key` ) ;
ALTER TABLE `Order Deal Bridge` ADD INDEX ( `Elegible` ); 
ALTER TABLE `Order Deal Bridge` ADD INDEX ( `Used` ) ;

ALTER TABLE `Order Deal Bridge` CHANGE `Elegible` `Applied` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `Deal Dimension` CHANGE `Deal Total Acc Elegible Orders` `Deal Total Acc Applied Orders` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `Deal Total Acc Elegible Customers` `Deal Total Acc Applied Customers` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Deal Dimension` CHANGE `Deal Terms Type` `Deal Terms Type` ENUM( 'Order Total Net Amount AND Order Number', 'Order Items Net Amount AND Shipping Country', 'Order Interval', 'Product Quantity Ordered', 'Family Quantity Ordered', 'Total Amount', 'Order Number', 'Total Amount AND Shipping Country', 'Total Amount AND Order Number', 'Voucher' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Metadata Allowance Type` `Deal Metadata Allowance Type` ENUM( 'Percentage Off', 'Get Free', 'Get Same Free', 'Credit' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Deal Metadata Dimension` CHANGE `Deal Metadata Terms Type` `Deal Metadata Terms Type` ENUM( 'Order Total Net Amount AND Order Number', 'Order Items Net Amount AND Shipping Country', 'Order Interval', 'Product Quantity Ordered', 'Family Quantity Ordered', 'Total Amount', 'Order Number', 'Total Amount AND Shipping Country', 'Total Amount AND Order Number', 'Voucher' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Order No Product Transaction Fact` CHANGE `Transaction Type` `Transaction Type` ENUM( 'Credit', 'Unknown', 'Refund', 'Shipping', 'Charges', 'Adjust', 'Other', 'Deal' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Order Transaction Deal Bridge` CHANGE `Deal Key` `Deal Metadata Key` MEDIUMINT( 9 ) NOT NULL ;
ALTER TABLE `Order Transaction Fact`  DROP `Deal Key`;
ALTER TABLE `Order Deal Bridge` ADD PRIMARY KEY ( `Order Key` , `Deal Key` ) ;

CREATE TABLE `Dashboard Dimension` (
`Dashboard Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`User key` MEDIUMINT UNSIGNED NOT NULL ,
`Dashbord Type` ENUM( '1 Block', '2 Blocks', '3 Blocks' ) NOT NULL ,
`Dashbord URL` TEXT NOT NULL ,
`Dashbord Metadata` TEXT NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `Dashboard Dimension` ADD `Dashboard Order` SMALLINT UNSIGNED NOT NULL AFTER `User key` ;
ALTER TABLE `Dashboard Dimension` CHANGE `Dashbord Type` `Dashbord Type` ENUM( '1 Block', '2 Blocks', '3 Blocks', '1.5 Block' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Dashboard Dimension` CHANGE `Dashbord Type` `Dashbord Class` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Dashboard Dimension` CHANGE `Dashbord Class` `Dashboard Class` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Dashboard Dimension` ADD INDEX ( `User key` ) ;
ALTER TABLE `Dashboard Dimension` CHANGE `Dashbord URL` `Dashboard URL` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , CHANGE `Dashbord Metadata` `Dashboard Metadata` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `Charge Dimension` ADD `Charge Active` ENUM( 'Yes', 'No' ) NOT NULL AFTER `Charge Terms Metadata` ,ADD INDEX ( `Charge Active` ) ;
ALTER TABLE `Store Dimension` ADD `Store Websites` SMALLINT UNSIGNED NOT NULL DEFAULT '0';
// right.sql
// themes.sql

CREATE TABLE `Site Header Image Dimension` (
  `Site Header Image Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Site Header Image Name` varchar(256) CHARACTER SET utf8 NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Image Key` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Site Header Image Key`),
  KEY `Store Key` (`Store Key`),
  KEY `Image Key` (`Image Key`)
) ENGINE=MyISAM ;
ALTER TABLE `List Dimension` ADD `List Number Items` MEDIUMINT NOT NULL DEFAULT '0';
ALTER TABLE `List Dimension` ADD `List Number Items B` MEDIUMINT NOT NULL DEFAULT '0';

ALTER TABLE `Site Dimension` ADD `Site Slogan` VARCHAR( 256 ) NOT NULL AFTER `Site URL` ,
ADD `Site Logo Image Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Site Slogan` ,
ADD `Site Checkout Method` ENUM( 'Mals', 'Inikoo' ) NOT NULL DEFAULT 'Inikoo' AFTER `Site Logo Image Key` ,
ADD `Site Registration Method` ENUM( 'SideBar', 'MainPage' ) NOT NULL AFTER `Site Checkout Method` ,
ADD `Site FTP Credentials` VARCHAR( 1028 ) NOT NULL AFTER `Site Registration Method` ;
CREATE TABLE `Email Template Historic Color Scheme Dimension` (
  `Email Template Historic Color Scheme Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `HQ Dimension` ADD `Inikoo Public URL` VARCHAR( 256 ) NOT NULL ;

ALTER TABLE `Email Template Header Image Dimension` ADD UNIQUE (`Store Key` ,`Image Key`);
ALTER TABLE `Email Content Dimension` CHANGE `Email Content Header Image Key` `Email Template Header Image Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;

ALTER TABLE `User Dimension` ADD `User Staff Type` ENUM( 'Active Working', 'Active Not Working', 'Inactive Working', 'Inactive Not Working' ) NOT NULL AFTER `User Type` ,ADD INDEX ( `User Staff Type` ) ;
ALTER TABLE `User Dimension` CHANGE `User Staff Type` `User Staff Type` ENUM( 'Active Working', 'Active Not Working', 'Inactive Working', 'Inactive Not Working' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Page Store Dimension` CHANGE `Page Store Source` `Page Store Source` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Store Email Template Header', 'Store Email Postcard', 'Email Image', 'Page' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Page Store Dimension` ADD `Page Store CSS` LONGTEXT NULL DEFAULT NULL AFTER `Page Store Source` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Header Source` LONGTEXT NULL DEFAULT NULL AFTER `Page Store Source` ,ADD `Page Store Footer Source` LONGTEXT NULL DEFAULT NULL AFTER `Page Store Header Source` ;

CREATE TABLE `Page Header Dimension` (
`Page Header Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Site Key` MEDIUMINT UNSIGNED NOT NULL ,
`Template` LONGTEXT NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `Page Footer Dimension` (
`Page Footer Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Site Key` MEDIUMINT UNSIGNED NOT NULL ,
`Template` LONGTEXT NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `Page Header Dimension` ADD `Page Header Name` VARCHAR( 256 ) NOT NULL AFTER `Page Header Key` ;
ALTER TABLE `Page Header Dimension` ADD `CSS` LONGTEXT NOT NULL ;
ALTER TABLE `Page Header Dimension` ADD `Javascript` LONGTEXT NOT NULL ;
ALTER TABLE `Page Footer Dimension` ADD `Page Footer Name` VARCHAR( 256 ) NOT NULL AFTER `Page Footer Key` ;
ALTER TABLE `Page Footer Dimension` ADD `CSS` LONGTEXT NOT NULL ;
ALTER TABLE `Page Footer Dimension` ADD `Javascript` LONGTEXT NOT NULL ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Javascript` LONGTEXT NULL DEFAULT NULL AFTER `Page Store CSS` ;
ALTER TABLE `Page Store Dimension` CHANGE `Page Store Header Source` `Page Header Key` MEDIUMINT NULL DEFAULT NULL ,
CHANGE `Page Store Footer Source` `Page Footer Key` MEDIUMINT NULL DEFAULT NULL ;
ALTER TABLE `Site Dimension` ADD `Site Contact Address` VARCHAR( 1024 ) NOT NULL AFTER `Site URL` ,ADD `Site Contact Telephone` VARCHAR( 256 ) NOT NULL AFTER `Site Contact Address` ;


CREATE TABLE `Page Store External File Dimension` (
`Page Store External File Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Page Key` MEDIUMINT UNSIGNED NOT NULL ,
`Page Store External File Type` ENUM( 'Javascript', 'CSS' ) NOT NULL ,
`Page Store External File Content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `Page Store External File Dimension` DROP `Page Key` ;


CREATE TABLE `Page Store External File Bridge` (
`Page Store External File Key` MEDIUMINT UNSIGNED NOT NULL ,
`Page Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Page Store External File Key` , `Page Key` )
) ENGINE = MYISAM ;
ALTER TABLE `Page Store External File Dimension` ADD `Page Store External File Name` VARCHAR( 256 ) NOT NULL AFTER `Page Store External File Key` ;

CREATE TABLE `Page Header External File Bridge` (
`Page Store External File Key` MEDIUMINT UNSIGNED NOT NULL ,
`Page Header Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Page Store External File Key`, `Page Header Key` )
) ENGINE = MYISAM ;

CREATE TABLE `Page Footer External File Bridge` (
`Page Store External File Key` MEDIUMINT UNSIGNED NOT NULL ,
`Page Footer Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Page Store External File Key`,`Page Footer Key` )
) ENGINE = MYISAM ;

CREATE TABLE `Site External File Bridge` (
`Page Store External File Key` MEDIUMINT UNSIGNED NOT NULL ,
`Site Key` MEDIUMINT UNSIGNED NOT NULL ,
PRIMARY KEY ( `Page Store External File Key`,`Site Key` )
) ENGINE = MYISAM ;

ALTER TABLE `Site External File Bridge` ADD `External File Type` ENUM( 'Javascript', 'CSS' ) NOT NULL ;
ALTER TABLE `Page Footer External File Bridge` ADD `External File Type` ENUM( 'Javascript', 'CSS' ) NOT NULL ;
ALTER TABLE `Page Header External File Bridge` ADD `External File Type` ENUM( 'Javascript', 'CSS' ) NOT NULL ;
ALTER TABLE `Page Store External File Bridge` ADD `External File Type` ENUM( 'Javascript', 'CSS' ) NOT NULL ;
ALTER TABLE `Page Header Dimension` ADD `Default Site` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes' AFTER `Template` ,ADD INDEX ( `Default Site` ) ;

ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Store Email Template Header', 'Store Email Postcard', 'Email Image', 'Page', 'Page Header', 'Page Footer', 'Site' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

//----

RENAME TABLE `Dashboard Dimension` TO `Dashboard User Bridge` ;
ALTER TABLE `Page Store Dimension` ADD `Page Product Metadata` LONGTEXT NOT NULL DEFAULT '';
ALTER TABLE `Page Store Dimension` ADD `Page List Metadata` LONGTEXT NOT NULL DEFAULT '';
ALTER TABLE `Page Store Dimension` ADD `Page Locale` CHAR( 5 ) NOT NULL DEFAULT 'en_GB' AFTER `Page Store Order Template` ;

ALTER TABLE `Site Dimension` ADD `Site Checkout Mals Metadata` MEDIUMTEXT NOT NULL AFTER `Site Checkout Method` ;
ALTER TABLE `Email Campaign Objetive Dimension` CHANGE `Email Campaign Objetive Parent` `Email Campaign Objective Parent` ENUM('Product','Family','Department','Store','Campaign','Deal','Store Page','External Link') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `Email Campaign Objetive Parent Key` `Email Campaign Objective Parent Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL, CHANGE `Email Campaign Objetive Name` `Email Campaign Objective Name` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `Email Campaign Objetive Links` `Email Campaign Objective Links` MEDIUMINT(8) UNSIGNED NULL DEFAULT '0', CHANGE `Email Campaign Objetive Term` `Email Campaign Objective Term` ENUM('Order','Buy','Visit','Use') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `Email Campaign Objetive Link Bridge` CHANGE `Email Campaign Objetive Key` `Email Campaign Objective Key` MEDIUMINT( 8 ) UNSIGNED NOT NULL ;
RENAME TABLE `Email Campaign Objetive Link Bridge` TO `Email Campaign Objective Link Bridge` ;
RENAME TABLE `Email Campaign Objetive Dimension` TO .`Email Campaign Objective Dimension` ;
ALTER TABLE `History Dimension` CHANGE `Direct Object` `Direct Object` ENUM( 'After', 'Delivery', 'Category', 'Warehouse', 'Warehouse', 'Shelf', 'Location', 'Company', 'Company', 'Position', 'Store', 'User', 'Product', 'Address', 'Customer', 'Note', 'Order', 'Telecom', 'Email', 'Company', 'Contact', 'FAX', 'Telephone', 'Mobile', 'Work', 'Office', 'Supplier', 'Family', 'Department', 'Attachment', 'Supplier', 'Part', 'Site', 'Page' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `Page Header Dimension` ADD `Number Pages` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Key` ;
ALTER TABLE `Page Footer Dimension` ADD `Number Pages` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Key` ;

ALTER TABLE `Page Header Dimension` ADD `Page Header Preview Image Key` MEDIUMINT NULL DEFAULT NULL AFTER `Page Header Name` ;
ALTER TABLE `Page Footer Dimension` ADD `Page Footer Preview Image Key` MEDIUMINT NULL DEFAULT NULL AFTER `Page Footer Name` ;

ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM('Product','Family','Department','Store','Website','Part','Supplier Product','Store Logo','Store Email Template Header','Store Email Postcard','Email Image','Page','Page Header','Page Footer','Site','Page Header Preview','Page Footer Preview','Page Preview') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `Site Dimension` ADD `Site Default Header Key` MEDIUMINT NULL DEFAULT NULL ,ADD `Site Default Footer Key` MEDIUMINT NULL DEFAULT NULL ;

ALTER TABLE `Page Store Dimension` ADD `Page Header Type` ENUM( 'Set', 'SiteDefault' ) NOT NULL DEFAULT 'SiteDefault' AFTER `Page Header Key` ,ADD INDEX ( `Page Header Type` ); 
ALTER TABLE `Page Store Dimension` ADD `Page Footer Type` ENUM( 'Set', 'SiteDefault' ) NOT NULL DEFAULT 'SiteDefault' AFTER `Page Footer Key` ,ADD INDEX ( `Page Footer Type` ); 

CREATE TABLE IF NOT EXISTS `Page Product List Dimension` (
  `Page Product Form Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Page Product Form Code` varchar(12) NOT NULL DEFAULT '1',
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Page Product Form Type` enum('FamilyList','CustomList') NOT NULL DEFAULT 'FamilyList',
  `Page Product Form Parent Key` mediumint(9) DEFAULT NULL,
  `Show RRP` enum('Yes','No') NOT NULL DEFAULT 'No',
  `List Order` enum('Code','Name','Special Characteristic','Price','RRP','Sales','Date') NOT NULL DEFAULT 'Code',
  `Range` varchar(246) DEFAULT NULL,
  `Range Scope` enum('Code','Name','Special Characteristic') NOT NULL DEFAULT 'Special Characteristic',
  `List Product Description` enum('Units Name','Units Special Characteristic') NOT NULL DEFAULT 'Units Special Characteristic',
  `List Max Items` smallint(5) unsigned NOT NULL DEFAULT '500',
  PRIMARY KEY (`Page Product Form Key`),
  KEY `Page Key` (`Page Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;


CREATE TABLE IF NOT EXISTS `Page Product From Dimension` (
  `Page Product From Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Page Key` mediumint(8) unsigned NOT NULL,
  `Product ID` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`Page Product From Key`),
  UNIQUE KEY `Page Key` (`Page Key`),
  KEY `Product ID` (`Product ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `Page Product List Dimension` CHANGE `Page Product Form Page Key` `Page Product Form Page Key` VARCHAR( 64 ) NOT NULL DEFAULT '1';
ALTER TABLE `Page Product List Dimension` CHANGE `Page Product Form Page Key` `Page Product Form Name` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1';
ALTER TABLE `Page Product List Dimension` CHANGE `Page Product Form Code` `Page Product Form Code` VARCHAR( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default';

//==================================


RENAME TABLE `Page Product From Dimension` TO `Page Product Dimension` ;
ALTER TABLE `Page Product Dimension` DROP INDEX `Page Key` ,ADD INDEX `Page Key` ( `Page Key` ) ;
ALTER TABLE `Page Store Dimension` ADD `Number Lists` SMALLINT NOT NULL DEFAULT '0',ADD `Number Products In Lists` SMALLINT NOT NULL DEFAULT '0',ADD `Number Buttons` SMALLINT NOT NULL DEFAULT '0',ADD `Number Products` SMALLINT NOT NULL DEFAULT '0';
ALTER TABLE `Page Product List Dimension` ADD `Page Product List Number Products` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Page Product Form Parent Key` ;
ALTER TABLE `Page Product List Dimension` CHANGE `List Product Description` `List Product Description` ENUM('Units Name','Units Special Characteristic','Units Name RRP','Units Special Characteristic RRP') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Units Special Characteristic';
ALTER TABLE `Page Product List Dimension` DROP `Range Scope`;
ALTER TABLE `Page Store Dimension` ADD FULLTEXT (`Page Store Title`);
ALTER TABLE `Page Store Dimension` ADD FULLTEXT (`Page Store Resume`);

ALTER TABLE `Dashboard User Bridge` ADD `Dashboard Height` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Dashboard URL` ;
ALTER TABLE `Site Dimension` ADD `Site Menu HTML` LONGTEXT NOT NULL ,ADD `Site Menu CSS` LONGTEXT NOT NULL ,ADD `Site Menu Javascript` LONGTEXT NOT NULL ,ADD `Site Search HTML` LONGTEXT NOT NULL ,ADD `Site Search CSS` LONGTEXT NOT NULL ,ADD `Site Search Javascript` LONGTEXT NOT NULL ;
ALTER TABLE `History Dimension` CHANGE `Direct Object` `Direct Object` ENUM( 'After Sale', 'Delivery Note', 'Category', 'Warehouse', 'Warehouse Area', 'Shelf', 'Location', 'Company Department', 'Company Area', 'Position', 'Store', 'User', 'Product', 'Address', 'Customer', 'Note', 'Order', 'Telecom', 'Email', 'Company', 'Contact', 'FAX', 'Telephone', 'Mobile', 'Work Telephone', 'Office Fax', 'Supplier', 'Family', 'Department', 'Attachment', 'Supplier Product', 'Part', 'Site', 'Page' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Category Dimension` ADD `Category Warehouse Key` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Category Store Key` ,ADD INDEX ( `Category Warehouse Key` ) ;

ALTER TABLE `Product Dimension` ADD `Product Main Image Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Main Image` ;
ALTER TABLE `Product Family Dimension` ADD `Product Family Main Image Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Family Main Image` ;
ALTER TABLE `Product Department Dimension` ADD `Product Department Main Image Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Department Main Image` ;
ALTER TABLE `Part Dimension` ADD `Part Main Image Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Part Main Image` ;

//run script Calc_Product_sales to add Product Main Image Key

ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Store Email Template Header', 'Store Email Postcard', 'Email Image', 'Page', 'Page Header', 'Page Footer', 'Site', 'Page Header Preview', 'Page Footer Preview', 'Page Preview', 'Site Menu', 'Site Search' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Site Dimension` ADD `Site Locale` ENUM( 'en_GB', 'de_DE', 'fr_FR', 'es_ES', 'pl_PL', 'it_IT' ) NOT NULL DEFAULT 'en_GB' AFTER `Site URL` ;
ALTER TABLE `MasterKey Dimension` CHANGE `Key` `Key` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Page Dimension` CHANGE `Page Thumbnail Image Key` `Page Snapshot Image Key` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Page Dimension` DROP `Page Thumbnail Image Key` ;
ALTER TABLE `Page Store Dimension` ADD `Page Preview Snapshot Image Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `Page Store Dimension` ADD `Page Header Height` MEDIUMINT UNSIGNED NOT NULL ,ADD `Page Content Height` MEDIUMINT UNSIGNED NOT NULL ,ADD `Page Footer Height` MEDIUMINT UNSIGNED NOT NULL ;
ALTER TABLE `Page Store Dimension` CHANGE `Page Header Height` `Page Header Height` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '150',CHANGE `Page Content Height` `Page Content Height` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '518',CHANGE `Page Footer Height` `Page Footer Height` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '100';
ALTER TABLE `Site Dimension` ADD `Site Code` VARCHAR( 8 ) NOT NULL AFTER `Site Store Key` ;

ALTER TABLE `Page Store Dimension` DROP `Page Product Metadata`,DROP `Page List Metadata`;
ALTER TABLE `Page Store Dimension` ADD `Number See Also Links` TINYINT( 3 ) UNSIGNED NOT NULL AFTER `Page Store Last Structural Change Date` ;

ALTER TABLE `Site Dimension` ADD `Site Default Number See Also Links` TINYINT( 4 ) UNSIGNED NOT NULL AFTER `Site Default Footer Key` ;

ALTER TABLE `Page Store Dimension` ADD `Page Store Content Display Type` ENUM( 'Source', 'Template' ) NOT NULL DEFAULT 'Source' AFTER `Page Store See Also Type` ,ADD `Page Store Content Template Filename` VARCHAR( 128 ) NULL DEFAULT NULL AFTER `Page Store Content Display Type` ;
ALTER TABLE `User Dimension` CHANGE `User Password` `User Password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `User Dimension` ADD `User Verified` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No';
ALTER TABLE `Custom Field Dimension` ADD `Custom Field In Registration` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `Custom Field In Showcase` ,ADD `Custom Field In Profile` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `Custom Field In Registration` ;

ALTER TABLE `Custom Field Dimension` CHANGE `Custom Field Type` `Custom Field Type` ENUM( 'Mediumint', 'Text', 'Longtext', 'Enum' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 
ALTER TABLE `Dashboard User Bridge` ADD `Dashboard ID` SMALLINT UNSIGNED NOT NULL DEFAULT '1' AFTER `User key` ;
ALTER TABLE `Dashboard User Bridge` ADD `Dashboard Widget Key` MEDIUMINT UNSIGNED NOT NULL DEFAULT '1' AFTER `Dashboard Order` ;
ALTER TABLE `Dashboard User Bridge` ADD `Dashboard Active` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No';

CREATE TABLE IF NOT EXISTS `Dashboard Widget Dimension` (
 `Dashboard Widget Key` mediumint(8) NOT NULL,
 `Widget Name` varchar(255) NOT NULL,
 `Widget Block` varchar(255) NOT NULL,
 `Widget Dimension` varchar(255) NOT NULL,
 `Widget URL` varchar(255) NOT NULL,
 `Widget Description` varchar(255) NOT NULL,
 PRIMARY KEY (`Dashboard Widget Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  ;

INSERT INTO `Dashboard Widget Dimension` (`Dashboard Widget Key`, `Widget Name`, `Widget Block`, `Widget Dimension`, `Widget URL`, `Widget Description`) VALUES
(1, 'Dashboard Block', 'block_3', '', 'dashboard_block.php?tipo=sales_overview', ''),
(2, 'Twitter', 'block_1', '405', 'splinter_twitter.php?', ''),
(3, 'Facebook', 'block_2', '560', '//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FAncientWisdom.biz&amp;width=614&amp;height=560&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=true&amp;header=false', '');

// dashboard.sql
ALTER TABLE `Site Dimension` CHANGE `Site Registration Method` `Site Registration Method` ENUM( 'Simple', 'Wholesale', 'None' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Simple';
ALTER TABLE `Site Dimension` DROP `Site FTP Credentials`;
ALTER TABLE `Site Dimension` ADD `Site FTP Server` VARCHAR( 256 ) NULL DEFAULT NULL ,
ADD `Site FTP User` VARCHAR( 256 ) NULL DEFAULT NULL ,
ADD `Site FTP Password` VARCHAR( 256 ) NULL DEFAULT NULL ,
ADD `Site FTP Directory` VARCHAR( 256 ) NOT NULL DEFAULT '',
ADD `Site FTP Passive` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No';

ALTER TABLE `Page Store Dimension` ADD `Page Upload State` ENUM( 'ToUpload', 'Uploaded' ) NOT NULL DEFAULT 'ToUpload' AFTER `Page Code` ;
ALTER TABLE `Page Store Dimension` ADD `Page Preview Snapshot Last Update` DATETIME NULL DEFAULT NULL AFTER `Page Preview Snapshot Image Key` ;


CREATE TABLE `Page Store Deleted Dimension` (
  `Page Store Deleted Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Page Title` varchar(255) NOT NULL,
  `Page Short Title` varchar(64) NOT NULL,
  `Page Description` text,
  `Page URL` varchar(1024) NOT NULL,
  `Page Snapshot Image Key` mediumint(8) unsigned DEFAULT NULL,
  `Page Snapshot Last Update` datetime DEFAULT NULL,
   `Page Valid To` datetime DEFAULT NULL,
  PRIMARY KEY (`Page Store Deleted Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `Page Store Deleted Dimension` ADD `Site Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Page Store Deleted Key` ,ADD INDEX ( `Site Key` ) ;
ALTER TABLE `Page Store Deleted Dimension` ADD `Page Code` VARCHAR( 256 ) NOT NULL AFTER `Page Store Deleted Key` ;
ALTER TABLE `Page Store Deleted Dimension` ADD `Page Store Section` VARCHAR( 256 ) NULL AFTER `Page URL` ,ADD `Page Parent Key` MEDIUMINT UNSIGNED NULL AFTER `Page Store Section` ,ADD `Page Parent Code` VARCHAR( 256 ) NULL AFTER `Page Parent Key` ;

ALTER TABLE `Part Location Dimension` ADD `Minimum Quantity` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Can Pick` ,ADD `Maximum Quantity` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Minimum Quantity` ;

ALTER TABLE `Store Dimension` ADD `Store VAT Number` VARCHAR( 255 ) NOT NULL ,ADD `Store Company Number` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `Customer Dimension` ADD `Customer Follower On Twitter` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes',ADD `Customer Friend On Facebook` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes';
ALTER TABLE `Staff Dimension` CHANGE `Staff Type` `Staff Type` ENUM( 'Employee', 'Volunteer', 'Contractor', 'Temporal Worker', 'Work Experience' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Employee';


ALTER TABLE `Staff Dimension` ADD `Staff PIN` VARCHAR( 4 ) NOT NULL DEFAULT '1234' AFTER `Staff Department Key`;
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Date Done Approved` DATETIME NULL DEFAULT NULL AFTER `Delivery Note Date Finish Packing` ;
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Approved Done` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `Delivery Note Date` , ADD `Delivery Note Approved To Dispatch` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `Delivery Note Approved Done` ;
ALTER TABLE `Staff Dimension` DROP `Staff Position Key`;

ALTER TABLE `HQ Dimension` ADD `Short Message` VARCHAR( 128 ) NOT NULL ;

ALTER TABLE `Part Dimension` ADD `Part Transactions` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Part Main Image Key` ,
ADD `Part Transactions In` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Part Transactions` ,
ADD `Part Transactions Out` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Part Transactions In` ,
ADD `Part Transactions Audit` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Part Transactions Out` ,
ADD `Part Transactions OIP` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Part Transactions Audit` ,
ADD `Part Transactions Move` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Part Transactions OIP` ;
ALTER TABLE `Staff Dimension` ADD `Staff Is Supervisor` ENUM( 'Yes', 'No' ) NOT NULL;
ALTER TABLE `Staff Dimension` CHANGE `Staff Is Supervisor` `Staff Is Supervisor` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No';
ALTER TABLE `Store Dimension` ADD `Store Company Name` VARCHAR( 256 ) NOT NULL ;
ALTER TABLE `Store Dimension` ADD `Store Invoice Message Header` text NOT NULL ;
ALTER TABLE `Store Dimension` ADD `Store Invoice Message` text NOT NULL ;

pcd.sql;

ALTER TABLE `Order Dimension` ADD `Order Customer Sevices Note` TEXT NOT NULL;
ALTER TABLE `Invoice Dimension` ADD `Invoice Customer Sevices Note` TEXT NOT NULL ;
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Customer Sevices Note` TEXT NOT NULL ;
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Warehouse Note` TEXT NOT NULL ;
ALTER TABLE `Order Dimension` ADD `Order Current XHTML Dispatch State` TEXT NOT NULL AFTER `Order Current Dispatch State` ;
ALTER TABLE `Delivery Note Dimension` CHANGE `Delivery Note State` `Delivery Note State` ENUM( 'Picker & Packer Assigned', 'Picking & Packing', 'Packer Assigned', 'Ready to be Picked', 'Picker Assigned', 'Picking', 'Picked', 'Packing', 'Packed', 'Approved', 'Dispatched', 'Cancelled', 'Cancelled to Restock', 'Packed Done' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Ready to be Picked';
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note XHTML State` TEXT NOT NULL AFTER `Delivery Note State`;

ALTER TABLE `User Dimension` ADD `User Dashboard Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `User Created` ;

ALTER TABLE `Order Transaction Deal Bridge` ADD `Fraction Discount` FLOAT NOT NULL DEFAULT '0' AFTER `Amount Discount` ;

ALTER TABLE `Store Dimension` ADD `Store Total Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store 3 Year Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store 1 Year Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store Year To Day Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store Month To Day Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store Week To Day Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store 6 Month Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store 1 Quarter Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store 1 Month Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store 10 Day Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store 1 Week Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store Today Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store Yesterday Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store Last Week Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `Store Last Month Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;


ALTER TABLE `Warehouse Dimension` ADD `Warehouse Total Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse 3 Year Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse 1 Year Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse Year To Day Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse Month To Day Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse Week To Day Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse 6 Month Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse 1 Quarter Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse 1 Month Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse 10 Day Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse 1 Week Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse Today Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse Yesterday Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse Last Week Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;
ALTER TABLE `Warehouse Dimension` ADD `Warehouse Last Month Acc Average Dispatch Time` FLOAT NULL DEFAULT NULL;

ALTER TABLE `Order Dimension` ADD `Order Dispatched Date` DATETIME NULL DEFAULT NULL AFTER `Order Date` ;
ALTER TABLE `Order Dimension` ADD `Order Post Transactions Dispatched Date` DATETIME NULL DEFAULT NULL AFTER `Order Dispatched Date`;
DROP TABLE `Dashboard User Bridge`;
ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Store Email Template Header', 'Store Email Postcard', 'Email Image', 'Page', 'Page Header', 'Page Footer', 'Site', 'Page Header Preview', 'Page Footer Preview', 'Page Preview', 'Site Menu', 'Site Search', 'User Profile' ) ;
ALTER TABLE `User Dimension` ADD `User Main Image Key` MEDIUMINT( 8 ) NULL;
ALTER TABLE `Category Bridge` ADD `Customer Other Note` VARCHAR( 255 ) NULL;
ALTER TABLE `Category Dimension` ADD `Is Category Field Other` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No';




ALTER TABLE `Category Dimension` ADD `Category Show New Subject` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes' AFTER `Category Key` ,ADD `Category Show Public New Subject` ENUM( 'Yes', 'No' ) NOT NULL AFTER `Category Show New Subject` ,ADD `Category Show Public Edit` ENUM( 'Yes', 'No' ) NOT NULL AFTER `Category Show Public New Subject`;
 ALTER TABLE `Category Dimension` CHANGE `Category Show Public New Subject` `Category Show Public New Subject` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No';
ALTER TABLE `Category Dimension` CHANGE `Category Show Public Edit` `Category Show Public Edit` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No';

ALTER TABLE `History Dimension` CHANGE `Direct Object` `Direct Object` ENUM( 'After Sale', 'Delivery Note', 'Category', 'Warehouse', 'Warehouse Area', 'Shelf', 'Location', 'Company Department', 'Company Area', 'Position', 'Store', 'User', 'Product', 'Address', 'Customer', 'Note', 'Order', 'Telecom', 'Email', 'Company', 'Contact', 'FAX', 'Telephone', 'Mobile', 'Work Telephone', 'Office Fax', 'Supplier', 'Family', 'Department', 'Attachment', 'Supplier Product', 'Part', 'Site', 'Page', 'Invoice' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
CREATE TABLE `Email Credentials Site Bridge` (
`Email Credentials Key` MEDIUMINT( 8 ) NOT NULL ,
`Site Key` MEDIUMINT( 8 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `Email Credentials Dimension` CHANGE `Email Address` `Email Address` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Login` `Login` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Password` `Password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Incoming Mail Server` `Incoming Mail Server` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `Outgoing Mail Sever` `Outgoing Mail Server` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `Page Store Dimension` ADD `Number Found In Links` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Number See Also Links` ;


ALTER TABLE `Site Dimension` ADD `Site Welcome Email Body` LONGTEXT NULL ,ADD `Site Forgot Password Email Body` LONGTEXT NULL;
ALTER TABLE `Site Dimension` CHANGE `Site Welcome Email Body` `Site Welcome Email Plain Body` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `Site Dimension` ADD `Site Welcome Email Subject` VARCHAR( 255 ) NULL AFTER `Site FTP Passive`;
 ALTER TABLE `Site Dimension` ADD `Site Welcome Email HTML Body` LONGTEXT NULL AFTER `Site Welcome Email Plain Body`;
ALTER TABLE `Site Dimension` CHANGE `Site Forgot Password Email Body` `Site Forgot Password Email Plain Body` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `Site Dimension` ADD `Site Forgot Password Email Subject` VARCHAR( 255 ) NULL AFTER `Site Welcome Email HTML Body`;
 ALTER TABLE `Site Dimension` ADD `Site Forgot Password Email HTML Body` LONGTEXT NULL;
ALTER TABLE `Site Dimension` ADD `Site Welcome Source` LONGTEXT NULL;
ALTER TABLE `Site Dimension` ADD `Site FTP Protocol` ENUM( 'SFTP', 'FTP', 'FTPS' ) NOT NULL DEFAULT 'FTP' AFTER `Site FTP Passive`;
ALTER TABLE `Site Dimension` ADD `Site FTP Port` MEDIUMINT( 8 ) NULL AFTER `Site FTP Protocol`
ALTER TABLE `Site Dimension` CHANGE `Site FTP Protocol` `Site FTP Protocol` ENUM( 'SFTP', 'FTP', 'FTPS' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'FTPS';
ALTER TABLE `Site Dimension` CHANGE `Site FTP Port` `Site FTP Port` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL ;

ALTER TABLE `Site Dimension` ADD `Site Total Users` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `User Dimension` DROP INDEX `User Handle` ,ADD UNIQUE `User Handle` ( `User Handle` , `User Type` , `User Site Key` ) ;
ALTER TABLE `Email Credentials Dimension` ADD `Email Provider` ENUM( 'Gmail', 'Other' ) NOT NULL DEFAULT 'Other' AFTER `Email Credentials Key` ;
ALTER TABLE `Email Credentials Site Bridge` ADD PRIMARY KEY ( `Email Credentials Key` , `Site Key` ) ;
ALTER TABLE `User Click Dimension` ADD `OS` VARCHAR( 64 ) NULL DEFAULT NULL ,ADD `Browser` VARCHAR( 64 ) NULL DEFAULT NULL ;
ALTER TABLE `Page Dimension` ADD `Page Published` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No';
ALTER TABLE `Site Dimension` ADD `Link Type` ENUM( 'Absolute', 'Relative' ) NOT NULL DEFAULT 'Absolute';
ALTER TABLE `User Click Dimension` ADD `IP` VARCHAR( 64 ) NOT NULL AFTER `Previous Page Key` ;
ALTER TABLE `User Click Dimension` CHANGE `User Click Key` `User Click Key` INT UNSIGNED NOT NULL AUTO_INCREMENT ;


CREATE TABLE `Page Redirection Dimension` (
  `Page Redirection Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Source Host` varchar(1024) NOT NULL,
  `Source Path` varchar(1024) NOT NULL,
  `Source File` varchar(1024) NOT NULL,
  `Page Target URL` varchar(1024) NOT NULL,
  `Page Target Key` mediumint(8) unsigned NOT NULL,
  `Can Upload` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Redirect Uploaded` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Page Redirection Key`),
  KEY `Page Target Key` (`Page Target Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `Email Credentials Dimension` CHANGE `Password` `Password` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
//==============
ALTER TABLE `User Dimension` ADD `User Inactive Note` VARCHAR( 1024 ) NOT NULL DEFAULT '';
ALTER TABLE `Site Dimension` ADD `Site Show Facebook` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes',ADD `Site Show Twitter` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes';
ALTER TABLE `Site Dimension` ADD `Show Site Badges` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes';
ALTER TABLE `User Right Scope Bridge` CHANGE `Scope` `Scope` ENUM( 'Store', 'Warehouse', 'Supplier', 'Website' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Store';
ALTER TABLE `Product Family Dimension` CHANGE `Product Family Store Key` `Product Family Store Key` SMALLINT( 5 ) UNSIGNED NOT NULL ;

ALTER TABLE `User Click Dimension` CHANGE `User Click Key` `User Request Key` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ;
RENAME TABLE `User Click Dimension` TO `User Request Dimension` ;
ALTER TABLE `User Request Dimension` ADD `User Agent Key` BIGINT NULL DEFAULT NULL AFTER `Previous Page Key` ,ADD INDEX ( `User Agent Key` ) ;
CREATE TABLE `User Visitor Dimension` (
  `User Visitor Key` mediumint(9) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`User Visitor Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE kbase.`User Agent Dimension` (
  `User Agent Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `User Agent Name` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `User Agent String` text CHARACTER SET utf8 NOT NULL,
  `User Agent Description` text CHARACTER SET utf8,
  `User Agent Type` enum('Browser','Bot','Spam','Proxy','Other') CHARACTER SET utf8 DEFAULT NULL,
  `User Agent Family` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`User Agent Key`),
  UNIQUE KEY `User Agent String_2` (`User Agent String`(300)),
  KEY `User Agent String` (`User Agent String`(64)),
  KEY `User Agent Type` (`User Agent Type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `User Session Dimension` (
  `User Session Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `User Session Start Date` datetime NOT NULL,
  `User Session Last Request Date` datetime NOT NULL,
  PRIMARY KEY (`User Session Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `User Request Dimension` ADD `User Visitor Key` MEDIUMINT NULL DEFAULT NULL AFTER `User Log Key` , ADD `User Session Key` MEDIUMINT NULL DEFAULT NULL AFTER `User Visitor Key` ;
ALTER TABLE `User Visitor Dimension` ADD `User Visitor Site Key` MEDIUMINT UNSIGNED NOT NULL , ADD INDEX ( `User Visitor Site Key` ) ;
ALTER TABLE `User Session Dimension` ADD `User Session Visitor Key` MEDIUMINT UNSIGNED NOT NULL AFTER `User Session Key` ,ADD INDEX ( `User Session Visitor Key` ) ;
ALTER TABLE `User Session Dimension` ADD `User Session Site Key` MEDIUMINT UNSIGNED NOT NULL AFTER `User Session Key` ,ADD INDEX ( `User Session Site Key` );
ALTER TABLE `Page Store Dimension` CHANGE `Page Store Section` `Page Store Section` ENUM( 'Front Page Store', 'Search', 'Product Description', 'Information', 'Category Catalogue', 'Family Catalogue', 'Department Catalogue', 'Unknown', 'Store Catalogue', 'Registration', 'Client Section', 'Check Out', 'Login', 'Welcome' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';
ALTER TABLE `Page Store Dimension` CHANGE `Page Store Section` `Page Store Section` ENUM( 'Front Page Store', 'Search', 'Product Description', 'Information', 'Category Catalogue', 'Family Catalogue', 'Department Catalogue', 'Unknown', 'Store Catalogue', 'Registration', 'Client Section', 'Check Out', 'Login', 'Welcome', 'Not Found' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown';

ALTER TABLE `User Dimension` ADD `User Sessions Count` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `User Last Failed Login` ,ADD `User Requests Count` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `User Sessions Count` ,ADD `User Last Request` DATETIME NULL DEFAULT NULL AFTER `User Requests Count` ;


ALTER TABLE `Site Dimension` ADD `Site Total Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Total Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Total Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Total Acc Sessions` , ADD `Site Total Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Total Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 3 Year Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 3 Year Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 3 Year Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 3 Year Acc Sessions` , ADD `Site 3 Year Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 3 Year Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 1 Year Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Year Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Year Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Year Acc Sessions` , ADD `Site 1 Year Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Year Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 6 Month Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 6 Month Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 6 Month Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 6 Month Acc Sessions` , ADD `Site 6 Month Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 6 Month Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 1 Quarter Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Quarter Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Quarter Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Quarter Acc Sessions` , ADD `Site 1 Quarter Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Quarter Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 1 Month Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Month Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Month Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Month Acc Sessions` , ADD `Site 1 Month Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Month Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 10 Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 10 Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 10 Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 10 Day Acc Sessions` , ADD `Site 10 Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 10 Day Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 1 Week Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Week Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Week Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Week Acc Sessions` , ADD `Site 1 Week Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Week Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 1 Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Day Acc Sessions` , ADD `Site 1 Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Day Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site 1 Hour Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Hour Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Hour Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Hour Acc Sessions` , ADD `Site 1 Hour Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site 1 Hour Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site Today Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Today Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Today Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Today Acc Sessions` , ADD `Site Today Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Today Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site Year To Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Year To Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Year To Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Year To Day Acc Sessions` , ADD `Site Year To Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Year To Day Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site Month To Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Month To Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Month To Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Month To Day Acc Sessions` , ADD `Site Month To Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Month To Day Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site Week To Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Week To Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Week To Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Week To Day Acc Sessions` , ADD `Site Week To Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Week To Day Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site Last Month Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Last Month Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Last Month Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Last Month Acc Sessions` , ADD `Site Last Month Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Last Month Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site Last Week Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Last Week Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Last Week Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Last Week Acc Sessions` , ADD `Site Last Week Month Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Last Week Acc Requests` ;
ALTER TABLE `Site Dimension` ADD `Site Yesterday Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Yesterday Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Yesterday Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Yesterday Acc Sessions` , ADD `Site Yesterday Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Site Yesterday Acc Requests` ;


ALTER TABLE `Site Dimension` ADD `Site Total Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Total Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ;
ALTER TABLE `Site Dimension` ADD `Site 3 Year Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 3 Year Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site 1 Year Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Year Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site 6 Month Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 6 Month Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site 1 Quarter Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Quarter Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site 1 Month Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Month Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site 10 Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 10 Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ;
ALTER TABLE `Site Dimension` ADD `Site 1 Week Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Week Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site 1 Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site 1 Hour Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site 1 Hour Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0'; 
ALTER TABLE `Site Dimension` ADD `Site Today Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Today Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site Year To Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Year To Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site Month To Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Month To Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site Week To Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Week To Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0'; 
ALTER TABLE `Site Dimension` ADD `Site Last Month Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Last Month Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ; 
ALTER TABLE `Site Dimension` ADD `Site Last Week Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Last Week Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Site Dimension` ADD `Site Yesterday Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Site Yesterday Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;


ALTER TABLE `Page Store Dimension` ADD `Page Store Total Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Total Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Total Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Total Acc Sessions` , ADD `Page Store Total Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Total Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 3 Year Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 3 Year Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 3 Year Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 3 Year Acc Sessions` , ADD `Page Store 3 Year Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 3 Year Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Year Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Year Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Year Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Year Acc Sessions` , ADD `Page Store 1 Year Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Year Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 6 Month Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 6 Month Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 6 Month Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 6 Month Acc Sessions` , ADD `Page Store 6 Month Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 6 Month Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Quarter Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Quarter Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Quarter Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Quarter Acc Sessions` , ADD `Page Store 1 Quarter Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Quarter Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Month Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Month Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Month Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Month Acc Sessions` , ADD `Page Store 1 Month Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Month Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 10 Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 10 Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 10 Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 10 Day Acc Sessions` , ADD `Page Store 10 Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 10 Day Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Week Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Week Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Week Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Week Acc Sessions` , ADD `Page Store 1 Week Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Week Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Day Acc Sessions` , ADD `Page Store 1 Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Day Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Hour Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Hour Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Hour Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Hour Acc Sessions` , ADD `Page Store 1 Hour Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store 1 Hour Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Today Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Today Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Today Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Today Acc Sessions` , ADD `Page Store Today Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Today Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Year To Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Year To Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Year To Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Year To Day Acc Sessions` , ADD `Page Store Year To Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Year To Day Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Month To Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Month To Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Month To Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Month To Day Acc Sessions` , ADD `Page Store Month To Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Month To Day Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Week To Day Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Week To Day Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Week To Day Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Week To Day Acc Sessions` , ADD `Page Store Week To Day Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Week To Day Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Last Month Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Last Month Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Last Month Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Last Month Acc Sessions` , ADD `Page Store Last Month Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Last Month Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Last Week Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Last Week Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Last Week Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Last Week Acc Sessions` , ADD `Page Store Last Week Month Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Last Week Acc Requests` ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Yesterday Acc Visitors` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Yesterday Acc Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Yesterday Acc Requests` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Yesterday Acc Sessions` , ADD `Page Store Yesterday Acc Users` MEDIUMINT NOT NULL DEFAULT '0' AFTER `Page Store Yesterday Acc Requests` ;


ALTER TABLE `Page Store Dimension` ADD `Page Store Total Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Total Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 3 Year Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 3 Year Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Year Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Year Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 6 Month Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 6 Month Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Quarter Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Quarter Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Month Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Month Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 10 Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 10 Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0'  ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Week Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Week Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store 1 Hour Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store 1 Hour Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0'; 
ALTER TABLE `Page Store Dimension` ADD `Page Store Today Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Today Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Year To Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Year To Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Month To Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Month To Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Week To Day Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Week To Day Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0'; 
ALTER TABLE `Page Store Dimension` ADD `Page Store Last Month Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Last Month Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ; 
ALTER TABLE `Page Store Dimension` ADD `Page Store Last Week Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Last Week Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;
ALTER TABLE `Page Store Dimension` ADD `Page Store Yesterday Acc Users Requests` MEDIUMINT NOT NULL DEFAULT '0'  ,ADD `Page Store Yesterday Acc Users Sessions` MEDIUMINT NOT NULL DEFAULT '0' ;


ALTER TABLE `User Request Dimension` ADD INDEX ( `Date` ) ;

ALTER TABLE `User Request Dimension` ADD INDEX ( `Page Key` ) ;
ALTER TABLE `User Request Dimension` ADD INDEX ( `Page Key` ) ;


ALTER TABLE `Customer Dimension` ADD `Customer Tax Number Valid` ENUM( 'Yes', 'No', 'Unknown' ) NOT NULL DEFAULT 'Unknown',ADD `Customer Tax Number Details Match` ENUM( 'Yes', 'No', 'Unknown' ) NOT NULL DEFAULT 'Unknown', ADD `Customer Tax Number Validation Date` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `Image Dimension` ADD `Last Modify Date` DATETIME NULL DEFAULT NULL ;
update `Image Dimension` set `Last Modify Date`=NOW();

INSERT INTO `Page Dimension` (`Page Key`, `Page Type`, `Page Section`, `Page Title`, `Page Short Title`, `Page Description`, `Page Keywords`, `Page URL`, `Page Javascript Files`, `Page CSS Files`, `Page Snapshot Image Key`, `Page Snapshot Last Update`, `Page Valid URL`, `Page Working URL`, `Page Published`) VALUES (NULL, 'Internal', 'Reports', 'Intrastat', 'Intrastat', 'Intrastat is the system for collecting information and producing statistics on the trade in goods between countries of the European Union (EU). ', '', 'reports_intrastat.php', '', '', NULL, NULL, 'Yes', 'Yes', 'Yes');
ALTER TABLE `Warehouse Dimension` ADD `Warehouse Picking Aid Type` ENUM( 'Inikoo', 'Static' ) NOT NULL DEFAULT 'Inikoo';

INSERT INTO `User Dimension` (`User Key`, `User Handle`, `User Password`, `User Active`, `User Alias`, `User Type`, `User Staff Type`, `User Site Key`, `User Parent Key`, `User Preferred Locale`, `User Login Count`, `User Last Login`, `User Last Login IP`, `User Failed Login Count`, `User Last Failed Login IP`, `User Last Failed Login`, `User Sessions Count`, `User Requests Count`, `User Last Request`, `User Created`, `User Dashboard Key`, `User Theme Key`, `User Theme Background Key`, `User Verified`, `User Main Image Key`, `User Inactive Note`) VALUES (NULL, 'warehouse', NULL, 'Yes', 'Warehouse', 'Warehouse', NULL, '0', NULL, 'en_GB.UTF-8', '0', NULL, NULL, '0', NULL, NULL, '0', '0', NULL, '2012-02-24 09:40:00', NULL, NULL, NULL, 'No', NULL, '');



ALTER TABLE `Search Full Text Dimension` ADD INDEX ( `Subject Key` ) ;
ALTER TABLE `Inventory Transaction Fact` ORDER BY `Date` DESC;
ALTER TABLE `Part Dimension` ADD `Part Current Stock In Process` FLOAT NOT NULL DEFAULT '0' AFTER `Part Current On Hand Stock` ,ADD `Part Current Stock Picked` FLOAT NOT NULL DEFAULT '0' AFTER `Part Current Stock In Process` ;
ALTER TABLE `Part Location Dimension` ADD `Quantity In Process` FLOAT NOT NULL DEFAULT '0' AFTER `Quantity On Hand` ;

ALTER TABLE `Supplier Product Part Dimension` ADD `Supplier Product Historic Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Supplier Product Key` , ADD INDEX ( `Supplier Product Historic Key` ) ;


ALTER TABLE `Inventory Transaction Fact` CHANGE `Inventory Transaction Type` `Inventory Transaction Type` ENUM( 'Move', 'Order In Process', 'No Dispatched', 'Sale', 'Audit', 'In', 'Adjust', 'Broken', 'Lost', 'Not Found', 'Associate', 'Disassociate', 'Move In', 'Move Out' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Inventory Transaction Fact` ADD `Relations` VARCHAR( 64 ) NOT NULL ;
ALTER TABLE `Inventory Transaction Fact` ADD `Inventory Transaction Stock` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `Inventory Spanshot Fact` ADD `Value Open` FLOAT NOT NULL DEFAULT '0' AFTER `Quantity Low` ,ADD `Value High` FLOAT NOT NULL DEFAULT '0' AFTER `Value Open` ,ADD `Value Low` FLOAT NOT NULL DEFAULT '0' AFTER `Value High` ;


ALTER TABLE `Email Credentials Dimension` ADD `Access Key` VARCHAR( 255 ) NOT NULL ,ADD `Secret Key` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `Email Credentials Dimension` CHANGE `Email Provider` `Email Provider` ENUM( 'Gmail', 'Other', 'Amazon', 'PHPMail' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Other';
ALTER TABLE `Image Bridge` CHANGE `Subject Type` `Subject Type` ENUM( 'Site Favicon', 'Product', 'Family', 'Department', 'Store', 'Website', 'Part', 'Supplier Product', 'Store Logo', 'Store Email Template Header', 'Store Email Postcard', 'Email Image', 'Page', 'Page Header', 'Page Footer', 'Site', 'Page Header Preview', 'Page Footer Preview', 'Page Preview', 'Site Menu', 'Site Search', 'User Profile' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `Inventory Transaction Fact` ADD `Dispatch Country Code` CHAR( 3 ) NOT NULL DEFAULT 'UNK';
ALTER TABLE `Location Dimension` ADD `Location Flag` ENUM( 'Blue', 'Green', 'Orange', 'Pink', 'Purple', 'Red', 'Yellow' ) NOT NULL;

ALTER TABLE `Product Dimension` CHANGE `Product YearToDay Acc Invoiced Gross Amount` `Product Year To Day Acc Invoiced Gross Amount` DECIMAL(12,2) NULL DEFAULT NULL, CHANGE `Product YearToDay Acc Invoiced Discount Amount` `Product Year To Day Acc Invoiced Discount Amount` DECIMAL(12,2) NULL DEFAULT NULL, CHANGE `Product YearToDay Acc Invoiced Amount` `Product Year To Day Acc Invoiced Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', CHANGE `Product YearToDay Acc Profit` `Product Year To Day Acc Profit` DECIMAL(12,2) NULL DEFAULT NULL, CHANGE `Product YearToDay Acc Quantity Ordered` `Product Year To Day Acc Quantity Ordered` FLOAT NULL DEFAULT NULL, CHANGE `Product YearToDay Acc Quantity Invoiced` `Product Year To Day Acc Quantity Invoiced` FLOAT NULL DEFAULT NULL, CHANGE `Product YearToDay Acc Quantity Delivered` `Product Year To Day Acc Quantity Delivered` FLOAT NULL DEFAULT NULL, CHANGE `Product YearToDay Acc Days On Sale` `Product Year To Day Acc Days On Sale` FLOAT NULL DEFAULT NULL, CHANGE `Product YearToDay Acc Days Available` `Product Year To Day Acc Days Available` FLOAT NULL DEFAULT NULL, CHANGE `Product YearToDay Acc Invoices` `Product Year To Day Acc Invoices` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', CHANGE `Product YearToDay Acc Customers` `Product Year To Day Acc Customers` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', CHANGE `Product YearToDay Acc Pending Orders` `Product Year To Day Acc Pending Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `Product Dimension`  ADD `Product Week To Day Acc Invoiced Gross Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Year To Day Acc Pending Orders`,  ADD `Product Week To Day Acc Invoiced Discount Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Invoiced Gross Amount`,  ADD `Product Week To Day Acc Invoiced Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Invoiced Discount Amount`,  ADD `Product Week To Day Acc Profit` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Invoiced Amount`,  ADD `Product Week To Day Acc Quantity Ordered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Profit`,  ADD `Product Week To Day Acc Quantity Invoiced` FLOAT NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Quantity Ordered`,  ADD `Product Week To Day Acc Quantity Delivered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Quantity Invoiced`,  ADD `Product Week To Day Acc Days On Sale` FLOAT NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Quantity Delivered`,  ADD `Product Week To Day Acc Days Available` FLOAT NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Days On Sale`,  ADD `Product Week To Day Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Days Available`,  ADD `Product Week To Day Acc Customers` MEDIUMINT UNSIGNED ZEROFILL NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Invoices`,  ADD `Product Week To Day Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Week To Day Acc Customers`;
ALTER TABLE `Product Dimension`  ADD `Product Month To Day Acc Invoiced Gross Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Year To Day Acc Pending Orders`,  ADD `Product Month To Day Acc Invoiced Discount Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Invoiced Gross Amount`,  ADD `Product Month To Day Acc Invoiced Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Invoiced Discount Amount`,  ADD `Product Month To Day Acc Profit` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Invoiced Amount`,  ADD `Product Month To Day Acc Quantity Ordered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Profit`,  ADD `Product Month To Day Acc Quantity Invoiced` FLOAT NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Quantity Ordered`,  ADD `Product Month To Day Acc Quantity Delivered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Quantity Invoiced`,  ADD `Product Month To Day Acc Days On Sale` FLOAT NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Quantity Delivered`,  ADD `Product Month To Day Acc Days Available` FLOAT NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Days On Sale`,  ADD `Product Month To Day Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Days Available`,  ADD `Product Month To Day Acc Customers` MEDIUMINT UNSIGNED ZEROFILL NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Invoices`,  ADD `Product Month To Day Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Month To Day Acc Customers`;
ALTER TABLE `Product Dimension`  ADD `Product Today Acc Invoiced Gross Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Year To Day Acc Pending Orders`,  ADD `Product Today Acc Invoiced Discount Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Today Acc Invoiced Gross Amount`,  ADD `Product Today Acc Invoiced Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Today Acc Invoiced Discount Amount`,  ADD `Product Today Acc Profit` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Today Acc Invoiced Amount`,  ADD `Product Today Acc Quantity Ordered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Today Acc Profit`,  ADD `Product Today Acc Quantity Invoiced` FLOAT NOT NULL DEFAULT '0' AFTER `Product Today Acc Quantity Ordered`,  ADD `Product Today Acc Quantity Delivered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Today Acc Quantity Invoiced`,  ADD `Product Today Acc Days On Sale` FLOAT NOT NULL DEFAULT '0' AFTER `Product Today Acc Quantity Delivered`,  ADD `Product Today Acc Days Available` FLOAT NOT NULL DEFAULT '0' AFTER `Product Today Acc Days On Sale`,  ADD `Product Today Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Today Acc Days Available`,  ADD `Product Today Acc Customers` MEDIUMINT UNSIGNED ZEROFILL NOT NULL DEFAULT '0' AFTER `Product Today Acc Invoices`,  ADD `Product Today Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Today Acc Customers`;
ALTER TABLE `Product Dimension`  ADD `Product Yesterday Acc Invoiced Gross Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Year To Day Acc Pending Orders`,  ADD `Product Yesterday Acc Invoiced Discount Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Invoiced Gross Amount`,  ADD `Product Yesterday Acc Invoiced Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Invoiced Discount Amount`,  ADD `Product Yesterday Acc Profit` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Invoiced Amount`,  ADD `Product Yesterday Acc Quantity Ordered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Profit`,  ADD `Product Yesterday Acc Quantity Invoiced` FLOAT NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Quantity Ordered`,  ADD `Product Yesterday Acc Quantity Delivered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Quantity Invoiced`,  ADD `Product Yesterday Acc Days On Sale` FLOAT NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Quantity Delivered`,  ADD `Product Yesterday Acc Days Available` FLOAT NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Days On Sale`,  ADD `Product Yesterday Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Days Available`,  ADD `Product Yesterday Acc Customers` MEDIUMINT UNSIGNED ZEROFILL NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Invoices`,  ADD `Product Yesterday Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Yesterday Acc Customers`;
ALTER TABLE `Product Dimension`  ADD `Product Last Week Acc Invoiced Gross Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Year To Day Acc Pending Orders`,  ADD `Product Last Week Acc Invoiced Discount Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Invoiced Gross Amount`,  ADD `Product Last Week Acc Invoiced Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Invoiced Discount Amount`,  ADD `Product Last Week Acc Profit` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Invoiced Amount`,  ADD `Product Last Week Acc Quantity Ordered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Profit`,  ADD `Product Last Week Acc Quantity Invoiced` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Quantity Ordered`,  ADD `Product Last Week Acc Quantity Delivered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Quantity Invoiced`,  ADD `Product Last Week Acc Days On Sale` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Quantity Delivered`,  ADD `Product Last Week Acc Days Available` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Days On Sale`,  ADD `Product Last Week Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Days Available`,  ADD `Product Last Week Acc Customers` MEDIUMINT UNSIGNED ZEROFILL NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Invoices`,  ADD `Product Last Week Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Last Week Acc Customers`;
ALTER TABLE `Product Dimension`  ADD `Product Last Month Acc Invoiced Gross Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Year To Day Acc Pending Orders`,  ADD `Product Last Month Acc Invoiced Discount Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Invoiced Gross Amount`,  ADD `Product Last Month Acc Invoiced Amount` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Invoiced Discount Amount`,  ADD `Product Last Month Acc Profit` DECIMAL NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Invoiced Amount`,  ADD `Product Last Month Acc Quantity Ordered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Profit`,  ADD `Product Last Month Acc Quantity Invoiced` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Quantity Ordered`,  ADD `Product Last Month Acc Quantity Delivered` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Quantity Invoiced`,  ADD `Product Last Month Acc Days On Sale` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Quantity Delivered`,  ADD `Product Last Month Acc Days Available` FLOAT NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Days On Sale`,  ADD `Product Last Month Acc Invoices` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Days Available`,  ADD `Product Last Month Acc Customers` MEDIUMINT UNSIGNED ZEROFILL NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Invoices`,  ADD `Product Last Month Acc Pending Orders` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Last Month Acc Customers`;

ALTER TABLE `Product Dimension` CHANGE `Product Total Invoiced Gross Amount` `Product Total Acc Invoiced Gross Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', CHANGE `Product Total Invoiced Discount Amount` `Product Total Acc Invoiced Discount Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', CHANGE `Product Total Invoiced Amount` `Product Total Acc Invoiced Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00', CHANGE `Product Total Profit` `Product Total Acc Profit` DECIMAL(12,2) NOT NULL DEFAULT '0.00', CHANGE `Product Total Quantity Ordered` `Product Total Acc Quantity Ordered` FLOAT NOT NULL DEFAULT '0', CHANGE `Product Total Quantity Invoiced` `Product Total Acc Quantity Invoiced` FLOAT NOT NULL DEFAULT '0', CHANGE `Product Total Quantity Delivered` `Product Total Acc Quantity Delivered` FLOAT NOT NULL DEFAULT '0', CHANGE `Product Total Days On Sale` `Product Total Acc Days On Sale` FLOAT NOT NULL DEFAULT '0', CHANGE `Product Total Days Available` `Product Total Acc Days Available` FLOAT NOT NULL DEFAULT '0', CHANGE `Product Total Invoices` `Product Total Acc Invoices` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', CHANGE `Product Total Customers` `Product Total Acc Customers` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', CHANGE `Product Total Pending Orders` `Product Total Acc Pending Orders` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0', CHANGE `Product Total Estimated GMROI` `Product Total Acc Estimated GMROI` FLOAT NULL DEFAULT NULL;
CREATE TABLE `Deal Campaign Dimension` (
  `Deal Campaign Key` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Deal Campaign Name` varchar(256) CHARACTER SET utf8 NOT NULL,
  `Store Key` mediumint(8) unsigned NOT NULL,
  `Deal Campaign Valid From` datetime DEFAULT NULL,
  `Deal Campaign Valid To` datetime DEFAULT NULL,
  PRIMARY KEY (`Deal Campaign Key`)
) ENGINE=MyISAM;

ALTER TABLE `Deal Dimension` ADD `Deal Campaign Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Deal Code` ,ADD INDEX ( `Deal Campaign Key` ); 
ALTER TABLE `Deal Metadata Dimension` ADD `Deal Campaign Key` MEDIUMINT UNSIGNED NOT NULL AFTER `Deal Metadata Name` ,ADD INDEX ( `Deal Campaign Key` ) ;
ALTER TABLE `Deal Campaign Dimension` ADD `Deal Campaign Description` TEXT NOT NULL AFTER `Deal Campaign Name` ;


ALTER TABLE `Location Dimension` CHANGE `Location Flag` `Location Flag` ENUM( 'Blue', 'Green', 'Orange', 'Pink', 'Purple', 'Red', 'Yellow' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Blue';
ALTER TABLE `Part Dimension` ADD `Part Tarrif Code Valid` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `Part Tariff Code` ,ADD INDEX ( `Part Tarrif Code Valid` ) ;

ALTER TABLE `Part Dimension` CHANGE `Part Tarrif Code Valid` `Part Tariff Code Valid` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No';
ALTER TABLE `Inventory Transaction Fact`  DROP PRIMARY KEY, ADD INDEX(`Inventory Transaction Key`);
alter table `Inventory Transaction Fact`  partition BY KEY(`Date`) PARTITIONS 30;

ALTER TABLE `List Dimension` CHANGE `List Store Key` `List Parent Key` SMALLINT( 5 ) UNSIGNED NOT NULL ;
ALTER TABLE `List Dimension` DROP INDEX `Customer List Store Key` , ADD UNIQUE `Customer List Store Key` ( `List Parent Key` , `List Name` , `List Scope` ) ;





DROP TABLE IF EXISTS `Email Credentials Dimension`;

CREATE TABLE `Email Credentials Dimension` (
  `Email Credentials Method` enum('Amazon','SMTP','Direct') NOT NULL DEFAULT 'SMTP',
  `Email Credentials Key` mediumint(8) NOT NULL AUTO_INCREMENT,
  `Email Provider` enum('Gmail','Other','Inikoo','PHPMail') NOT NULL DEFAULT 'Other',
  `Email Address Gmail` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Password Gmail` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
  `Incoming Mail Server` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Outgoing Mail Server` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Amazon Access Key` varchar(255) DEFAULT NULL,
  `Amazon Secret Key` varchar(255) DEFAULT NULL,
  `Email Address Amazon Mail` varchar(255) DEFAULT NULL,
  `Email Address Direct Mail` varchar(255) DEFAULT NULL,
  `Email Address Other` varchar(255) DEFAULT NULL,
  `Password Other` varchar(1024) DEFAULT NULL,
  `Login Other` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Email Credentials Key`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

ALTER TABLE `Customers Send Post` ADD PRIMARY KEY ( `Customer Send Post Key` ) ;
ALTER TABLE `Customers Send Post` CHANGE `Customer Send Post Key` `Customer Send Post Key` MEDIUMINT( 11 ) NOT NULL AUTO_INCREMENT ;

RENAME TABLE `Customers Send Post` TO `Customer Send Post` ;
ALTER TABLE `Customer Send Post` CHANGE `Date Send` `Date Send` DATETIME NULL DEFAULT NULL ;

ALTER TABLE `Order Transaction Fact`  DROP PRIMARY KEY, ADD INDEX(`Order Transaction Fact Key`);
ALTER TABLE `Page Store Deleted Dimension` ADD `Page Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Page Store Deleted Key` ;
ALTER TABLE `Store Dimension` ADD `Store Contacts Who Visit Website` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Contacts With Orders` ;
ALTER TABLE `Site Dimension` ADD `Site From` DATETIME NULL DEFAULT NULL ,ADD `Site To` DATETIME NULL DEFAULT NULL ;
