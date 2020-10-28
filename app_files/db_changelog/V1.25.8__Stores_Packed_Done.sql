ALTER TABLE `Store Data` ADD `Store Orders Packed Done Number` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Orders Packed Amount`, ADD `Store Orders Packed Done Amount` DECIMAL(12,2) NOT NULL DEFAULT '0' AFTER `Orders Packed Done Number`;

ALTER TABLE `Store DC Data` ADD `Store DC Orders Packed Done Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `Store DC Orders Packed Amount`;
ALTER TABLE `Account Data` ADD `Account Orders Packed Done Number` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Account Orders Packed Amount`, ADD `Account Orders Packed Done Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `Account Orders Packed Done Number`;
