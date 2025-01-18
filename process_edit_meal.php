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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
        $conn->begin_transaction();
    
        foreach ($meals as $day => $meal_data) {
            // Check if an entry already exists for this day
            $check_stmt = $conn->prepare("
                SELECT id 
                FROM weekly_menu 
                WHERE user_id = ? AND week_start_date = ? AND day_name = ?
            ");
            $check_stmt->bind_param("iss", $user_id, $week_start_date, $day);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
    
            if ($result->num_rows > 0) {
                // Entry exists, update it
                $update_stmt = $conn->prepare("
                    UPDATE weekly_menu 
                    SET breakfast = ?, lunch = ?, dinner = ?
                    WHERE user_id = ? AND week_start_date = ? AND day_name = ?
                ");
                $update_stmt->bind_param(
                    "ssssss",
                    $meal_data['breakfast'],
                    $meal_data['lunch'],
                    $meal_data['dinner'],
                    $user_id,
                    $week_start_date,
                    $day
                );
                $update_stmt->execute();
                $update_stmt->close();
            } else if (empty($meal_data['breakfast']) && empty($meal_data['lunch']) && empty($meal_data['dinner'])) {
                continue;
            }else {
                // Entry does not exist, insert it
                $insert_stmt = $conn->prepare("
                    INSERT INTO weekly_menu (user_id, week_start_date, day_name, breakfast, lunch, dinner)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $insert_stmt->bind_param(
                    "isssss",
                    $user_id,
                    $week_start_date,
                    $day,
                    $meal_data['breakfast'],
                    $meal_data['lunch'],
                    $meal_data['dinner']
                );
                $insert_stmt->execute();
                $insert_stmt->close();
            }
    
            $check_stmt->close();
        }
    
        $conn->commit();
        $_SESSION['success'] = "Weekly plan updated successfully!";
        header("Location: ./pages/home.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error saving weekly plan: " . $e->getMessage();
        header("Location: ./pages/edit_meal.php");
        exit;
    } finally {
        $conn->close();
    }
}
?>