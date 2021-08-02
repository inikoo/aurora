ALTER TABLE `Product Dimension` ADD `Product Tax Category Data` JSON NULL DEFAULT NULL AFTER `Product Tax Category Key`;
update  `Product Dimension` set `Product Tax Category Data` ='{}';