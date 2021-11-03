ALTER TABLE `Payment Account Store Bridge` CHANGE `Payment Account Store Show In Cart` `Payment Account Store Show In Cart` enum('Yes','No') NOT NULL DEFAULT 'No' COMMENT '';

ALTER TABLE `Payment Account Store Bridge` ADD COLUMN `hide` varchar(255) NULL COMMENT '';