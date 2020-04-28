drop table `History Currency Exchange Dimension`;
ALTER TABLE `HR History Bridge` ADD `HR History Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`HR History Bridge Key`);
drop table `Invoice Category History Bridge`;
ALTER TABLE `Timeseries Record Drill Down` ADD `Timeseries Record Drill Down Key` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Timeseries Record Drill Down Key`);
ALTER TABLE `Supplier Data` DROP INDEX `Supplier Key`, ADD INDEX `Supplier Key` (`Supplier Key`) USING BTREE;
ALTER TABLE `User Group User Bridge` ADD `User Group User Bridge Key` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`User Group User Bridge Key`);
ALTER TABLE `Voucher Order Bridge` ADD `Voucher Order Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Voucher Order Bridge Key`);




