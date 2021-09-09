ALTER TABLE `Customer Fulfilment Dimension` ADD `Customer Fulfilment Current Rent Order Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`Customer Fulfilment Current Rent Order Key`);

ALTER TABLE `Fulfilment Asset Dimension` ADD `Fulfilment Asset Last Rent Order Date` DATE NULL DEFAULT NULL AFTER `Fulfilment Asset To`;
