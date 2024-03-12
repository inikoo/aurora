ALTER TABLE `Staff Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `User Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `User Dimension` ADD `staging_aiku_token` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `Store Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Warehouse Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Warehouse Area Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Supplier Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Product Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Order Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Delivery Note Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Invoice Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Customer Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Customer Client Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Product History Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Customer Deleted Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Attachment Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Page Store Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Prospect Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Supplier Part Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Agent Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Customer Portfolio Fact` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Back in Stock Reminder Fact` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Timesheet Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Timesheet Record Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Category Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Payment Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Image Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Location Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Staff Deleted Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Fulfilment Asset Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Fulfilment Transaction Fact` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Fulfilment Delivery Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Fulfilment Rent Transaction Fact` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Purchase Order Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Purchase Order Deleted Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Supplier Deleted Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Purchase Order Transaction Fact` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Part Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Customer Fulfilment Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Website Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Website User Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);


ALTER TABLE `Email Campaign Type Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Email Campaign Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);


ALTER TABLE `Staff Dimension` ADD `staging_aiku_guest_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_guest_id`);
ALTER TABLE `Staff Deleted Dimension` ADD `staging_aiku_guest_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_guest_id`);

ALTER TABLE `User Deleted Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);

ALTER TABLE `Invoice Deleted Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Shipper Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Location Deleted Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Part Deleted Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);


ALTER TABLE `Attachment Bridge` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Attachment Dimension` CHANGE `staging_aiku_id` `staging_aiku_master_id` int unsigned NULL COMMENT '';
ALTER TABLE `Image Subject Bridge` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Image Dimension` CHANGE `staging_aiku_id` `staging_aiku_master_id` int unsigned NULL COMMENT '';

ALTER TABLE `Shipping Zone Schema Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Shipping Zone Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Charge Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);

ALTER TABLE `Clocking Machine Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);

ALTER TABLE `Supplier Delivery Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);

ALTER TABLE `Part Location Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Customer Favourite Product Fact` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);

ALTER TABLE `Order No Product Transaction Fact` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);


ALTER TABLE `Payment Account Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
ALTER TABLE `Payment Service Provider Dimension` ADD `staging_aiku_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`staging_aiku_id`);
