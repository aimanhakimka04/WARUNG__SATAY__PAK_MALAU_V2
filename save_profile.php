<?php
// Start the session
session_start();

// Include the database connection
include 'admin/db_connect.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the action parameter is set
if (isset($_GET['action']) && $_GET['action'] == "save_profile") {
    // Retrieve values from the form and sanitize them
    $user_id = $_SESSION['login_user_id']; // Assuming user_id is stored in session
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $last_login = mysqli_real_escape_string($conn, $_POST['lastlogin']);

    // Update user profile data in the session
    $_SESSION['login_first_name'] = $first_name;
    $_SESSION['login_last_name'] = $last_name;
    $_SESSION['login_email'] = $email;
    $_SESSION['login_address'] = $address;
    $_SESSION['login_mobile'] = $mobile;
    $_SESSION['login_last_login'] = $last_login;

    // Check if a file is uploaded
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        // File upload directory
        $uploadDir = 'assets/img/';

        // Get the uploaded file extension
        $fileExtension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

        // Validate file type
        $allowedExtensions = array('png', 'jpg', 'jpeg', 'gif');
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Only PNG, JPG, JPEG, and GIF files are allowed.";
            exit;
        }

        // Validate file size (assuming a maximum size of 5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($_FILES['profile_image']['size'] > $maxFileSize) {
            echo "File size exceeds the maximum limit of 5MB.";
            exit;
        }

        // Retrieve the current image file name from the database
        $sql = "SELECT img_user FROM user_info WHERE user_id = '$user_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $oldFileName = $row['img_user'];

            // Delete the old image file from the server
            if (file_exists($uploadDir . $oldFileName)) {
                unlink($uploadDir . $oldFileName);
            }
        }

        // Generate a unique file name to prevent conflicts
        $newFileName = uniqid() . '.' . $fileExtension;

        // Move the uploaded file to the destination directory
        $targetFilePath = $uploadDir . $newFileName;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
            // Update the session with the new image file name
            $_SESSION['login_img_user'] = $newFileName;

            // Save the new image file name to the database
            $sql = "UPDATE user_info SET img_user = '$newFileName' WHERE user_id = '$user_id'";
            if ($conn->query($sql) === TRUE) {
                // Success message or redirect back to the profile page
                header("Location: index.php?page=user_profile");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
                exit;
            }
        } else {
            echo "Error moving uploaded file to target directory.";
            exit;
        }
    }

    // Save user profile data to the database
    $sql = "UPDATE user_info SET 
            first_name = '$first_name',
            last_name = '$last_name',
            email = '$email',
            address = '$address',
            mobile = '$mobile',
            last_login = '$last_login'
            WHERE user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the profile page or display a success message
        header("Location: index.php?page=user_profile");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
        exit;
    }
}
?>
