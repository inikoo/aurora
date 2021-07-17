
ALTER TABLE `Invoice Tax Bridge` DROP `Tax Base`;

ALTER TABLE `Invoice Tax Bridge`
    CHANGE `Invoice Key` `Invoice Tax Invoice Key` MEDIUMINT UNSIGNED                                                 NOT NULL,
    CHANGE `Tax Code` `Invoice Tax Code`       VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci       NOT NULL,
    CHANGE `Tax Amount` `Invoice Tax Amount`   DECIMAL(12, 2)                                                     NOT NULL;
ALTER TABLE `Invoice Tax Bridge` ADD `Invoice Tax Category Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Invoice Tax Bridge Key`, ADD INDEX (`Invoice Tax Category Key`);


