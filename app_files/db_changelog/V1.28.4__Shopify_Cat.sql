ALTER TABLE `Category Dimension` ADD `Category Shopify Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Category Properties`, ADD INDEX (`Category Shopify Key`);
