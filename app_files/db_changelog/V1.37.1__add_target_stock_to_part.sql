ALTER TABLE `Part Dimension` ADD `Part Target Stock` INT UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `Part Dimension` ADD `Part Target Modifierr` ENUM('Suspended','Normal','Priority') NULL DEFAULT 'Normal' ;

