<?php
function reset_img()
{
    // Start the session
    session_start();

    // Assuming you have included the database connection script
    include 'admin/db_connect.php';

    // Retrieve user ID from session
    $user_id = $_SESSION['login_user_id']; // Assuming user_id is stored in session

    // Set default image filename
    $defaultImage = 'user.png';

    // Update the session with the new image file name
    $_SESSION['login_img_user'] = $defaultImage;

    // Update the `img_user` field in the `user_info` table to 'user.png' for the user
    $sql = "UPDATE user_info SET img_user = '$defaultImage' WHERE user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        // Image reset successfully
        echo "Image reset successfully.";
    } else {
        // Error occurred while resetting image
        echo "Error resetting image: " . $conn->error;
    }
}
?>
