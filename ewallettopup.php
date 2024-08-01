<?php 
session_start();
$amount = $_GET['amount'];
$_SESSION['wallettopup'] = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topup E Wallet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            text-align: center;
            max-width: 450px;
            width: 100%;
            animation: fadeIn 1s ease-in-out;
        }
        .container p {
            font-size: 1.4em;
            color: #555;
        }
        .amount {
            font-size: 2.4em;
            color: #333;
            margin: 20px 0;
            font-weight: bold;
        }
        input[type="submit"] {
            background-color: #0070ba;
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 1.2em;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #005b8a;
        }
        .icons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .icons img {
            margin: 0 15px;
            max-width: 150px;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icons">
            <img src="images/logo.png" alt="Logo">
            <img src="./assets/img/PayPal.png" alt="PayPal">
        </div>
        <p>Topup E Wallet Pak Malau</p>
        <p class="amount">Amount: RM<?php echo htmlspecialchars($amount); ?></p>
        <form method="get" action="admin/charge.php">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>" />
            <input type="hidden" name="paymentMethod" value="paypal" />
            <input type="hidden" name="paymentType" value="topup" />
            <input type="submit" name="submit" value="Pay Now With PayPal">
        </form>
    </div>
</body>
</html>
