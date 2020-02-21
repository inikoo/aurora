CREATE TABLE `Customer SNS Fact` (
                                     `Customer SNS Key` mediumint UNSIGNED NOT NULL,
                                     `Customer SNS Created Date` datetime DEFAULT NULL,
                                     `Customer SNS Customer Key` mediumint UNSIGNED DEFAULT NULL,
                                     `Customer SNS Store Key` smallint UNSIGNED DEFAULT NULL,
                                     `Customer SNS Subscription ARN` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `Customer SNS Subscription Protocol` enum('http','https','email','email-json','sms','sqs','application','lambda') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `Customer SNS Subscription Endpoint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `Customer SNS Subscription Status` enum('Pending','Confirmed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
                                     `Customer SNS Price Updated` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
                                     `Customer SNS Stock Updated` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
                                     `Customer SNS Order Updated` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
                                     `Customer SNS New Products` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
                                     `Customer SNS Settings` json DEFAULT NULL,
                                     `Customer SNS Sent` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Customer SNS Fact`
--
ALTER TABLE `Customer SNS Fact`
    ADD PRIMARY KEY (`Customer SNS Key`),
    ADD UNIQUE KEY `Customer SNS Customer Key_2` (`Customer SNS Customer Key`,`Customer SNS Subscription ARN`),
    ADD KEY `Customer SNS Customer Key` (`Customer SNS Customer Key`),
    ADD KEY `Customer SNS Store Key` (`Customer SNS Store Key`),
    ADD KEY `Customer SNS Subscription Status` (`Customer SNS Subscription Status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Customer SNS Fact`
--
ALTER TABLE `Customer SNS Fact`
    MODIFY `Customer SNS Key` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;
