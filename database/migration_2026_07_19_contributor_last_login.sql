-- Adds last_login to tn_contributors so admin\ContributorController list
-- (app/views/admin/contributors/index.php) can show a real timestamp instead
-- of always falling back to "Never". Already set by ContributorModel on
-- Google OAuth login; only the column was missing on some environments.

ALTER TABLE `tn_contributors`
  ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL AFTER `article_count`;
