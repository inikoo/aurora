ALTER TABLE `Purchase Order Transaction Fact` CHANGE `Purchase Order Transaction Type` `Purchase Order Transaction Type` ENUM('Production','Parcel','Container','Return') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL;