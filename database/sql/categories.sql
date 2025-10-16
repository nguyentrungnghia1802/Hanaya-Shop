DELETE FROM categories;
ALTER TABLE categories AUTO_INCREMENT = 1;
INSERT INTO categories (name, description, image_path, created_at, updated_at) VALUES ('Birthday Flowers', 'Bouquets designed to celebrate birthdays.', NULL, NOW(), NOW());
INSERT INTO categories (name, description, image_path, created_at, updated_at) VALUES ('Wedding Flowers', 'Elegant arrangements for weddings and engagements.', NULL, NOW(), NOW());
INSERT INTO categories (name, description, image_path, created_at, updated_at) VALUES ('Congratulation Flowers', 'Flowers to celebrate achievements and promotions.', NULL, NOW(), NOW());
INSERT INTO categories (name, description, image_path, created_at, updated_at) VALUES ('Sympathy Flowers', 'Calm, respectful arrangements for funerals and condolences.', NULL, NOW(), NOW());
INSERT INTO categories (name, description, image_path, created_at, updated_at) VALUES ('Romantic Flowers', 'Lovely bouquets perfect for anniversaries and Valentine''s Day.', NULL, NOW(), NOW());