ALTER TABLE `Customer Portfolio Fact` ADD `Customer Portfolio Shopify Key` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`Customer Portfolio Shopify Key`);
ALTER TABLE `Customer Client Dimension` ADD `Customer Client Shopify Key` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`Customer Client Shopify Key`);
