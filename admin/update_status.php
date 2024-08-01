<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rate_id'])) {
    // Validate and sanitize rate_id
    $rate_id = intval($_POST['rate_id']);

    // Prepare the SQL statement to fetch the current status_comment
    $fetch_query = $conn->prepare("SELECT status_comment FROM rating WHERE rate_id = ?");
    $fetch_query->bind_param("i", $rate_id);
    $fetch_query->execute();
    $result = $fetch_query->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $current_status = $row['status_comment'];

        // Toggle status_comment
        $new_status = ($current_status == 1) ? 2 : 1;

        // Prepare the SQL statement to update the status_comment
        $update_query = $conn->prepare("UPDATE rating SET status_comment = ? WHERE rate_id = ?");
        $update_query->bind_param("ii", $new_status, $rate_id);

        if ($update_query->execute() === TRUE) {
            // Redirect to the comment review page
            echo "<script>window.location.href=index.php?page=comment_review';</script>";

            exit();
        } else {
            echo "Error updating status: " . $conn->error;
        }
    } else {
        echo "Rate ID not found";
    }
} else {
    echo "Invalid request";
}

$conn->close();
?>
