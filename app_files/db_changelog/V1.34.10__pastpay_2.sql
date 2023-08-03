ALTER TABLE `Order Dimension` ADD `Order Pastpay Data` JSON NULL DEFAULT NULL;
update  `Order Dimension` set `Order Pastpay Data` ='{}';