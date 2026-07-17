-- Company (house) ad banners — self-promotional ads for தினத்துளிர் itself,
-- shown on the individual ad detail page (/ad/{id}) in place of other
-- customers' ads. Uploaded via chief editor portal, no click-through link.

CREATE TABLE IF NOT EXISTS `tn_company_ads` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slot_type` enum('square','horizontal','vertical') NOT NULL DEFAULT 'square',
  `filepath` varchar(500) NOT NULL,
  `alt_text` varchar(200) DEFAULT NULL,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `slot_type` (`slot_type`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
