ALTER TABLE `Order Basket History Dimension` CHANGE `Date` `Order Basket History Date` DATETIME NULL DEFAULT NULL,
    CHANGE `Order Transaction Key` `Order Basket History Order Transaction Key` INT(10) UNSIGNED NULL DEFAULT NULL,
    CHANGE `Site Key` `Order Basket History Website Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL,
    CHANGE `Store Key` `Order Basket History Store Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL,
    CHANGE `Customer Key` `Order Basket History Customer Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL,
    CHANGE `Order Key` `Order Basket History Order Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL,
    CHANGE `Page Key` `Order Basket History Webpage Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL,
    CHANGE `Product ID` `Order Basket History Product ID` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL,
    CHANGE `Quantity Delta` `Order Basket History Quantity Delta` FLOAT NULL DEFAULT '0',
    CHANGE `Quantity` `Order Basket History Quantity` FLOAT NULL DEFAULT '0',
    CHANGE `Net Amount Delta` `Order Basket History Net Amount Delta` DECIMAL(12,2) NULL DEFAULT '0.00',
    CHANGE `Net Amount` `Order Basket History Net Amount` DECIMAL(12,2) NULL DEFAULT '0.00',
    CHANGE `Page Store Section Type` `Order Basket History Source` ENUM('System','Info','Department','Family','Product','Basket','Search') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;



