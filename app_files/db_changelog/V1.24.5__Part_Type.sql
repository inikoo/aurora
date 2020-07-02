ALTER TABLE `Part Dimension` ADD `Part Type` ENUM('ForSale','RawMaterial') NOT NULL DEFAULT 'ForSale' AFTER `Part SKU`, ADD INDEX (`Part Type`);
