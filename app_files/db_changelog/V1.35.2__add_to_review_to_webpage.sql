ALTER TABLE `Page Store Dimension` ADD `Page Store To Review`  ENUM('Yes','No') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No',   ADD INDEX (`Page Store To Review`);



