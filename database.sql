-- ==========================================================
-- FitZone Gym - Database Schema & Starter Records
-- Demonstrates MySQL concepts from Module 4:
-- CREATE TABLE, INSERT INTO, PRIMARY KEY, AUTO_INCREMENT
-- ==========================================================

-- 1. Create database if it does not already exist
CREATE DATABASE IF NOT EXISTS `fitzone_db`;
USE `fitzone_db`;

-- 2. Drop existing tables if re-importing (clean setup)
DROP TABLE IF EXISTS `members`;
DROP TABLE IF EXISTS `workouts`;

-- 3. Table: members (Stores registrations from the Join FitZone form)
-- Module 4: CREATE TABLE with AUTO_INCREMENT and PRIMARY KEY
CREATE TABLE `members` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(25) NOT NULL,
    `start_date` DATE NOT NULL,
    `plan` VARCHAR(50) NOT NULL,
    `workout_pref` VARCHAR(50) DEFAULT 'Strength Training',
    `goal` VARCHAR(150) DEFAULT 'General Fitness',
    `experience` INT DEFAULT 5,
    `fee` DECIMAL(10, 2) NOT NULL DEFAULT 999.00,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Active',
    `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Table: workouts (Stores dynamic workout catalog)
CREATE TABLE `workouts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `description` TEXT NOT NULL,
    `exercises` TEXT NOT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Module 4: INSERT INTO sample records
INSERT INTO `members` (`fullname`, `email`, `phone`, `start_date`, `plan`, `workout_pref`, `goal`, `experience`, `fee`, `status`) VALUES
('John Doe', 'john.doe@example.com', '9876543210', '2026-11-01', 'Pro', 'Strength Training', 'Build Muscle & Strength', 6, 2499.00, 'Active'),
('Sarah Connor', 'sarah.c@example.com', '9812345678', '2026-11-05', 'Elite', 'Cardio Fitness', 'Athletic Conditioning', 8, 4499.00, 'Active'),
('Alex Miller', 'alex.m@example.com', '9701122334', '2026-11-10', 'Basic', 'Muscle Building', 'Weight Loss & Endurance', 3, 999.00, 'Active');

INSERT INTO `workouts` (`title`, `category`, `description`, `exercises`, `image_url`) VALUES
('Strength Training', 'Strength', 'Build strength with compound movements and progressive overload training.', 'Squats,Bench Press,Barbell Rows,Overhead Press', 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=900&q=80'),
('Cardio Fitness', 'Cardio', 'Improve stamina, endurance and cardiovascular health with high-energy sessions.', 'Warm up,HIIT Intervals,Treadmill Run,Cool down', 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=900&q=80'),
('Muscle Building', 'Hypertrophy', 'A balanced routine combining resistance training, targeted isolation and recovery.', 'Push exercises,Pull exercises,Leg exercises,Core finish', 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=80');

