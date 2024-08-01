<?php
// Include database connection file
include 'db_connect.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs (for security)
    $customer_id = $_POST['customer_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $customer_email = $_POST['customer_email'];
    $customer_mobile = $_POST['customer_mobile'];
    $customer_address = $_POST['customer_address'];

    // Prepare update statement
    $stmt = $conn->prepare("UPDATE user_info SET first_name=?, last_name=?, email=?, mobile=?, address=? WHERE user_id=?");

    // Bind parameters
    $stmt->bind_param("sssssi", $first_name, $last_name, $customer_email, $customer_mobile, $customer_address, $customer_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to the customer detail page with success message
        header("Location: index.php?page=customer_list");
        exit();
    } else {
        // Redirect back to the customer detail page with error message
        header("Location: index.php?page=customer_list");
        exit();
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
