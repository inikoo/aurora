

CREATE TABLE `Staff Operative Data` (
                                        `Staff Operative Key` smallint UNSIGNED NOT NULL,
                                        `Staff Operative Purchase Orders Planning` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Purchase Orders Queued` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Purchase Orders Manufacturing` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Purchase Orders Waiting QC` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Purchase Orders QC Pass` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Purchase Orders Waiting Placing` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Purchase Orders` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Deliveries` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Deliveries In Process` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Today Deliveries` smallint UNSIGNED NOT NULL DEFAULT '0',
                                            `Staff Operative Products Planning` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Products Queued` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Products Manufacturing` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Products Waiting QC` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Products QC Pass` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Products Waiting Placing` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Products Placed` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Products Delivered Today` smallint UNSIGNED NOT NULL DEFAULT '0',
                                        `Staff Operative Products` smallint UNSIGNED NOT NULL DEFAULT '0'

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
ALTER TABLE `Staff Operative Data`
    ADD PRIMARY KEY (`Staff Operative Key`);
COMMIT;


ALTER TABLE `Staff Operative Data` ADD `Staff Operative Status` ENUM('Worker','Other','NoWorking') NOT NULL DEFAULT 'Other' AFTER `Staff Operative Key`, ADD INDEX (`Staff Operative Status`);

