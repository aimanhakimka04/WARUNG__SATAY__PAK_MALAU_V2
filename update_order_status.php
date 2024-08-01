<?php
include 'admin/db_connect.php'; // Include database connection file

// Check if order_id is sent via POST
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    
    // Update order status to 4 (assuming 'status' is the column name)
    $updateQuery = "UPDATE orders SET status = 5 WHERE id = $order_id";
    if ($conn->query($updateQuery) === TRUE) {
        echo "Order status updated successfully";
    } else {
        echo "Error updating order status: " . $conn->error;
    }
} else {
    echo "Order ID not provided";
}
?>
