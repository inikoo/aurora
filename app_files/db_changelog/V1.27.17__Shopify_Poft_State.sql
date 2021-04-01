ALTER TABLE `Customer Portfolio Fact` ADD `Customer Portfolio Shopify State` ENUM('Linked','Unlinked','NA') NOT NULL DEFAULT 'NA' AFTER `Customer Portfolio Shopify Key`;
