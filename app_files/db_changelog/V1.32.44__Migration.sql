ALTER TABLE `Staff Dimension` ADD `aiku_guest_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_guest_id`);
ALTER TABLE `Staff Deleted Dimension` ADD `aiku_guest_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_guest_id`);

