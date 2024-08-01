<?php
session_start();

if (isset($_POST['selectedDate']) && isset($_POST['selectedTime'])) {
    // Retrieve selected date and time from the POST request
    $selectedDate = $_POST['selectedDate'];
    $selectedTime = $_POST['selectedTime'];

    // You can perform additional validation here if needed

    // Store selected date and time in session variables
    $_SESSION['selectedDate'] = $selectedDate;
    $_SESSION['selectedTime'] = $selectedTime;

    // Return a JSON response indicating success
    //echo json_encode(array('success' => true, 'message' => 'Date and time saved successfully!'));
} else {
    // Return a JSON response indicating failure
    //echo json_encode(array('success' => false, 'message' => 'Error: Selected date and time not received'));
}
?>
