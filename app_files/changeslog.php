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
CHANGE `Notes` `Notes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 

*/
?>