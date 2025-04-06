CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'coordinator', 'manager', 'admin') NOT NULL,
    faculty_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE faculties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    faculty_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    word_file VARCHAR(255) NOT NULL, -- path to Word file
    image_file VARCHAR(255) DEFAULT NULL, -- path to image file
    agreed_terms BOOLEAN NOT NULL DEFAULT FALSE,
    is_selected BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (faculty_id) REFERENCES faculties(id)
);
CREATE TABLE article_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    coordinator_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    FOREIGN KEY (coordinator_id) REFERENCES users(id)
);
CREATE TABLE maganize_submission_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    academic_year VARCHAR(10) NOT NULL,
    submission_deadline DATE NOT NULL,
    final_closure_deadline DATE NOT NULL
);

CREATE TABLE email_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    recipient_email VARCHAR(100) NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id)
);

