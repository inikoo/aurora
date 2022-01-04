ALTER TABLE `Clocking Machine Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Timesheet Record Dimension` ADD `aiku_time_tracking_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_time_tracking_id`);
