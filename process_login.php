<?php include 'db/db_connections.php'; ?>
<?php
    session_start();

    // Check if the HTTP request is a POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Retrieve the username and password to log the user in
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Connect to the database
        $conn = getDatabaseConnection();

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Execute the query
        $stmt = $conn->prepare("SELECT id, password_hash FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password_hash'])) {
                // The password is correct, log the user in by setting the session id and username
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $username;

                // Redirect the user to the meal plans page
                header("Location: ./pages/home.php");
                exit;
            } else {
                // Password is incorrect, set session message
                $_SESSION['error'] = "Invalid password!";
                header("Location: ./pages/login.php");
                exit;
            }
        } else {
            // Username not found, set session message
            $_SESSION['error'] = "User not found!";
            header("Location: ./pages/login.php");
            exit;
        }
    }
?>