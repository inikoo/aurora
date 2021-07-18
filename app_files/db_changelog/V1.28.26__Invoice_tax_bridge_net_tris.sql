ALTER TABLE `Invoice Tax Bridge` ADD `Invoice Tax Metadata` JSON NULL DEFAULT NULL AFTER `Invoice Tax Net`;
update `Invoice Tax Bridge` set `Invoice Tax Metadata`='{}';