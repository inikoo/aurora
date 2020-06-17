ALTER TABLE `Purchase Order Transaction Fact` ADD `Purchase Order Manufactures Units` FLOAT NULL DEFAULT NULL AFTER `Purchase Order Submitted Cancelled Units`;
ALTER TABLE `Purchase Order Transaction Fact` ADD `Purchase Order QC Pass Units` FLOAT NULL DEFAULT NULL AFTER `Purchase Order Manufactures Units`;
