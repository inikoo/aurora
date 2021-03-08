ALTER TABLE `Production Part Dimension` CHANGE `Production Part Components Number` `Production Part Raw Materials Number` SMALLINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `Raw Material Dimension` CHANGE `Raw Material Products` `Raw Material Production Parts Number` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
