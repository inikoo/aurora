ALTER TABLE `Material Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Agent Supplier Purchase Order Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Bounced Email Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Customer Poll Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);

ALTER TABLE `Customer Poll Query Deleted Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Customer Poll Query Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Customer Poll Query Option Deleted Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Customer Poll Query Option Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);

ALTER TABLE `Email Blueprint Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Email Spam Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Email Template Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);


ALTER TABLE `Feedback Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Feedback ITF Bridge` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Feedback ONPTF Bridge` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Feedback OTF Bridge` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);

ALTER TABLE `Order Basket Purge Dimension` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Order Basket Purge Order Fact` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);






