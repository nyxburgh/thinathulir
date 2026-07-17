-- Fixes tn_editor_permissions: the live table only has district_id/category_id
-- columns, but every actual code path (UserModel::addPermission/getPermissions,
-- ApprovalService) reads/writes a generic ref_id + can_approve + can_publish
-- shape instead. Every insert/select against this table has been silently
-- failing inside a try/catch, so the "Approval Permissions" admin UI has never
-- actually saved anything (table has 0 rows in production). This adds the
-- missing columns the code expects; district_id/category_id are left in place
-- (unused going forward) since the table is empty and dropping them isn't
-- necessary.
ALTER TABLE `tn_editor_permissions`
  ADD COLUMN IF NOT EXISTS `ref_id` smallint(5) UNSIGNED NULL AFTER `perm_type`,
  ADD COLUMN IF NOT EXISTS `can_approve` tinyint(1) NOT NULL DEFAULT 1 AFTER `ref_id`,
  ADD COLUMN IF NOT EXISTS `can_publish` tinyint(1) NOT NULL DEFAULT 0 AFTER `can_approve`;

ALTER TABLE `tn_editor_permissions`
  ADD UNIQUE KEY IF NOT EXISTS `uniq_ep_user_type_ref` (`user_id`, `perm_type`, `ref_id`);
