<?php
include 'db_connect.php';

if(isset($_POST['id'])) {
    $user_id = $_POST['id'];
    
    // Perform the deletion query
    $result = $conn->query("DELETE FROM users WHERE id = '$user_id'");
    
    if($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
