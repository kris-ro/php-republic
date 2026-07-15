CREATE TABLE `users` (
  -- Ids and authentication
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` CHAR(255) NOT NULL,
  `email` CHAR(255) NOT NULL,
  `password` CHAR(255) NOT NULL, -- Length 255 for compatibility with password_hash()

  -- Status and Security
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `role` ENUM('guest', 'customer', 'company', 'app') NOT NULL DEFAULT 'customer',

  -- Audit (Time)
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` DATETIME DEFAULT NULL,

  -- Indexes
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_email` (`email`),
  UNIQUE KEY `idx_username` (`username`),
  KEY `idx_created` (`created`),
  KEY `idx_updated` (`updated`),
  KEY `idx_last_login` (`last_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;