<?php include '../db/db_connections.php'; ?>
<?php
    include '../includes/header.php';
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    // Check if 'day_name' parameter is provided in the URL
    if (isset($_GET['day_name'])) {
        $day_name = $_GET['day_name'];
    } else {
        echo "No Day name provided.";
        exit;
    }

    // Check if 'week_start_date' parameter is provided in the URL
    if (isset($_GET['week_start_date'])) {
        $week_start_date = $_GET['week_start_date'];
    } else {
        echo "No Week start date provided.";
        exit;
    }
?>
    <h1>Add Meal Plan</h1>

    <!-- Display the day and week information -->
    <p><strong>Week Starting Date:</strong> <?= htmlspecialchars($week_start_date) ?></p>
    <p><strong>Day:</strong> <?= htmlspecialchars($day_name) ?></p>

    <form method="POST" action="../process_add_daily_meal.php">
        <input type="hidden" name="week_start_date" value="<?= htmlspecialchars($week_start_date) ?>">
        <input type="hidden" name="day_name" value="<?= htmlspecialchars($day_name) ?>">

        <label for="breakfast">Breakfast:</label>
        <input type="text" id="breakfast" name="breakfast" placeholder="Breakfast"><br>

        <label for="lunch">Lunch:</label>
        <input type="text" id="lunch" name="lunch" placeholder="Lunch"><br>

        <label for="dinner">Dinner:</label>
        <input type="text" id="dinner" name="dinner" placeholder="Dinner"><br>

        <button type="submit">Add Meal</button>
    </form>
<?php include '../includes/footer.php'; ?>