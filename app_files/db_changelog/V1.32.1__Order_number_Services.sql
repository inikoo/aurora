ALTER TABLE `Order Dimension` ADD `Order Number Services` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Order Type`;
ALTER TABLE `Order Transaction Fact` ADD `Order Transaction Product Type` ENUM('Product','Service') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Product' AFTER `Order Transaction Fact Kind`, ADD INDEX (`Order Transaction Product Type`);
