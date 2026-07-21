-- Adds share-image generation metadata to tn_photo_news.
-- A social share image generated from an article IS a Photo News entry —
-- no new table needed, just tracking how it was produced.
ALTER TABLE tn_photo_news
  ADD COLUMN share_placement ENUM('left','right','center') NULL AFTER image_path,
  ADD COLUMN is_auto_generated TINYINT(1) NOT NULL DEFAULT 0 AFTER share_placement;
