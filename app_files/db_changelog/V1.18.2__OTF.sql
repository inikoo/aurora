ALTER TABLE `Order Transaction Fact` CHANGE `Product Code` `Product Code` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `Product Family Key` `Product Family Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL, CHANGE `Product Department Key` `Product Department Key` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL;


