<?php include '../db/db_connections.php'; ?>
<?php
    include '../includes/header.php';
    session_start();

    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit;
    }

    // Retrieve week_start_date and user_id from session or URL
    $week_start_date = $_GET['week_start_date'] ?? null;
    $user_id = $_SESSION['user_id'];

    if (!$week_start_date) {
        $_SESSION['error'] = "No week selected for editing!";
        header("Location: home.php?");
        exit;
    }

    // Declare meals array
    $meals = [];

    // Connect to the database
    $conn = getDatabaseConnection();

    // Prepare SQL Query to retrieve all meals for the week
    $query = $conn->prepare("
        SELECT day_name, breakfast, lunch, dinner 
        FROM weekly_menu 
        WHERE user_id = ? AND week_start_date = ?
        ORDER BY FIELD(day_name, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')
    ");
    $query->bind_param("is", $user_id, $week_start_date);
    
    // Execute the query and fetch the result
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $meals[$row['day_name']] = [
                'breakfast' => $row['breakfast'],
                'lunch' => $row['lunch'],
                'dinner' => $row['dinner']
            ];
        }
    } else {
        $_SESSION['error'] = "No meal plan found for the selected week.";
        header("Location: home.php");
        exit;
    }
?>

<h2>Edit Weekly Meal Plan</h2>
<form method="POST" action="../process_edit_meal.php">
    <input type="hidden" name="week_start_date" value="<?= htmlspecialchars($week_start_date) ?>">

    <label for="week_start_date">Week Starting Date:</label>
    <input type="date" id="week_start_date" name="week_start_date_display" value="<?= htmlspecialchars($week_start_date) ?>" disabled><br><br>

    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Dinner</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach ($days as $day) {
                $breakfast = $meals[$day]['breakfast'] ?? '';
                $lunch = $meals[$day]['lunch'] ?? '';
                $dinner = $meals[$day]['dinner'] ?? '';

                echo "
                <tr>
                    <td>$day</td>
                    <td><input type='text' name='meals[$day][breakfast]' value='" . htmlspecialchars($breakfast) . "' placeholder='Breakfast'></td>
                    <td><input type='text' name='meals[$day][lunch]' value='" . htmlspecialchars($lunch) . "' placeholder='Lunch'></td>
                    <td><input type='text' name='meals[$day][dinner]' value='" . htmlspecialchars($dinner) . "' placeholder='Dinner'></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
    <button type="submit">Update Weekly Plan</button>
</form>
<?php include '../includes/footer.php'; ?>