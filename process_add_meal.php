<?php include 'db/db_connections.php'; ?>
<?php
    session_start();

    // Check if the user is logged in and redirect them to the log in page in not.
    if (!isset($_SESSION['user_id'])) {
        // Redirect the user to the login page
        header("Location: ./pages/login.php");
        exit;
    }
    // Check if the HTTP request is a POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Retrieve the username
        $user_id = $_SESSION['user_id'];

        // Get week start date and meals from POST
        $week_start_date = $_POST['week_start_date'];
        $meals = $_POST['meals'];

        // Connect to the database
        $conn = getDatabaseConnection();

        // Begin a transaction to ensure atomicity
        $conn->begin_transaction();

        try {
            // Check if any meals already exist for the given week
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM weekly_menu WHERE user_id = ? AND week_start_date = ?");
            $stmt->bind_param("is", $user_id, $week_start_date);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                // If meals already exist, redirect to the edit page or throw an error
                $_SESSION['error'] = "A meal plan already exists for the week starting on $week_start_date. Please edit it instead.";
                header("Location: ./pages/edit_meal.php?week_start_date=" . urlencode($week_start_date));
                exit;
            }

            // If no meals exist, proceed with the insert
            $stmt = $conn->prepare("INSERT INTO weekly_menu (user_id, week_start_date, day_name, breakfast, lunch, dinner) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($meals as $day => $meal_data) {
                // Skip if no meals are provided for this day
                if (empty($meal_data['breakfast']) && empty($meal_data['lunch']) && empty($meal_data['dinner'])) {
                    continue;
                }
                $stmt->bind_param("isssss", $user_id, $week_start_date, $day, $meal_data['breakfast'], $meal_data['lunch'], $meal_data['dinner']);
                $stmt->execute();
            }

            // Commit the transaction
            $conn->commit();

            $_SESSION['success'] = "Weekly plan saved successfully!";
            header("Location: ./pages/home.php");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Error saving weekly plan: " . $e->getMessage();
            header("Location: ../pages/add_meal.php");
            exit;
        } finally {
            // Close the statement and connection
            $stmt->close();
            $conn->close();
        }
    }
?>