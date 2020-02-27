ALTER TABLE `Email Campaign Dimension` ADD `Email Campaign Spam Soft Bounces` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Email Campaign Spams`;
ALTER TABLE `Email Campaign Type Dimension` ADD `Email Campaign Type Spam Soft Bounces` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Email Campaign Type Spams`;
ALTER TABLE `Email Tracking Dimension` CHANGE `Email Tracking Delivery Status Code` `Email Tracking Delivery Status Code` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

