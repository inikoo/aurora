ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Using Shipper API` ENUM('Yes','No') NOT NULL DEFAULT 'No' AFTER `Delivery Note Properties`, ADD INDEX (`Delivery Note Using Shipper API`);
