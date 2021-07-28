ALTER TABLE `Picking Pipeline Dimension` ADD `Picking Pipeline Replenishable Part Locations` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Picking Pipeline Number Parts`, ADD `Picking Pipeline Part Locations To Replenish` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Picking Pipeline Replenishable Part Locations`;

