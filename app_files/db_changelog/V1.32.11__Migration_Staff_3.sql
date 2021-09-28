ALTER TABLE `Attachment Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Page Store Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Prospect Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Supplier Part Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Agent Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Customer Portfolio Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Back in Stock Reminder Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Timesheet Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Timesheet Record Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Category Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);



