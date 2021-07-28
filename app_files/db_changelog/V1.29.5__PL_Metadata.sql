ALTER TABLE `Part Location Dimension` ADD `Part Location Metadata` JSON NULL DEFAULT NULL AFTER `Part Location Note`;
update `Part Location Dimension` set `Part Location Metadata`='{}';