<?php include '../db/db_connections.php'; ?>
<?php
    include '../includes/header.php';
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    // Check if 'id' parameter is provided in the URL
    if (isset($_GET['meal_id'])) {
        $meal_id = $_GET['meal_id'];
    } else {
        echo "No menu ID provided.";
        exit;
    }


    // Connect to the database
    $conn = getDatabaseConnection();

    // Retrieve the existing meal plan record
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT week_start_date, day_name, breakfast, lunch, dinner FROM weekly_menu WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $meal_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $meal = $result->fetch_assoc();
    if ($result->num_rows === 1) {
    } else {
        echo "Menu item not found.";
        exit;
    }
?>
<h1>Edit Meal Plan</h1>

<!-- Display the day and week information -->
<p><strong>Week Starting Date:</strong> <?= htmlspecialchars($meal['week_start_date']) ?></p>
<p><strong>Day:</strong> <?= htmlspecialchars($meal['day_name']) ?></p>

<form method="POST" action="../process_edit_daily_meal.php">
    <input type="hidden" name="meal_id" value="<?= htmlspecialchars($meal_id) ?>">
    <input type="hidden" name="week_start_date" value="<?= htmlspecialchars($meal['week_start_date']) ?>">
    <input type="hidden" name="day_name" value="<?= htmlspecialchars($meal['day_name']) ?>">

    <label for="breakfast">Breakfast:</label>
    <input type="text" id="breakfast" name="breakfast" value="<?= htmlspecialchars($meal['breakfast']) ?>" required><br>

    <label for="lunch">Lunch:</label>
    <input type="text" id="lunch" name="lunch" value="<?= htmlspecialchars($meal['lunch']) ?>" required><br>

    <label for="dinner">Dinner:</label>
    <input type="text" id="dinner" name="dinner" value="<?= htmlspecialchars($meal['dinner']) ?>" required><br>

    <button type="submit">Update Meal</button>
</form>
<?php include '../includes/footer.php'; ?>