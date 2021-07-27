ALTER TABLE `Location Dimension` ADD `Location Pipeline` ENUM('Yes','No') NOT NULL DEFAULT 'No' AFTER `Location Fulfilment`, ADD INDEX (`Location Pipeline`);
