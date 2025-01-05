CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(64),
    reset_token_expiry DATETIME,
    login_attempts INT DEFAULT 0,
    last_login_attempt DATETIME
);