CREATE TABLE `Fulfilment Rent Transaction Fact` (
                                                    `Fulfilment Rent Transaction Key` int UNSIGNED NOT NULL,
                                                    `Fulfilment Rent Transaction Asset Key` mediumint UNSIGNED NOT NULL,
                                                    `Fulfilment Rent Transaction Order Key` mediumint UNSIGNED NOT NULL,
                                                    `Fulfilment Rent Transaction OTF Key` int UNSIGNED DEFAULT NULL,
                                                    `Fulfilment Rent Transaction Product ID` mediumint UNSIGNED NOT NULL,
                                                    `Fulfilment Rent Transaction From` date NOT NULL,
                                                    `Fulfilment Rent Transaction To` date NOT NULL,
                                                    `Fulfilment Rent Transaction Units` decimal(14,6) NOT NULL,
                                                    `Fulfilment Rent Transaction Unit Price` decimal(12,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Fulfilment Rent Transaction Fact`
--
ALTER TABLE `Fulfilment Rent Transaction Fact`
    ADD PRIMARY KEY (`Fulfilment Rent Transaction Key`),
    ADD UNIQUE KEY `Fulfilment Rent Transaction As_2` (`Fulfilment Rent Transaction Asset Key`,`Fulfilment Rent Transaction Order Key`),
    ADD KEY `Fulfilment Rent Transaction Asset Key` (`Fulfilment Rent Transaction Asset Key`),
    ADD KEY `Fulfilment Rent Transaction Order Key` (`Fulfilment Rent Transaction Order Key`),
    ADD KEY `Fulfilment Rent Transaction OTF Key` (`Fulfilment Rent Transaction OTF Key`),
    ADD KEY `Fulfilment Rent Transaction Product ID` (`Fulfilment Rent Transaction Product ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Fulfilment Rent Transaction Fact`
--
ALTER TABLE `Fulfilment Rent Transaction Fact`
    MODIFY `Fulfilment Rent Transaction Key` int UNSIGNED NOT NULL AUTO_INCREMENT;

