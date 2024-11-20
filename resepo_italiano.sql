CREATE DATABASE resepo_italiano;

USE resepo_italiano;

CREATE TABLE users
(
    user_id       int auto_increment PRIMARY KEY,
    username      varchar(100)        not null,
    email         varchar(250) unique not null,
    password      varchar(250)        not null,
    profile_image varchar(250)        null
);

CREATE TABLE sessions
(
    session_id VARCHAR(255) PRIMARY KEY,
    user_id    int NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE
);

CREATE TABLE categories
(
    category_id   INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

INSERT INTO categories (categories.category_name)
VALUES ('Pizza'),
       ('Pasta'),
       ('Risotto'),
       ('Gelato'),
       ('Tiramisu'),
       ('Burrata'),
       ('Bruschetta');

CREATE TABLE recipes
(
    recipe_id   INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(200) NOT NULL,
    ingredients TEXT         NOT NULL,
    steps       TEXT         NOT NULL,
    note        TEXT         NULL,
    image       VARCHAR(100) NULL,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    user_id     INT          NOT NULL,
    category_id INT          NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories (category_id)
);

-- CREATE TABLE recipe_images
-- (
--     image_id   INT AUTO_INCREMENT PRIMARY KEY,
--     recipe_id  INT          NOT NULL,
--     image_name VARCHAR(100) NOT NULL,
--     FOREIGN KEY (recipe_id) REFERENCES recipes (recipe_id) ON DELETE CASCADE
-- );


CREATE TABLE saved_recipes
(
    saved_id  INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    user_id   INT NOT NULL,

    FOREIGN KEY (recipe_id) REFERENCES recipes (recipe_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

-- comment recepies
-- rating recepies