CREATE TABLE `recovery_token` (
  `recovery_token_id` bigint UNSIGNED NOT NULL,
  `fingerprint` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `random_nonce` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires` datetime DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `recovery_token`
  ADD PRIMARY KEY (`recovery_token_id`),
  ADD UNIQUE KEY `idx_fingerprint` (`fingerprint`),
  ADD KEY `idx_created` (`created`),
  ADD KEY `idx_expiration` (`expires`);

ALTER TABLE `recovery_token`
  MODIFY `recovery_token_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
