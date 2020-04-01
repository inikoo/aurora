ALTER TABLE `Credit Transaction Fact` CHANGE `Credit Transaction Type` `Credit Transaction Type` ENUM('TopUp','Payment','Adjust','Cancel','Return','PayReturn','AddFundsOther','Compensation','TransferIn','MoneyBack','TransferOut','RemoveFundsOther') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Payment';
ALTER TABLE `Credit Transaction Fact` ADD `Credit Transaction Top Up Key` MEDIUMINT NULL DEFAULT NULL AFTER `Credit Transaction Payment Key`, ADD INDEX (`Credit Transaction Top Up Key`);
ALTER TABLE `Credit Transaction Fact` ADD INDEX (`Credit Transaction Payment Key`);


