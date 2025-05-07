-- This script prepares the database for export with compatible settings
-- Run this in your current database before exporting

-- Create backups of existing data
CREATE TABLE IF NOT EXISTS admin_activity_logs_backup AS SELECT * FROM admin_activity_logs;
CREATE TABLE IF NOT EXISTS admin_login_logs_backup AS SELECT * FROM admin_login_logs;
CREATE TABLE IF NOT EXISTS admin_users_backup AS SELECT * FROM admin_users;
CREATE TABLE IF NOT EXISTS blog_posts_backup AS SELECT * FROM blog_posts;

-- Set character set for the session
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Instructions for export:
-- 1. After running this script, use this command to export:
--    mysqldump --default-character-set=utf8mb4 -u username -p database_name > database_export.sql
-- 
-- 2. Edit the SQL file with a text editor to replace all instances of:
--    utf8mb4_0900_ai_ci with utf8mb4_general_ci
--
-- 3. Import into cPanel using phpMyAdmin with utf8mb4 character set

-- Alternative: After importing complete_fix.sql, export the database with:
-- mysqldump --default-character-set=utf8mb4 -u username -p database_name > fixed_database.sql 