truncate `Order Source Dimension`;

INSERT INTO `Order Source Dimension` (`Order Source Key`, `Order Source Code`, `Order Source Type`, `Order Source Name`, `Order Source Option Key`, `Order Source Locked`) VALUES
                                                                                                                                                                               (1, 'Website', 'website', 'Website', NULL, 'Yes'),
                                                                                                                                                                               (2, 'Call', 'phone', 'Call', NULL, 'Yes'),
                                                                                                                                                                               (3, 'Showroom', 'show', 'Showroom', NULL, 'Yes'),
                                                                                                                                                                               (4, 'Email', 'email', 'Email', NULL, 'Yes'),
                                                                                                                                                                               (5, 'Other', 'other', 'Other', NULL, 'Yes');

ALTER TABLE `Order Dimension` ADD `Order Source Key` SMALLINT UNSIGNED NULL DEFAULT NULL , ADD INDEX (`Order Source Key`);
ALTER TABLE `Invoice Dimension` ADD `Invoice Source Key` SMALLINT UNSIGNED NULL DEFAULT  NULL, ADD INDEX (`Invoice Source Key`);
ALTER TABLE `Order Source Dimension` ADD `Order Source Store Key` SMALLINT UNSIGNED NULL DEFAULT NULL ;
