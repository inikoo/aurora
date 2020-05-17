RENAME TABLE `Customer Portfolio Timeline` TO  `Customer Portfolio Timeline Legacy`;

CREATE TABLE `Customer Portfolio Timeline` (
                                               `Customer Portfolio Timeline Key` int UNSIGNED NOT NULL,
                                               `Customer Portfolio Timeline Customer Portfolio Key` int UNSIGNED NOT NULL,
                                               `Customer Portfolio Timeline Action` enum('Add','Remove') COLLATE utf8mb4_unicode_ci NOT NULL,
                                               `Customer Portfolio Timeline Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Customer Portfolio Timeline`
--
ALTER TABLE `Customer Portfolio Timeline`
    ADD PRIMARY KEY (`Customer Portfolio Timeline Key`),
    ADD KEY `Customer Portfolio Timeline Customer Portfolio Key` (`Customer Portfolio Timeline Customer Portfolio Key`),
    ADD KEY `Customer Portfolio Timeline Action` (`Customer Portfolio Timeline Action`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Customer Portfolio Timeline`
--
ALTER TABLE `Customer Portfolio Timeline`
    MODIFY `Customer Portfolio Timeline Key` int UNSIGNED NOT NULL AUTO_INCREMENT;
