ALTER TABLE `Order Transaction Fact` ADD `aiku_basket_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_basket_id`);
ALTER TABLE `Order No Product Transaction Fact` ADD `aiku_basket_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`aiku_basket_id`);


