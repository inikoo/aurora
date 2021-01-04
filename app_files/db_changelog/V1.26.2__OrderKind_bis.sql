ALTER TABLE `Order Dimension` ADD `Order External Invoicer Key` TINYINT UNSIGNED NULL DEFAULT NULL AFTER `Order Key`, ADD INDEX (`Order External Invoicer Key`);
ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note External Invoicer Key` TINYINT UNSIGNED NULL DEFAULT NULL AFTER `Delivery Note Key`, ADD INDEX (`Delivery Note External Invoicer Key`);
ALTER TABLE `Invoice Dimension` ADD `Invoice External Invoicer Key` TINYINT UNSIGNED NULL DEFAULT NULL AFTER `Invoice Key`, ADD INDEX (`Invoice External Invoicer Key`);
ALTER TABLE `Order Transaction Fact` ADD `Order Transaction External Invoicer Key` TINYINT UNSIGNED NULL DEFAULT NULL AFTER `Order Transaction Fact Key`, ADD INDEX (`Order Transaction External Invoicer Key`);

CREATE TABLE `External Invoicer Dimension` (
                                               `External Invoicer Key` tinyint UNSIGNED NOT NULL,
                                               `External Invoicer Account Code` varchar(64) NOT NULL,
                                               `External Invoicer Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
                                               `External Invoicer Creation Date` datetime NOT NULL,
                                               `External Invoicer Metadata` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
ALTER TABLE `External Invoicer Dimension`
    ADD PRIMARY KEY (`External Invoicer Key`),
    ADD KEY `External Invoicer Account Code` (`External Invoicer Account Code`);

ALTER TABLE `External Invoicer Dimension`
    MODIFY `External Invoicer Key` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `External Invoicing Customer Dimension` (
                                                         `External Invoicing Customer Key` tinyint UNSIGNED NOT NULL,
                                                         `External Invoicing Customer Account Code` varchar(64) NOT NULL,
                                                         `External Invoicing Customer External Invoicer Key` tinyint UNSIGNED NOT NULL,
                                                         `External Invoicing Customer Name` varchar(255) NOT NULL,
                                                         `External Invoicing Customer Creation Date` datetime NOT NULL,
                                                         `External Invoicing Customer Metadata` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `External Invoicing Customer Dimension`
    ADD PRIMARY KEY (`External Invoicing Customer Key`),
    ADD UNIQUE KEY `External Invoicing Customer Account Code` (`External Invoicing Customer Account Code`,`External Invoicing Customer External Invoicer Key`);


ALTER TABLE `External Invoicing Customer Dimension`
    MODIFY `External Invoicing Customer Key` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;
