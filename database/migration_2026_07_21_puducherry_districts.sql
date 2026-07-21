-- Add the 3 missing Puducherry U.T. districts (only "Puducherry" existed before).
-- Puducherry is a union territory made up of 4 non-contiguous districts.
INSERT INTO tn_districts (state_id, name, name_ta, slug)
SELECT 2, 'Karaikal', 'காரைக்கால்', 'karaikal'
WHERE NOT EXISTS (SELECT 1 FROM tn_districts WHERE state_id = 2 AND slug = 'karaikal');

INSERT INTO tn_districts (state_id, name, name_ta, slug)
SELECT 2, 'Mahe', 'மாஹே', 'mahe'
WHERE NOT EXISTS (SELECT 1 FROM tn_districts WHERE state_id = 2 AND slug = 'mahe');

INSERT INTO tn_districts (state_id, name, name_ta, slug)
SELECT 2, 'Yanam', 'யானம்', 'yanam'
WHERE NOT EXISTS (SELECT 1 FROM tn_districts WHERE state_id = 2 AND slug = 'yanam');
