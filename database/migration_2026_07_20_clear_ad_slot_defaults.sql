-- Removes the old per-slot-type "default image" ad_code values.
-- The default-ad feature (admin/ad-defaults, public/uploads/ads/defaults/)
-- has been removed; company ads (tn_company_ads) now serve as the fallback
-- when no business ad is active for a slot.
UPDATE `tn_ad_slots`
SET `ad_code` = NULL
WHERE `ad_code` LIKE '/uploads/ads/defaults/%';
