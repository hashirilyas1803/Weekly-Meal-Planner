<?php
function getDatabaseConnection() {
    // Set the connection string to the MySQL database
    $host = 'localhost';
    $user = 'admin1';
    $password = 'Password123';
    $dbname = 'meal_planner';
    
    // Attempt to connect to the database
    $conn = new mysqli($host, $user, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Return the connection object
    return $conn;
}
?>