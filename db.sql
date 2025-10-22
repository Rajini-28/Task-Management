-- Run this in MySQL (phpMyAdmin, CLI, etc.)
CREATE DATABASE IF NOT EXISTS `taskapp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `taskapp`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `priority` ENUM('Low','Medium','High') DEFAULT 'Medium',
  `deadline` DATE DEFAULT NULL,
  `progress` TINYINT UNSIGNED DEFAULT 0,
  `status` ENUM('pending','in-progress','done') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`user_id`),
  CONSTRAINT `fk_tasks_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;
