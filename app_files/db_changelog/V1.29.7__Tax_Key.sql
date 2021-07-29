ALTER TABLE `Fulfilment Transaction Fact` ADD `Fulfilment Transaction Tax Category Key` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Fulfilment Transaction Exchange Rate`, ADD INDEX (`Fulfilment Transaction Tax Category Key`);
ALTER TABLE `Invoice Dimension` ADD `Invoice Tax Category Key` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Invoice Metadata`, ADD INDEX (`Invoice Tax Category Key`);
ALTER TABLE `Order Dimension` ADD `Order Tax Category Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Order Billing To Country 2 Alpha Code`, ADD INDEX (`Order Tax Category Key`);
ALTER TABLE `Purchase Order Transaction Fact` ADD `Purchase Order Transaction Category Tax Key` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Purchase Order Ordering Units`, ADD INDEX (`Purchase Order Transaction Category Tax Key`);

