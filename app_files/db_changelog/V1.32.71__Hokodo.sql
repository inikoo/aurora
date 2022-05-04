ALTER TABLE `Payment Service Provider Dimension` CHANGE `Payment Service Provider Type` `Payment Service Provider Type` enum('EPS','EBeP','Bank','Cash','Account','ConD','BPL') NOT NULL COMMENT '';

ALTER TABLE `Payment Dimension` CHANGE `Payment Method` `Payment Method` enum('Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown','Account','Sofort','Ecredit') NOT NULL DEFAULT 'Unknown' COMMENT '';

ALTER TABLE `Customer Dimension` ADD COLUMN `hokodo_co_id` varchar(128) NULL COMMENT '';
ALTER TABLE `Customer Dimension` ADD COLUMN `hokodo_org_id` varchar(128) NULL COMMENT '';
ALTER TABLE `Customer Dimension` ADD COLUMN `hokodo_user_id` varchar(128) NULL COMMENT '';
ALTER TABLE `Customer Dimension` ADD COLUMN `hokodo_type` enum('registered-company','sole-trader') NULL DEFAULT NULL COMMENT '';

ALTER TABLE `Customer Dimension` ADD COLUMN `hokodo_sole_id` varchar(128) NULL COMMENT '';


ALTER TABLE `Customer Dimension` ADD COLUMN `hokodo_data` json NULL COMMENT '';

ALTER TABLE `Order Dimension` ADD COLUMN `hokodo_order_id` varchar(128) NULL COMMENT '';

ALTER TABLE `Order Dimension` ADD COLUMN `pending_hokodo_payment_id` varchar(32) NULL COMMENT '';

ALTER TABLE `Payment Dimension` CHANGE `Payment Metadata` `Payment Metadata` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '';
ALTER TABLE `Payment Dimension` CHANGE `Payment Transaction Status` `Payment Transaction Status` enum('Pending','Approving','Completed','Cancelled','Error','Declined') NOT NULL DEFAULT 'Pending' COMMENT '';