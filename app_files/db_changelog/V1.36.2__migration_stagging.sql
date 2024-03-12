ALTER TABLE `Order Transaction Fact` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
