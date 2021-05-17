ALTER TABLE `Charge Dimension` CHANGE `Charge Scope` `Charge Scope` ENUM('Hanging','Premium','Insurance','Tracking') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `Order Dimension` ADD `Order Shipping Level` ENUM('Normal','Tracking') NULL DEFAULT 'Normal' AFTER `Order Care Level`, ADD INDEX (`Order Shipping Level`);
