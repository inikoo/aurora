/* 11:03:48 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` ADD `Shipping Zone Schema Zone Number History Records` SMALLINT  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `Shipping Zone Schema Creation Date`;
/* 11:06:16 localhost sk */ ALTER TABLE `Shipping Zone Dimension` ADD `Shipping Zone Number History Records` SMALLINT  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `Shipping Zone Position`;
/* 11:07:25 localhost sk */ ALTER TABLE `Shipping Zone Dimension` ADD `Shipping Zone Number Customers` SMALLINT(5)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `Shipping Zone Number History Records`;

/* 11:07:32 localhost sk */ ALTER TABLE `Shipping Zone Dimension` ADD `Shipping Zone Number Orders` SMALLINT(5)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `Shipping Zone Number Customers`;
/* 11:15:21 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` ADD `Shipping Zone Schema Number Zones` SMALLINT  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `Shipping Zone Schema Zone Number History Records`;
/* 11:15:47 localhost sk */ ALTER TABLE `Shipping Zone Schema Data` DROP `Shipping Zone Schema Number Zones`;

/* 11:17:16 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` ADD `Shipping Zone Schema Last Used` DATETIME  NULL  DEFAULT NULL  AFTER `Shipping Zone Schema Number Zones`;
/* 11:17:41 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` ADD `Shipping Zone Schema First Used` DATETIME  NULL  DEFAULT NULL  AFTER `Shipping Zone Schema Last Used`;
/* 11:17:49 localhost sk */ ALTER TABLE `Shipping Zone Schema Data` DROP `Shipping Zone Schema Last Used`;

/* 11:19:23 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` MODIFY COLUMN `Shipping Zone Schema Last Used` DATETIME DEFAULT NULL AFTER `Shipping Zone Schema First Used`;
/* 11:21:31 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` ADD `Shipping Zone Schema Created` DATETIME  NULL  DEFAULT NULL  AFTER `Shipping Zone Schema Last Used`;
/* 11:21:35 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` MODIFY COLUMN `Shipping Zone Schema Created` DATETIME DEFAULT NULL AFTER `Shipping Zone Schema Number Zones`;

/* 11:39:51 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` ADD `Shipping Zone Schema Number Customers` SMALLINT(5)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `Shipping Zone Schema Last Used`;
/* 11:39:57 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` ADD `Shipping Zone Schema Number Orders` SMALLINT(5)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `Shipping Zone Schema Number Customers`;
/* 12:13:06 localhost sk */ ALTER TABLE `Shipping Zone Schema Dimension` CHANGE `Shipping Zone Schema Zone Number History Records` `Shipping Zone Schema Number History Records` SMALLINT(5)  UNSIGNED  NOT NULL  DEFAULT '0';
