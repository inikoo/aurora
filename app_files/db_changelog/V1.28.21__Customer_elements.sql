ALTER TABLE `Customer History Bridge` CHANGE `Type` `Type` ENUM('Notes','Orders','Changes','ChangesT2','WebLog','Emails') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Notes';
