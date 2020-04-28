ALTER TABLE `Order Transaction Out of Stock in Basket Bridge` DROP INDEX `Order Transaction Key`, ADD PRIMARY KEY (`Order Transaction Fact Key`) USING BTREE;

