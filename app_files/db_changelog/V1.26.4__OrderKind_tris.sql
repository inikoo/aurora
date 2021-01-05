ALTER TABLE `Store Dimension` ADD `Store External Invoicer Key` TINYINT UNSIGNED NULL DEFAULT NULL AFTER `Store Key`, ADD INDEX (`Store External Invoicer Key`);
