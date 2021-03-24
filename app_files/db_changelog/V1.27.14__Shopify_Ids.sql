ALTER TABLE `Customer Dimension` ADD `Customer Shopify Key` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`Customer Shopify Key`);
ALTER TABLE `Product Dimension` ADD `Product Shopify Key` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`Product Shopify Key`);
ALTER TABLE `Store Dimension` ADD `Store Shopify Key` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`Store Shopify Key`);
