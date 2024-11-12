CREATE DATABASE resepo_italiano;

USE resepo_italiano;

CREATE TABLE users
(
    user_id  int auto_increment PRIMARY KEY,
    username varchar(100)        not null,
    email    varchar(250) unique not null,
    password varchar(250)        not null,
    profile  varchar(250)        null
);

CREATE TABLE sessions
(
    session_id VARCHAR(255) PRIMARY KEY,
    user_id    int NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE
);