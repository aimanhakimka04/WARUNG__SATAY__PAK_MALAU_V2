<?php

//this is for paypal payment gateway by aiman hakim


require_once 'config.php';
session_start();
//once the payment is done, paypal will redirect the user to this page

if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)) {
    $transaction = $gateway->completePurchase(array(
        'payer_id' => $_GET['PayerID'],
        'transactionReference' => $_GET['paymentId'],

    ));
    $response = $transaction->send();

    if ($response->isSuccessful()) {
        //the customer has successfully paid
        $arr_body = $response->getData();
if(!isset($_SESSION['paymentreference']) || $_SESSION['paymentreference'] == null) {
    // Do something
    $_SESSION['paymentreference'] = 'order';
}
        $payment_id = $arr_body['id'];
        $payer_id = $arr_body['payer']['payer_info']['payer_id'];
        $payer_email = $arr_body['payer']['payer_info']['email'];
        $amount = $arr_body['transactions'][0]['amount']['total']; // 0 is the first transaction
        $currency = PAYPAL_CURRENCY;
        $payment_status = $arr_body['state'];

        //insert the transaction data into the database
        $query = $conn->query("INSERT INTO payments (payment_id, payer_id, payer_email, amount, currency, payment_status) VALUES ('" . $payment_id . "', '" . $payer_id . "', '" . $payer_email . "', '" . $amount . "', '" . $currency . "', '" . $payment_status . "')"); // "'..'" is concatenating the value to sql query string.
    
        if(isset($_SESSION['paymentreference']) && $_SESSION['paymentreference'] == 'topup') {
            // Do something
         
            $address = "digital";
            $collectionMethod = "E-Wallet";
            $first_name = $_SESSION['login_first_name'];
            $last_name = $_SESSION['login_last_name'];
            $mobile = $_SESSION['order_mobile'];
            $email = $_SESSION['login_email'];
            $paymentMethod = $_SESSION['order_paymentMethod'];
            $amount = $_SESSION['amountTopup'];
            $orderrequest = $_SESSION['order_orderrequest'];
            $delivery_fee = $_SESSION['order_delivery_fee'];
            $_SESSION['order_total_fee'] = $amount;
            $total_fee = $_SESSION['order_total_fee'];
            $datetime = $_SESSION['order_datetime'];
            

            //update the user wallet
            $wallet = $conn->query("SELECT * FROM user_info where email = '" . $email . "' ");
            $row = $wallet->fetch_assoc();
            $walletbalance = $row['walletbalance'];
            $newbalance = $walletbalance + $amount;
            $_SESSION['login_walletbalance'] = $newbalance;
            $conn->query("UPDATE user_info set walletbalance = '" . $newbalance . "' where email = '" . $email . "' ");
            $_SESSION['paymentreference'] == null;
           //go to page receipt_ewallet directly
            header("Location: ../receipt_ewallet.php?paymentMethod=Paypal&payment_id=" . $payment_id . "&payer_id=" . $payer_id . "&payer_email=" . $payer_email . "&amount=" . $amount . "&currency=" . $currency . "&payment_status=" . $payment_status . "&address=" . $address . "&collectionMethod=" . $collectionMethod . "&first_name=" . $first_name . "&last_name=" . $last_name . "&mobile=" . $mobile . "&email=" . $email . "&paymentMethod=" . $paymentMethod . "&amount=" . $amount . "&orderrequest=" . $orderrequest . "&delivery_fee=" . $delivery_fee . "&total_fee=" . $total_fee . "&datetime=" . $datetime . "&walletbalance=" . $newbalance . "&paymentreference=" . $_SESSION['paymentreference']);


        } else {
            $address = $_SESSION['order_address'];
            $collectionMethod = $_SESSION['order_collectionMethod'];
            $first_name = $_SESSION['order_first_name'];
            $last_name = $_SESSION['order_last_name'];
            $mobile = $_SESSION['order_mobile'];
            $email = $_SESSION['order_email'];
            $paymentMethod = $_SESSION['order_paymentMethod'];
            $amount = $_SESSION['order_amount'];
            $orderrequest = $_SESSION['order_orderrequest'];
            $delivery_fee = $_SESSION['order_delivery_fee'];
            $total_fee = $_SESSION['order_total_fee'];
            $datetime = $_SESSION['order_datetime'];
        }

        //for orders database
        $data = " name = '" . $first_name . "," . $last_name . "' ";
        $data .= ", mobile = '$mobile' "; //.= is used to append the value to the variable
        $data .= ", email = '$email' ";
        $data .= ", address = '$address' ";
        $data .= ", orderrequest = '$orderrequest' ";
        $data .= ", payment_method = '$paymentMethod' ";
        $data .= ", collect_method = '$collectionMethod' ";
        $data .= ", delivery_fee = '$delivery_fee' ";
        $data .= ", total_fee = '$total_fee' ";
        $data .= ", collect_time = '$datetime' ";
        $db = $conn;
        $save = $db->query("INSERT INTO orders set " . $data);
        if ($save && $_SESSION['paymentreference'] != 'topup') {
            $id = $db->insert_id;
            $qry = $db->query("SELECT * FROM cart where user_id =" . $_SESSION['login_user_id']);
            while ($row = $qry->fetch_assoc()) {

                $data = " order_id = '$id' ";
                $data .= ", product_id = '" . $row['product_id'] . "' ";
                $data .= ", qty = '" . $row['qty'] . "' ";
                $save2 = $db->query("INSERT INTO order_list set " . $data);
                if ($save2) {
                    $db->query("DELETE FROM cart where id= " . $row['id']);
                }
            }
            $successcash = true;
        }else if($save && $_SESSION['paymentreference'] == 'topup'){
            
            $successcash = true;

        }
    } else {
        echo $response->getMessage();
    }
} else {
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
            width: 900px;
            margin-top: 70px;

        }

        h1 {
            color: #297bbe;
        }

        .icon {

            margin-bottom: 0px;
            width: 150px;
            height: 150px;
            margin-left: auto;
            margin-right: auto;
            border: 3px solid gray;
            border-radius: 150px
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
            width: 700px;
            height: 300px;
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

        <a href="../index.php?page=order_track&id=<?php echo $id; ?>&paymentMethod=Paypal"><button class="button" style="width:500px;height:100px; font-size: 30px;">Go to Receipt </button></a>

        <p class="message">You can view your transaction history, send receipts, and apply refunds from Sqare Dashboard</p>

    </div>
</body>
 
</html>


