ALTER TABLE `Customer Product Bridge` DROP `Customer Product Penultimate Invoice Date`;
delete from `Customer Product Bridge` where `Customer Product Customer Key`=0;
delete from `Customer Product Bridge` where `Customer Product Product ID`=0;
ALTER TABLE `Customer Part Bridge` DROP `Customer Part Penultimate Delivery Note Date`;
DROP TABLE `Customer Part Category Bridge`;