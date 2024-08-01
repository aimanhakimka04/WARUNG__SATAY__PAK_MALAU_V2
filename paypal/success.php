<?php 
require_once 'config.php';

//once the payment is done, paypal will redirect the user to this page

if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)) {
 $transaction = $gateway->completePurchase(array(
    'payer_id' => $_GET['PayerID'],
    'transactionReference' => $_GET['paymentId'],

 ));
 $response = $transaction->send();

 if ($response->isSuccessful()){
    //the customer has successfully paid
    $arr_body = $response->getData();

    $payment_id = $arr_body['id'];
    $payer_id = $arr_body['payer']['payer_info']['payer_id'];
    $payer_email = $arr_body['payer']['payer_info']['email'];
    $amount = $arr_body['transactions'][0]['amount']['total']; // 0 is the first transaction
    $currency = PAYPAL_CURRENCY;
    $payment_status = $arr_body['state']; 

    //insert the transaction data into the database
    $query = $conn->query("INSERT INTO payments (payment_id, payer_id, payer_email, amount, currency, payment_status) VALUES ('".$payment_id."', '".$payer_id."', '".$payer_email."', '".$amount."', '".$currency."', '".$payment_status."')"); // "'..'" is concatenating the value to sql query string.
   
 }else {
    echo $response->getMessage();
 }
}else {
    echo 'Transaction is declined';
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
           
            display: flex;
            justify-content: center;
            align-items: center;
            
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 600px;
            width:900px;
            margin-top:70px;
            
        }
        h1 {
            color: #297bbe;
        }
        .icon {
            
            margin-bottom: 0px;
            width:150px;
            height:150px;
            margin-left:auto;
            margin-right:auto;
            border:3px solid gray;
            border-radius:150px
        }
        .message {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .button {
            background-color: #297bbe;
            color: white;
            margin-top: 30px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            width:700px;
            height:300px;
        }
        .button:hover {
            background-color: #2076bd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
        	<p style="font-size:80px; font-width:100;color:gray;margin-top:10px;">&#10004;</p>		
        </div>
        <h1>Payment Successful</h1>
        
        <a href="../index.php?page=order_track"><button class="button" style="width:500px;height:100px; font-size: 30px;">Go to Receipt </button></a>
        
        <p class="message">You can view your transaction history, send receipts, and apply refunds from Sqare Dashboard</p>
        
    </div>
</body>
</html>
