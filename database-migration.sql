-- Migration: Add categories table and update services

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    icon VARCHAR(100) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add category_id column if it doesn't exist
SET @dbname = DATABASE();
SET @exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'services' AND COLUMN_NAME = 'category_id');
SET @sql = IF(@exists = 0, 'ALTER TABLE services ADD COLUMN category_id INT DEFAULT NULL AFTER id', 'SELECT "category_id already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add sort_order column if it doesn't exist
SET @exists2 = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'services' AND COLUMN_NAME = 'sort_order');
SET @sql2 = IF(@exists2 = 0, 'ALTER TABLE services ADD COLUMN sort_order INT DEFAULT 0 AFTER image', 'SELECT "sort_order already exists"');
PREPARE stmt2 FROM @sql2;
EXECUTE stmt2;
DEALLOCATE PREPARE stmt2;

-- Add foreign key if it doesn't exist
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = @dbname AND TABLE_NAME = 'services' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME LIKE '%category_id%');
SET @sql3 = IF(@fk_exists = 0, 'ALTER TABLE services ADD FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL', 'SELECT "FK already exists"');
PREPARE stmt3 FROM @sql3;
EXECUTE stmt3;
DEALLOCATE PREPARE stmt3;

-- Insert default categories (ignore if already exist)
INSERT IGNORE INTO categories (name, slug, icon, sort_order) VALUES
('Plumbing Services', 'plumbing-services', 'fas fa-wrench', 1),
('Electrical Services', 'electrical-services', 'fas fa-bolt', 2),
('AC & Appliance Repair', 'ac-appliance-repair', 'fas fa-snowflake', 3),
('Painting Services', 'painting-services', 'fas fa-paint-roller', 4),
('Cleaning Services', 'cleaning-services', 'fas fa-broom', 5),
('Home Maintenance', 'home-maintenance', 'fas fa-tools', 6);
