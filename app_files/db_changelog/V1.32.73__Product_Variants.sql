ALTER TABLE `Product Dimension` ADD COLUMN `has_variants` enum('Yes','No') NULL  DEFAULT 'No' COMMENT '';
ALTER TABLE `Product Dimension` ADD COLUMN `is_variant` enum('Yes','No')   DEFAULT 'No' NULL COMMENT '';
ALTER TABLE `Product Dimension` ADD COLUMN `variant_parent_id` mediumint unsigned NULL COMMENT '';