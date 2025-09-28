CREATE DATABASE IF NOT EXISTS lupyanatech
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE lupyanatech;

-- Users
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  full_name VARCHAR(255) NOT NULL,
  phone VARCHAR(15) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Packages
CREATE TABLE IF NOT EXISTS packages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  duration VARCHAR(50) DEFAULT NULL,
  gb_amount DECIMAL(10,2) DEFAULT 0,
  price DECIMAL(10,2) DEFAULT 0,
  description TEXT DEFAULT NULL,
  allow_custom_gb TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins
CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders
CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED DEFAULT NULL,
  package_id INT UNSIGNED DEFAULT NULL,
  quantity INT DEFAULT 1,
  amount_paid DECIMAL(10,2) DEFAULT 0,
  status VARCHAR(50) DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Uploads audit
CREATE TABLE IF NOT EXISTS uploads (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED DEFAULT NULL,
  filename VARCHAR(255) DEFAULT NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed sample packages (if not exists)
INSERT INTO packages (name, duration, gb_amount, price, description, allow_custom_gb)
SELECT * FROM (SELECT 'Starter - 3 weeks' AS name, '3_weeks' AS duration, 2 AS gb_amount, 1000.00 AS price, '2GB, valid 3 weeks' AS description, 0 AS allow_custom_gb) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM packages WHERE name = 'Starter - 3 weeks') LIMIT 1;

INSERT INTO packages (name, duration, gb_amount, price, description, allow_custom_gb)
SELECT * FROM (SELECT 'Basic - 1 month' AS name, '1_month' AS duration, 5 AS gb_amount, 2000.00 AS price, '5GB, valid 1 month' AS description, 0 AS allow_custom_gb) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM packages WHERE name = 'Basic - 1 month') LIMIT 1;

INSERT INTO packages (name, duration, gb_amount, price, description, allow_custom_gb)
SELECT * FROM (SELECT 'Standard - 6 months' AS name, '6_months' AS duration, 30 AS gb_amount, 9000.00 AS price, '30GB, valid 6 months' AS description, 0 AS allow_custom_gb) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM packages WHERE name = 'Standard - 6 months') LIMIT 1;

INSERT INTO packages (name, duration, gb_amount, price, description, allow_custom_gb)
SELECT * FROM (SELECT 'Pro - 12 months' AS name, '12_months' AS duration, 60 AS gb_amount, 16000.00 AS price, '60GB, valid 12 months' AS description, 0 AS allow_custom_gb) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM packages WHERE name = 'Pro - 12 months') LIMIT 1;

-- End of schema
