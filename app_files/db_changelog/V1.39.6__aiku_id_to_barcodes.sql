ALTER TABLE `Barcode Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Barcode Asset Bridge` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
