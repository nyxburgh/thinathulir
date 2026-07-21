-- Per-user permission overrides (grant/revoke a specific permission slug for
-- one user regardless of role) + team/ID-card fields for the public "Our
-- Team" verification page. See app/core/Auth.php for the live permission
-- matrix and app/controllers/frontend/TeamController.php for the public page.

CREATE TABLE IF NOT EXISTS `tn_user_permission_overrides` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_slug` varchar(60) NOT NULL,
  `effect` enum('grant','revoke') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_perm` (`user_id`,`permission_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tn_users`
  ADD COLUMN IF NOT EXISTS `designation` varchar(100) DEFAULT NULL AFTER `bio`,
  ADD COLUMN IF NOT EXISTS `id_no` varchar(50) DEFAULT NULL AFTER `designation`,
  ADD COLUMN IF NOT EXISTS `dob` date DEFAULT NULL AFTER `id_no`;

ALTER TABLE `tn_users`
  ADD UNIQUE KEY IF NOT EXISTS `uniq_id_no` (`id_no`);
