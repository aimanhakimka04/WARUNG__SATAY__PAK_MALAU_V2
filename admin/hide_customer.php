<?php
// Include database connection file
include 'db_connect.php';

// Check if request method is POST and 'id' and 'status' parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    $customer_id = $_POST['id'];
    $new_status = $_POST['status'];

    // Update the status_hide value
    $stmt = $conn->prepare("UPDATE user_info SET hide_status = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $new_status, $customer_id);
    if ($stmt->execute()) {
        echo 1; // Return 1 for success
    } else {
        echo 0; // Return 0 for failure
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
