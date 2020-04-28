ALTER TABLE `Part Category Data` DROP INDEX `Part Category Key`, ADD PRIMARY KEY (`Part Category Key`) USING BTREE;
ALTER TABLE `Product Category History Bridge` ADD `Product Category History Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Product Category History Bridge Key`);
ALTER TABLE `Purchase Order History Bridge` ADD `Purchase Order History Bridge Key` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Purchase Order History Bridge Key`);
ALTER TABLE `Supplier Category Data` DROP INDEX `Supplier Category Key`, ADD PRIMARY KEY (`Supplier Category Key`) USING BTREE;
ALTER TABLE `Supplier Category History Bridge` ADD `Supplier Category History Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Supplier Category History Bridge Key`);



