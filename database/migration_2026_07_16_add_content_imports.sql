-- Adds URL-import support so staff reporters can pull title+content from a
-- third-party article URL into a review queue, then convert it into a draft.
-- Run this once against the target database (local dev and, separately, live).

CREATE TABLE IF NOT EXISTS `tn_content_imports` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `source_url` varchar(1000) NOT NULL,
  `title` varchar(300) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `status` enum('pending','converted','discarded') NOT NULL DEFAULT 'pending',
  `converted_article_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ci_user` (`user_id`),
  CONSTRAINT `fk_ci_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
