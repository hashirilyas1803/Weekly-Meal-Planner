<?php include 'db/db_connections.php'; ?>
<?php
    session_start();

    // Check if the user is logged in and redirect them to the log in page in not.
    if (!isset($_SESSION['user_id'])) {
        // Redirect the user to the login page
        header("Location: ./pages/login.php");
        exit;
    }

     // Retrieve the username
     $user_id = $_SESSION['user_id'];
        
     // Retrieve the meal id from the URL
     $meal_id = intval($_GET['meal_id']);

     // Connect to the database
     $conn = getDatabaseConnection();

     try {
         // Delete the meal from the database
         $stmt = $conn->prepare('DELETE FROM weekly_menu WHERE id = ? AND user_id = ?');
         $stmt->bind_param('ii', $meal_id, $user_id);
         $stmt->execute();
         
         $_SESSION['success'] = "Meal plan deleted successfully!";
         header("Location: ./pages/home.php");
         exit;
     } catch (Exception $e) {
         $conn->rollback();
         $_SESSION['error'] = "Error deleting meal plan: " . $e->getMessage();
         header("Location: ./pages/home.php");
         exit;
     } finally {
         $conn->close();
     }

?>