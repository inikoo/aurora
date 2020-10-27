ALTER TABLE `Order Dimension` CHANGE `Order State` `Order State` ENUM('InBasket','InProcess','InWarehouse','Packed','PackedDone','Approved','Dispatched','Cancelled') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'InBasket';
ALTER TABLE `Order Dimension` ADD `Order Packed Date` DATETIME NULL DEFAULT NULL AFTER `Order Send to Warehouse Date`;
