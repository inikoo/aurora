ALTER TABLE `ITF Picking Band Bridge` ADD `ITF Picking Band Cartons` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `ITF Picking Band Amount`, ADD `ITF Picking Band SKOs` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `ITF Picking Band Cartons`;