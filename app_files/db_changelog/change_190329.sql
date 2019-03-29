/* 13:33:49 localhost dw */ ALTER TABLE `Account Dimension` ADD `Account Label Signature` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `Account Default Warehouse`;

CREATE TABLE `Webpage Analytics Timeseries` (
  `Webpage Analytics Key` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Webpage Analytics Webpage Key` mediumint(10) unsigned NOT NULL,
  `Webpage Analytics Date` date DEFAULT NULL,
  `Webpage Anaylitcs Pageviews` mediumint(10) unsigned DEFAULT '0',
  `Webpage Anaylitcs Google Rank` smallint(5) unsigned DEFAULT '0',
  PRIMARY KEY (`Webpage Analytics Key`),
  KEY `Webpage Analytics Webpage Key` (`Webpage Analytics Webpage Key`),
  KEY `date_webpage` (`Webpage Analytics Date`,`Webpage Analytics Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
