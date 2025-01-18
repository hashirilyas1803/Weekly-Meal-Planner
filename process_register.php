<?php include 'db/db_connections.php'; ?>
<?php
    session_start();
    
    // Confirm that the HTTP request is a POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Hash the password before storing it in the database
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
        // Connect to the database
        $conn = getDatabaseConnection();
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the username is available
        $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if (($row = $result->fetch_assoc()) > 0) {
            // Redirect to the register page
            $_SESSION['error'] = "Username already exists!";
            header("Location: ./pages/register.php");
            exit;
        }
    
        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, username, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $username, $password_hash);
    
        if ($stmt->execute()) {
            // Registration successful, log the user in by storing their ID in the session
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            
            // Redirect to the meal plans page
            header("Location: ./pages/home.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }
?>