-- Free (trial) ads were getting a 6-month validity fallback instead of a
-- short trial window. Free ads should only run 10 days.
-- "Free" = tn_ad_packages.is_trial = 1 (currently only package id 1, "Free Trial").

UPDATE tn_ad_packages
SET min_days = 10, max_days = 10, duration_days = 10
WHERE is_trial = 1;

UPDATE tn_business_ads a
JOIN tn_ad_packages p ON p.id = a.package_id
SET a.valid_from  = CURDATE(),
    a.valid_until = DATE_ADD(CURDATE(), INTERVAL 10 DAY)
WHERE p.is_trial = 1;
