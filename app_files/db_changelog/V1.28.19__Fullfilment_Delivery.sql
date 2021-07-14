DROP TABLE IF EXISTS `Fulfilment Delivery Dimension`;

CREATE TABLE `Fulfilment Delivery Dimension`
(
    `Fulfilment Delivery Key`                               mediumint unsigned                                                                    NOT NULL AUTO_INCREMENT,
    `Fulfilment Delivery Type`                              enum ('Part','Asset')                                                                          DEFAULT 'Asset',
    `Fulfilment Delivery Customer Key`                      mediumint unsigned                                                                             DEFAULT NULL,
    `Fulfilment Delivery Customer Name`                     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                                  DEFAULT NULL,
    `Fulfilment Delivery Customer Contact Name`             varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                                  DEFAULT NULL,
    `Fulfilment Delivery Customer Email`                    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                                  DEFAULT NULL,
    `Fulfilment Delivery Customer Telephone`                varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                                  DEFAULT NULL,
    `Fulfilment Delivery Store Key`                         smallint unsigned                                                                              DEFAULT NULL,
    `Fulfilment Delivery Warehouse Key`                     smallint unsigned                                                                              DEFAULT NULL,
    `Fulfilment Delivery State`                             enum ('InProcess','Received','Checked','ReadyToPlace','Placed','InOrder','Cancelled') NOT NULL DEFAULT 'InProcess',
    `Fulfilment Delivery Date`                              date                                                                                           DEFAULT NULL,
    `Fulfilment Delivery Date Type`                         enum ('Creation','Received','Placed','InOrder','Cancelled')                                    DEFAULT NULL,
    `Fulfilment Delivery Creation Date`                     datetime                                                                              NOT NULL,
    `Fulfilment Delivery Estimated Receiving Date`          datetime                                                                                       DEFAULT NULL,
    `Fulfilment Delivery Received Date`                     datetime                                                                                       DEFAULT NULL,
    `Fulfilment Delivery Checked Date`                      datetime                                                                                       DEFAULT NULL,
    `Fulfilment Delivery Placed Date`                       datetime                                                                                       DEFAULT NULL,
    `Fulfilment Delivery Cancelled Date`                    datetime                                                                                       DEFAULT NULL,
    `Fulfilment Delivery Last Updated Date`                 datetime                                                                                       DEFAULT NULL COMMENT 'Latest Date when Adding/Modify Fulfilment Delivery Transaction or Data',
    `Fulfilment Delivery Public ID`                         varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                                  DEFAULT NULL,
    `Fulfilment Delivery File As`                           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                                  DEFAULT NULL,
    `Fulfilment Delivery Number Items`                      smallint unsigned                                                                     NOT NULL DEFAULT '0',
    `Fulfilment Delivery Number Checked Items`              smallint unsigned                                                                     NOT NULL DEFAULT '0',
    `Fulfilment Delivery Number Over Delivered Items`       smallint unsigned                                                                     NOT NULL DEFAULT '0',
    `Fulfilment Delivery Number Under Delivered Items`      smallint unsigned                                                                     NOT NULL DEFAULT '0',
    `Fulfilment Delivery Number Received and Checked Items` mediumint unsigned                                                                    NOT NULL DEFAULT '0',
    `Fulfilment Delivery Number Placed Items`               smallint unsigned                                                                     NOT NULL DEFAULT '0',
    `Fulfilment Delivery Placed Items`                      enum ('Yes','No')                                                                     NOT NULL DEFAULT 'No',
    `Fulfilment Delivery Sticky Note`                       text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
    `Fulfilment Delivery Metadata`                          json                                                                                           DEFAULT NULL,
    `Fulfilment Delivery Number Attachments`                smallint unsigned                                                                     NOT NULL DEFAULT '0',
    `Fulfilment Delivery Number History Records`            smallint                                                                              NOT NULL DEFAULT '0',

    PRIMARY KEY (`Fulfilment Delivery Key`),
    KEY `Fulfilment Delivery Date` (`Fulfilment Delivery Date`),
    KEY `Fulfilment Delivery Type` (`Fulfilment Delivery Type`),
    KEY `Fulfilment Delivery State` (`Fulfilment Delivery State`),
    KEY `Fulfilment Delivery Store Key` (`Fulfilment Delivery Store Key`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = UTF8MB4;
DROP TABLE IF EXISTS `Fulfilment Delivery History Bridge`;

CREATE TABLE `Fulfilment Delivery History Bridge`
(
    `Fulfilment Delivery Key` mediumint unsigned       NOT NULL,
    `History Key`             int unsigned             NOT NULL,
    `Deletable`               enum ('Yes','No')        NOT NULL DEFAULT 'No',
    `Strikethrough`           enum ('Yes','No')        NOT NULL DEFAULT 'No',
    `Type`                    enum ('Notes','Changes') NOT NULL DEFAULT 'Notes',
    PRIMARY KEY (`Fulfilment Delivery Key`, `History Key`),
    KEY `Account Key` (`Fulfilment Delivery Key`),
    KEY `History Key` (`History Key`),
    KEY `Deletable` (`Deletable`),
    KEY `Type` (`Type`)
) ENGINE = InnoDB
  DEFAULT CHARSET = UTF8MB4;

DROP TABLE IF EXISTS `Fulfilment Transaction Fact`;
CREATE TABLE `Fulfilment Transaction Fact`
(
    `Fulfilment Transaction Key`                              mediumint unsigned                                                                                                      NOT NULL AUTO_INCREMENT,
    `Fulfilment Transaction Customer Key`                     mediumint unsigned                                                                                                               DEFAULT NULL,
    `Fulfilment Transaction Store Key`                        mediumint unsigned                                                                                                               DEFAULT NULL,

    `Fulfilment Transaction Delivery Key`                     mediumint unsigned                                                                                                               DEFAULT NULL,
    `Fulfilment Transaction Order Key`                        mediumint unsigned                                                                                                               DEFAULT NULL,
    `Fulfilment Transaction State`                            enum ('Cancelled','InProcess','Received','Checked','Placed','InOrder') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'InProcess',

    `Fulfilment Transaction Type`                             enum ('Part','Asset','Service','Location') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                                      DEFAULT NULL,
    `Fulfilment Transaction Last Updated Date`                datetime                                                                                                                         DEFAULT NULL COMMENT 'Latest Date when Adding/Modify Fulfilment Delivery Transaction or Data',

    `Fulfilment Transaction Type Key`                         int unsigned                                                                                                                     DEFAULT NULL,
    `Fulfilment Transaction Delivery Units`                   float unsigned                                                                                                                   DEFAULT NULL,
    `Fulfilment Transaction Delivery Checked Units`           float unsigned                                                                                                                   DEFAULT NULL,
    `Fulfilment Transaction Delivery Placed Units`            float unsigned                                                                                                                   DEFAULT NULL,
    `Fulfilment Transaction Order Units`                      float unsigned                                                                                                                   DEFAULT NULL,
    `Fulfilment Transaction Order Net Unit Gross Amount`      decimal(16, 4)                                                                                                                   DEFAULT NULL,
    `Fulfilment Transaction Order Net Unit Discounted Amount` decimal(16, 4)                                                                                                                   DEFAULT NULL,
    `Fulfilment Transaction Order Net Unit Amount`            decimal(16, 4)                                                                                                                   DEFAULT NULL,
    `Fulfilment Transaction Order Net Amount`                 decimal(16, 4)                                                                                                                   DEFAULT NULL,
    `Fulfilment Transaction Exchange Rate`                    float                                                                                                                            DEFAULT NULL,
    `Fulfilment Transaction Tax Code`                         varchar(16)                                                                                                                      DEFAULT NULL,
    `Fulfilment Transaction Metadata`                         json                                                                                                                             DEFAULT NULL,

    PRIMARY KEY (`Fulfilment Transaction Key`),
    KEY `Fulfilment Transaction Customer Key` (`Fulfilment Transaction Customer Key`),
    KEY `Fulfilment Transaction Store Key` (`Fulfilment Transaction Store Key`),
    KEY `Fulfilment Transaction Delivery Key` (`Fulfilment Transaction Delivery Key`),
    KEY `Fulfilment Transaction Order Key` (`Fulfilment Transaction Order Key`)


) ENGINE = InnoDB
  DEFAULT CHARSET = UTF8MB4;

ALTER TABLE `Attachment Bridge`
    CHANGE `Subject` `Subject` enum
        ('Part','Staff','Customer Communications','Customer History Attachment','Product History Attachment','Part History Attachment','Part MSDS','Product MSDS','Supplier Product MSDS','Product Info Sheet','Purchase Order History Attachment','Purchase Order','Supplier Delivery Note History Attachment','Supplier Delivery Note','Supplier Invoice History Attachment','Supplier Invoice','Order Note History Attachment','Delivery Note History Attachment','Invoice History Attachment','Supplier','Supplier Delivery','Fulfilment Delivery') NOT NULL COMMENT '';


DROP TABLE IF EXISTS `Fulfilment Asset Dimension`;

CREATE TABLE `Fulfilment Asset Dimension`
(
    `Fulfilment Asset Key`                     int unsigned                                                                                   NOT NULL AUTO_INCREMENT,
    `Fulfilment Asset State`                   enum ('InProcess','Stored','Returned','Lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'InProcess',
    `Fulfilment Asset Type`                    enum ('Pallet','Box') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                         NOT NULL DEFAULT 'Pallet',
    `Fulfilment Asset Warehouse Key`           mediumint unsigned                                                                             NOT NULL,
    `Fulfilment Asset Customer Key`            mediumint unsigned                                                                             NOT NULL,
    `Fulfilment Asset Fulfilment Delivery Key` mediumint unsigned                                                                             NOT NULL,
    `Fulfilment Asset Fulfilment Order Key`    mediumint unsigned                                                                                      DEFAULT NULL,
    `Fulfilment Asset Location Key`            mediumint unsigned                                                                                      DEFAULT NULL,
    `Fulfilment Asset Reference`               varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                                            DEFAULT NULL,
    `Fulfilment Asset Note`                    text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
    `Fulfilment Asset From`                    datetime                                                                                                DEFAULT NULL,
    `Fulfilment Asset To`                      datetime                                                                                                DEFAULT NULL,
    `Fulfilment Asset Metadata`                json                                                                                                    DEFAULT NULL,
    `Fulfilment Asset Number History Records`  mediumint unsigned                                                                             NOT NULL DEFAULT '0',
    PRIMARY KEY (`Fulfilment Asset Key`),
    KEY `Fulfilment Asset Customer Key` (`Fulfilment Asset Customer Key`),
    KEY `Fulfilment Asset Reference` (`Fulfilment Asset Reference`),
    KEY `Fulfilment Asset Status` (`Fulfilment Asset State`),
    KEY `Fulfilment Asset Fulfilment Delivery Key` (`Fulfilment Asset Fulfilment Delivery Key`),
    KEY `Fulfilment Asset Fulfilment Order Key` (`Fulfilment Asset Fulfilment Order Key`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `Fulfilment Asset History Bridge`;

CREATE TABLE `Fulfilment Asset History Bridge`
(
    `Fulfilment Asset Key` mediumint unsigned       NOT NULL,
    `History Key`          int unsigned             NOT NULL,
    `Deletable`            enum ('Yes','No')        NOT NULL DEFAULT 'No',
    `Strikethrough`        enum ('Yes','No')        NOT NULL DEFAULT 'No',
    `Type`                 enum ('Notes','Changes') NOT NULL DEFAULT 'Notes',
    PRIMARY KEY (`Fulfilment Asset Key`, `History Key`),
    KEY `Account Key` (`Fulfilment Asset Key`),
    KEY `History Key` (`History Key`),
    KEY `Deletable` (`Deletable`),
    KEY `Type` (`Type`)
) ENGINE = InnoDB
  DEFAULT CHARSET = UTF8MB4;