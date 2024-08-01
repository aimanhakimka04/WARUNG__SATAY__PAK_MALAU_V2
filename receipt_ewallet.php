<?php
// Retrieve the query parameters
$paymentMethod = $_GET['paymentMethod'];
$payment_id = $_GET['payment_id'];
$payer_id = $_GET['payer_id'];
$payer_email = $_GET['payer_email'];
$amount = $_GET['amount'];
$currency = $_GET['currency'];
$payment_status = $_GET['payment_status'];
$address = $_GET['address'];
$collectionMethod = $_GET['collectionMethod'];
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$mobile = $_GET['mobile'];
$email = $_GET['email'];
$orderrequest = $_GET['orderrequest'];
$delivery_fee = $_GET['delivery_fee'];
$total_fee = $_GET['total_fee'];
$datetime = $_GET['datetime'];
$walletbalance = $_GET['walletbalance'];
$paymentreference = $_GET['paymentreference'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }
        h1 {
            color: #4CAF50;
            text-align: center;
        }
        .receipt {
            margin-top: 20px;
            font-size: 16px;
            text-align: left;
        }
        .receipt p {
            margin: 10px 0;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .receipt p strong {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        .highlight {
            background-color: #e7f4e4;
        }
        .highlight strong {
            color: #4CAF50;
        }
        .link {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .link:hover {
            background-color: #45a049;
        }
        .print-button {
            margin-top: 20px;
        }
        .print-button button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .print-button button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
        </div>
        <h1>Payment Successful</h1>
        <div class="receipt">
            <p class="highlight"><strong>Payment Method:</strong> <?php echo htmlspecialchars($paymentMethod); ?></p>
            <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($payment_id); ?></p>
            <p><strong>Payer ID:</strong> <?php echo htmlspecialchars($payer_id); ?></p>
            <p><strong>Payer Email:</strong> <?php echo htmlspecialchars($payer_email); ?></p>
            <p class="highlight"><strong>Amount:</strong> <?php echo htmlspecialchars($amount); ?> <?php echo htmlspecialchars($currency); ?></p>
            <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($payment_status); ?></p>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p class="highlight"><strong>Date and Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>  
            <p><strong>New Wallet Balance:</strong> RM <?php echo htmlspecialchars($walletbalance); ?></p>
        </div>
        <a href="index.php?page=ewalletpakmalau" class="link">Go to E-Wallet</a>
        <button onclick="window.print()" class="link">Print Receipt</button>
        <div class="footer">
            Thank you for your payment!
        </div>
    </div>
    
</body>
</html>
