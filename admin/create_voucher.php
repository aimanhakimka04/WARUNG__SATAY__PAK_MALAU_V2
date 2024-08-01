<?php
include('db_connect.php');

// Function to generate a random 10-digit voucher code
function generateVoucherCode() {
    return str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_vouchers = $_POST['num_vouchers'];
    $v_points = $_POST['v_points'];
    $vouchers = [];

    for ($i = 0; $i < $num_vouchers; $i++) {
        $v_code = generateVoucherCode();
        $sql = "INSERT INTO voucher (v_code, v_point) VALUES ('$v_code', '$v_points')";
        if ($conn->query($sql) === TRUE) {
            $v_id = $conn->insert_id;
            $vouchers[] = ['v_id' => $v_id, 'v_code' => $v_code, 'v_points' => $v_points];
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    echo json_encode($vouchers);
}

$conn->close();
?>
