ALTER TABLE `Product Dimension` ADD COLUMN `number_variants` smallint unsigned NULL DEFAULT '0' COMMENT '';

ALTER TABLE `Product Dimension` ADD COLUMN `Product Variant Position` smallint unsigned NULL DEFAULT '1' COMMENT '';

ALTER TABLE `Product Dimension` ADD COLUMN `Product Variant Short Name` varchar(255) NULL COMMENT '';

ALTER TABLE `Product Dimension` ADD COLUMN `number_visible_variants` smallint unsigned NULL DEFAULT '0' COMMENT '' ;

ALTER TABLE `Product Dimension` ADD COLUMN `Product Show Variant` enum('Yes','No') NULL DEFAULT 'Yes' COMMENT '';

ALTER TABLE `Product Dimension` CHANGE `has_variants` `has_variants` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';

ALTER TABLE `Product Dimension` CHANGE `is_variant` `is_variant` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';

