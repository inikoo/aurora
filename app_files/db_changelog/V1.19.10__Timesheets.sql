ALTER TABLE `Timesheet Record Dimension` CHANGE `Timesheet Record Source` `Timesheet Record Source` ENUM('ClockingMachine','Manual','API','System','WorkHome','WorkOutside') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Manual';
ALTER TABLE `Staff Dimension` ADD `Staff Attendance Status` ENUM('Work','Home','Outside','Off','Break') NOT NULL DEFAULT 'Off' AFTER `Staff Number Attachments`, ADD INDEX (`Staff Attendance Status`);

