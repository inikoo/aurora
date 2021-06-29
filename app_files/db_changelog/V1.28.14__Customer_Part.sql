CREATE TABLE `Customer Part Dimension` (
                                           `Customer Part Key` mediumint unsigned NOT NULL AUTO_INCREMENT,
                                           `Customer Part Customer Key` mediumint unsigned NOT NULL,
                                           `Customer Part Part SKU` mediumint unsigned DEFAULT NULL,
                                           `Customer Part Reference` varchar(255) DEFAULT NULL,
                                           `Customer Part Description` varchar(255) DEFAULT NULL,
                                           `Customer Part Status` enum('Available','NoAvailable','Discontinued') NOT NULL DEFAULT 'Available',
                                           `Customer Part From` datetime NOT NULL,
                                           `Customer Part To` datetime DEFAULT NULL,
                                           `Customer Part Unit Cost` decimal(16,4) unsigned DEFAULT NULL,
                                           `Customer Part Currency Code` varchar(3) DEFAULT NULL,
                                           `Customer Part Packages Per Carton` smallint NOT NULL DEFAULT '1',
                                           `Customer Part Carton CBM` float unsigned DEFAULT NULL COMMENT 'cubic meters',
                                           `Customer Part Carton Barcode` varchar(64) DEFAULT NULL,
                                           `Customer Part Note to Customer` text,
                                           `Customer Part Sticky Note` text,
                                           `Customer Part Properties` json DEFAULT NULL,
                                           `Customer Part Number History Records` smallint unsigned DEFAULT '0',
                                           PRIMARY KEY (`Customer Part Key`),
                                           KEY `Customer Part Customer Key` (`Customer Part Customer Key`),
                                           KEY `Customer Part Description` (`Customer Part Description`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `Part Dimension` ADD COLUMN `Part Customer Key` mediumint unsigned NULL COMMENT 'Only applicable to fulfilment';

CREATE INDEX `Part Dimension_Part_Customer_Key_idx` ON `Part Dimension` (`Part Customer Key`) USING BTREE;

CREATE TABLE `Customer Part Deleted Dimension` (
                                                   `Customer Part Deleted Key` mediumint NOT NULL,
                                                   `Customer Part Deleted Reference` varchar(255) DEFAULT NULL,
                                                   `Customer Part Deleted Date` datetime DEFAULT NULL,
                                                   `Customer Part Deleted Metadata` blob,
                                                   PRIMARY KEY (`Customer Part Deleted Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Customer Part History Bridge` (
                                                `Customer Part Key` mediumint unsigned NOT NULL,
                                                `History Key` int unsigned NOT NULL,
                                                `Deletable` enum('Yes','No') NOT NULL DEFAULT 'No',
                                                `Strikethrough` enum('Yes','No') NOT NULL DEFAULT 'No',
                                                `Type` enum('Notes','Changes','Part') NOT NULL,
                                                PRIMARY KEY (`Customer Part Key`,`History Key`),
                                                KEY `Customer Part Key` (`Customer Part Key`),
                                                KEY `Deletable` (`Deletable`),
                                                KEY `History Key` (`History Key`),
                                                KEY `Type` (`Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;