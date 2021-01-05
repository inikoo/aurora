ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Consignment Key` MEDIUMINT NULL DEFAULT NULL AFTER `Delivery Note Delivery Data`, ADD INDEX (`Delivery Note Consignment Key`);
CREATE TABLE `Consignment Dimension` (
                                         `Consignment Key` mediumint UNSIGNED NOT NULL,
                                         `Consignment External Invoicer Key` tinyint DEFAULT NULL,
                                         `Consignment State` enum('InProcess','Closed','Dispatched','Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'InProcess',
                                         `Consignment Public ID` varchar(255) NOT NULL,
                                         `Consignment Date` datetime NOT NULL,
                                         `Consignment Creation Date` datetime NOT NULL,
                                         `Consignment Scheduled Date` datetime DEFAULT NULL,
                                         `Consignment Closed Date` datetime DEFAULT NULL,
                                         `Consignment Dispatched Date` datetime DEFAULT NULL,
                                         `Consignment Cancelled Date` datetime DEFAULT NULL,
                                         `Consignment Metadata` json DEFAULT NULL,
                                         `Consignment Number Delivery Notes` smallint UNSIGNED NOT NULL DEFAULT '0',
                                         `Consignment Number Boxes` mediumint UNSIGNED NOT NULL DEFAULT '0',
                                         `Consignment Number Tariff Codes` mediumint UNSIGNED NOT NULL DEFAULT '0',
                                         `Consignment Net Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
                                         `Consignment Tax Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
                                         `Consignment Total Amount` decimal(16,2) NOT NULL DEFAULT '0.00',
                                         `Consignment Currency` varchar(3) NOT NULL,
                                         `Consignment Exchange` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `Consignment Dimension`
    ADD PRIMARY KEY (`Consignment Key`),
    ADD KEY `Consignment State` (`Consignment State`),
    ADD KEY `Consignment Date` (`Consignment Date`),
    ADD KEY `Consignment Public ID` (`Consignment Public ID`),
    ADD KEY `Consignment External Invoicer Key` (`Consignment External Invoicer Key`);

ALTER TABLE `Consignment Dimension`
    MODIFY `Consignment Key` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;


CREATE TABLE `Consignment History Bridge` (
                                                `Consignment Key` mediumint UNSIGNED NOT NULL,
                                                `History Key` int UNSIGNED NOT NULL,
                                                `Deletable` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'No',
                                                `Strikethrough` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'No',
                                                `Type` enum('Notes','Changes','Attachments') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `Consignment History Bridge`
    ADD PRIMARY KEY (`Consignment Key`,`History Key`),
    ADD KEY `Delivery Note Key` (`Consignment Key`),
    ADD KEY `Deletable` (`Deletable`),
    ADD KEY `History Key` (`History Key`),
    ADD KEY `Type` (`Type`);

