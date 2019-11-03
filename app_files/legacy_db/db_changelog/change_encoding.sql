ALTER TABLE `Email Template Dimension` CHANGE `Email Template State` `Email Template State` ENUM('Active','Suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active', CHANGE `Email Template Name` `Email Template Name` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `Email Template Type` `Email Template Type` ENUM('HTML','Text') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'HTML', CHANGE `Email Template Role Type` `Email Template Role Type` ENUM('Transactional','Marketing') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Template Role` `Email Template Role` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Template Scope` `Email Template Scope` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `Email Template Subject` `Email Template Subject` VARCHAR(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Template HTML` `Email Template HTML` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Template Text` `Email Template Text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Template Editing JSON` `Email Template Editing JSON` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Template Editing Checksum` `Email Template Editing Checksum` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Template Published Checksum` `Email Template Published Checksum` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `Email Template Dimension` CHANGE `Email Template Selecting Blueprints` `Email Template Selecting Blueprints` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No', CHANGE `Email Template Role` `Email Template Role` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `Email Blueprint Dimension` CHANGE `Email Blueprint Role` `Email Blueprint Role` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `Email Blueprint Scope` `Email Blueprint Scope` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Blueprint Name` `Email Blueprint Name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Blueprint JSON` `Email Blueprint JSON` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Email Blueprint HTML` `Email Blueprint HTML` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `Email Campaign Dimension` CHANGE `Email Campaign Type` `Email Campaign Type` ENUM('Newsletter','Marketing','GR Reminder','AbandonedCart','Invite Mailshot','OOS Notification') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Marketing', CHANGE `Email Campaign Name` `Email Campaign Name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `Email Campaign State` `Email Campaign State` ENUM('InProcess','SetRecipients','ComposingEmail','Ready','Scheduled','Sending','Sent','Cancelled','Stopped') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'InProcess', CHANGE `Email Campaign Selecting Blueprints` `Email Campaign Selecting Blueprints` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No', CHANGE `Email Campaign Metadata` `Email Campaign Metadata` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `Email Campaign History Bridge` CHANGE `Deletable` `Deletable` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No', CHANGE `Strikethrough` `Strikethrough` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No', CHANGE `Type` `Type` ENUM('Notes','Changes') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Notes';

ALTER TABLE `Email Template History Bridge` CHANGE `Deletable` `Deletable` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No', CHANGE `Strikethrough` `Strikethrough` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No', CHANGE `Type` `Type` ENUM('Notes','Changes') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Notes';

ALTER TABLE `Published Email Template Dimension` CHANGE `Published Email Template JSON` `Published Email Template JSON` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Published Email Template Subject` `Published Email Template Subject` VARCHAR(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Published Email Template HTML` `Published Email Template HTML` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Published Email Template Text` `Published Email Template Text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Published Email Template Checksum` `Published Email Template Checksum` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE `History Dimension` CHANGE `Author Name` `Author Name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Subject` `Subject` ENUM('Customer','Staff','Supplier','Administrator','System') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Action` `Action` ENUM('sold_since','last_sold','first_sold','placed','wrote','deleted','edited','cancelled','charged','merged','created','associated','disassociate','register','login','logout','fail_login','password_request','password_reset','search') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'edited', CHANGE `Direct Object` `Direct Object` ENUM('Order Basket Purge','Email Campaign','Deal Campaign','Account','After Sale','Delivery Note','Category','Warehouse','Warehouse Area','Shelf','Location','Company Department','Company Area','Position','Store','User','Product','Address','Customer','Note','Order','Telecom','Email','Company','Contact','FAX','Telephone','Mobile','Work Telephone','Office Fax','Supplier','Family','Department','Attachment','Supplier Product','Part','Site','Page','Invoice','Category Customer','Category Part','Category Invoice','Category Supplier','Category Product','Category Family','Purchase Order','Supplier Delivery Note','Supplier Invoice','Webpage','Website','Prospect') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Preposition` `Preposition` ENUM('about','','to','on','because') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Indirect Object` `Indirect Object` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `History Abstract` `History Abstract` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `History Details` `History Details` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `Deep` `Deep` ENUM('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1', CHANGE `Metadata` `Metadata` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;