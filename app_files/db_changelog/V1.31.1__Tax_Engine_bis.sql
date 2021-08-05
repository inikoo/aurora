ALTER TABLE  `Invoice Tax Bridge` DROP INDEX `Invoice Key_2`, ADD UNIQUE `Invoice Key Tax Cat Key` (`Invoice Tax Invoice Key`, `Invoice Tax Category Key`) USING BTREE;
ALTER TABLE `Invoice Tax Bridge` DROP INDEX `Tax Code`;
