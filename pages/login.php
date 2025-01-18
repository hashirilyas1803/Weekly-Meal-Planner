<!-- Includes the boiler plate html to be included in subsequent html pages-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Menu Planner</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php
        session_start();

        // Check if there's an error message in the session
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
            
            // Clear the error message after displaying it
            unset($_SESSION['error']);
        }
    ?>
    <h2>Login</h2>
    <form method="POST" action="../process_login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
<?php include '../includes/footer.php'; ?>
