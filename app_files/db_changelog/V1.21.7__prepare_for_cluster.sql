ALTER TABLE `Order Sent Email Bridge` ADD `Order Sent Email Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Order Sent Email Bridge Key`);
ALTER TABLE `ITF POTF Costing Done Bridge` ADD `ITF POTF Costing Done Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`ITF POTF Costing Done Bridge Key`);
delete FROM `List Customer Bridge` WHERE `List Key` = 0;
ALTER TABLE `List Customer Bridge` ADD `List Customer Bridge Key` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`List Customer Bridge Key`);
drop table `List Part Bridge`;
ALTER TABLE `Order Payment Bridge` ADD `Order Payment Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Order Payment Bridge Key`);







