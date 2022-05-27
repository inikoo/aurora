ALTER TABLE  `Customer Dimension` ADD COLUMN `Customer Send Basket Emails` enum('Yes','No') NOT NULL DEFAULT 'No' COMMENT '';
update `Customer Dimension` set `Customer Send Basket Emails`=`Customer Send Email Marketing`;

ALTER TABLE `Order Dimension` ADD COLUMN `Order Third Basket Email` datetime NULL COMMENT '' AFTER `Order Last Updated Date`;
ALTER TABLE `Order Dimension` ADD COLUMN `Order Second Basket Email` datetime NULL COMMENT '' AFTER `Order Last Updated Date`;
ALTER TABLE `Order Dimension` ADD COLUMN `Order First Basket Email` datetime NULL COMMENT '' AFTER `Order Last Updated Date`;


