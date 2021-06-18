CREATE TABLE `Customer Fulfilment Dimension` (
  `Customer Fulfilment Customer Key` mediumint NOT NULL,
  `Customer Fulfilment Warehouse Key` mediumint unsigned NOT NULL,
  `Customer Fulfilment Status` enum('ToApprove','Rejected','Approved','InProcess','Storing','StoringEnd','Lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Approved',
  `Customer Fulfilment Parts` mediumint unsigned NOT NULL DEFAULT '0',
  `Customer Fulfilment Stored Parts` mediumint unsigned NOT NULL DEFAULT '0',
  `Customer Fulfilment Locations` mediumint unsigned NOT NULL DEFAULT '0',
  `Customer Fulfilment Metadata` json DEFAULT NULL,
  PRIMARY KEY (`Customer Fulfilment Customer Key`),
  KEY `Customer Fulfilment Customer Status` (`Customer Fulfilment Status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

