-- Add new "Pro Combo" package as C4 (Square 6mo + Horizontal 6mo + Vertical 30 days, ₹14500)
-- Renumber the existing 25000 "Premium (All 6 Months)" package from C4 to C5,
-- so ordering becomes: ... C3 (11500) -> C4 (14500, new) -> C5 (25000)

UPDATE `tn_ad_packages`
SET `code` = 'C5', `sort_order` = 9
WHERE `code` = 'C4';

INSERT INTO `tn_ad_packages`
  (`code`, `includes_square`, `includes_horizontal`, `includes_vertical`, `name`, `name_tamil`, `slot_type`,
   `amount`, `type`, `description`, `price_inr`, `rate_per_day`, `min_days`, `max_days`,
   `allow_images`, `image_change_days`, `allow_news`, `news_quota`, `news_interval_days`, `is_trial`,
   `qr_code_path`, `duration_days`, `max_images`, `includes_news`, `includes_video`,
   `is_active`, `sort_order`, `sq_duration_months`, `hr_duration_months`, `vt_duration_days`, `yearly_discount_pct`)
VALUES
  ('C4', 1, 1, 1, 'Pro Combo', NULL, 'any',
   0.00, 'free', NULL, 14500.00, 0.00, 7, NULL,
   1, 30, 1, 9, 0, 0,
   NULL, 7, 5, 0, 0,
   1, 8, 6, 6, 30, 10);
