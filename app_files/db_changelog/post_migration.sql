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
  Drop `Billing To 2 Alpha Country Code`,
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
