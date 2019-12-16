ALTER TABLE `Invoice Dimension` DROP INDEX `Metadata`;
update `Invoice Dimension` set `Invoice Metadata`='{}';
ALTER TABLE `Invoice Dimension` CHANGE `Invoice Metadata` `Invoice Metadata` JSON NULL DEFAULT NULL;


