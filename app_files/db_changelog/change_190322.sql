/* 10:53:31 localhost sk */ ALTER TABLE `Credit Transaction Fact` ADD `Credit Transaction Running Amount` DECIMAL(16,2)  NOT NULL  AFTER `Credit Transaction Payment Key`;

CREATE TABLE `Credit Transaction History Bridge` (
  `Credit Transaction History Credit Transaction Key` int(10) unsigned NOT NULL,
  `Credit Transaction History History Key` int(10) unsigned NOT NULL,
  UNIQUE KEY `Credit Transaction History Credit Transaction Key` (`Credit Transaction History Credit Transaction Key`,`Credit Transaction History History Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* 12:19:51 localhost sk */ ALTER TABLE `Customer Dimension` MODIFY COLUMN `Customer Account Balance` DECIMAL(14,2) NOT NULL DEFAULT '0.00' AFTER `Customer Email State`;
/* 12:29:12 localhost sk */ ALTER TABLE `Customer Dimension` ADD `Customer Number Credit Transactions` SMALLINT  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `Customer Account Balance`;
ALTER TABLE `User Dimension` ADD `User Settings` JSON  ;
UPDATE `User Dimension` SET `User Settings`='{}' ;