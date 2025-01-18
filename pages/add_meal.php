<?php 
    include '../includes/header.php';
    include '../process_meal_plan.php';

    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    // Retrieve the user id
    $user_id = $_SESSION['user_id'];

    // Get the next available Monday
    $nextMonday = getNextAvailableMonday($user_id);

    // Connect to the database
    $conn = getDatabaseConnection();
    // Fetch all taken dates for validation
    $stmt = $conn->prepare("SELECT week_start_date FROM weekly_menu WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $takenDates = [];
    while ($row = $result->fetch_assoc()) {
        $takenDates[] = $row['week_start_date'];
    }
?>
<h2>Add New Meal Plan</h2>
<form method="POST" action="../process_add_meal.php">
    <label for="week_start_date">Start Date of the Week:</label>
    <p id="date-warning" style="color: red;"></p>
    <input type="date" id="week_start_date" name="week_start_date" value="<?= htmlspecialchars($nextMonday) ?>" required><br><br>

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
                echo "
                <tr>
                    <td>$day</td>
                    <td><input type='text' name='meals[$day][breakfast]' placeholder='Breakfast'></td>
                    <td><input type='text' name='meals[$day][lunch]' placeholder='Lunch'></td>
                    <td><input type='text' name='meals[$day][dinner]' placeholder='Dinner'></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
    <button type="submit">Save Weekly Plan</button>
</form>
<script>
    // Validate the selected date
    document.addEventListener('DOMContentLoaded', function () {
        const takenDates = <?= json_encode($takenDates) ?>;
        const dateInput = document.getElementById('week_start_date');
        const warning = document.getElementById('date-warning');

        dateInput.addEventListener('change', function () {
            const selectedDate = new Date(dateInput.value);
            const dayOfWeek = selectedDate.getUTCDay(); 

            // Check if the selected date is a Monday
            if (dayOfWeek !== 1) {
                warning.textContent = "Please select a Monday as the week starting date.";
                dateInput.value = "";
                return;
            }
            if (takenDates.includes(dateInput.value)) {
                warning.textContent = "This week already has a meal plan. Please choose another date.";
                dateInput.value = "";
            } else {
                warning.textContent = "";
            }
        });
    });
</script>


<?php include '../includes/footer.php'; ?>
