ALTER TABLE `Account Data`
    CHANGE `pika_token` `aiku_token` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '',
    CHANGE `pika_url` `aiku_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '';