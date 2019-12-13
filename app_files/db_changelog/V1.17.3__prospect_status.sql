ALTER TABLE `Prospect Dimension` CHANGE `Prospect Status` `Prospect Status` ENUM('NoContacted','Contacted','NotInterested','Registered','Invoiced','Bounced') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NoContacted';

