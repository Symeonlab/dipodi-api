-- ============================================
-- Dipoddi API — MySQL Initialization Script
-- ============================================
-- This runs on first container startup only.
-- Creates the database, user, and sets permissions.
-- For Sliplane: set MYSQL_DATABASE, MYSQL_USER,
-- MYSQL_PASSWORD as env vars on the MySQL service.
-- ============================================

-- Create database if not exists (redundant with MYSQL_DATABASE env, but safe)
CREATE DATABASE IF NOT EXISTS `dipodi_api`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Grant privileges to the dipodi user on the database
-- (User is auto-created by MYSQL_USER env var)
GRANT ALL PRIVILEGES ON `dipodi_api`.* TO 'dipodi'@'%';
FLUSH PRIVILEGES;

-- Security: remove test database if it exists
DROP DATABASE IF EXISTS `test`;

-- Security: remove anonymous users
DELETE FROM mysql.user WHERE User='';
FLUSH PRIVILEGES;
