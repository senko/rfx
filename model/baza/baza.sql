DROP TABLE topic IF EXISTS;
CREATE TABLE topic (
    topic_id INT AUTO_INCREMENT PRIMARY KEY,
    
    uri VARCHAR(255) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    password VARCHAR(255),
    created INT, -- seconds since epoch
    mode ENUM ('private', 'moderated', 'public') DEFAULT 'public'),
    
    INDEX (uri)
) DEFAULT CHARACTER SET=utf8;

DROP TABLE post IF EXISTS;
CREATE TABLE post (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id VARCHAR(255) NOT NULL,
    text VARCHAR(8192),
    posted INT, -- seconds since epoch
    score INT DEFAULT 0,
    FOREIGN KEY (topic_id) REFERENCES topic(id),
    KEY (score)
) DEFAULT CHARACTER SET=utf8;

DROP TABLE visitor IF EXISTS;
CREATE TABLE visitor (
    visitor_id INT AUTO_INCREMENT PRIMARY KEY,
    cookie CHAR(32),
    last_topic_id INT,
    
    FOREIGN KEY (last_topic_id) REFERENCES topic(id)
) DEFAULT CHARACTER SET=utf8;

INSERT INTO topic (topic_id, uri, title, pasword, created, mode)
    VALUES (0, '', 'Welcome to Speeka! Write anything you want...',
        NULL, UNIX_TIMESTAMP(NOW()), 'public');

