-- Select the database to use
USE meal_planner;

-- Create the table to store the weekly meal plan
CREATE TABLE weekly_menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    start_date DATE NOT NULL,
    day_name VARCHAR(10) NOT NULL,
    meal_type VARCHAR(10) NOT NULL,
    menu_item VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    CONSTRAINT chk_day CHECK (day IN ('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')),
    CONSTRAINT chk_meal_type CHECK (meal_type IN ('Breakfast', 'Lunch', 'Dinner'))
);

-- Create the user table
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    username VARCHAR(255),
    password_hash VARCHAR(255)
);

