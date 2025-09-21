CREATE DATABASE IF NOT EXISTS lupyanatech 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE lupyanatech;

-- Packages
CREATE TABLE IF NOT EXISTS packages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  duration ENUM('3_weeks','1_month','6_months','12_months','custom') NOT NULL,
  gb_amount DECIMAL(6,2) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT DEFAULT NULL,
  allow_custom_gb TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders
CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  package_id INT UNSIGNED DEFAULT NULL, -- allow NULL for ON DELETE SET NULL
  package_name VARCHAR(255) NOT NULL,
  phone VARCHAR(32) NOT NULL,
  network VARCHAR(50) DEFAULT NULL,
  custom_gb DECIMAL(6,2) DEFAULT NULL,
  amount_paid DECIMAL(10,2) DEFAULT NULL,
  payment_screenshot_url VARCHAR(1024) NOT NULL,
  status ENUM('pending','awaiting_confirmation','confirmed','delivered','cancelled') 
         NOT NULL DEFAULT 'pending',
  notes TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins
CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(150) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Uploads audit (optional)
CREATE TABLE IF NOT EXISTS uploads (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED DEFAULT NULL,
  filename VARCHAR(255) DEFAULT NULL,
  remote_url VARCHAR(1024) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed sample packages
INSERT INTO packages (name, duration, gb_amount, price, description, allow_custom_gb) VALUES
('Starter - 3 weeks', '3_weeks', 2, 1000.00, '2GB, valid 3 weeks', 0),
('Basic - 1 month', '1_month', 5, 2000.00, '5GB, valid 1 month', 0),
('Standard - 6 months', '6_months', 30, 9000.00, '30GB, valid 6 months', 0),
('Pro - 12 months', '12_months', 60, 16000.00, '60GB, valid 12 months', 0);
