ALTER TABLE `Order Dimension` ADD `Order Metadata` JSON NULL DEFAULT NULL;
update `Order Dimension` set `Order Metadata`='{}' where `Order Metadata` is NULL;
