ALTER TABLE `Customer Fulfilment Dimension` ADD COLUMN `Customer Fulfilment Type` enum('Dropshipping','Asset_Keeping') NOT NULL DEFAULT 'Asset_Keeping' COMMENT '' AFTER `Customer Fulfilment Warehouse Key`;
ALTER TABLE `Customer Fulfilment Dimension` DROP COLUMN `Customer Fulfilment Allow Pallet Storing`;
ALTER TABLE `Customer Fulfilment Dimension` DROP COLUMN `Customer Fulfilment Allow Part Procurement`;
ALTER TABLE `Customer Fulfilment Dimension` CHANGE `Customer Fulfilment Stored Pallets` `Customer Fulfilment Stored Assets` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '';
