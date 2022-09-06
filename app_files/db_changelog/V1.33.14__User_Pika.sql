ALTER TABLE `User Dimension` ADD `pika_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`pika_id`);
ALTER TABLE `User Dimension` ADD pika_data JSON NULL DEFAULT NULL;
update `User Dimension`  set pika_data='{}';
ALTER TABLE `Account Data` ADD COLUMN `pika_token` varchar(128) NULL COMMENT '';