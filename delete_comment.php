<?php
// Include database connection
include 'db_connect.php';

// Check if rate_id is provided via POST request
if(isset($_POST['rate_id'])) {
    // Sanitize the input
    $rateId = $_POST['rate_id'];

    // Prepare the SQL statement to delete the comment
    $deleteQuery = "DELETE FROM rating WHERE rate_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $rateId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Comment deleted successfully";
    } else {
        echo "Error deleting comment";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Rate ID not provided";
}
?>
