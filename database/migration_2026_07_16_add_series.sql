-- Adds "series" support so contributors can group articles into a
-- multi-part story/web-series instead of only submitting standalone pieces.
-- Run this once against the target database (local dev and, separately, live).

CREATE TABLE IF NOT EXISTS `tn_series` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contributor_id` int(10) UNSIGNED NOT NULL,
  `category_id` smallint(5) UNSIGNED DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('ongoing','completed') NOT NULL DEFAULT 'ongoing',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_series_contributor` (`contributor_id`),
  KEY `fk_series_category` (`category_id`),
  CONSTRAINT `fk_series_contributor` FOREIGN KEY (`contributor_id`) REFERENCES `tn_contributors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_series_category` FOREIGN KEY (`category_id`) REFERENCES `tn_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tn_articles`
  ADD COLUMN `series_id` int(10) UNSIGNED DEFAULT NULL AFTER `contributor_id`,
  ADD COLUMN `series_part` int(10) UNSIGNED DEFAULT NULL AFTER `series_id`,
  ADD KEY `idx_art_series` (`series_id`, `series_part`),
  ADD CONSTRAINT `fk_art_series` FOREIGN KEY (`series_id`) REFERENCES `tn_series` (`id`) ON DELETE SET NULL;
