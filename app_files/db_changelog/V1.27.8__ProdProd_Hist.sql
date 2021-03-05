
CREATE TABLE `Production Part History Bridge` (
                                          `Production Part Key` mediumint unsigned NOT NULL,
                                          `History Key` int unsigned NOT NULL,
                                          `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
                                          `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
                                          `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes',
                                          PRIMARY KEY (`Production Part Key`,`History Key`),
                                          KEY `API Key Key` (`Production Part Key`),
                                          KEY `History Key` (`History Key`),
                                          KEY `Deletable` (`Deletable`),
                                          KEY `Type` (`Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;