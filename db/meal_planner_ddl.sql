-- Select the database to use
USE meal_planner;

-- Create the user table
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    username VARCHAR(255),
    password_hash VARCHAR(255),
    CONSTRAINT unique_username UNIQUE (username)
);

-- Create the weekly_menu table
CREATE TABLE weekly_menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    week_start_date DATE NOT NULL,
    day_name ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
    breakfast VARCHAR(255),
    lunch VARCHAR(255),
    dinner VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);


