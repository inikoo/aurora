ALTER TABLE `Purchase Order Dimension` ADD `Purchase Order Date` DATE NULL DEFAULT NULL AFTER `Purchase Order Agent Data`, ADD `Purchase Order Date Type` ENUM('Created','Submitted','ETA','Received','Cancelled') NULL DEFAULT NULL AFTER `Purchase Order Date`, ADD INDEX (`Purchase Order Date`);
ALTER TABLE `Supplier Delivery Dimension` ADD `Supplier Delivery Date Type` ENUM('Creation','ETA','Received','Cancelled') NULL DEFAULT NULL  AFTER `Supplier Delivery Date`;
ALTER TABLE `Supplier Delivery Dimension` ADD INDEX (`Supplier Delivery Date`);


