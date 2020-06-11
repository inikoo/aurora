ALTER TABLE `Supplier Delivery Dimension` ADD `Supplier Delivery Number History Records` SMALLINT NOT NULL DEFAULT '0' AFTER `Supplier Delivery Invoice Public ID`;
ALTER TABLE `Purchase Order Dimension` ADD `Purchase Order Number History Records` SMALLINT NOT NULL DEFAULT '0' AFTER `Purchase Order Production`;
