-- User Registration System Database Schema
-- Database: user_registration_db

CREATE DATABASE IF NOT EXISTS user_registration_db;
USE user_registration_db;

-- Roles table
CREATE TABLE IF NOT EXISTS roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default roles
INSERT INTO roles (role_name) VALUES 
    ('Client'),
    ('Volunteer'),
    ('Staff'),
    ('SysAdmin');

-- Security questions table
CREATE TABLE IF NOT EXISTS security_questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    question_text VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default security questions
INSERT INTO security_questions (question_text) VALUES 
    ('What was the name of your first pet?'),
    ('What city were you born in?'),
    ('What is your mother\'s maiden name?'),
    ('What was the name of your elementary school?'),
    ('What is your favorite color?');

-- Users table
CREATE TABLE IF NOT EXISTS users (
    user_id VARCHAR(20) PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    security_question_id INT NOT NULL,
    security_answer_hash VARCHAR(255) NOT NULL,
    role_id INT NOT NULL DEFAULT 1,
    consent_share_info BOOLEAN NOT NULL DEFAULT FALSE,
    consent_terms_service BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    FOREIGN KEY (security_question_id) REFERENCES security_questions(question_id),
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- Index for faster lookups during recovery
CREATE INDEX idx_user_lookup ON users(first_name, last_name, dob);
