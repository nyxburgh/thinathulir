-- Adds a settings-driven ads.txt (Authorized Digital Sellers) entry,
-- served at /ads.txt (see frontend\SeoController@adsTxt). Lets an admin
-- paste ad-network verification lines (e.g. Google AdSense) from
-- Admin → Settings without a code deploy.

INSERT INTO `tn_settings` (`group`, `key`, `value`, `label`, `input_type`)
SELECT 'seo', 'ads_txt_content', '', 'Ads.txt content (one authorized seller per line)', 'textarea'
WHERE NOT EXISTS (SELECT 1 FROM `tn_settings` WHERE `key` = 'ads_txt_content');
