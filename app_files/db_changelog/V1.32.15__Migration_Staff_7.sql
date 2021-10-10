ALTER TABLE `Order Transaction Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Fulfilment Asset Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Fulfilment Transaction Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Fulfilment Delivery Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Fulfilment Rent Transaction Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
