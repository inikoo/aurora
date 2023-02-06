CREATE TABLE `pika_fetch` (
                              `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                              `model` varchar(255) DEFAULT NULL,
                              `model_id` int DEFAULT NULL,
                              `created_at` datetime DEFAULT NULL,
                              `updated_at` datetime DEFAULT NULL,
                              `count` smallint DEFAULT '1',
                              `error` enum('Yes','No') DEFAULT 'No',
                              UNIQUE KEY `id` (`id`),
                              UNIQUE KEY `unique_index` (`model`,`model_id`,`error`),
                              KEY `error_idx` (`error`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;