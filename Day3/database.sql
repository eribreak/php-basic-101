CREATE DATABASE IF NOT EXISTS todo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE todo_db;

CREATE TABLE IF NOT EXISTS todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL, description TEXT,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE todos ADD COLUMN category_id INT NULL AFTER status;
ALTER TABLE todos ADD FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;
ALTER TABLE todos ADD INDEX idx_category_id (category_id);

INSERT INTO categories (name) VALUES 
    ('Công việc'),
    ('Cá nhân'),
    ('Học tập');

INSERT INTO todos (title, description, status, category_id) VALUES 
    ('Học PHP', 'Nắm vững cách kết nối MySQL bằng PDO', 'pending', 1),
    ('Day 3', 'Hoàn thành CRUD todolist', 'pending', 3),
    ('Mua sắm', 'Mua đồ dùng cá nhân', 'pending', 2);

