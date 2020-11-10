ALTER TABLE `Order Dimension` ADD `Order Dispatched State` VARCHAR(64) NULL DEFAULT NULL AFTER `Order State`;
ALTER TABLE `Order Dimension` ADD `Order Delivered Date` DATETIME NULL DEFAULT NULL AFTER `Order Dispatched Date`;
ALTER TABLE `Order Dimension` ADD `Order Delivery Data` JSON NULL DEFAULT NULL AFTER `Order Number History Records`;


ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Dispatched State` VARCHAR(64) NULL DEFAULT NULL AFTER `Delivery Note State`;
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Date Delivered` DATETIME NULL DEFAULT NULL AFTER `Delivery Note Date Dispatched`;
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Delivery Data` JSON NULL DEFAULT NULL;

update `Delivery Note Dimension` set `Delivery Note Delivery Data`='{}';
update `Order Dimension` set `Order Delivery Data`='{}';
