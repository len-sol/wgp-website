-- Add role and active columns to login table
ALTER TABLE `login`
ADD COLUMN `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user' AFTER `password`,
ADD COLUMN `active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `role`;

-- Update existing admin user
UPDATE `login` SET `role` = 'admin' WHERE `username` = 'admin';

-- Add timestamp to log table
ALTER TABLE `log`
ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `activity`;

-- Add foreign key constraint to log table
ALTER TABLE `log`
ADD CONSTRAINT `fk_log_username`
FOREIGN KEY (`username`) REFERENCES `login`(`username`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Add delete_by column to warranty table
ALTER TABLE `warranty`
ADD COLUMN `delete_by` varchar(100) NULL AFTER `delete`,
ADD CONSTRAINT `fk_warranty_delete_by`
FOREIGN KEY (`delete_by`) REFERENCES `login`(`username`)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- Add deleted_at timestamp to warranty table
ALTER TABLE `warranty`
ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `delete_by`;
