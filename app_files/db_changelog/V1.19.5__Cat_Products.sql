ALTER TABLE `Product Category Dimension`
    DROP `Product Category Departments`,
    DROP `Product Category Families`,
    DROP `Product Category Products`;
ALTER TABLE `Product Category Dimension` ADD `Product Category Active Web New Products` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Category Active Web Offline`;
ALTER TABLE `Product Category Dimension` ADD `Product Category Active Web Families` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Category Unknown Stock Products`;
ALTER TABLE `Product Category Dimension` CHANGE `Product Category Surplus Availability Products` `Product Category Excess Availability Products` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0', CHANGE `Product Category Optimal Availability Products` `Product Category Normal Availability Products` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0', CHANGE `Product Category Critical Availability Products` `Product Category Very Low Availability Products` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0', CHANGE `Product Category Unknown Stock Products` `Product Category Error Availability Products` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Product Category Dimension` DROP `Product Category Unknown Sales State Products`;
ALTER TABLE `Product Category Dimension` CHANGE `Product Category Out Of Stock Products` `Product Category Out of Stock Availability Products` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Product Category Dimension` ADD `Product Category On Demand Availability Products` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Product Category Error Availability Products`;
ALTER TABLE `Product Category Dimension` ADD `Product Category New Discontinued Products` SMALLINT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Discontinued last 2 weeks' AFTER `Product Category Discontinued Products`;
