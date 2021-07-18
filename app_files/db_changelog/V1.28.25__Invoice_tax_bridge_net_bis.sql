ALTER TABLE `Invoice Tax Bridge` CHANGE `Invoice Tax Net` `Invoice Tax Net` DECIMAL(12,2) NULL DEFAULT NULL;
update `Invoice Tax Bridge` set `Invoice Tax Net`=null;