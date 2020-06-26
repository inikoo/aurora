ALTER TABLE `Staff Operative Data` ADD `Staff Operative Transactions` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Staff Operative Today Deliveries`;
ALTER TABLE `Staff Operative Data` ADD `Staff Operative Transactions Waiting Placing Today` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Staff Operative Today Deliveries`;
ALTER TABLE `Staff Operative Data` ADD `Staff Operative Transactions Waiting Placing` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Staff Operative Today Deliveries`;
ALTER TABLE `Staff Operative Data` ADD `Staff Operative Transactions QC Pass` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Staff Operative Today Deliveries`;
ALTER TABLE `Staff Operative Data` ADD `Staff Operative Transactions Waiting QC` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Staff Operative Today Deliveries`;
ALTER TABLE `Staff Operative Data` ADD `Staff Operative Transactions Manufacturing` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Staff Operative Today Deliveries`;
ALTER TABLE `Staff Operative Data` ADD `Staff Operative Transactions Queued` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Staff Operative Today Deliveries`;
ALTER TABLE `Staff Operative Data` ADD `Staff Operative Transactions Planning` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Staff Operative Today Deliveries`;
