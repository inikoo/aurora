ALTER TABLE `Download Dimension` CHANGE `Download User Key` `Download Creator Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `Download Dimension` ADD `Download Creator Type` ENUM('User','Customer') NOT NULL DEFAULT 'User' AFTER `Download Date`, ADD INDEX (`Download Creator Type`);


