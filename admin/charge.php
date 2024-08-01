<?php
session_start();
require_once 'config.php';
include 'db_connect.php';

// i want to set submit by name of the button
// if button is set


if (isset($_GET['submit'])) {
    //extract is used to get the value of the variable 


    //take http://localhost/index.php?address=Warung+SATAY+PAK+MALAU%2C+75100+Malacca&collectionMethod=selfCollect&first_name=aimanhakimka&last_name=&mobile=&email=aimanhakimka%40gmail.com&paymentMethod=paypal&amount=30.00 data to variable

    // TAKE THE DATA FROM THE FORM
    $address = $_GET['address'];
    $collectionMethod = $_GET['collectionMethod'];
    $first_name = $_GET['first_name'];
    $last_name = $_GET['last_name'];
    $mobile = $_GET['mobile'];
    $email = $_GET['email'];
    $orderrequest = $_GET['orderrequest'];

    $orderrequest = htmlspecialchars($orderrequest); //prevent xss attack convert special character to html entity. xss is a type of computer security vulnerability typically found in web applications. XSS enables attackers to inject client-side scripts into web pages viewed by other users. A cross-site scripting vulnerability may be used by attackers to bypass access controls such as the same-origin policy.

    $paymentMethod = $_GET['paymentMethod'];
    $delivery_fee = $_GET['feedelivery'];
    $total_fee = $_SESSION['total'];
    $amount = $_GET['amount'];
    $datetime = $_SESSION['selectedDate'] . " " . $_SESSION['selectedTime'];
    $paymentreference = $_GET['paymentType'];  


    $_SESSION['order_address'] = $address;
    $_SESSION['order_collectionMethod'] = $collectionMethod;
    $_SESSION['order_first_name'] = $first_name;
    $_SESSION['order_last_name'] = $last_name;
    $_SESSION['order_mobile'] = $mobile;
    $_SESSION['order_email'] = $email;
    $_SESSION['order_paymentMethod'] = $paymentMethod;
    $_SESSION['order_delivery_fee'] = $delivery_fee;
    $_SESSION['order_total_fee'] = $total_fee;
    $_SESSION['order_datetime'] = $datetime;
    $_SESSION['order_orderrequest'] = $orderrequest;

    $_SESSION['order_amount'] = $amount;


    if ($paymentMethod == 'paypal') {
        if($paymentreference == 'topup'){
            $amount = $_GET['amount'];
            $_SESSION['amountTopup'] = $amount;
            $_SESSION['paymentreference']=$paymentreference;
            
        }
        try {
            $response = $gateway->purchase(array(
                'amount' => $amount,
                'currency' => PAYPAL_CURRENCY,
                'returnUrl' => PAYPAL_RETURN_URL,
                'cancelUrl' => PAYPAL_CANCEL_URL,
                /*'items' => array(
                array(
                    'name' => $_POST['item_name'],
                    'quantity' => 1,
                    'price' => $_POST['amount']
                )
            )*/
            ))->send();
            if ($response->isRedirect()) {
                //forward customer to paypal
                //set as modal redirect
                $response->redirect();
            } else {
                //display error to customer
                echo $response->getMessage();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    } else if ($paymentMethod == 'cash') {
        $db = $conn;
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





        $save = $db->query("INSERT INTO orders set " . $data);
        if ($save) {
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
            $successcash = true; //set the successcash to true
        }




        if ($successcash == true) {
            //delete localstorage mangkukTimunImage  bawangImage kuahImage  after payment success
echo "<script>localStorage.removeItem('mangkukTimunImage');</script>";
echo "<script>localStorage.removeItem('bawangImage');</script>";
echo "<script>localStorage.removeItem('kuahImage');</script>";
echo "<script>localStorage.removeItem('selectedDiscount');</script>";
           
            echo "<script>location.href='../index.php?page=order_track&id=$id'</script>"; //@AIMANSTUDENT04 if payment success redirect to receipt page
            


        }
    } else if ($paymentMethod == 'ewallet') {
        //sent data to paymentewallet.php like http://localhost/admin/charge.php?address=2.2493224%2C102.27769044785956&collectionMethod=delivery&feedelivery=3&first_name=aimanhakimka&last_name=hakim&mobile=6017501931&email=aimanhakimka%40gmail.com&orderrequest=&paymentMethod=ewallet&paymentType=Food&discount=dis&amount=4.3&submit=
        echo "<script>location.href='../paymentewallet.php?address=$address&collectionMethod=$collectionMethod&feedelivery=$delivery_fee&first_name=$first_name&last_name=$last_name&mobile=$mobile&email=$email&orderrequest=$orderrequest&paymentMethod=$paymentMethod&paymentType=$paymentreference&amount=$amount&submit='</script>";

    }
}
