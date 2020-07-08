ALTER TABLE `Location Deleted Dimension` ADD `Location Deleted Warehouse Key` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Location Deleted File As`, ADD INDEX (`Location Deleted Warehouse Key`);
