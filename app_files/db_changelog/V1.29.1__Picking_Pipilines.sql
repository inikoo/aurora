DROP TABLE IF EXISTS `Picking Pipeline Dimension`;
CREATE TABLE `Picking Pipeline Dimension` (
                                              `Picking Pipeline Key` smallint unsigned NOT NULL AUTO_INCREMENT,
                                              `Picking Pipeline Warehouse Key` smallint NOT NULL,
                                              `Picking Pipeline Store Key` smallint unsigned NOT NULL,
                                              `Picking Pipeline Name` varchar(255) NOT NULL,
                                              `Picking Pipeline Number Locations` mediumint unsigned NOT NULL DEFAULT '0',
                                              `Picking Pipeline Number Part Locations` mediumint unsigned NOT NULL DEFAULT '0',
                                              `Picking Pipeline Number History Records` mediumint unsigned NOT NULL DEFAULT '0',
                                              `Picking Pipeline Metadata` json DEFAULT NULL,
                                              PRIMARY KEY (`Picking Pipeline Key`),
                                              KEY `Picking Pipeline Store Key` (`Picking Pipeline Store Key`),
                                              KEY `Picking Pipeline Warehouse Key` (`Picking Pipeline Warehouse Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `Location Picking Pipeline Bridge`;

CREATE TABLE `Location Picking Pipeline Bridge` (
                                                    `Location Picking Pipeline Key` mediumint unsigned NOT NULL AUTO_INCREMENT,
                                                    `Location Picking Pipeline Picking Pipeline Key` smallint unsigned NOT NULL,
                                                    `Location Picking Pipeline Location Key` mediumint unsigned NOT NULL,
                                                    `Location Picking Pipeline Creation Date` datetime DEFAULT NULL,
                                                    PRIMARY KEY (`Location Picking Pipeline Key`),
                                                    UNIQUE KEY `Picking Pipeline Key` (`Location Picking Pipeline Picking Pipeline Key`,`Location Picking Pipeline Location Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `Picking Pipeline History Bridge`;
CREATE TABLE `Picking Pipeline History Bridge` (
                                                  `Picking Pipeline Key` mediumint unsigned NOT NULL,
                                                  `History Key` int unsigned NOT NULL,
                                                  `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
                                                  `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
                                                  `Type` enum('Notes','Changes') NOT NULL DEFAULT 'Notes',
                                                  PRIMARY KEY (`Picking Pipeline Key`,`History Key`),
                                                  KEY `Picking Pipeline Key` (`Picking Pipeline Key`),
                                                  KEY `History Key` (`History Key`),
                                                  KEY `Deletable` (`Deletable`),
                                                  KEY `Type` (`Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;