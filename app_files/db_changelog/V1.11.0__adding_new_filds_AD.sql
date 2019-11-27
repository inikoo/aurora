ALTER TABLE `Account Data` ADD `Account Parts No Products` MEDIUMINT UNSIGNED NULL DEFAULT '0' AFTER `Account Total Acc Distinct Parts Dispatched`, ADD `Account Parts Forced not for Sale` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Account Parts No Products`;

