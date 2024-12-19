ALTER TABLE `Order Dimension` ADD `aiku_all_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_all_id`);
ALTER TABLE `Invoice Dimension` ADD `aiku_all_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_all_id`);
ALTER TABLE `Delivery Note Dimension` ADD `aiku_all_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_all_id`);
