-- Backup existing data
CREATE TABLE IF NOT EXISTS `admin_activity_logs_backup` AS SELECT * FROM `admin_activity_logs`;

-- Drop the existing table
DROP TABLE IF EXISTS `admin_activity_logs`;

-- Recreate the table with compatible structure
CREATE TABLE `admin_activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Restore the data
INSERT INTO `admin_activity_logs` SELECT * FROM `admin_activity_logs_backup`;

-- Drop the backup table
DROP TABLE IF EXISTS `admin_activity_logs_backup`;

-- Reset auto_increment using a direct value
-- Note: Replace 17 with your actual last ID number plus 1
ALTER TABLE `admin_activity_logs` AUTO_INCREMENT = 17; 