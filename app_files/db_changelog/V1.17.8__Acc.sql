ALTER TABLE `Account Data` ADD `Account Properties` JSON NULL DEFAULT NULL AFTER `Account Key`;
update `Account Data` set `Account Properties` ='{}';


