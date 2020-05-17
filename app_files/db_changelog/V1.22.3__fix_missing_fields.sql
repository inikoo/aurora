ALTER TABLE `Website User Dimension` ADD `Website User Number History Records` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Website User Static API Hash`;
ALTER TABLE `Order Dimension` ADD `Order Number History Records` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Order Replacements Dispatched Today`;


