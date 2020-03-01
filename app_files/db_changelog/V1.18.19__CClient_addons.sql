ALTER TABLE `Customer Client Dimension` ADD `Customer Client Status` ENUM('Active','Inactive') NOT NULL DEFAULT 'Active' AFTER `Customer Client Key`, ADD INDEX (`Customer Client Status`);

