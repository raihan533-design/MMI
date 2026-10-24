-- kiuixshop database schema
-- Import this file in phpMyAdmin or: mysql -u root -p < database.sql

CREATE DATABASE IF NOT EXISTS kiuixshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kiuixshop;

-- Admin users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Product categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    icon VARCHAR(100) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT DEFAULT NULL,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact form messages
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FAQs
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Site settings (editable from Admin > Settings)
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('company_name', 'kiuixshop'),
('hero_tagline', 'Your trusted online shop'),
('hero_title', 'Shop Smart. Live Better.'),
('hero_subtitle', 'Quality products at honest prices, delivered to your door. Browse the collection and order in minutes.'),
('about_content', 'kiuixshop is an online shop built around one idea: good products, honest prices and friendly service.\n\nWe pick every item with care and keep ordering simple. Choose a product, tap Order Now, and confirm the details with us on WhatsApp.\n\nThank you for shopping with kiuixshop.'),
('company_email', ''),
('company_phone', ''),
('company_address', ''),
('whatsapp_number', ''),
('working_hours', '9:00 AM - 9:00 PM'),
('currency_symbol', '৳'),
('facebook_url', ''),
('instagram_url', ''),
('tiktok_url', ''),
('youtube_url', '');

INSERT IGNORE INTO categories (name, slug, icon, sort_order) VALUES
('Electronics', 'electronics', 'fas fa-headphones', 1),
('Fashion', 'fashion', 'fas fa-shirt', 2),
('Home & Living', 'home-living', 'fas fa-couch', 3),
('Beauty', 'beauty', 'fas fa-spa', 4),
('Accessories', 'accessories', 'fas fa-clock', 5),
('Gifts', 'gifts', 'fas fa-gift', 6);

-- Sample products (delete or edit them from Admin > Products)
INSERT INTO products (category_id, name, price, description, sort_order)
SELECT c.id, p.name, p.price, p.description, 0 FROM (
    SELECT 'electronics' AS slug, 'Wireless Earbuds' AS name, 1490.00 AS price, 'Comfortable wireless earbuds with clear sound and a charging case.' AS description
    UNION ALL SELECT 'fashion', 'Classic Cotton T-Shirt', 650.00, 'Soft everyday t-shirt in a relaxed fit.'
    UNION ALL SELECT 'home-living', 'Ceramic Coffee Mug', 380.00, 'A sturdy mug for your morning coffee or tea.'
    UNION ALL SELECT 'accessories', 'Minimal Wrist Watch', 1890.00, 'Clean, simple watch that goes with everything.'
) p JOIN categories c ON c.slug = p.slug
WHERE NOT EXISTS (SELECT 1 FROM products);

INSERT INTO faqs (question, answer, sort_order)
SELECT q, a, s FROM (
    SELECT 'How do I place an order?' AS q, 'Open the Products page, choose what you like and tap Order Now. This opens WhatsApp with your order details, and we confirm everything with you there.' AS a, 1 AS s
    UNION ALL SELECT 'How long does delivery take?', 'Delivery time depends on your location. We will tell you the estimated time when we confirm your order.', 2
    UNION ALL SELECT 'What payment methods do you accept?', 'We confirm payment options with you on WhatsApp when you place your order.', 3
    UNION ALL SELECT 'Can I return or exchange a product?', 'If something is wrong with your order, contact us as soon as you receive it and we will help you sort it out.', 4
    UNION ALL SELECT 'How can I contact you?', 'Use the contact form, or message us on WhatsApp using the green button on every page.', 5
) f WHERE NOT EXISTS (SELECT 1 FROM faqs);

-- Default admin login: username "admin", password "admin123"  -> CHANGE IT after first login (Admin > Settings)
INSERT IGNORE INTO users (username, email, password) VALUES
('admin', '', '$2y$10$EkaIlo7f3kfntZP/OoCb5.nrygKww2WJtHBG8sNYF2OJ6AZAlOntq');
