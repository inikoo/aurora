ALTER TABLE `Account Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Account Dimension` ADD `aiku_token` VARCHAR(255) NULL DEFAULT NULL;
