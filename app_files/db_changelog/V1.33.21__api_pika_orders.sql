CREATE TABLE `pika_api_orders` (
                                   `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                   `order_id` bigint unsigned DEFAULT NULL,
                                   `processing` tinyint(1) DEFAULT '0',
                                   `created_at` datetime DEFAULT NULL,
                                   `processed_at` datetime DEFAULT NULL,
                                   `data` json DEFAULT NULL,
                                   `debug` json DEFAULT NULL,
                                   PRIMARY KEY (`id`),
                                   UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;