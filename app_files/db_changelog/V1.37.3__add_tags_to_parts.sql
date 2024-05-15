ALTER TABLE `Part Dimension` ADD COLUMN `Part Seasonal`  enum('Yes','No') NOT NULL DEFAULT 'No' AFTER `Part Margin`;
ALTER TABLE `Part Dimension` ADD COLUMN `Part For Disconinue Review`  enum('Yes','No') NOT NULL DEFAULT 'No' AFTER `Part Margin`;
