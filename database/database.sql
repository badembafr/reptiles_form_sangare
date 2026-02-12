CREATE DATABASE IF NOT EXISTS les_reptiles 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE les_reptiles;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME NULL,
    login_attempts TINYINT UNSIGNED DEFAULT 0,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Compte test : christophe01 / n1SD1cQIzUFnWy*M3%Y0
INSERT INTO users (username, password) VALUES 
('christophe01', '$2y$10$P.v5h2uGP1bsOHlybXBGo.qKRgRGRanzXYfhIdmDIPcXf.NXDAEDu');