ALTER TABLE `Account Data` ADD `Account Shopify Key` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`Account Shopify Key`);
ALTER TABLE `Store Dimension` ADD `Store Shopify API Key` VARCHAR(255) NULL DEFAULT NULL AFTER `Store Settings`;
