-- Allows an article to belong to one optional additional category besides
-- its main category (e.g. a Cinema story about Tamil Nadu can also surface
-- under Tamil Nadu). Old articles are untouched — they simply have no row
-- here until edited and given an additional category.
CREATE TABLE IF NOT EXISTS `tn_article_additional_categories` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `category_id` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`article_id`),
  KEY `fk_aac_category` (`category_id`),
  CONSTRAINT `fk_aac_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aac_category` FOREIGN KEY (`category_id`) REFERENCES `tn_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
