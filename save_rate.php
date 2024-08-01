<?php
session_start();
include 'admin/db_connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['login_user_id'])) {
        echo "User is not logged in.";
        exit;
    }
    $order_id = $_POST['order_id'] ?? null;
    if (!$order_id) {
        echo "Invalid order ID.";
        exit;
    }
    // Get user ID from session
    $user_id = $_SESSION['login_user_id'];
    
    $user_firstname = $_SESSION['login_first_name'];
    $user_lastname = $_SESSION['login_last_name'];

    // Concatenate first name and last name to create user_name
    $username = $user_firstname . ' ' . $user_lastname;

    // Get rating and description from POST data
    $rating = $_POST['custom-rating'] ?? 0;
    $description = $_POST['custom-description'] ?? '';

    // Handle image upload (if provided)
    $img_comment = '';
    if (isset($_FILES['custom-img_comment']) && $_FILES['custom-img_comment']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "assets/img/"; // Directory where you want to store uploaded images
        $target_file = $target_dir . basename($_FILES["custom-img_comment"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if the file is an actual image
        $check = getimagesize($_FILES["custom-img_comment"]["tmp_name"]);
        if ($check !== false) {
            // Allow only certain file formats
            if (in_array($imageFileType, array("jpg", "png", "jpeg", "gif"))) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["custom-img_comment"]["tmp_name"], $target_file)) {
                    $img_comment = basename($_FILES["custom-img_comment"]["name"]);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    exit;
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

    // Insert the data into your database table
    $stmt = $conn->prepare("INSERT INTO rating (user_id, user_name, order_id, rate, description, img_comment) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiss", $user_id,$username, $order_id,$rating, $description, $img_comment);

    if ($stmt->execute()) {
        echo "Rating saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Handle invalid request method
    echo "Invalid request method!";
}
?>
