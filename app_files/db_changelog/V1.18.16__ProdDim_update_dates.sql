ALTER TABLE `Product Dimension` ADD `Product Data Updated` DATETIME NULL DEFAULT NULL AFTER `Product Ignore Correlation`, ADD `Product Stock Updated` DATETIME NULL DEFAULT NULL AFTER `Product Data Updated`, ADD `Product Price Updated` DATETIME NULL DEFAULT NULL AFTER `Product Stock Updated`, ADD `Product Images Updated` DATETIME NULL DEFAULT NULL AFTER `Product Price Updated`;

