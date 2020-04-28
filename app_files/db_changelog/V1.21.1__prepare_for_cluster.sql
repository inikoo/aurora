ALTER TABLE `Barcode Asset Bridge` ADD `Barcode Asset Bridge` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Barcode Asset Bridge`);
ALTER TABLE `Category Bridge` ADD `Category Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Category Bridge Key`);
DROP TABLE `Configuration Dimension`;
ALTER TABLE `Credit Transaction History Bridge` ADD `Credit Transaction History Bridge Key` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Credit Transaction History Bridge Key`);
ALTER TABLE `Customer Category History Bridge` ADD `Customer Category History Bridge` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Customer Category History Bridge`);
ALTER TABLE `Deal Target Bridge` ADD `Deal Target Bridge Key` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Deal Target Bridge Key`);
DROP TABLE `Message Dimension`;
ALTER TABLE `Site History Bridge` ADD `Site History Bridge Key` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Site History Bridge Key`);
DROP TABLE  `Page Snapshot Fact`;
ALTER TABLE `User Failed Log Dimension` ADD `User Failed Log Key` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`User Failed Log Key`);
DROP TABLE `Session Dimension`;







