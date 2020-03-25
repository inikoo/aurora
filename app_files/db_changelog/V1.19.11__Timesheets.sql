ALTER TABLE `Timesheet Record Dimension` CHANGE `Timesheet Record Source` `Timesheet Record Source` ENUM('ClockingMachine','Manual','API','System','WorkHome','WorkOutside','Break') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Manual';

