ALTER TABLE `Email Campaign Dimension` CHANGE `Email Campaign Wave Type` `Email Campaign Wave Type` ENUM('No','Yes','Wave','Sent','Cancelled') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No';

