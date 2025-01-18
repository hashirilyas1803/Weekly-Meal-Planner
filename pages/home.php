<?php include '../includes/header.php'; ?>
    <h2>Your Weekly Meal Plans</h2>

    <?php
    include '../process_meal_plan.php';
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Fetch weekly meal plans
    $meal_plans = getMealPlan($user_id);

    if (count($meal_plans) > 0): 
        // Group meal plans by week_start_date
        $grouped_plans = [];
        foreach ($meal_plans as $meal) {
            $grouped_plans[$meal['week_start_date']][] = $meal;
        }
    ?>

    <?php foreach ($grouped_plans as $week_start_date => $meals): ?>
        <h3>Week Starting: <?= htmlspecialchars($week_start_date) ?></h3>
        <a href="edit_meal.php?week_start_date=<?= htmlspecialchars($week_start_date) ?>">Edit Weekly Menu</a>
        <a href="../process_delete_meal.php?week_start_date=<?= htmlspecialchars($week_start_date) ?>">Delete Weekly Menu</a>
        <table border="1">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Breakfast</th>
                    <th>Lunch</th>
                    <th>Dinner</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $meals_by_day = [];
                
                // Organize meals by day for easier lookup
                foreach ($meals as $meal) {
                    $meals_by_day[$meal['day_name']] = $meal;
                }

                foreach ($days as $day): 
                    $breakfast = $meals_by_day[$day]['breakfast'] ?? '';
                    $lunch = $meals_by_day[$day]['lunch'] ?? '';
                    $dinner = $meals_by_day[$day]['dinner'] ?? '';
                    $meal_id = $meals_by_day[$day]['id'] ?? null;
                ?>
                <tr>
                    <td><?= htmlspecialchars($day) ?></td>
                    <td><?= htmlspecialchars($breakfast) ?></td>
                    <td><?= htmlspecialchars($lunch) ?></td>
                    <td><?= htmlspecialchars($dinner) ?></td>
                    <td>
                    <?php if ($meal_id): ?>
                        <a href="edit_daily_meal.php?meal_id=<?= htmlspecialchars($meal_id) ?>">Edit</a>
                        <a href="../process_delete_daily_meal.php?meal_id=<?= htmlspecialchars($meal_id) ?>">Delete</a>
                    <?php else: ?>
                        <a href="add_daily_meal.php?day_name=<?= htmlspecialchars($day) ?>&week_start_date=<?= htmlspecialchars($week_start_date) ?>">Add</a>
                    <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
    <?php endforeach; ?>

    <?php else: ?>
        <p>No meal plans found.</p>
    <?php endif; ?>

    <a href="add_meal.php">Add a new weekly meal plan</a>
<?php include '../includes/footer.php'; ?>