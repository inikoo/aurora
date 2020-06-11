ALTER TABLE `Purchase Order Dimension` ADD `Purchase Order Type` ENUM('Production','Local','International') NOT NULL DEFAULT 'Local' AFTER `Purchase Order Key`, ADD INDEX (`Purchase Order Type`);
ALTER TABLE `Purchase Order Dimension` ADD `Purchase Order Source` ENUM('Manual','Aurora') NOT NULL DEFAULT 'Manual' AFTER `Purchase Order Key`, ADD INDEX (`Purchase Order Source`);

