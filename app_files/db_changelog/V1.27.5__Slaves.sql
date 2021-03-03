CREATE TABLE `Slave Account Dimension` (
                                   `Slave Account Key` mediumint UNSIGNED NOT NULL,
                                   `Slave Account Type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
                                   `Slave Account Creation Date` datetime DEFAULT NULL,
                                   `Slave Account Metadata` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `Slave Account Dimension`
    ADD PRIMARY KEY (`Slave Account Key`);


ALTER TABLE `Slave Account Dimension`
    MODIFY `Slave Account Key` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;