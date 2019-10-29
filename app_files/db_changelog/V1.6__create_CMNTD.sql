
-- Generation Time: Oct 29, 2019 at 06:06 PM


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
--

-- --------------------------------------------------------

--
-- Table structure for table `Clocking Machine NFC Tag Dimension`
--

CREATE TABLE `Clocking Machine NFC Tag Dimension` (
  `Clocking Machine NFC Tag Key` int(11) NOT NULL,
  `Clocking Machine NFC Tag ID` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Clocking Machine NFC Tag Hash` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Clocking Machine NFC Tag Status` enum('Assigned','Pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `Clocking Machine NFC Tag Creation Date` datetime NOT NULL,
  `Clocking Machine NFC Tag Scans While Pending` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `Clocking Machine NFC Tag Assigned Date` datetime DEFAULT NULL,
  `Clocking Machine NFC Tag Assigner User Key` smallint(5) UNSIGNED DEFAULT NULL,
  `Clocking Machine NFC Tag Staff Key` mediumint(9) DEFAULT NULL,
  `Clocking Machine NFC Tag Scans` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `Clocking Machine NFC Tag Last Scan` datetime DEFAULT NULL,
  `Clocking Machine NFC Tag Last Scan Box Key` smallint(5) UNSIGNED DEFAULT NULL,
  `Clocking Machine NFC Tag Number History Records` smallint(5) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Clocking Machine NFC Tag Dimension`
--
ALTER TABLE `Clocking Machine NFC Tag Dimension`
  ADD PRIMARY KEY (`Clocking Machine NFC Tag Key`),
  ADD UNIQUE KEY `Box NFC Tag ID` (`Clocking Machine NFC Tag ID`),
  ADD KEY `Box NFC Tag Status` (`Clocking Machine NFC Tag Status`),
  ADD KEY `Box NFC Tag Staff Key` (`Clocking Machine NFC Tag Staff Key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Clocking Machine NFC Tag Dimension`
--
ALTER TABLE `Clocking Machine NFC Tag Dimension`
  MODIFY `Clocking Machine NFC Tag Key` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

