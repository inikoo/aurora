ALTER TABLE `Order Dimension` ADD `Order Kind` ENUM('Internal','External') NOT NULL DEFAULT 'Internal' AFTER `Order Key`, ADD INDEX (`Order Kind`);
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Kind` ENUM('Internal','External') NOT NULL DEFAULT 'Internal' AFTER `Delivery Note Key`, ADD INDEX (`Delivery Note Kind`);
ALTER TABLE `Invoice Dimension` ADD `Invoice Kind` ENUM('Internal','External') NOT NULL DEFAULT 'Internal' AFTER `Invoice Key`, ADD INDEX (`Invoice Kind`);
ALTER TABLE `Store Dimension` ADD `Store Kind` ENUM('Internal','External') NOT NULL DEFAULT 'Internal' AFTER `Store Key`, ADD INDEX (`Store Kind`);
ALTER TABLE `Order Transaction Fact` ADD `Order Transaction Fact Kind` ENUM('Internal','External') NOT NULL DEFAULT 'Internal' AFTER `Order Transaction Fact Key`, ADD INDEX (`Order Transaction Fact Kind`);
