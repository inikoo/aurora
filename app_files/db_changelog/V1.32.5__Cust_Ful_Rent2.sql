ALTER TABLE `Order Dimension` CHANGE `Order Type` `Order Type` enum('Order','Sample','Donation',  'FulfilmentRent', 'Other') NOT NULL DEFAULT 'Other' COMMENT '';
ALTER TABLE `Invoice Dimension` ADD `Invoice Order Type` ENUM('Order','FulfilmentRent') NULL DEFAULT 'Order';
