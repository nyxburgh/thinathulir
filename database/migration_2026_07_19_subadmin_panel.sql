-- Adds the "Sub Admin" role for a restricted panel with exactly four
-- modules: URL import, news/ad approval, and rate cards — see
-- app/controllers/panel/*, app/views/layouts/subadmin.php, and the
-- /panel/* block in config/routes.php.
--
-- Gated purely by role (requireRole('sub_admin') in each panel controller),
-- not tn_role_permissions — this is one shared role covering exactly these
-- four modules, so no permission-slug wiring is needed. Role slug is
-- deliberately NOT added to App\Core\Auth::EDITORIAL_ROLES, so sub-admins
-- do not appear on the public "Our Team" page (app/models/UserModel.php
-- activeTeamMembers()/findTeamMember()).

INSERT INTO `tn_roles` (`name`, `slug`, `sort_order`)
SELECT 'Sub Admin', 'sub_admin', 10
WHERE NOT EXISTS (SELECT 1 FROM `tn_roles` WHERE `slug` = 'sub_admin');
