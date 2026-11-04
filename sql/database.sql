-- COVID-19 Database Schema
-- Software Testing & QA Mini Project

CREATE DATABASE IF NOT EXISTS covid19_db;
USE covid19_db;

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    full_name  VARCHAR(100) NOT NULL,
    phone      VARCHAR(15),
    role       ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS registrations (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    full_name    VARCHAR(100) NOT NULL,
    email        VARCHAR(100) NOT NULL,
    phone        VARCHAR(15)  NOT NULL,
    dob          DATE         NOT NULL,
    address      TEXT         NOT NULL,
    city         VARCHAR(50)  NOT NULL,
    state        VARCHAR(50)  NOT NULL,
    pincode      VARCHAR(10)  NOT NULL,
    vaccine_type ENUM('Covishield','Covaxin','Sputnik V','Johnson & Johnson') NOT NULL,
    slot_date    DATE         NOT NULL,
    slot_time    ENUM('09:00 AM','11:00 AM','02:00 PM','04:00 PM') NOT NULL,
    status       ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS comments (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT  NOT NULL,
    comment    TEXT NOT NULL,
    category   ENUM('general','prevention','symptoms','vaccine','treatment') DEFAULT 'general',
    likes      INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS covid_stats (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    country      VARCHAR(100) NOT NULL,
    total_cases  BIGINT DEFAULT 0,
    active_cases BIGINT DEFAULT 0,
    recovered    BIGINT DEFAULT 0,
    deaths       BIGINT DEFAULT 0,
    vaccinated   BIGINT DEFAULT 0,
    stat_date    DATE NOT NULL,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seed: Admin user (password = Admin@123)
INSERT IGNORE INTO users (username, email, password, full_name, role) VALUES
('admin','admin@covid19info.com','$2y$10$KIHsOGMTycLJzRKavd3xT.G7JAEPx.1k1T/k4xU35tAdnPgbmyGIW','Admin User','admin');

-- Seed: COVID Stats
INSERT IGNORE INTO covid_stats (country,total_cases,active_cases,recovered,deaths,vaccinated,stat_date) VALUES
('India', 44690764, 91234,  44240000,  530512, 2200000000, CURDATE()),
('USA',  103436829, 1200000,100836829, 1127354,  680000000, CURDATE()),
('World',700000000, 5000000,672000000, 6958163,13500000000, CURDATE());
