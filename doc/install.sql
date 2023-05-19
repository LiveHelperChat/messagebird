CREATE TABLE `lhc_messagebird_message` (
                                           `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                           `user_id` bigint(20) unsigned NOT NULL,
                                           `created_at` bigint(20) unsigned NOT NULL,
                                           `updated_at` bigint(20) unsigned NOT NULL,
                                           `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                           `template` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `mb_id_message` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `send_status_raw` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `conversation_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `dep_id` bigint(20) unsigned NOT NULL,
                                           `chat_id` bigint(20) unsigned NOT NULL,
                                           `initiation` bigint(20) unsigned NOT NULL,
                                           `message_variables` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                                           PRIMARY KEY (`id`),
                                           KEY `mb_id_message` (`mb_id_message`),
                                           KEY `conversation_id` (`conversation_id`),
                                           KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lhc_messagebird_phone` (
                                           `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                           `created_at` bigint(20) unsigned NOT NULL,
                                           `updated_at` bigint(20) unsigned NOT NULL,
                                           `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `dep_id` bigint(20) unsigned NOT NULL,
                                           PRIMARY KEY (`id`),
                                           KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lhc_messagebird_sms_message` (
                                           `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                           `sender_phone_id` bigint(20) unsigned NOT NULL,
                                           `mb_id_message` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `user_id` bigint(20) unsigned NOT NULL,
                                           `created_at` bigint(20) unsigned NOT NULL,
                                           `updated_at` bigint(20) unsigned NOT NULL,
                                           `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `originator` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `status_txt` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                           `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `send_status_raw` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `dep_id` bigint(20) unsigned NOT NULL,
                                           `chat_id` bigint(20) unsigned NOT NULL,
                                           `message_variables` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                                           PRIMARY KEY (`id`),
                                           KEY `sender_phone_id` (`sender_phone_id`),
                                           KEY `mb_id_message` (`mb_id_message`),
                                           KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lhc_messagebird_tmpl_disabled` (`id` bigint(20) unsigned NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;