DROP TABLE IF EXISTS `Order Spanshot Fact`;
ALTER TABLE `ITF POTF Bridge` ADD `ITF POTF Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`ITF POTF Bridge Key`);
drop table `Language Country Bridge`;
ALTER TABLE  `Part Data` DROP INDEX `Part SKU`, ADD INDEX `Part SKU` (`Part SKU`) USING BTREE;
drop table `Product Category Bridge`;
drop table `Overtime Timesheet Bridge`;
DROP TABLE `Payment Service Provider Payment Method Bridge`;



