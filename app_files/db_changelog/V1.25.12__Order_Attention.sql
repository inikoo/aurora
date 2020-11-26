ALTER TABLE `Order Dimension` ADD `Order Attention` ENUM('Yes','No') NOT NULL DEFAULT 'No' AFTER `Order Delivery Data`, ADD INDEX (`Order Attention`);
