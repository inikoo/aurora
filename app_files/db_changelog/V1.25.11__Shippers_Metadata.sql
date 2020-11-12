ALTER TABLE `Shipper Dimension` ADD `Shipper Metadata` JSON NULL DEFAULT NULL AFTER `Shipper API Key`;
update `Shipper Dimension`  set `Shipper Metadata`='{}';