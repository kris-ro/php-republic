CREATE TABLE `notification` (
  `notifications_id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `subject` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `body` mediumtext COLLATE utf8mb4_unicode_ci,
  `users_id` bigint UNSIGNED NOT NULL,
  `email` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `notifications`
  ADD KEY `text` (`subject`,`body`(512)),
  ADD KEY `email` (`email`),
  ADD KEY `from` (`from`),
  ADD KEY `created` (`created`),
  ADD KEY `users_id` (`users_id`);
