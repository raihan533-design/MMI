-- MMI ENGINEERING Database Schema
-- Run this SQL in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS mmi_engineering;
USE mmi_engineering;

-- Admin users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact messages table
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Website settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('hero_title', 'MMI ENGINEERING'),
('hero_subtitle', 'Your trusted partner in precision engineering solutions. We deliver quality, innovation, and excellence across every project.'),
('about_title', 'About Us'),
('about_content', 'MMI ENGINEERING is a trusted bus service provider with 17+ years of experience in Singapore. We deliver safe, comfortable, and punctual bus services at the most competitive prices in the market.\n\nWe focus on professionalism, reliability, and customer satisfaction. With 65+ trusted customers, we have built our reputation on honesty and quality service. From daily commuting to special event transport, we ensure every trip is smooth and hassle-free.\n\nYour safety and comfort are our top priority. Travel with confidence - Travel with MMI ENGINEERING.'),
('company_name', 'MMI ENGINEERING'),
('company_email', 'mmiengpte@gmail.com'),
('company_phone', '+65 8587 8190'),
('company_address', 'Singapore'),
('whatsapp_number', '+6585878190');

-- Insert default admin (username: admin, password: admin123)
INSERT INTO users (username, email, password) VALUES
('admin', 'mmiengpte@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
