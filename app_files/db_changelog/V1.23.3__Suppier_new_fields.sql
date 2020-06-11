ALTER TABLE `Supplier Dimension` ADD `Supplier Purchase Order Type` ENUM('Production','Local','International') NOT NULL DEFAULT 'Local' AFTER `Supplier Key`;
ALTER TABLE `Agent Dimension` ADD `Agent Purchase Order Type` ENUM('Local','International') NOT NULL DEFAULT 'International' AFTER `Agent Key`;

ALTER TABLE `Supplier Delivery Dimension` ADD `Supplier Delivery Type` ENUM('Production','Local','International') NOT NULL DEFAULT 'Local' AFTER `Supplier Delivery Key`, ADD INDEX (`Supplier Delivery Type`);
ALTER TABLE `Supplier Delivery Dimension` ADD `Supplier Delivery Source` ENUM('Manual','Aurora') NOT NULL DEFAULT 'Manual' AFTER `Supplier Delivery Key`, ADD INDEX (`Supplier Delivery Source`);
