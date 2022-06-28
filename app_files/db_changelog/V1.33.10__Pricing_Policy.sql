ALTER TABLE `Product Dimension` ADD COLUMN `Product Pricing Policy Key` smallint unsigned NULL COMMENT '';

CREATE TABLE `Product Pricing Policy Dimension` (
                                          `Product Pricing Policy Key` int unsigned NOT NULL AUTO_INCREMENT,
                                          `Product Pricing Policy Type` varchar(255) DEFAULT NULL,
                                          `Product Pricing Policy Code` varchar(16) DEFAULT NULL,
                                          `Product Pricing Policy Label` varchar(255) DEFAULT NULL,
                                          PRIMARY KEY (`Product Pricing Policy Key`),
                                          KEY `Product Pricing Policy Type` (`Product Pricing Policy Type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `Store Dimension` ADD COLUMN `Store Default Product Pricing Policy Key` smallint unsigned NULL COMMENT '';
