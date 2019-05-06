ALTER TABLE `Page Store Dimension` DROP `Page Header Key`,  DROP `Page Header Type`,  DROP `Page Footer Key`,   DROP `Page Footer Type`;

update `Order Dimension` set `Order Website Key`=`Order Site Key` where `Order Website Key` is null;

ALTER TABLE `User Log Dimension` CHANGE `Site Key` `Site Key` SMALLINT(5) UNSIGNED NULL DEFAULT '0', CHANGE `Remember Cookie` `Remember Cookie` ENUM('Yes','No','Unknown') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Unknown';

// back up `User Log Dimension`
// run legacy/cron/fix_user_log_dimension.php


ALTER TABLE `User Log Dimension` DROP `Site Key`, DROP `Remember Cookie`;
// back up `User Dimension`
delete from `User Dimension` where `User Type`='Customer';

// back up `Shipping Dimension`
DROP TABLE `Shipping Dimension`;
// back up `Site Dimension`
DROP TABLE `Site Dimension`;



update `Order Transaction Fact` set `No Shipped Due Out of Stock`=`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`;


ALTER TABLE `Order Transaction Fact` ADD `OTF Category Family Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Product Code`, ADD `OTF Category Department Key` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `OTF Category Family Key`,
 ADD INDEX (`OTF Category Family Key`),
 ADD INDEX (`OTF Category Department Key`);


php update_products_department_category_key.php
php migrate_otf_family_and_departmetns_to_cat.php
php fix_missing_categories.php



ALTER TABLE `Order Transaction Fact`
DROP `Invoice Transaction Gross Amount`,
  DROP `Invoice Transaction Total Discount Amount`,
  DROP `Invoice Transaction Item Tax Amount`,
  DROP `Invoice Transaction Shipping Amount`,
  DROP `Invoice Transaction Shipping Tax Amount`,
  DROP `Invoice Transaction Charges Amount`,
  DROP `Invoice Transaction Charges Tax Amount`,
  DROP `Invoice Transaction Insurance Amount`,
  DROP `Invoice Transaction Insurance Tax Amount`,
  DROP `Invoice Transaction Outstanding Net Balance`,
  DROP `Invoice Transaction Outstanding Tax Balance`,
  DROP `Invoice Transaction Net Refund Items`,
  DROP `Invoice Transaction Net Refund Shipping`,
  DROP `Invoice Transaction Net Refund Charges`,
  DROP `Invoice Transaction Net Refund Insurance`,
  DROP `Invoice Transaction Tax Refund Items`,
  DROP `Invoice Transaction Tax Refund Shipping`,
  DROP `Invoice Transaction Tax Refund Charges`,
  DROP `Invoice Transaction Tax Refund Insurance`,
  DROP `Invoice Transaction Net Refund Amount`,
  DROP `Invoice Transaction Tax Refund Amount`,
  DROP `Invoice Transaction Outstanding Refund Net Balance`,
  DROP `Invoice Transaction Outstanding Refund Tax Balance`,
  DROP `Invoice Transaction Net Adjust`,
  DROP `Invoice Transaction Tax Adjust`,
  DROP `Shipped Quantity`,
  Drop `Current Autorized to Sell Quantity`,
  DROP `Estimated Dispatched Weight`,
DROP `Weight`,
  Drop `Current Manufacturing Quantity`,
  Drop `Current On Shelf Quantity`,
  Drop `Current On Box Quantity`,
  Drop `Company Departmet Key`,
  Drop `Billing To Key`,
  Drop `Ship to Key`,
  Drop `Customer Return Quantity`,
/  Drop `Billing To 2 Alpha Country Code`,
  Drop `Manufacturing Facility Key`,

  Drop `Release to Manufacturing Date`,
  Drop `Finished Inventory Placement Date`,
  Drop `Requested Shipping Date`,
  Drop `Scheduled Shipping Date`,
  Drop `Actual Shipping Date`,
  Drop `Arrival Date`,
 Drop `Backlog Date`,
 Drop `Paid Date`,
 Drop `Start Picking Date`,
 Drop `Picking Finished Date`,
 Drop `Start Packing Date`,
 Drop `Packing Finished Date`,
 Drop `Picking Factor`,
 Drop `Packing Factor`,
 Drop `Paid Factor`,
 Drop `Picked Quantity`,
 Drop `Customer Message`,
 Drop `Order Source Type`,
 Drop `Source Type`,
 Drop `Refund Key`,
  Drop `Multipart Partically No Picked`,
  Drop `Refund Method`,

 DROP `Order Public ID`,DROP `Estimated Volume`,DROP `Volume`,DROP `Sales Rep Key`,DROP `Warehouse Key`,DROP `Picker Key`,DROP `Packer Key`,DROP `Shipper Key`,DROP `Invoice Quantity`,DROP `Refund Quantity`,DROP `Payment Method`,DROP `Cost Storing`,DROP `Cost Handing`,DROP `Cost Shipping`,DROP `Backlog to Shipping Lag`,DROP `Metadata`,DROP `Refund Metadata`,DROP `Supplier Metadata`,DROP `Invoice Public ID`,DROP `Delivery Note ID`,DROP `Estimated Dispatched Weight`,DROP `Weight`,DROP `Estimated Volume`,DROP `Volume`,DROP `Sales Rep Key`,DROP `Warehouse Key`,DROP `Picker Key`,DROP `Packer Key`,DROP `Shipper Key`,DROP `Invoice Quantity`,DROP `Refund Quantity`,DROP `Payment Method`,DROP `Cost Storing`,DROP `Cost Handing`,DROP `Cost Shipping`,DROP `Backlog to Shipping Lag`,DROP `Metadata`,DROP `Refund Metadata`,DROP `Supplier Metadata`,DROP `No Shipped Due No Authorized`,DROP `No Shipped Due Not Found`,DROP `No Shipped Due Other`,drop `Transaction Notes`, DROP `Invoice Currency Code`;


ALTER TABLE `Invoice Dimension` DROP `Invoice Payment Account Code`, DROP `Invoice Has Been Paid In Full`, DROP `Invoice Billing Country 2 Alpha Code`, DROP `Invoice Delivery Country 2 Alpha Code`, DROP `Invoice Taxable`,DROP `Invoice For Partner`, DROP `Invoice For`, DROP `Invoice Dispatching Lag`, DROP `Invoice Tax Shipping Code`, DROP `Invoice Tax Charges Code`, DROP `Invoice Billing World Region Code`, DROP `Invoice Billing Country Code`, DROP `Invoice Billing Town`, DROP `Invoice Billing Postal Code`,DROP `Invoice Title`, DROP `Invoice XHTML Orders`, DROP `Invoice XHTML Delivery Notes`, DROP `Invoice XHTML Store`, DROP `Invoice Store Code`, DROP `Invoice XHTML Sales Representative`, DROP `Invoice XHTML Processed By`, DROP `Invoice XHTML Charged By`, DROP `Invoice Bonus Amount Value`, DROP `Invoice Refund Items Net Amount`, DROP `Invoice Refund Shipping Net Amount`, DROP `Invoice Refund Charges Net Amount`, DROP `Invoice Refund Unknown Net Amount`, DROP `Invoice Refund Items Tax Amount`, DROP `Invoice Refund Shipping Tax Amount`, DROP `Invoice Refund Charges Tax Amount`, DROP `Invoice Refund Unknown Tax Amount`, DROP `Invoice Outstanding Net Balance`, DROP `Invoice Outstanding Tax Balance`, DROP `Invoice Outstanding Total Amount`, DROP `Invoice Payment Key`, DROP `Invoice XHTML Address`, DROP `Invoice Billing To Key`, DROP `Invoice Delivery World Region Code`, DROP `Invoice Delivery Country Code`, DROP `Invoice Delivery Town`, DROP `Invoice Delivery Postal Code`, DROP `Invoice Version`;

ALTER TABLE `Invoice Dimension` CHANGE `Invoice Customer Sevices Note` `Invoice Message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `Page State Timeline` CHANGE `Site Key` `Website Key` MEDIUMINT(8) UNSIGNED NOT NULL;
ALTER TABLE `Page Store Deleted Dimension` CHANGE `Site Key` `Website Key` MEDIUMINT(8) UNSIGNED NOT NULL;

DROP TABLE `Site Content Word Dimension`, `Site Deleted Dimension`, `Site External File Bridge`, `Site Flag Dimension`, `Site Header Image Dimension`, `Site Image Bridge`;

ALTER TABLE `Customer Dimension` DROP `Customer Main XHTML Address`, DROP `Customer Main Postal Address`, DROP `Customer Main Plain Address`, DROP `Customer Main Location`, DROP `Customer Main Address Line 1`, DROP `Customer Main Address Line 2`, DROP `Customer Main Address Line 3`, DROP `Customer Main Address Lines`, DROP `Customer Main Town`, DROP `Customer Main Postal Code`, DROP `Customer Main Plain Postal Code`, DROP `Customer Main Postal Code Country Second Division`, DROP `Customer Main Country Second Division`, DROP `Customer Main Country First Division`, DROP `Customer Main Address Key`, DROP `Customer Main Country`, DROP `Customer Main Country Key`, DROP `Customer Main Country Code`, DROP `Customer Main Country 2 Alpha Code`, DROP `Customer Main Address Incomplete`, DROP `Customer XHTML Billing Address`, DROP `Customer Billing Address Country Code`, DROP `Customer Billing Address 2 Alpha Country Code`, DROP `Customer Billing Address Lines`, DROP `Customer Billing Address Line 1`, DROP `Customer Billing Address Line 2`, DROP `Customer Billing Address Line 3`, DROP `Customer Billing Address Town`, DROP `Customer Billing Address Postal Code`, DROP `Customer Billing Address Key`, DROP `Customer XHTML Main Delivery Address`, DROP `Customer Main Delivery Address Key`, DROP `Customer Main Delivery Address Lines`, DROP `Customer Main Delivery Address Town`, DROP `Customer Main Delivery Address Postal Code`, DROP `Customer Main Delivery Address Region`, DROP `Customer Main Delivery Address Country`, DROP `Customer Main Delivery Address Country Code`, DROP `Customer Main Delivery Address Country 2 Alpha Code`, DROP `Customer Main Delivery Address Country Key`, DROP `Customer Last Ship To Key`, DROP `Customer Active Ship To Records`, DROP `Customer Total Ship To Records`, DROP `Customer Last Billing To Key`, DROP `Customer Active Billing To Records`, DROP `Customer Total Billing To Records`, DROP `Customer Last Invoiced Dispatched Date`;

ALTER TABLE `Customer Dimension` DROP `Customer Main Plain Address`, DROP `Customer Main Location`, DROP `Customer Main Address Line 1`, DROP `Customer Main Address Line 2`, DROP `Customer Main Address Line 3`, DROP `Customer Main Address Lines`, DROP `Customer Main Town`, DROP `Customer Main Postal Code`, DROP `Customer Main Plain Postal Code`, DROP `Customer Main Postal Code Country Second Division`, DROP `Customer Main Country Second Division`, DROP `Customer Main Country First Division`, DROP `Customer Main Country`, DROP `Customer Main Country Key`, DROP `Customer Main Country Code`, DROP `Customer Main Country 2 Alpha Code`, DROP `Customer XHTML Billing Address`, DROP `Customer Billing Address Country Code`, DROP `Customer Billing Address 2 Alpha Country Code`, DROP `Customer Billing Address Lines`, DROP `Customer Billing Address Line 1`, DROP `Customer Billing Address Line 2`, DROP `Customer Billing Address Line 3`, DROP `Customer Billing Address Town`, DROP `Customer Billing Address Postal Code`, DROP `Customer Billing Address Key`, DROP `Customer XHTML Main Delivery Address`, DROP `Customer Main Delivery Address Key`, DROP `Customer Main Delivery Address Lines`, DROP `Customer Main Delivery Address Town`, DROP `Customer Main Delivery Address Postal Code`, DROP `Customer Main Delivery Address Region`, DROP `Customer Main Delivery Address Country`, DROP `Customer Main Delivery Address Country Code`, DROP `Customer Main Delivery Address Country 2 Alpha Code`, DROP `Customer Main Delivery Address Country Key`,

ALTER TABLE `Customer Dimension`  DROP `Customer Last Ship To Key`, DROP `Customer Active Ship To Records`, DROP `Customer Total Ship To Records`, DROP `Customer Last Billing To Key`, DROP `Customer Active Billing To Records`, DROP `Customer Total Billing To Records`, DROP `Customer Last Invoiced Dispatched Date`;

