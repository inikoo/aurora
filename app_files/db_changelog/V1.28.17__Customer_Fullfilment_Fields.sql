ALTER TABLE `Customer Fulfilment Dimension` ADD COLUMN `Customer Fulfilment Allow Part Procurement` enum('Yes','No') NOT NULL DEFAULT 'No' COMMENT '' AFTER `Customer Fulfilment Status`;

ALTER TABLE `Customer Fulfilment Dimension` ADD COLUMN `Customer Fulfilment Allow Pallet Storing` enum('Yes','No') NOT NULL DEFAULT 'Yes' COMMENT '' AFTER `Customer Fulfilment Status`;

ALTER TABLE `Customer Fulfilment Dimension` ADD COLUMN `Customer Fulfilment Stored Pallets` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `Customer Fulfilment Stored Parts`;