  CREATE TABLE IF NOT EXISTS `Clocking Machine NFC Tag History Bridge` (
    `Clocking Machine NFC Tag Key` mediumint(8) unsigned NOT NULL,
    `History Key` int(10) unsigned NOT NULL,
    `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
    `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
    `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes'
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ALTER TABLE `Clocking Machine NFC Tag History Bridge`
    ADD PRIMARY KEY (`Clocking Machine NFC Tag Key`,`History Key`),
    ADD KEY `Clocking Machine NFC Tag Key` (`Clocking Machine NFC Tag Key`),
    ADD KEY `History Key` (`History Key`),
    ADD KEY `Deletable` (`Deletable`),
    ADD KEY `Type` (`Type`);
