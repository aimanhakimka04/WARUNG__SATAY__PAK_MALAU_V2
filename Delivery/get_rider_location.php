<?php
include 'admin/db_connect.php';

$order_id = $_GET['id'];
$qryRider = $conn->query("SELECT last_location FROM rider WHERE order_id = $order_id");
if ($qryRider->num_rows > 0) {
    $rider_location = $qryRider->fetch_assoc()['last_location'];
    echo json_encode(['location' => $rider_location]); //
} else {
    echo json_encode(['location' => '']);
}
?>
