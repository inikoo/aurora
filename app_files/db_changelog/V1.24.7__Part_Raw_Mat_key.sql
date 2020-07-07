ALTER TABLE `Part Dimension` ADD `Part Raw Material Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Part Properties`, ADD INDEX (`Part Raw Material Key`);
