-- Migration: Align chat schema with application models
-- This script creates `chats` and ensures `messages.chat_id` exists and is populated.

START TRANSACTION;

-- 1) Create `chats` table if it does not exist
CREATE TABLE IF NOT EXISTS `chats` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user1_id` INT NOT NULL,
  `user2_id` INT NOT NULL,
  `product_id` INT NULL,
  `last_message` TEXT NULL,
  `last_message_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user1_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user2_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL,
  INDEX `idx_users` (`user1_id`, `user2_id`),
  INDEX `idx_product` (`product_id`),
  INDEX `idx_last_message` (`last_message_at`)
);

-- 2) If an old `conversations` table exists, copy rows into `chats` (keeping the same IDs)
--    so that existing messages referencing conversation_id can be mapped to chat_id.
INSERT INTO `chats` (`id`, `user1_id`, `user2_id`, `product_id`, `last_message`, `last_message_at`, `created_at`, `updated_at`)
SELECT c.`id`, c.`user1_id`, c.`user2_id`, c.`product_id`, NULL, NULL, c.`created_at`, c.`updated_at`
FROM `conversations` c
LEFT JOIN `chats` ch ON ch.`id` = c.`id`
WHERE ch.`id` IS NULL;

-- 3) Ensure `messages.chat_id` column exists
ALTER TABLE `messages` ADD COLUMN IF NOT EXISTS `chat_id` INT NULL;

-- 4) Populate `chat_id` from legacy `conversation_id` if present
UPDATE `messages` m
JOIN `conversations` c ON m.`conversation_id` = c.`id`
SET m.`chat_id` = c.`id`
WHERE m.`chat_id` IS NULL;

-- 5) Add index for `messages.chat_id` if missing
-- Some MySQL versions don't support IF NOT EXISTS for indexes; this may fail harmlessly if it already exists
ALTER TABLE `messages` ADD INDEX `idx_chat` (`chat_id`);

COMMIT;

-- Note: After migration, you may optionally drop legacy columns/tables:
-- ALTER TABLE `messages` DROP COLUMN `conversation_id`;
-- DROP TABLE `conversations`;


