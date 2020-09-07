ALTER TABLE `Delivery Note Dimension` ADD `Delivery Note Properties` JSON NULL DEFAULT NULL AFTER `Delivery Note Number History Records`;
update `Delivery Note Dimension` set `Delivery Note Properties`='{}';
