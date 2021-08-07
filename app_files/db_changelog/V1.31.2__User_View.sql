
ALTER TABLE `User Dimension` CHANGE `User Type` `User Type` ENUM('Staff','Supplier','Administrator','Warehouse','Contractor','Agent','Customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL;
ALTER TABLE `User Dimension` ADD `User View` ENUM('Staff','Supplier','Agent','Customer') NULL DEFAULT NULL AFTER `User Type`;
update `User Dimension` set `User View`='Staff';
update `User Dimension` set `User View`='Supplier'  where `User Type`='Supplier';