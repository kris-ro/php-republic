CREATE TABLE `todo` (
  `todo_id` int UNSIGNED NOT NULL,
  `title` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `status` enum('new','in progress','in review','in tests','done') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `users_id` int UNSIGNED DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `todo`
  ADD PRIMARY KEY (`todo_id`),
  ADD KEY `title` (`title`),
  ADD KEY `created` (`created`),
  ADD KEY `updated` (`updated`),
  ADD KEY `users_id` (`users_id`);

ALTER TABLE `todo`
  MODIFY `todo_id` int UNSIGNED NOT NULL AUTO_INCREMENT;
