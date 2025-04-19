CREATE TABLE `Stack Aiku Dimension` (
                                   `Stack Aiku Key` bigint unsigned NOT NULL AUTO_INCREMENT,
                                   `Stack Aiku Creation Date` datetime DEFAULT NULL,
                                   `Stack Aiku Last Update Date` datetime DEFAULT NULL,
                                   `Stack Aiku Operation` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                                   `Stack Aiku Operation Key` int unsigned DEFAULT NULL,
                                   `Stack Aiku Counter` mediumint unsigned NOT NULL DEFAULT '1',
                                   `Stack Aiku Metadata` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                                   PRIMARY KEY (`Stack Aiku Key`),
                                   UNIQUE KEY `Stack Operation` (`Stack Aiku Operation`,`Stack Aiku Operation Key`),

                                       INDEX `idx_stack_aiku_creation_date` (`Stack Aiku Creation Date`)

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=COMPACT;