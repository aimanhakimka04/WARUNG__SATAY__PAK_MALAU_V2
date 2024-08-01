<?php
session_start();
include('admin/db_connect.php'); // Include your database connection file

// Check if the voucher code is set in the POST request
if(isset($_POST['voucherCode'])) {
    $voucherCode = $_POST['voucherCode'];

    // Validate the voucher code
    $isValidVoucher = validateVoucher($voucherCode);

    if($isValidVoucher) {
        // Assuming you have a function to redeem the voucher and add points to the user's account
        // Replace this with your actual code to redeem the voucher
        $pointsAdded = redeemVoucher($voucherCode);

        if($pointsAdded !== false) {
            // If points are successfully added, update the session variable
            $_SESSION['login_discount_point'] += $pointsAdded;

            // Assuming you have a function to delete the voucher from the database
            // Replace this with your actual code to delete the voucher
            $voucherDeleted = deleteVoucher($voucherCode);

            if($voucherDeleted) {
                // Save the updated discount points to the session
                $_SESSION['login_discount_point'] = saveDiscountPoints($_SESSION['login_discount_point']);

                //update database user_info discount_point
                $query = $conn->query("UPDATE user_info set discount_point = ".$_SESSION['login_discount_point']." where user_id = ".$_SESSION['login_user_id']);
                if ($query) {
                    echo "<script>alert('Voucher claimed successfully. Points added to your account.'); window.location.href='index.php';</script>";
                    exit;
                }

            
            } else {
                echo "<script>alert('Failed to delete voucher. Please contact support.'); window.location.href='index.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Failed to claim voucher. Please try again later.'); window.location.href='index.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Invalid voucher code.'); window.location.href='index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Voucher code not provided.'); window.location.href='index.php';</script>";
    exit;
}

// Function to validate the voucher code (replace this with your actual validation logic)
function validateVoucher($voucherCode) {
    global $conn; // Using the database connection object

    // Query to check if the voucher code exists in the database
    $query = "SELECT * FROM voucher WHERE v_code = '$voucherCode'";
    $result = $conn->query($query);

    if($result->num_rows > 0) {
        return true; // Voucher code is valid
    } else {
        return false; // Voucher code is invalid
    }
}

// Function to redeem the voucher and add points to the user's account (replace this with your actual redemption logic)
function redeemVoucher($voucherCode) {
    global $conn; // Using the database connection object

    // Query to get the points associated with the voucher code
    $query = "SELECT v_point FROM voucher WHERE v_code = '$voucherCode'";
    $result = $conn->query($query);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pointsAdded = $row['v_point'];

        // Assuming you have a function to update the user's account with the points
        // Replace this with your actual code to update the user's account
        // For example, you can add the points to $_SESSION['login_discount_point']
        return $pointsAdded;
    } else {
        return false; // Failed to redeem voucher
    }
}

// Function to delete the voucher from the database (replace this with your actual deletion logic)
function deleteVoucher($voucherCode) {
    global $conn; // Using the database connection object

    // Query to delete the voucher from the database
    $query = "DELETE FROM voucher WHERE v_code = '$voucherCode'";
    $result = $conn->query($query);

    if($result) {
        return true; // Voucher deleted successfully
    } else {
        return false; // Failed to delete voucher
    }
}

// Function to save the discount points to the session (replace this with your actual saving logic)
function saveDiscountPoints($points) {
    // No additional saving logic required as we are updating the session directly
    return $points;
}
?>
