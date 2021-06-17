ALTER TABLE `Location Dimension` ADD `Location Fulfilment` ENUM('Yes','No') NOT NULL DEFAULT 'No' AFTER `Location Sticky Note`, ADD INDEX (`Location Fulfilment`);
ALTER TABLE `Part Dimension` CHANGE `Part Type` `Part Type` ENUM('ForSale','RawMaterial','Fulfilment') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ForSale';
ALTER TABLE `Purchase Order Dimension` CHANGE `Purchase Order Type` `Purchase Order Type` ENUM('Production','Parcel','Container','Fulfulment') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'Parcel';
ALTER TABLE `Purchase Order Dimension` CHANGE `Purchase Order Parent` `Purchase Order Parent` ENUM('Supplier','Agent','Customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Supplier';
ALTER TABLE `Purchase Order Transaction Fact` CHANGE `Purchase Order Transaction Type` `Purchase Order Transaction Type` ENUM('Production','Parcel','Container','Return','Fulfilment') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL;
ALTER TABLE `Customer Dimension` ADD `Customer Fulfilment` ENUM('Yes','No') NOT NULL DEFAULT 'No' AFTER `Customer Level Type`, ADD INDEX (`Customer Fulfilment`);
ALTER TABLE `Store Dimension` CHANGE `Store Type` `Store Type` ENUM('B2B','B2C','Dropshipping','External','Fulfilment') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `Website Dimension` CHANGE `Website Type` `Website Type` ENUM('Ecom','EcomB2B','EcomDS','Fulfilment') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
