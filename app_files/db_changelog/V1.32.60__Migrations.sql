ALTER TABLE `Supplier Part Dimension` CHANGE `aiku_id` `aiku_supplier_id` int unsigned NULL COMMENT '';
ALTER TABLE `Supplier Part Historic Dimension` CHANGE `aiku_id` `aiku_supplier_historic_product_id` int unsigned NULL COMMENT '';
ALTER TABLE `Supplier Part Dimension` ADD COLUMN `aiku_workshop_id` int unsigned NULL COMMENT '';
ALTER TABLE `Supplier Part Historic Dimension` ADD COLUMN `aiku_workshop_historic_product_id` int unsigned NULL COMMENT '';