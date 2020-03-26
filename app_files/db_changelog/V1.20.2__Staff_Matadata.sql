ALTER TABLE `Staff Dimension` ADD `Staff Properties` JSON NULL DEFAULT NULL AFTER `Staff Attendance End`;
update `Staff Dimension` set `Staff Properties`='{}';
