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
    <h2>Register</h2>
    <form method="POST" action="../process_register.php">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <p id="confirm_password_warning" style="color: red;"></p>

        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const form = document.getElementById('password_form'); // Replace with your form's ID
            const password = document.getElementById('password');
            const confirm_password = document.getElementById('confirm_password');
            const warning = document.getElementById('confirm_password_warning');

            // Real-time validation
            confirm_password.addEventListener('input', validatePasswords);
            password.addEventListener('input', validatePasswords);

            // Prevent form submission without proper pre-requisites
            form.addEventListener('submit', function (event) {
                if (!validatePasswords()) {
                    event.preventDefault();
                }
            });

            // Function to validate passwords
            function validatePasswords() {
                if (password.value !== confirm_password.value) {
                    // Set custom validation message
                    confirm_password.setCustomValidity("Passwords do not match!");
                    warning.textContent = "Passwords do not match!";
                    confirm_password.style.borderColor = "red";
                    return false;
                } else {
                    // Clear custom validation message
                    confirm_password.setCustomValidity("");
                    warning.textContent = "";
                    confirm_password.style.borderColor = "green";
                    return true;
                }
            }
        });
    </script>
<?php include '../includes/footer.php'; ?>
