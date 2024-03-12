ALTER TABLE `Email Tracking Event Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Email Tracking Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
