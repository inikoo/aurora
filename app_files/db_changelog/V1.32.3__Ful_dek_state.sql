ALTER TABLE `Fulfilment Delivery Dimension` CHANGE `Fulfilment Delivery State` `Fulfilment Delivery State` enum('InProcess','Received','BookedIn','Invoicing','Invoiced','Cancelled') NOT NULL DEFAULT 'InProcess' COMMENT '';

ALTER TABLE `Fulfilment Delivery Dimension` DROP COLUMN `Fulfilment Delivery Checked Date`;

ALTER TABLE `Fulfilment Delivery Dimension` DROP COLUMN `Fulfilment Delivery Placed Date`;

ALTER TABLE `Fulfilment Delivery Dimension` CHANGE `Fulfilment Delivery In Order  Date` `Fulfilment Delivery Booked In Date` datetime NULL COMMENT '';

ALTER TABLE `Fulfilment Delivery Dimension` CHANGE `Fulfilment Delivery Date Type` `Fulfilment Delivery Date Type` enum('Creation','Received','BookedIn','Cancelled') NULL COMMENT '';

ALTER TABLE `Fulfilment Asset Dimension` CHANGE `Fulfilment Asset State` `Fulfilment Asset State` enum('InProcess','Received','BookedIn','BookedOut','Invoiced','Lost') NOT NULL DEFAULT 'InProcess' COMMENT '';

ALTER TABLE `Fulfilment Delivery Dimension` ADD COLUMN `Fulfilment Delivery Invoicing Date` datetime NULL COMMENT '' AFTER `Fulfilment Delivery Received Date`;

ALTER TABLE `Fulfilment Delivery Dimension` ADD COLUMN `Fulfilment Delivery Invoiced Date` datetime NULL COMMENT '' AFTER `Fulfilment Delivery Received Date`;