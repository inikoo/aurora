ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Toxic` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Acute toxicity';
ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Corrosive` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';
ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Explosive` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';
ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Flammable` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';
ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Gas` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Gas under pressure';
ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Environment` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Hazards to the aquatic environment';
ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Health` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Health hazard';
ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Oxidising` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';
ALTER TABLE `Part Dimension` ADD COLUMN `Part Pictogram Danger` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Serious health hazard';

ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Toxic` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Acute toxicity';
ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Corrosive` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';
ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Explosive` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';
ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Flammable` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';
ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Gas` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Gas under pressure';
ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Environment` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Hazards to the aquatic environment';
ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Health` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Health hazard';
ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Oxidising` enum('Yes','No') NULL DEFAULT 'No' COMMENT '';
ALTER TABLE `Product Dimension` ADD COLUMN `Product Pictogram Danger` enum('Yes','No') NULL DEFAULT 'No' COMMENT 'Serious health hazard';
