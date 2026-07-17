-- Run this once against the LIVE production database (u240888951_thulir).
-- Fixes two schema-drift bugs where the code was updated but the live DB was not:
--   1. /public/newspaper  -> Unknown column 'n.is_active'
--   2. Contributor login  -> Table 'tn_contributor_categories' doesn't exist

-- 1. Bring tn_newspapers up to the schema the code expects
ALTER TABLE `tn_newspapers`
  CHANGE `user_id` `uploaded_by` int(10) UNSIGNED NOT NULL,
  CHANGE `is_published` `is_active` tinyint(1) NOT NULL DEFAULT 1,
  CHANGE `cover_image` `thumb_path` varchar(500) DEFAULT NULL,
  ADD COLUMN `title_tamil` varchar(200) DEFAULT NULL AFTER `title`,
  ADD COLUMN `edition_type` enum('daily','weekly','special') NOT NULL DEFAULT 'daily' AFTER `edition_date`,
  ADD COLUMN `file_size` int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `thumb_path`,
  ADD COLUMN `pages` tinyint(3) UNSIGNED DEFAULT NULL AFTER `file_size`;

-- 2. Create the missing contributor <-> category join table
CREATE TABLE IF NOT EXISTS `tn_contributor_categories` (
  `contributor_id` int(10) UNSIGNED NOT NULL,
  `category_id` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`contributor_id`,`category_id`),
  KEY `fk_cc_category` (`category_id`),
  CONSTRAINT `fk_cc_category` FOREIGN KEY (`category_id`) REFERENCES `tn_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cc_contributor` FOREIGN KEY (`contributor_id`) REFERENCES `tn_contributors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
