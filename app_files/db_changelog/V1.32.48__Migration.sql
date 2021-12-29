ALTER TABLE `Image Subject Bridge` ADD `aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_id`);
ALTER TABLE `Image Dimension` CHANGE `aiku_id` `aiku_master_id` int unsigned NULL COMMENT '';
