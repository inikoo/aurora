ALTER TABLE `Download Attempt Dimension` CHANGE `Download Attempt User Key` `Download Attempt Creator Key` MEDIUMINT UNSIGNED NOT NULL;
ALTER TABLE `Download Attempt Dimension` ADD `Download Attempt Creator Type` ENUM('User','Customer') NULL DEFAULT 'User' AFTER `Download Attempt Download Key`, ADD INDEX (`Download Attempt Creator Type`);


