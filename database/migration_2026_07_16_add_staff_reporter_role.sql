-- Adds the "Staff Reporter" role for office-based staff who post/edit/import
-- regularly and verify incoming submissions, with all editorial rights except
-- delete. See app/core/Auth.php for the permission matrix.
INSERT INTO `tn_roles` (`name`, `slug`, `sort_order`)
SELECT 'Staff Reporter', 'staff_reporter', 9
WHERE NOT EXISTS (SELECT 1 FROM `tn_roles` WHERE `slug` = 'staff_reporter');
