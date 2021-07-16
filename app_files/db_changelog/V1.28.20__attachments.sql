ALTER TABLE `Attachment Bridge`
    CHANGE `Subject` `Subject` ENUM('Customer','Order','Part','Staff','Customer Communications','Customer History Attachment','Product History Attachment','Part History Attachment','Part MSDS','Product MSDS','Supplier Product MSDS','Product Info Sheet','Purchase Order History Attachment','Purchase Order','Supplier Delivery Note History Attachment','Supplier Delivery Note','Supplier Invoice History Attachment','Supplier Invoice','Order Note History Attachment','Delivery Note History Attachment','Invoice History Attachment','Supplier','Supplier Delivery','Fulfilment Delivery')
        CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL;

ALTER TABLE `Customer Dimension` ADD `Customer Number Attachments` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Customer Number Products`;
ALTER TABLE `Order Dimension` ADD `Order Number Attachments` SMALLINT UNSIGNED NOT NULL DEFAULT '0';


