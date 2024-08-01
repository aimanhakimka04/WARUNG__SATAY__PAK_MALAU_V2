<?php
include('db_connect.php');

if (isset($_POST['voucher_id'])) {
    $voucherId = $_POST['voucher_id'];
    $sql = "DELETE FROM voucher WHERE v_id = '$voucherId'";
    
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
