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

ALTER TABLE `Inventory Transaction Fact`
  DROP `Dispatch Country Code`,
  DROP `Out of Stock Tag`,
  DROP `Map To Order Transaction Fact Parts Multiplicity`,
  DROP `Map To Order Transaction Fact XHTML Info`,
  DROP `Inventory Transaction State`;

  ALTER TABLE `User Dimension` CHANGE `User Type` `User Type` ENUM('Staff','Supplier','Administrator','Warehouse','Contractor','Agent') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;


ALTER TABLE `Inventory Spanshot Fact` DROP `Quantity Open`, DROP `Quantity High`, DROP `Quantity Low`, DROP `Value At Cost Open`, DROP `Value At Cost High`, DROP `Value At Cost Low`, DROP `Value At Day Cost Open`, DROP `Value At Day Cost High`, DROP `Value At Day Cost Low`, DROP `Value Commercial Open`, DROP `Value Commercial High`, DROP `Value Commercial Low`;
ALTER TABLE `Inventory Warehouse Spanshot Fact` DROP `Value At Cost Open`, DROP `Value At Cost High`, DROP `Value At Cost Low`, DROP `Value At Day Cost Open`, DROP `Value At Day Cost High`, DROP `Value At Day Cost Low`, DROP `Value Commercial Open`, DROP `Value Commercial High`, DROP `Value Commercial Low`;


 update  `Inventory Transaction Fact` set `Inventory Transaction Section`='Leakage Detail' where `Inventory Transaction Type`='Adjust' and  `Inventory Transaction Section`='Audit';
 update  `Inventory Transaction Fact` set `Inventory Transaction Section`='Lost' where `Inventory Transaction Type` in ('Broken','Lost');
update  `Inventory Transaction Fact` set `Inventory Transaction Section`='In',`Inventory Transaction Type`='Found' where `Inventory Transaction Type`='Other Out' and `Inventory Transaction Quantity`>=0;
update  `Inventory Transaction Fact` set `Inventory Transaction Section`='Lost' where `Inventory Transaction Type`='Other Out' and `Inventory Transaction Quantity`<0;

update  `Inventory Transaction Fact` set `Inventory Transaction Section`='Move Detail' where `Inventory Transaction Type` in ('Move In','Move Out');
update  `Inventory Transaction Fact` set `Inventory Transaction Section`='NoDispatched' where `Inventory Transaction Type` in ('Failsale');

ALTER TABLE `Inventory Spanshot Fact` ADD `Inventory Spanshot Stock Left 1 Year Ago` FLOAT NULL DEFAULT '0' AFTER `Inventory Spanshot Warehouse SKO Value`;
ALTER TABLE `Inventory Spanshot Fact` CHANGE `Dormant 1 Year` `Dormant 1 Year` ENUM('Yes','No','NA') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `Inventory Warehouse Spanshot Fact` ADD `Inventory Warehouse Spanshot Fact Dormant Parts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Inventory Warehouse Spanshot Out Other`, ADD `Inventory Warehouse Spanshot Fact Stock Left 1 Year Parts` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Inventory Warehouse Spanshot Fact Dormant Parts`;
ALTER TABLE `Inventory Transaction Fact` CHANGE `Waitng` `Waiting` FLOAT UNSIGNED NOT NULL DEFAULT '0';

update `Purchase Order Dimension` set `Purchase Order Production`='Yes' where `Purchase Order Parent`='Supplier' and `Purchase Order Parent Key`=6472;
update `Supplier Delivery Dimension` set `Supplier Delivery Production`='Yes' where `Supplier Delivery Parent`='Supplier' and `Supplier Delivery Parent Key`=6472;

update `Supplier Part Dimension` set `Supplier Part Production`='Yes' where   `Supplier Part Supplier Key`=6472;


ALTER TABLE `Order Transaction Fact` ADD `OTF Webpage Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `OTF Category Department Key`;
ALTER TABLE `Category Dimension` ADD `Category Properties` JSON NULL DEFAULT NULL AFTER `Category Number History Records`;
update `Category Dimension` set `Category Properties`='{}'  where  `Category Properties`='' or `Category Properties` is null  ;
ALTER TABLE `Product Dimension` ADD `Product Properties` JSON NULL DEFAULT NULL AFTER `Product Department Category Key`;
update `Product Dimension` set `Product Properties`='{}'  where  `Product Properties`='' or `Product Properties` is null ;

update `Supplier Dimension` set `Supplier Metadata`='{}'  where  `Supplier Metadata`='' or `Supplier Metadata` is null ;

RENAME TABLE `Product Family Sales Correlation` TO `Product Category Sales Correlation`;
ALTER TABLE `Product Category Sales Correlation` CHANGE `Family A Key` `Category A Key` MEDIUMINT(8) UNSIGNED NOT NULL, CHANGE `Family B Key` `Category B Key` MEDIUMINT(8) UNSIGNED NOT NULL;
ALTER TABLE `Product Category Sales Correlation` CHANGE `Correlation` `Correlation` DOUBLE NOT NULL;

ALTER TABLE `Product Category Dimension` ADD `Product Category Ignore Correlation` ENUM('Yes','No')  NULL DEFAULT 'No' AFTER `Product Category Status`, ADD INDEX (`Product Category Ignore Correlation`);
ALTER TABLE `Product Dimension` ADD `Product Ignore Correlation` ENUM('Yes','No')  NULL DEFAULT 'No' , ADD INDEX (`Product Ignore Correlation`);


truncate  `Product Category Sales Correlation`;
ALTER TABLE `Product Category Sales Correlation` ADD `Product Category Sales Correlation Store Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Samples`, ADD `Product Category Sales Correlation Type` ENUM('Department','Family') NULL DEFAULT NULL AFTER `Product Category Sales Correlation Store Key`, ADD INDEX (`Product Category Sales Correlation Store Key`);
ALTER TABLE `Product Category Sales Correlation` ADD `Customers A` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Product Category Sales Correlation Type`, ADD `Customers B` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers A`, ADD `Customers AB` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers B`, ADD `Customers All A` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers AB`, ADD `Customers All B` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers All A`, ADD `Product Category Sales Correlation Last Updated` DATETIME NULL DEFAULT NULL AFTER `Customers All B`;

truncate  `Product Sales Correlation`;

ALTER TABLE `Product Sales Correlation` ADD `Product Sales Correlation Store Key` SMALLINT UNSIGNED NULL DEFAULT NULL FIRST, ADD INDEX (`Product Sales Correlation Store Key`);
ALTER TABLE `Product Sales Correlation` ADD `Customers A` SMALLINT UNSIGNED NULL DEFAULT NULL , ADD `Customers B` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers A`, ADD `Customers AB` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers B`, ADD `Customers All A` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers AB`, ADD `Customers All B` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers All A`, ADD `Product Sales Correlation Last Updated` DATETIME NULL DEFAULT NULL AFTER `Customers All B`;

truncate  `Product Sales Anticorrelation`;

ALTER TABLE `Product Sales Anticorrelation` ADD `Product Sales Anticorrelation Store Key` SMALLINT UNSIGNED NULL DEFAULT NULL FIRST, ADD INDEX (`Product Sales Anticorrelation Store Key`);
ALTER TABLE `Product Sales Anticorrelation` ADD `Customers A` SMALLINT UNSIGNED NULL DEFAULT NULL , ADD `Customers B` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers A`, ADD `Customers AB` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers B`, ADD `Customers All A` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers AB`, ADD `Customers All B` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `Customers All A`, ADD `Product Sales Anticorrelation Last Updated` DATETIME NULL DEFAULT NULL AFTER `Customers All B`;
ALTER TABLE `Part Dimension` ADD `Part Main Supplier Part Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Part SKU`, ADD INDEX (`Part Main Supplier Part Key`);
update `Part Dimension` set `Part Properties`='{}'  where  `Part Properties`='' or `Part Properties` is null  ;

ALTER TABLE `Supplier Part Dimension` ADD `Supplier Part Carton Barcode` VARCHAR(64) NULL DEFAULT NULL AFTER `Supplier Part Carton CBM`;
ALTER TABLE `Product Dimension` CHANGE `Product Hazard Indentification Number` `Product Hazard Identification Number` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `Part Dimension` CHANGE `Part Hazard Indentification Number` `Part Hazard Identification Number` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
DROP TABLE `Supplier Product Dimension`, `Supplier Product History Bridge`, `Supplier Product History Dimension`, `Supplier Product Part Dimension`, `Supplier Product Part List`;
ALTER TABLE `Part Dimension` CHANGE `Part Recommended Packages Per Selling Outer` `Part Recommended Packages Per Selling Outer` FLOAT UNSIGNED NULL DEFAULT '1';


ALTER TABLE `Agent Dimension`
    DROP `Agent Main XHTML Address`,
    DROP `Agent Main Plain Address`,
    DROP `Agent Main Country Key`,
    DROP `Agent Main Country Code`,
    DROP `Agent Main Country`,
    DROP `Agent Main Location`,
    DROP `Agent Main XHTML Email`,
    DROP `Agent Main Email Key`,
    DROP `Agent Main Telephone Key`;



ALTER TABLE `Part Dimension`
    DROP `Part Currently Used In`,
    DROP `Part XHTML Currently Supplied By`,
    DROP `Part XHTML Description`,
    DROP `Part Unit Description`,
    DROP `Part Unit Dimensions Type`,
    DROP `Part Unit Dimensions Display Units`,
    DROP `Part Unit Dimensions Width`,
    DROP `Part Unit Dimensions Depth`,
    DROP `Part Unit Dimensions Diameter`,
    DROP `Part Unit Dimensions Width Display`,
    DROP `Part Unit Dimensions Depth Display`,
    DROP `Part Unit Dimensions Length Display`,
    DROP `Part Unit Dimensions Diameter Display`,
    DROP `Part Unit Dimensions Volume`,
    DROP `Part Unit XHTML Dimensions`,
    DROP `Part Unit Materials`,
    DROP `Part Unit XHTML Materials`,
    DROP `Part Package Dimensions Type`,
    DROP `Part Package Dimensions Display Units`,
    DROP `Part Package Dimensions Width`,
    DROP `Part Package Dimensions Depth`,
    DROP `Part Package Dimensions Length`,
    DROP `Part Package Dimensions Diameter`,
    DROP `Part Package Dimensions Width Display`,
    DROP `Part Package Dimensions Depth Display`,
    DROP `Part Package Dimensions Length Display`,
    DROP `Part Package Dimensions Diameter Display`,
    DROP `Part Package Dimensions Volume`,
    DROP `Part Package XHTML Dimensions`;


update  `Purchase Order Dimension` O left join `Supplier Dimension` D on (O.`Purchase Order Parent Key`=D.`Supplier Key`) set `Purchase Order Parent Country Code`=`Supplier Contact Address Country 2 Alpha Code` where `Purchase Order Parent`='Supplier';
update  `Purchase Order Dimension` O left join `Agent Dimension` D on (O.`Purchase Order Parent Key`=D.`Agent Key`) set `Purchase Order Parent Country Code`=`Agent Contact Address Country 2 Alpha Code` where `Purchase Order Parent`='Agent';
update  `Supplier Delivery Dimension` O left join `Supplier Dimension` D on (O.`Supplier Delivery Parent Key`=D.`Supplier Key`) set `Supplier Delivery Parent Country Code`=`Supplier Contact Address Country 2 Alpha Code` where `Supplier Delivery Parent`='Supplier';
update  `Supplier Delivery Dimension` O left join `Agent Dimension` D on (O.`Supplier Delivery Parent Key`=D.`Agent Key`) set `Supplier Delivery Parent Country Code`=`Agent Contact Address Country 2 Alpha Code` where `Supplier Delivery Parent`='Agent';


update `Website User Dimension` WUD left join old_dw.`User Dimension` U on (`User Parent Key`=`Website User Customer Key`) set `Website User Has Login`='Yes' where  `User Type`='Customer'  and U.`User Login Count`>0;
update `Website User Dimension` WUD left join old_dw.`User Dimension` U on (`User Parent Key`=`Website User Customer Key`) set `Website User Has Login`='Yes' where  `User Type`='Customer'  and U.`User Sessions Count`>0;
update `Website User Dimension` WUD left join old_dw.`User Dimension` U on (`User Parent Key`=`Website User Customer Key`) set `Website User Has Login`='Yes' where  `User Type`='Customer'  and U.`User Requests Count`>0;
update `Website User Dimension` WUD left join `History Dimension` C on (`Subject Key`=`Website User Customer Key`) set `Website User Has Login`='Yes' where  `Subject`='Customer'  and  `Author Name`='Customer' and `History Abstract` like '% registered';
delete from `History Dimension` where `Subject`='Customer' and `Author Name`='Customer' and `History Abstract` like 'Website user %';

update  `Website Webpage Scope Map` O left join `Product Category Dimension` D on (O.`Website Webpage Scope Scope Key`=D.`Product Category Key`) set `Website Webpage Scope Scope Website Key`=`Product Category Webpage Key` where `Website Webpage Scope Scope`='Category';
update  `Website Webpage Scope Map` O left join `Product Dimension` D on (O.`Website Webpage Scope Scope Key`=D.`Product ID`) set `Website Webpage Scope Scope Website Key`=`Product Webpage Key` where `Website Webpage Scope Scope`='Product';

ALTER TABLE `Website Webpage Scope Map` ADD `Website Webpage Scope Key` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Website Webpage Scope Key`);

ALTER TABLE `Part Dimension` DROP `Part Stock State`;

ALTER TABLE `Website Webpage Scope Map` CHANGE `Website Webpage Scope Scope Website Key` `Website Webpage Scope Scope Webpage Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL;
update `Supplier Part Dimension` set `Supplier Part Properties`='{}'  where  `Supplier Part Properties`='' or `Supplier Part Properties` is null ;

ALTER TABLE `Invoice Dimension` ADD `Invoice Tax Number Validation Message` TEXT NULL DEFAULT NULL AFTER `Invoice Tax Number Valid`;
ALTER TABLE `Invoice Dimension` ADD `Invoice Tax Number Validation Source` ENUM('Online', 'Staff') NULL DEFAULT NULL AFTER `Invoice Tax Number Valid`;

update `Customer Dimension` set `Customer Tax Number Validation Date`=NULL where `Customer Tax Number Validation Date`='0000-00-00 00:00:00';
update `Order Dimension` set `Order Tax Number Validation Date`=NULL where `Order Tax Number Validation Date`='0000-00-00 00:00:00';
update `Invoice Dimension` set `Invoice Tax Number Validation Date`=NULL where `Invoice Tax Number Validation Date`='0000-00-00 00:00:00';

update `Invoice Dimension` left join `Order Dimension` on (`Order Key`=`Invoice Order Key`)  set `Invoice Tax Number Validation Source`=`Order Tax Number Validation Source`;
update `Invoice Dimension` left join `Order Dimension` on (`Order Key`=`Invoice Order Key`)  set `Invoice Tax Number Validation Message`=`Order Tax Number Validation Message`;

update `Order Dimension` set `Order Metadata`='{}'  where  `Order Metadata`='' or `Order Metadata` is null ;

UPDATE `Order Dimension` SET `Order Metadata`= JSON_SET(`Order Metadata`,'$.tax_name',ifnull(`Order Tax Name`,'')) ;
UPDATE `Order Dimension` SET `Order Metadata`= JSON_SET(`Order Metadata`,'$.why_tax',ifnull(`Order Tax Selection Type`,'')) ;
ALTER TABLE `Order Dimension` DROP `Order Tax Name`, DROP `Order Tax Operations`, DROP `Order Tax Selection Type` , drop `Order Apply Auto Customer Account Payment`   ;


DROP TABLE `Part Availability for Products Timeline`;

UPDATE `User Dimension` SET `User Settings`= JSON_SET(`User Settings`,'$.Timezone','Account') ;

DROP TABLE  `Webpage Panel Dimension`;

ALTER TABLE `Order Dimension` ADD `Customer Client Customer Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Order Customer Key`, ADD INDEX (`Customer Client Customer Key`);
ALTER TABLE `Invoice Dimension` ADD `Customer Client Customer Key` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `Order Customer Key`, ADD INDEX (`Customer Client Customer Key`);
