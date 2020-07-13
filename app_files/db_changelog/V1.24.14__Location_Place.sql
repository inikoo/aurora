ALTER TABLE `Location Dimension`   ADD `Location Place` ENUM ('Local','External') NOT NULL DEFAULT 'Local' AFTER `Location Key`, ADD INDEX (`Location Place`);
ALTER TABLE `Warehouse Area Dimension`   ADD `Warehouse Area Place` ENUM ('Local','External') NOT NULL DEFAULT 'Local' AFTER `Warehouse Area Key`, ADD INDEX (`Warehouse Area Place`);
