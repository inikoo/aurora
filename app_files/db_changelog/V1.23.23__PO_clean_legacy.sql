alter table `Purchase Order Dimension` drop column `Purchase Order Production`;
alter table `Supplier Delivery Dimension` drop column `Supplier Delivery Production`;
alter table `Purchase Order Dimension` add `Purchase Order Operator Key` smallint unsigned null after `Purchase Order Metadata`