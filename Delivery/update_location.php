<?php 
include '../admin/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $last_location = $latitude . ',' . $longitude;

    $stmt = $conn->prepare("UPDATE rider SET last_location = ? WHERE order_id = ?");
    $stmt->bind_param("si", $last_location, $order_id);

    if ($stmt->execute()) {
        echo "Location updated successfully.";
    } else {
        echo "Error updating location: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
