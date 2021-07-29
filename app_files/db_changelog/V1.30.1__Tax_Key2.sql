ALTER TABLE `Order Transaction Fact` ADD `Order Transaction Tax Category Key` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Order Transaction Amount`, ADD INDEX (`Order Transaction Tax Category Key`);
ALTER TABLE `Purchase Order Transaction Fact` CHANGE `Purchase Order Transaction Category Tax Key` `Purchase Order Transaction Tax Category Key` SMALLINT UNSIGNED NULL DEFAULT NULL;
