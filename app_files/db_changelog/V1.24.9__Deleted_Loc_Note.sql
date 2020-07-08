ALTER TABLE `Location Deleted Dimension` ADD `Location Deleted Note` TEXT NULL DEFAULT NULL AFTER `Location Deleted Date`;
ALTER TABLE `Location Deleted Dimension` CHANGE `Location Deleted Metadata` `Location Deleted Data` BLOB NULL DEFAULT NULL;
ALTER TABLE `Location Deleted Dimension` ADD `Location Deleted Metadata` JSON NULL DEFAULT NULL AFTER `Location Deleted Date`;
update `Location Deleted Dimension` set `Location Deleted Metadata`='{}';