ALTER TABLE `User Deleted Dimension` ADD COLUMN `aiku_alt_username` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '';
ALTER TABLE `User Deleted Dimension` ADD `aiku_related_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_related_id`);
