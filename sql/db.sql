CREATE DATABASE graduation_management;

USE graduation_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('student', 'admin',) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    degree ENUM('бакалавър', 'магистър', 'докторант') NOT NULL,
    graduation_year YEAR NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

