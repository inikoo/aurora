ALTER TABLE `Invoice Tax Bridge` ADD `Invoice Tax Net` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `Invoice Tax Amount`;
ALTER TABLE `Invoice Tax Bridge` CHANGE `Invoice Tax Net` `Invoice Tax Net` DECIMAL(12,2) NOT NULL;
