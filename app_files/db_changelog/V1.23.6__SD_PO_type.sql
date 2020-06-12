ALTER TABLE `Supplier Dimension` CHANGE `Supplier Purchase Order Type` `Supplier Purchase Order Type` ENUM('Production','Parcel','Container') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci  NULL DEFAULT 'Parcel';
ALTER TABLE `Agent Dimension` CHANGE `Agent Purchase Order Type` `Agent Purchase Order Type` ENUM('Parcel','Container') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci  NULL DEFAULT 'Parcel';
ALTER TABLE `Purchase Order Dimension` CHANGE `Purchase Order Type` `Purchase Order Type` ENUM('Production','Parcel','Container') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci  NULL DEFAULT 'Parcel';
ALTER TABLE `Supplier Delivery Dimension` CHANGE `Supplier Delivery Type` `Supplier Delivery Type` ENUM('Production','Parcel','Container') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci  NULL DEFAULT 'Parcel';


