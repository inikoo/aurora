ALTER TABLE `Customer Portfolio Fact` DROP INDEX `Customer Portfolio Customer Key`, ADD UNIQUE `Customer Portfolio Customer Key` (`Customer Portfolio Customer Key`, `Customer Portfolio Product ID`) USING BTREE;

