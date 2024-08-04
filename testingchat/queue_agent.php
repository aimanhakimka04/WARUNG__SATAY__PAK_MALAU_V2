<?php
session_start();
require 'db_connection.php'; // Include your database connection

$email = $_SESSION['login_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is already in the queue
    $stmt = $conn->prepare("SELECT * FROM support_queue WHERE sender_email = ? AND staff_assign = FALSE");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['response' => 'You are already in the queue. Please wait for an agent to connect.']);
    } else {
        // Insert user into the queue
        $stmt = $conn->prepare("INSERT INTO support_queue (sender_email) VALUES (?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['response' => 'You have been added to the queue. An agent will connect with you shortly.']);
        } else {
            echo json_encode(['response' => 'Failed to add to the queue. Please try again.']);
        }
    }
}
?>
