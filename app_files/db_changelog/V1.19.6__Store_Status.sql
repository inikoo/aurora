ALTER TABLE `Store Dimension` CHANGE `Store State` `Store State` ENUM('InProcess','Normal','Closed','Suspended','CoolDown') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Normal';
