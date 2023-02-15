
truncate pika_fetch;
ALTER TABLE `pika_fetch`  DROP index `unique_index`;
ALTER TABLE `pika_fetch`  DROP COLUMN `error`;
ALTER TABLE `pika_fetch`   ADD UNIQUE INDEX `unique_index` (`model`,`model_id`) USING BTREE;

CREATE TABLE `pika_fetch_error` (
                              `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                              `model` varchar(255) DEFAULT NULL,
                              `model_id` int DEFAULT NULL,
                              `created_at`  datetime DEFAULT CURRENT_TIMESTAMP,
                              `updated_at` datetime DEFAULT NULL,
                              UNIQUE KEY `id` (`id`),
                              UNIQUE KEY `unique_index` (`model`,`model_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;