ALTER TABLE `Store Dimension` CHANGE `Store State` `Store Status` ENUM('InProcess','Normal','ClosingDown','Closed') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'InProcess';
