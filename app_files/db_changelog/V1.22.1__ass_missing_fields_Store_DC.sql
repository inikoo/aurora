ALTER TABLE `Store DC Data` ADD `Store DC Orders Cancelled Amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `Store DC Orders In Dispatch Area Amount`, ADD `Store DC Total Acc Payments Amount` DECIMAL(18,2) NOT NULL DEFAULT '0.00' AFTER `Store DC Orders Cancelled Amount`;
ALTER TABLE `Store DC Data` ADD `Store DC Orders Dispatched Amount` DECIMAL(18,2) NOT NULL DEFAULT '0.00' AFTER `Store DC Orders Packed Amount`;

