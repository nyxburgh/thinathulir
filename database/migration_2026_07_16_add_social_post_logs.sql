-- Run this once against the LIVE production database (u240888951_thulir).
-- Adds the log table backing SocialPostService (Facebook / Threads auto-post on publish).

CREATE TABLE IF NOT EXISTS `tn_social_post_logs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform` enum('facebook','threads') NOT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `remote_post_id` varchar(191) DEFAULT NULL COMMENT 'ID returned by the platform, for auditing/debugging',
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `error` text DEFAULT NULL,
  `posted_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_spl_article` (`article_id`),
  CONSTRAINT `fk_spl_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
