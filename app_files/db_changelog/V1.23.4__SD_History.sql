ALTER TABLE `Supplier Delivery History Bridge` DROP `Supplier Delivery History Bridge Key`;
ALTER TABLE `Supplier Delivery History Bridge` DROP INDEX `Supplier Delivery Key`, ADD PRIMARY KEY (`Supplier Delivery Key`, `History Key`) USING BTREE;

ALTER TABLE `Supplier Delivery History Bridge` CHANGE `Deletable` `Deletable` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'No', CHANGE `Strikethrough` `Strikethrough` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'No', CHANGE `Type` `Type` ENUM('Notes','Changes') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL;


ALTER TABLE `Purchase Order History Bridge` DROP `Purchase Order History Bridge Key`;
ALTER TABLE `Purchase Order History Bridge` DROP INDEX `Purchase Order Key`, ADD PRIMARY KEY (`Purchase Order Key`, `History Key`) USING BTREE;

ALTER TABLE `Purchase Order History Bridge` CHANGE `Deletable` `Deletable` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'No', CHANGE `Strikethrough` `Strikethrough` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'No', CHANGE `Type` `Type` ENUM('Notes','Changes') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL;

