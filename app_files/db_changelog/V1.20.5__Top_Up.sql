CREATE TABLE `Top Up Dimension` (
                                    `Top Up Key` int UNSIGNED NOT NULL,
                                    `Top Up Status` enum('InProcess','Paid','Error') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'InProcess',
                                    `Top Up Customer Key` mediumint UNSIGNED DEFAULT NULL,
                                    `Top Up Store key` smallint UNSIGNED DEFAULT NULL,
                                    `Top Up Payment Key` mediumint UNSIGNED DEFAULT NULL,
                                    `Top Up Date` datetime DEFAULT NULL,
                                    `Top Up Amount` decimal(18,2) DEFAULT NULL,
                                    `Top Up Currency Code` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                    `Top Up Exchange` float DEFAULT NULL,
                                    `Top Up Metadata` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Top Up Dimension`
--
ALTER TABLE `Top Up Dimension`
    ADD PRIMARY KEY (`Top Up Key`),
    ADD KEY `Top Up Payment Key` (`Top Up Payment Key`),
    ADD KEY `Top Up Customer key` (`Top Up Customer Key`),
    ADD KEY `Top Up Status` (`Top Up Status`),
    ADD KEY `Top Up Store key` (`Top Up Store key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Top Up Dimension`
--
ALTER TABLE `Top Up Dimension`
    MODIFY `Top Up Key` int UNSIGNED NOT NULL AUTO_INCREMENT;