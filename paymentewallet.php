<?php
require 'vendor/autoload.php';
$payment_status = 'Waiting for payment...';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'admin/db_connect.php';
session_start();

// Fetch the user's current balance and discount points from the database
$user_id = $_SESSION['login_user_id'];
$user_query = $conn->query("SELECT * FROM user_info WHERE user_id = $user_id");
$user_info = $user_query->fetch_array();
$balance = $user_info['walletbalance'];
$discountPoints = $user_info['discount_point'];
$user_email = $user_info['email'];

// Store the balance and discount points in session variables
$_SESSION['ewallet_balance'] = $balance;
$_SESSION['discount_points'] = $discountPoints;

// Get the values from the URL parameters
$address = isset($_GET['address']) ? $_GET['address'] : '';
$collectionMethod = isset($_GET['collectionMethod']) ? $_GET['collectionMethod'] : '';
$feeDelivery = isset($_GET['feedelivery']) ? $_GET['feedelivery'] : '';
$firstName = isset($_GET['first_name']) ? $_GET['first_name'] : '';
$lastName = isset($_GET['last_name']) ? $_GET['last_name'] : '';
$mobile = isset($_GET['mobile']) ? $_GET['mobile'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$orderRequest = isset($_GET['orderrequest']) ? $_GET['orderrequest'] : '';
$paymentMethod = isset($_GET['paymentMethod']) ? $_GET['paymentMethod'] : '';
$paymentType = isset($_GET['paymentType']) ? $_GET['paymentType'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '';
$datetime = date("Y-m-d H:i:s"); // Set the current datetime

$payment_status = '';
$payment_success = false;

function sendTacCode($user_email, $firstName, $lastName)
{
    $tac_code = rand(100000, 999999);
    $_SESSION['tac_code'] = (string)$tac_code;

    // Send the TAC code via email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pakmalauwarungsatay@gmail.com';
        $mail->Password = 'cemjkjawoatwbdct';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('pakmalauwarungsatay@gmail.com', 'Pak Malau Warung Satay');
        $mail->addAddress($user_email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your TAC Code for Payment';
        $message = "Dear $firstName $lastName,<br><br>Your TAC code for the payment is <strong>$tac_code</strong>. Please enter this code to complete your transaction.<br><br>Thank you!";
        $mail->Body = $message;

        $mail->send();
        return "A TAC code has been sent to your email. Please enter the TAC code to complete the transaction.";
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Handle the payment process
if ($paymentMethod === 'ewallet') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['request_tac'])) {
            $payment_status = sendTacCode($user_email, $firstName, $lastName);
        } elseif (isset($_POST['tac_code'])) {
            $entered_tac = (string)$_POST['tac_code'];
            $stored_tac = (string)$_SESSION['tac_code'];

            if ($entered_tac === $stored_tac) {
                if ($balance >= $amount) {
                    // Deduct the amount from the wallet balance
                    $new_balance = $balance - $amount;
                    $conn->query("UPDATE user_info SET walletbalance = $new_balance WHERE user_id = $user_id");
                    $_SESSION['ewallet_balance'] = $new_balance;
                    $payment_status = "Payment successful! New balance: RM $new_balance";
                    $payment_success = true;

                    if ($payment_success) {
                        $db = $conn;
                        $data = " name = '" . $firstName . " " . $lastName . "' ";
                        $data .= ", mobile = '$mobile' ";
                        $data .= ", email = '$email' ";
                        $data .= ", address = '$address' ";
                        $data .= ", orderrequest = '$orderRequest' ";
                        $data .= ", payment_method = '$paymentMethod' ";
                        $data .= ", collect_method = '$collectionMethod' ";
                        $data .= ", delivery_fee = '$feeDelivery' ";
                        $total_fee = $amount + $feeDelivery; // Calculate total fee
                        $data .= ", total_fee = '$total_fee' ";
                        $data .= ", collect_time = '$datetime' ";

                        $save = $db->query("INSERT INTO orders set " . $data);
                        if ($save) {
                            $order_id = $db->insert_id;
                            $qry = $db->query("SELECT * FROM cart where user_id =" . $_SESSION['login_user_id']);
                            while ($row = $qry->fetch_assoc()) {
                                $data = " order_id = '$order_id' ";
                                $data .= ", product_id = '" . $row['product_id'] . "' ";
                                $data .= ", qty = '" . $row['qty'] . "' ";
                                $save2 = $db->query("INSERT INTO order_list set " . $data);
                                if ($save2) {
                                    $db->query("DELETE FROM cart where id= " . $row['id']);
                                }
                            }
                            $successcash = true;
                        }

                        if ($successcash) {
                            echo "<script>localStorage.removeItem('mangkukTimunImage');</script>";
                            echo "<script>localStorage.removeItem('bawangImage');</script>";
                            echo "<script>localStorage.removeItem('kuahImage');</script>";
                            echo "<script>localStorage.removeItem('selectedDiscount');</script>";
                        }
                    }
                } else {
                    $payment_status = "Insufficient balance!";
                }
            } else {
                $payment_status = "Invalid TAC code!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment E-Wallet</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .container::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: linear-gradient(45deg, #0070ba, #00d4ff);
            z-index: -1;
            transform: rotate(45deg);
            border-radius: 50%;
            transition: transform 0.5s;
        }

        .container:hover::before {
            transform: rotate(0);
        }

        h2 {
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
            animation: logoBounce 1s infinite;
        }

        @keyframes logoBounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        p {
            font-size: 1em;
            margin: 10px 0;
            color: #666;
        }

        p span {
            color: #000;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }

        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
        }

        .form-group {
            margin-bottom: 15px;
            animation: slideIn 0.5s ease-out;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #007bff;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.3s;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: transform 0.3s;
            transform: scaleX(0);
            transform-origin: left;
        }

        button:hover::before {
            transform: scaleX(1);
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="images/logo.png" alt="Logo" class="logo">
        <h2>Payment Details</h2>
        <p><strong>Address:</strong> <span><?php echo htmlspecialchars($address); ?></span></p>
        <p><strong>Collection Method:</strong> <span><?php echo htmlspecialchars($collectionMethod); ?></span></p>
        <p><strong>Delivery Fee:</strong> <span><?php echo htmlspecialchars($feeDelivery); ?></span></p>
        <p><strong>First Name:</strong> <span><?php echo htmlspecialchars($firstName); ?></span></p>
        <p><strong>Last Name:</strong> <span><?php echo htmlspecialchars($lastName); ?></span></p>
        <p><strong>Mobile:</strong> <span><?php echo htmlspecialchars($mobile); ?></span></p>
        <p><strong>Email:</strong> <span><?php echo htmlspecialchars($email); ?></span></p>
        <p><strong>Order Request:</strong> <span><?php echo htmlspecialchars($orderRequest); ?></span></p>
        <p><strong>Payment Method:</strong> <span><?php echo htmlspecialchars($paymentMethod); ?></span></p>
        <p><strong>Payment Type:</strong> <span><?php echo htmlspecialchars($paymentType); ?></span></p>
        <p><strong>Amount:</strong> <span><?php echo htmlspecialchars($amount); ?></span></p>
        <p><strong>Status:</strong> <span><?php echo htmlspecialchars($payment_status); ?></span></p>

        <?php if ($payment_success) : ?>
            <form method="GET" action="index.php?page=order_track&id=<?php echo $order_id ?>">
                <button type="submit">Go to Receipt</button>
            </form>
        <?php else : ?>
            <?php if ($payment_status === "A TAC code has been sent to your email. Please enter the TAC code to complete the transaction." || isset($_POST['tac_code'])) : ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="tac_code">Enter TAC Code:</label>
                        <input type="text" name="tac_code" id="tac_code">
                    </div>
                    <button type="submit">Submit TAC Code</button>
                    <button type="submit" name="request_tac">Resend TAC Code</button>
                </form>
            <?php else : ?>
                <form method="POST" action="">
                    <button type="submit" name="request_tac">Request TAC Code</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>
