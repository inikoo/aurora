ALTER TABLE `User Rights Bridge` ADD `User Rights Bridge Key` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`User Rights Bridge Key`);
drop table `User Group Rights Bridge`;
ALTER TABLE `Supplier History Bridge` ADD `Supplier History Bridge Key` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Supplier History Bridge Key`);
ALTER TABLE `Supplier Delivery History Bridge` ADD `Supplier Delivery History Bridge Key` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Supplier Delivery History Bridge Key`);
ALTER TABLE `Part Category History Bridge` ADD `Part Category History Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Part Category History Bridge Key`);




