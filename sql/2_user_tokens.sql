CREATE TABLE `user_tokens` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, -- UID or internal ID for the token record
  `label` CHAR(255) NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,                  -- Link to your users table
  `fingerprint` CHAR(64) NOT NULL,                  -- SHA-256 hash of the token (don't store the raw token)
  `random_nonce` CHAR(64) NOT NULL,                 -- The 32-byte (hex) random string you generated
  `expires` DATETIME DEFAULT NULL,                  -- Allow for user choice on expiration
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  `revoked` TINYINT(1) DEFAULT 0,                   -- To allow manual blacklisting

  PRIMARY KEY (`id`),
  INDEX `idx_label` (`label`),
  INDEX `idx_user_lookup` (`user_id`),
  INDEX `idx_created` (`created`),
  INDEX `idx_expiration` (`expires`),
  INDEX `idx_revoked` (`revoked`),
  UNIQUE INDEX `idx_fingerprint` (`fingerprint`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
