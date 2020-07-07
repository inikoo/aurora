
DROP table IF EXISTS `Raw Material Dimension`;
CREATE TABLE `Raw Material Dimension` (
                                          `Raw Material Key` mediumint UNSIGNED NOT NULL,
                                          `Raw Material Type` enum('Part','Consumable','Intermediate') DEFAULT NULL,
                                          `Raw Material Type Key` mediumint UNSIGNED DEFAULT NULL,
                                          `Raw Material State` enum('InProcess','InUse','Orphan','Discontinued') NOT NULL DEFAULT 'InProcess',
                                          `Raw Material Production Supplier Key` mediumint UNSIGNED DEFAULT NULL,
                                          `Raw Material Creation Date` datetime DEFAULT NULL,
                                          `Raw Material Code` varchar(64) DEFAULT NULL,
                                          `Raw Material Description` varchar(255) DEFAULT NULL,
                                          `Raw Material Unit` enum('Unit','Pack','Carton','Liter','Kilogram') DEFAULT NULL,
                                          `Raw Material Unit Label` varchar(64) DEFAULT NULL,
                                          `Raw Material Unit Cost` decimal(18,3)  DEFAULT NULL,
                                          `Raw Material Stock` decimal(18,3)  NOT NULL DEFAULT '0.000',
                                          `Raw Material Stock Status` enum('Unlimited','Surplus','Optimal','Low','Critical','Out_Of_Stock','Error') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Optimal',
                                          `Raw Material Products` mediumint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
ALTER TABLE `Raw Material Dimension`
    ADD PRIMARY KEY (`Raw Material Key`),
    ADD KEY `Raw Material Type` (`Raw Material Type`),
    ADD KEY `Raw Material Type Key` (`Raw Material Type Key`),
    ADD KEY `Raw Material Code` (`Raw Material Code`),
    ADD KEY `Raw Material Stock Status` (`Raw Material Stock Status`),
    ADD KEY `Raw Material Production Supplier Key` (`Raw Material Production Supplier Key`),
    ADD KEY `Raw Material State` (`Raw Material State`);


ALTER TABLE `Raw Material Dimension`
    MODIFY `Raw Material Key` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;
