<?php include 'db/db_connections.php'; ?>
<?php
    function getMealPlan($user_id) {
        // Connect to the database
        $conn = getDatabaseConnection();

        // Prepare the SQL Query to retrive the weekly menu
        $query = $conn->prepare("SELECT * FROM weekly_menu WHERE user_id = ?");
        $query->bind_param("s", $user_id);
        
        // Execute the query and store the result
        $query->execute();
        $result = $query->get_result();
        
        // Array to hold the weekly menu
        $weekly_menu = [];

        // Store the weekly menu in an array
        while ($row = $result->fetch_assoc()) {
            $weekly_menu[] = $row;
        }
        
        // Return the weekly menu
        return $weekly_menu;
    }

    function getNextAvailableMonday($user_id) {
        // Get the current date
        $today = new DateTime();

        // Start with the next Monday
        $today->modify('next Monday');
        $nextMonday = $today->format('Y-m-d');

        // Connect to the database
        $conn = getDatabaseConnection();
    
        // Query the database to find available dates
        $stmt = $conn->prepare("SELECT week_start_date FROM weekly_menu WHERE user_id = ? AND week_start_date >= ?");
        $stmt->bind_param("is", $user_id, $nextMonday);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $takenDates = [];
        while ($row = $result->fetch_assoc()) {
            $takenDates[] = $row['week_start_date'];
        }
    
        // Keep moving to the next Monday until an available date is found
        while (in_array($nextMonday, $takenDates)) {
            $today->modify('+1 week');
            $nextMonday = $today->format('Y-m-d');
        }
    
        return $nextMonday;
    }
?>