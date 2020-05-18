ALTER TABLE `Invoice Dimension` ADD `Invoice Number History Records` SMALLINT UNSIGNED NULL DEFAULT '0' AFTER `Invoice Sales Representative Key`;
ALTER TABLE `Supplier Part Dimension` ADD `Supplier Part Number History Records` SMALLINT UNSIGNED NULL DEFAULT '0' AFTER `Supplier Part Properties`;

