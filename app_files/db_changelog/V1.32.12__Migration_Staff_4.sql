ALTER TABLE `Email Tracking Event Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Email Tracking Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Inventory Transaction Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Image Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Payment Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Order No Product Transaction Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);


