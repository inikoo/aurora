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
  DROP `Invoice Transaction Insurance Amount`,ALTER TABLE `Product Dimension` DROP `Product Parts Weight`, DROP `Product XHTML Package Weight`, DROP `Product MSDS Attachment Key`;
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
 Drop `Paid Factor`;

ALTER TABLE `Order Transaction Fact` Drop `Picked Quantity`,Drop `Customer Message`,Drop `Order Source Type`,Drop `Source Type`,Drop `Refund Key`,  Drop `Multipart Partically No Picked`,  Drop `Refund Method`, DROP `Order Public ID`;

ALTER TABLE DROP `Estimated Volume`,DROP `Volume`,DROP `Sales Rep Key`,DROP `Warehouse Key`,DROP `Picker Key`,DROP `Packer Key`,DROP `Shipper Key`,DROP `Invoice Quantity`,DROP `Refund Quantity`,DROP `Payment Method`,DROP `Cost Storing`,DROP `Cost Handing`,DROP `Cost Shipping`,DROP `Backlog to Shipping Lag`,DROP `Metadata`,DROP `Refund Metadata`,DROP `Supplier Metadata`,DROP `Invoice Public ID`,DROP `Delivery Note ID`,DROP `No Shipped Due No Authorized`,DROP `No Shipped Due Not Found`,DROP `No Shipped Due Other`,drop `Transaction Notes`, DROP `Invoice Currency Code`;

ALTER TABLE `Invoice Dimension` DROP `Invoice Payment Account Code`, DROP `Invoice Has Been Paid In Full`, DROP `Invoice Billing Country 2 Alpha Code`, DROP `Invoice Delivery Country 2 Alpha Code`, DROP `Invoice Taxable`,DROP `Invoice For Partner`, DROP `Invoice For`, DROP `Invoice Dispatching Lag`, DROP `Invoice Tax Shipping Code`, DROP `Invoice Tax Charges Code`, DROP `Invoice Billing World Region Code`, DROP `Invoice Billing Country Code`, DROP `Invoice Billing Town`, DROP `Invoice Billing Postal Code`,DROP `Invoice Title`, DROP `Invoice XHTML Orders`, DROP `Invoice XHTML Delivery Notes`, DROP `Invoice XHTML Store`, DROP `Invoice Store Code`, DROP `Invoice XHTML Sales Representative`, DROP `Invoice XHTML Processed By`, DROP `Invoice XHTML Charged By`, DROP `Invoice Bonus Amount Value`, DROP `Invoice Refund Items Net Amount`, DROP `Invoice Refund Shipping Net Amount`, DROP `Invoice Refund Charges Net Amount`, DROP `Invoice Refund Unknown Net Amount`, DROP `Invoice Refund Items Tax Amount`, DROP `Invoice Refund Shipping Tax Amount`, DROP `Invoice Refund Charges Tax Amount`, DROP `Invoice Refund Unknown Tax Amount`, DROP `Invoice Outstanding Net Balance`, DROP `Invoice Outstanding Tax Balance`, DROP `Invoice Outstanding Total Amount`, DROP `Invoice Payment Key`, DROP `Invoice XHTML Address`, DROP `Invoice Billing To Key`, DROP `Invoice Delivery World Region Code`, DROP `Invoice Delivery Country Code`, DROP `Invoice Delivery Town`, DROP `Invoice Delivery Postal Code`, DROP `Invoice Version`;

ALTER TABLE `Invoice Dimension` CHANGE `Invoice Customer Sevices Note` `Invoice Message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `Page State Timeline` CHANGE `Site Key` `Website Key` MEDIUMINT(8) UNSIGNED NOT NULL;
ALTER TABLE `Page Store Deleted Dimension` CHANGE `Site Key` `Website Key` MEDIUMINT(8) UNSIGNED NOT NULL;

DROP TABLE `Site Content Word Dimension`, `Site Deleted Dimension`, `Site External File Bridge`, `Site Flag Dimension`, `Site Header Image Dimension`, `Site Image Bridge`;

ALTER TABLE `Customer Dimension` DROP `Customer Main XHTML Address`, DROP `Customer Main Postal Address`, DROP `Customer Main Plain Address`, DROP `Customer Main Location`, DROP `Customer Main Address Line 1`, DROP `Customer Main Address Line 2`, DROP `Customer Main Address Line 3`, DROP `Customer Main Address Lines`, DROP `Customer Main Town`, DROP `Customer Main Postal Code`, DROP `Customer Main Plain Postal Code`, DROP `Customer Main Postal Code Country Second Division`, DROP `Customer Main Country Second Division`, DROP `Customer Main Country First Division`, DROP `Customer Main Address Key`, DROP `Customer Main Country`, DROP `Customer Main Country Key`, DROP `Customer Main Country Code`, DROP `Customer Main Country 2 Alpha Code`, DROP `Customer Main Address Incomplete`, DROP `Customer XHTML Billing Address`, DROP `Customer Billing Address Country Code`, DROP `Customer Billing Address 2 Alpha Country Code`, DROP `Customer Billing Address Lines`, DROP `Customer Billing Address Line 1`, DROP `Customer Billing Address Line 2`, DROP `Customer Billing Address Line 3`, DROP `Customer Billing Address Town`, DROP `Customer Billing Address Postal Code`, DROP `Customer Billing Address Key`, DROP `Customer XHTML Main Delivery Address`, DROP `Customer Main Delivery Address Key`, DROP `Customer Main Delivery Address Lines`, DROP `Customer Main Delivery Address Town`, DROP `Customer Main Delivery Address Postal Code`, DROP `Customer Main Delivery Address Region`, DROP `Customer Main Delivery Address Country`, DROP `Customer Main Delivery Address Country Code`, DROP `Customer Main Delivery Address Country 2 Alpha Code`, DROP `Customer Main Delivery Address Country Key`, DROP `Customer Last Ship To Key`, DROP `Customer Active Ship To Records`, DROP `Customer Total Ship To Records`, DROP `Customer Last Billing To Key`, DROP `Customer Active Billing To Records`, DROP `Customer Total Billing To Records`, DROP `Customer Last Invoiced Dispatched Date`;


ALTER TABLE `Customer Dimension` DROP `Customer Outstanding Net Balance`, DROP `Customer Outstanding Tax Balance`, DROP `Customer Outstanding Total Balance`, DROP `Customer Next Invoice Credit Amount`, DROP `Customer Company Key`, DROP `Customer Has More Orders Than`, DROP `Customer Has More Invoices Than`, DROP `Customer Has Better Balance Than`, DROP `Customer Is More Profiteable Than`, DROP `Customer Order More Frecuently Than`, DROP `Customer Older Than`, DROP `Customer Orders Position`, DROP `Customer Invoices Position`, DROP `Customer Balance Position`, DROP `Customer Profit Position`, DROP `Customer Staff`, DROP `Customer Staff Key`, DROP `Customer Tax Category Code`, DROP `Customer Last Payment Method`, DROP `Customer Usual Payment Method`, DROP `Customer Follower On Twitter`, DROP `Customer Friend On Facebook`, DROP `Customer Preferred Shipper Code`, DROP `Customer Metadata`, DROP `Customer Net Amount`;

ALTER TABLE `Product Dimension` DROP `Product Record Type`, DROP `Product Stage`, DROP `Product Sales Type`, DROP `Product Availability Type`, DROP `Product Main Type`, DROP `Product Number Web Pages`, DROP `Product XHTML Short Description`, DROP `Product Special Characteristic Component A`, DROP `Product Special Characteristic Component B`;
ALTER TABLE `Product Dimension` DROP `Product Slogan`, DROP `Product Family Key`, DROP `Product Family Code`, DROP `Product Family Name`, DROP `Product Main Department Key`, DROP `Product Main Department Code`, DROP `Product Main Department Name`, DROP `Product Department Degeneration`, DROP `Product Unit Dimensions Type`, DROP `Product Unit Dimensions Display Units`, DROP `Product Unit Dimensions Width`, DROP `Product Unit Dimensions Depth`, DROP `Product Unit Dimensions Length`, DROP `Product Unit Dimensions Diameter`, DROP `Product Unit Dimensions Width Display`, DROP `Product Unit Dimensions Depth Display`, DROP `Product Unit Dimensions Length Display`, DROP `Product Unit Dimensions Diameter Display`, DROP `Product Unit Dimensions Volume`, DROP `Product Package Dimensions Type`, DROP `Product Package Dimensions Display Units`, DROP `Product Package Dimensions Width`, DROP `Product Package Dimensions Depth`, DROP `Product Package Dimensions Length`, DROP `Product Package Dimensions Diameter`, DROP `Product Package Dimensions Width Display`, DROP `Product Package Dimensions Depth Display`, DROP `Product Package Dimensions Length Display`, DROP `Product Package Dimensions Diameter Display`, DROP `Product Package Dimensions Volume`;
ALTER TABLE `Product Dimension` DROP `Product Package Weight Display`, DROP `Product Unit Weight Display`, DROP `Product Unit Weight Display Units`, DROP `Product Unit Materials`, DROP `Product Main Picking Location Key`, DROP `Product Main Picking Location`, DROP `Product Main Picking Location Stock`, DROP `Product XHTML Picking`, DROP `Product Manufacure Metadata`, DROP `Product Manufacture Type Metadata`, DROP `Product Editing Price`, DROP `Product Editing RRP`, DROP `Product Editing Name`, DROP `Product Editing Special Characteristic`, DROP `Product Editing Units Per Case`, DROP `Product Editing Unit Type`, DROP `Product New on Family`;
ALTER TABLE `Product Dimension` DROP `Product Barcode Type`, DROP `Product Barcode Data Source`, DROP `Product Barcode Data`, DROP `Product Short Description`, DROP `Product Package Type`, DROP `Product Package Weight Display Units`, DROP `Product Package XHTML Dimensions`, DROP `Product XHTML Unit Weight`, DROP `Product XHTML Package Dimensions`, DROP `Product XHTML Unit Dimensions`, DROP `Product Unit Container`, DROP `Product XHTML Supplied By`;
ALTER TABLE `Product Dimension` DROP `Product Info Sheet Attachment Key`, DROP `Product Parts Data`, DROP `Product Part Metadata`;
ALTER TABLE `Product Dimension` DROP `Product Parts Weight`, DROP `Product XHTML Package Weight`, DROP `Product MSDS Attachment Key`;

ALTER TABLE `Deal Component Dimension` DROP `Deal Component Name Label`,  DROP `Deal Component Term Label`;
ALTER TABLE `Order Dimension` DROP `Order For`;
ALTER TABLE `Order Dimension` ADD `Order Customer Level Type` ENUM('Normal','VIP','Partner','Staff') NULL DEFAULT 'Normal' AFTER `Order Customer Fiscal Name`, ADD INDEX (`Order Customer Level Type`);


 ALTER TABLE `Delivery Note Dimension` CHANGE `Delivery Note State` `Delivery Note State` ENUM('Ready to be Picked','Picker Assigned','Picking','Picked','Packing','Packed','Packed Done','Approved','Dispatched','Cancelled','Cancelled to Restock') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Ready to be Picked';
ALTER TABLE `Store Dimension` DROP `Store Invoices`, DROP `Store Refunds`, DROP `Store Paid Invoices`, DROP `Store Partially Paid Invoices`, DROP `Store Paid Refunds`, DROP `Store Partially Paid Refunds`;
ALTER TABLE `Store Data` ADD `Store Total Acc Debit` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Store Total Acc Credits Amount`, ADD `Store Total Acc Debit Amount` DECIMAL(18,2) NOT NULL DEFAULT '0.00' AFTER `Store Total Acc Debit`;


update `Order Dimension` set `Order Last Updated by Customer`=`Order Last Updated Date` where `Order State`='InBasket';
update `Order Dimension` set `Order Last Updated by Customer`=`Order Submitted by Customer Date`  where `Order Last Updated by Customer` is null and `Order Submitted by Customer Date` is not null;
update `Order Dimension` set `Order Last Updated by Customer`=`Order Last Updated Date`  where `Order Last Updated by Customer` is null;
update `Order Dimension` set `Order Last Updated by Customer`=NOW()  where `Order Last Updated by Customer` is null;

 ALTER TABLE `Delivery Note Dimension` DROP `Delivery Note XHTML State`, DROP `Delivery Note Waiting For Parts`, DROP `Delivery Note Title`, DROP `Delivery Note XHTML Orders`, DROP `Delivery Note XHTML Invoices`, DROP `Delivery Note XHTML Pickers`, DROP `Delivery Note Number Pickers`, DROP `Delivery Note XHTML Packers`, DROP `Delivery Note Number Packers`, DROP `Delivery Note XHTML Ship To`, DROP `Delivery Note Ship To Key`, DROP `Delivery Note Country 2 Alpha Code`, DROP `Delivery Note World Region Code`, DROP `Delivery Note Country Code`, DROP `Delivery Note Town`, DROP `Delivery Note Postal Code`, DROP `Delivery Note XHTML Public Message`, DROP `Delivery Note Show in Warehouse Orders`, DROP `Delivery Note Pending`;


ALTER TABLE `Order Dimension` DROP `Order Class`, drop `Order Checkout Submitted Payment Date`,drop `Order Checkout Completed Payment Date`,drop `Order XHTML Invoices`,drop `Order Store Code`,drop `Order XHTML Delivery Notes`,drop `Order Current XHTML Post Dispatch State`;
ALTER TABLE `Order Dimension` DROP `Order Ship To Key To Deliver`, DROP `Order XHTML Ship Tos`, DROP `Order Ship To Country 2 Alpha Code`, DROP `Order Ship To World Region Code`, DROP `Order Ship To Town`, DROP `Order Ship To Postal Code`, DROP `Order Billing To Key To Bill`, DROP `Order XHTML Billing Tos`, DROP `Order Billing To Keys`, DROP `Order Billing To Country Code`, DROP `Order Billing To World Region Code`, DROP `Order Billing To Town`, DROP `Order Billing To Postal Code`;

Drop table `Campaign`;
Drop table `Campaign`;
Drop table `Campaign`;
Drop table `Campaign`;



DROP TABLE `Billing To Dimension`, `Shipping Notices Fact`, `Ship to Contact Dimension`, `Ship To Dimension`;

Drop table `Campaign Deal Schema`, `Comment Dimension`;

DROP TABLE `Organization Dimension`,`Company Area Department Bridge`, `Company Area Dimension`, `Company Department Dimension`, `Company Department Position Bridge`, `Company Position Dimension`, `Company Position Staff Bridge`;

DROP TABLE `Address Bridge`, `Address Telecom Bridge`, `Company Bridge`, `Company Dimension`, `Company Old ID Bridge`, `Company Web Site Bridge`, `Contact Bridge`, `Contact Dimension`, `Contract Terms Dimension`, `Email Bridge`, `Email Dimension`, `Telecom Bridge`, `Telecom Dimension`;

DROP TABLE `Email Campaign Content Bridge`, `Email Campaign Mailing List`, `Email Campaign Objective Dimension`, `Email Campaign Objective Link Bridge`, `Email Content Dimension`, `Email Content Paragraph Dimension`, `Email Credentials Dimension`, `Email Credentials Scope Bridge`, `Email Credentials Scope Mailbox`, `Email Credentials Site Bridge`, `Email Credentials Store Bridge`, `Email Credentials User Bridge`, `Email Link Click Fact`, `Email Link Dimension`, `Email Queue Attachement Dimension`, `Email Queue Dimension`, `Email Read Dimension`, `Email Send Dimension`, `Email Send Read Fact`, `Email Template Color Scheme Dimension`, `Email Template Header Image Dimension`, `Email Template Historic Color Scheme Dimension`, `Email Template Postcard Dimension`;

Drop table `Page URL Dimension`,`Search Full Text Dimension`,`Page Store Search Query Dimension`,`Visitor Session Dimension`,`Visitor Dimension`;

ALTER TABLE `Image Dimension` ADD `Image MIME Type` ENUM('image/jpeg', 'image/png','image/gif','image/x-icon') NULL DEFAULT NULL AFTER `Image Key`, ADD INDEX (`Image MIME Type`);

ALTER TABLE `Image Dimension` CHANGE `Image Data` `Image Data` LONGBLOB NULL DEFAULT NULL, CHANGE `Image Filename` `Image Filename` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `Image File Checksum` `Image File Checksum` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `Image Width` `Image Width` SMALLINT(5) UNSIGNED NULL DEFAULT NULL, CHANGE `Image Height` `Image Height` SMALLINT(5) UNSIGNED NULL DEFAULT NULL, CHANGE `Image File Size` `Image File Size` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `Image Dimension` CHANGE `Image File Format` `Image File Format` ENUM('jpeg','png','gif','ico') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'jpeg';
ALTER TABLE `Image Dimension` ADD `Image Path` VARCHAR(255) NULL DEFAULT NULL AFTER `Last Modify Date`;
ALTER TABLE `Image Subject Bridge` ADD `Image Subject Metadata` JSON NULL DEFAULT NULL AFTER `Image Subject Date`;
update `Image Subject Bridge` set `Image Subject Metadata`='{}';
Drop table `Image Bridge`;
