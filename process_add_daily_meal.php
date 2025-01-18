<?php include 'db/db_connections.php'; ?>
<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect the user to the login page
        header("Location: ./pages/login.php");
        exit;
    }

    // Check if the HTTP request is a POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve the user id 
        $user_id = $_SESSION['user_id'];

        // Retreive the data from the form
        $week_start_date = $_POST['week_start_date'];
        $day_name = $_POST['day_name'];
        $breakfast = $_POST['breakfast'];
        $lunch = $_POST['lunch'];
        $dinner = $_POST['dinner'];

        // Get the database connection
        $conn = getDatabaseConnection();

        try {
            // Insert the meal into the database
            $stmt = $conn->prepare('INSERT INTO weekly_menu (week_start_date, day_name, user_id, breakfast, lunch, dinner) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('ssisss', $week_start_date, $day_name, $user_id, $breakfast, $lunch, $dinner);
            $stmt->execute();
            $result = $stmt->get_result();

            $_SESSION['success'] = "Weekly plan saved successfully!";
            header("Location: ./pages/home.php");
        } catch (Exception $e) {
            $_SESSION['error'] = "Error saving weekly plan: " . $e->getMessage();
            header("Location: ./pages/add_daily_meal.php?day_name=$day_name&week_start_date=$week_start_date");
            exit;
        } finally {
            // Close the statement and connection
            $stmt->close();
            $conn->close();
        }

    }
?>